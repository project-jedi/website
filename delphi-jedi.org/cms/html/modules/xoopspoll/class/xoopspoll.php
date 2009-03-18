<?php
// $Id: xoopspoll.php,v 1.9 2003/10/05 00:34:12 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

class XoopsPoll extends XoopsObject
{
	var $db;

	//constructor
	function XoopsPoll($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("poll_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("question", XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar("description", XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar("user_id", XOBJ_DTYPE_INT, null, false);
		$this->initVar("start_time", XOBJ_DTYPE_INT, null, false);
		$this->initVar("end_time", XOBJ_DTYPE_INT, null, true);
		$this->initVar("votes", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("voters", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("display", XOBJ_DTYPE_INT, 1, false);
		$this->initVar("weight", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("multiple", XOBJ_DTYPE_INT, 0, false);
		$this->initVar("mail_status", XOBJ_DTYPE_INT, 1, false);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}
	}

	// public
	function store()
	{
		if ( !$this->cleanVars() ) {
			return false;
		}
		foreach ( $this->cleanVars as $k=>$v ) {
			$$k = $v;
		}
		$start_time = empty($start_time) ? time() : $start_time;
		if ( $end_time <= $start_time ) {
			$this->setErrors("End time must be set to future");
			return false;
		}
		if ( empty($poll_id) ) {
			$poll_id = $this->db->genId($this->db->prefix("xoopspoll_desc")."_poll_id_seq");
			$sql = "INSERT INTO ".$this->db->prefix("xoopspoll_desc")." (poll_id, question, description, user_id, start_time, end_time, votes, voters, display, weight, multiple, mail_status) VALUES ($poll_id, ".$this->db->quoteString($question).", ".$this->db->quoteString($description).", $user_id, $start_time, $end_time, 0, 0, $display, $weight, $multiple, $mail_status)";
		} else {
			$sql ="UPDATE ".$this->db->prefix("xoopspoll_desc")." SET question=".$this->db->quoteString($question).", description=".$this->db->quoteString($description).", start_time=$start_time, end_time=$end_time, display=$display, weight=$weight, multiple=$multiple, mail_status=$mail_status WHERE poll_id=$poll_id";
		}
		//echo $sql;
		if ( !$result = $this->db->query($sql) ) {
			$this->setErrors("Could not store data in the database.");
			return false;
		}
		if ( empty($poll_id) ) {
			return $this->db->getInsertId();
		}
		return $poll_id;
	}

	// private
	function load($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix("xoopspoll_desc")." WHERE poll_id=".$id."";
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
	}

	// public
	function hasExpired()
	{
		if ( $this->getVar("end_time") > time() ) {
			return false;
		}
		return true;
	}

	// public
	function delete()
	{
		$sql = sprintf("DELETE FROM %s WHERE poll_id = %u", $this->db->prefix("xoopspoll_desc"), $this->getVar("poll_id"));
        	if ( !$this->db->query($sql) ) {
			return false;
		}
		return true;
	}

	// private, static
	function &getAll($criteria=array(), $asobject=true, $orderby="end_time DESC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		}
		if ( !$asobject ) {
			$sql = "SELECT poll_id FROM ".$db->prefix("xoopspoll_desc")."$where_query ORDER BY $orderby";
			$result = $db->query($sql,intval($limit),intval($start));
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['poll_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("xoopspoll_desc")."".$where_query." ORDER BY $orderby";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new XoopsPoll($myrow);
			}
		}
		//echo $sql;
		return $ret;
	}

	// public
	function vote($option_id, $ip, $user_id=null)
	{
		if (!empty($option_id)) {
			if (is_array($option_id)) {
				foreach ($option_id as $vote) {
					$option = new XoopsPollOption($vote);
					if ( $this->getVar("poll_id") == $option->getVar("poll_id") ) {
						$log = new XoopsPollLog();
						$log->setVar("poll_id", $this->getVar("poll_id"));
						$log->setVar("option_id", $vote);
						$log->setVar("ip", $ip);
						if ( isset($user_id) ) {
							$log->setVar("user_id", $user_id);
						}
						if(!$log->store()) {
						} else {
							$option->updateCount();
						}
					}
				}
			} else {
				$option = new XoopsPollOption($option_id);
				if ( $this->getVar("poll_id") == $option->getVar("poll_id") ) {
					$log = new XoopsPollLog();
					$log->setVar("poll_id", $this->getVar("poll_id"));
					$log->setVar("option_id", $option_id);
					$log->setVar("ip", $ip);
					if ( isset($user_id) ) {
						$log->setVar("user_id", $user_id);
					}
					$log->store();
					$option->updateCount();
				}
			}
			return true;
		}
		return false;
	}

	// public
	function updateCount()
	{
		$votes = XoopsPollLog::getTotalVotesByPollId($this->getVar("poll_id"));
		$voters = XoopsPollLog::getTotalVotersByPollId($this->getVar("poll_id"));
		$sql ="UPDATE ".$this->db->prefix("xoopspoll_desc")." SET votes=$votes, voters=$voters WHERE poll_id=".$this->getVar("poll_id")."";
		$this->db->query($sql);
	}
}
?>