<?php

/*
 * Created on Mar 06, 2012
 * API for MediaWiki 1.14+
 *
 * Copyright (C) 2012 Florent Ouchet outchy@users.sourceforge.net
 * Parts of this file are from:
 *  -UserMerge_body.php Copyright (C) 2011 Tim Laqua <t.laqua@gmail.com>, Thomas Gries
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ("ApiBase.php");
}

/**
 * API module that facilitates merging and deleting users.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiUserMerge extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		global $wgUser;
		$this->getMain()->requestWriteMode();
		$params = $this->extractRequestParams();

		if(!isset($params['olduser']))
			$this->dieUsageMsg(array('missingparam', 'olduser'));

		if(!isset($params['newuser']))
			$this->dieUsageMsg(array('missingparam', 'newuser'));

		if(!isset($params['deleteuser']))
			$this->dieUsageMsg(array('missingparam', 'deleteuser'));

		if(!$wgUser->isAllowed('usermerge'))
			$this->dieUsageMsg(array('notallowed'));

		$olduser = Title::newFromText( $params['olduser'] );
		$olduser_text = is_object( $olduser ) ? $olduser->getText() : '';

		$newuser = Title::newFromText( $params['newuser'] );
		$newuser_text = is_object( $newuser ) ? $newuser->getText() : '';

		$deleteUserCheck = $params['deleteuser'] == 'true';

		if (strlen($olduser_text)>0) {
			$objOldUser = User::newFromName( $olduser_text );
			$olduserID = $objOldUser->idForName();
		} else {
			$this->dieUsageMsg(array('notallowed'));
		}

		$validOldUser = false;
		if ( !is_object( $objOldUser ) ) {
			$this->dieUsageMsg(array('notallowed'));
		} elseif ( $olduserID == $wgUser->getID() ) {
			$this->dieUsageMsg(array('notallowed'));
		} else {
			global $wgUserMergeProtectedGroups;

			$boolProtected = false;
			foreach ( $objOldUser->getGroups() as $userGroup ) {
				if ( in_array( $userGroup, $wgUserMergeProtectedGroups ) ) {
					$boolProtected = true;
				}
			}

			if ( $boolProtected ) {
				$this->dieUsageMsg(array('notallowed'));
			} else {
	                        $validOldUser = true;
			}
		}

		if (strlen($newuser_text)>0) {
			$objNewUser = User::newFromName( $newuser_text );
			$newuserID = $objNewUser->idForName();
		} else {
			$this->dieUsageMsg(array('notallowed'));
		}

		$validNewUser = false;
		if ( !is_object( $objNewUser ) || $newuserID == 0 ) {
			if ( $newuser_text == 'Anonymous' ) {
				// Merge to anonymous
				$validNewUser = true;
				$newuserID = 0;
			} else {
				//invalid newuser entered
				$validNewUser = false;
				$this->dieUsageMsg(array('notallowed'));
			}
		} else {
			//newuser looks good
			$validNewUser = true;
		}

		if ($validNewUser && $validOldUser) {
			$this->mergeUser($newuser_text,$newuserID,$olduser_text,$olduserID);
			if ($deleteUserCheck) {
				$this->deleteUser($olduserID, $olduser_text);
			}
			$r = array('result' => 'Success', 'olduser' => $olduser_text, 'newuser' => $newuser_text);
			$this->getResult()->addValue(null, $this->getModuleName(), $r);
		} else {
			$r = array('result' => 'Failure');
			$this->getResult()->addValue(null, $this->getModuleName(), $r);
		}
	}

	public function mustBePosted() { return false; }

	public function getAllowedParams() {
		return array (
			'olduser' => null,
			'newuser' => null,
			'deleteuser' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'olduser' => 'Name of the user to be merged and/or deleted',
			'newuser' => 'User name to be merged to',
			'deleteuser' => 'Boolean flag to delete the olduser once merged',
		);
	}

	public function getDescription() {
		return array(
			'Merge and delete an user.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=usermerge&olduser=Foo&newuser=Bar&deleteuser=true',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiUserMerge.php 2012-06-12 outchy $';
	}

	///Function to delete users following a successful mergeUser call
	/**
	 * Removes user entries from the user table and the user_groups table
	 *
	 * @param $olduserID int ID of user to delete
	 * @param $olduser_text string Username of user to delete
	 *
	 * @return Always returns true - throws exceptions on failure.
	 */
	private function deleteUser ($olduserID, $olduser_text) {
		global $wgOut,$wgUser;

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->delete( 'user_groups', array( 'ug_user' => $olduserID ));
		$dbw->delete( 'user', array( 'user_id' => $olduserID ));

		$log = new LogPage( 'usermerge' );
		$log->addEntry( 'deleteuser', $wgUser->getUserPage(),'',array($olduser_text,$olduserID) );

		$users = $dbw->selectField( 'user', 'COUNT(*)', array() );
		$admins = $dbw->selectField( 'user_groups', 'COUNT(*)', array( 'ug_group' => 'sysop' ) );
		$dbw->update(	'site_stats',
				array( 'ss_users' => $users, 'ss_admins' => $admins ),
				array( 'ss_row_id' => 1 ) );
		return true;
        }

	///Function to merge database referances from one user to another user
	/**
	 * Merges database references from one user ID or username to another user ID or username
	 * to preserve referential integrity.
	 *
	 * @param $newuser_text string Username to merge referances TO
	 * @param $newuserID int ID of user to merge referances TO
	 * @param $olduser_text string Username of user to remove referances FROM
	 * @param $olduserID int ID of user to remove referances FROM
	 *
	 * @return Always returns true - throws exceptions on failure.
	 */
	private function mergeUser ($newuser_text, $newuserID, $olduser_text, $olduserID) {
		global $wgOut, $wgUser;

		$textUpdateFields = array(	array('archive','ar_user_text'),
						array('revision','rev_user_text'),
						array('filearchive','fa_user_text'),
						array('image','img_user_text'),
						array('oldimage','oi_user_text'),
						array('recentchanges','rc_user_text'),
						array('ipblocks','ipb_address'));

		$idUpdateFields = array(	array('archive','ar_user'),
						array('revision','rev_user'),
						array('filearchive','fa_user'),
						array('image','img_user'),
						array('oldimage','oi_user'),
						array('recentchanges','rc_user'),
						array('logging','log_user'));

		$dbw =& wfGetDB( DB_MASTER );

		foreach ($idUpdateFields as $idUpdateField) {
			$dbw->update($idUpdateField[0], array( $idUpdateField[1] => $newuserID ), array( $idUpdateField[1] => $olduserID ));
			$wgOut->addHTML(wfMsg('usermerge-updating', $idUpdateField[0], $olduserID, $newuserID) . "<br />\n");
		}

		foreach ($textUpdateFields as $textUpdateField) {
			$dbw->update($textUpdateField[0], array( $textUpdateField[1] => $newuser_text ), array( $textUpdateField[1] => $olduser_text ));
			$wgOut->addHTML(wfMsg('usermerge-updating', $textUpdateField[0], $olduser_text, $newuser_text) . "<br />\n");
		}


		$dbw->delete( 'user_newtalk', array( 'user_id' => $olduserID ));

		$log = new LogPage( 'usermerge' );
		$log->addEntry( 'mergeuser', $wgUser->getUserPage(),'',array($olduser_text,$olduserID,$newuser_text,$newuserID) );

		return true;
	}
}
