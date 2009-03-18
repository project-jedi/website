<?php
// $Id: main.php,v 1.11 2003/09/22 05:03:15 okazu Exp $
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

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
	exit("Access Denied");
}
$op = 'mod_users';
include_once XOOPS_ROOT_PATH."/modules/system/admin/users/users.php";
if (isset($HTTP_POST_VARS)) {
	foreach ( $HTTP_POST_VARS as $k => $v ) {
		${$k} = $v;
	}
}
if (isset($HTTP_GET_VARS['op'])) {
	$op = trim($HTTP_GET_VARS['op']);
	if (isset($HTTP_GET_VARS['uid'])) {
		$uid = intval($HTTP_GET_VARS['uid']);
	}
}
switch ($op) {

case "modifyUser":
	modifyUser($uid);
	break;
case "updateUser":
	// RMV-NOTIFY
	updateUser($uid, $uname, $name, $url, $email, $user_icq, $user_aim, $user_yim, $user_msnm, $user_from, $user_occ, $user_intrest, $user_viewemail, $user_avatar, $user_sig, $attachsig, $theme, $pass, $pass2, $rank, $bio, $uorder, $umode, $notify_method, $notify_mode, $timezone_offset, $user_mailok);
	break;
case "delUser":
	xoops_cp_header();
	$member_handler =& xoops_gethandler('member');
	$userdata =& $member_handler->getUser($uid);
	xoops_confirm(array('fct' => 'users', 'op' => 'delUserConf', 'del_uid' => $userdata->getVar('uid')), 'admin.php', sprintf(_AM_AYSYWTDU,$userdata->getVar('uname')));
	xoops_cp_footer();
	break;
case "delete_many":
	xoops_cp_header();
	$count = count($memberslist_id);
	if ( $count > 0 ) {
		$list = "<a href='".XOOPS_URL."/userinfo.php?uid=".$memberslist_id[0]."' target='_blank'>".$memberslist_uname[$memberslist_id[0]]."</a>";
		$hidden = "<input type='hidden' name='memberslist_id[]' value='".$memberslist_id[0]."' />\n";
		for ( $i = 1; $i < $count; $i++ ) {
			$list .= ", <a href='".XOOPS_URL."/userinfo.php?uid=".$memberslist_id[$i]."' target='_blank'>".$memberslist_uname[$memberslist_id[$i]]."</a>";
			$hidden .= "<input type='hidden' name='memberslist_id[]' value='".$memberslist_id[$i]."' />\n";
		}
		echo "<div><h4>".sprintf(_AM_AYSYWTDU," ".$list." ")."</h4>";
		echo _AM_BYTHIS."<br /><br />
		<form action='admin.php' method='post'>
		<input type='hidden' name='fct' value='users' />
		<input type='hidden' name='op' value='delete_many_ok' />
		<input type='submit' value='"._YES."' />
		<input type='button' value='"._NO."' onclick='javascript:location.href=\"admin.php?op=adminMain\"' />";
		echo $hidden;
		echo "</form></div>";
	} else {
		echo _AM_NOUSERS;
	}
	xoops_cp_footer();
	break;
case "delete_many_ok":
	$count = count($memberslist_id);
	$output = "";
	$member_handler =& xoops_gethandler('member');
	for ( $i = 0; $i < $count; $i++ ) {
		$deluser =& $member_handler->getUser($memberslist_id[$i]);
		if (!$member_handler->deleteUser($deluser)) {
			$output .= "Could not delete ".$deluser->getVar("uname")."<br />";
		} else {
			$output .= $deluser->getVar("uname")." deleted<br />";
		}
		// RMV-NOTIFY
		xoops_notification_deletebyuser($deluser->getVar('uid'));
	}
	xoops_cp_header();
	echo $output;
	xoops_cp_footer();
	break;
case "delUserConf":
	$member_handler =& xoops_gethandler('member');
	$user =& $member_handler->getUser($del_uid);
	if (!$member_handler->deleteUser($user)) {
		xoops_cp_header();
		echo "Could not delete ".$deluser->getVar("uname");
		xoops_cp_footer();
	}
	$online_handler =& xoops_gethandler('online');
	$online_handler->destroy($del_uid);
	// RMV-NOTIFY
	xoops_notification_deletebyuser($del_uid);
	redirect_header("admin.php?fct=users",1,_AM_DBUPDATED);
	break;
case "addUser":
	if (!$uname || !$email || !$pass) {
		$adduser_errormsg = _AM_YMCACF;
	} else {
		$member_handler =& xoops_gethandler('member');
		// make sure the username doesnt exist yet
		if ($member_handler->getUserCount(new Criteria('uname', $uname)) > 0) {
			$adduser_errormsg = 'User name '.$uname.' already exists';
		} else {
			$newuser =& $member_handler->createUser();
			if ( isset($user_viewemail) ) {
				$newuser->setVar("user_viewemail",$user_viewemail);
			}
			if ( isset($attachsig) ) {
				$newuser->setVar("attachsig",$attachsig);
			}
			$newuser->setVar("name", $name);
			$newuser->setVar("uname", $uname);
			$newuser->setVar("email", $email);
			$newuser->setVar("url", formatURL($url));
			$newuser->setVar("user_avatar",'blank.gif');
			$newuser->setVar("user_icq", $user_icq);
			$newuser->setVar("user_from", $user_from);
			$newuser->setVar("user_sig", $user_sig);
			$newuser->setVar("user_aim", $user_aim);
			$newuser->setVar("user_yim", $user_yim);
			$newuser->setVar("user_msnm", $user_msnm);
			if ($pass2 != "") {
				if ( $pass != $pass2 ) {
					xoops_cp_header();
					echo "
					<b>"._AM_STNPDNM."</b>";
					xoops_cp_footer();
					exit();
				}
				$newuser->setVar("pass", md5($pass));
			}
			$newuser->setVar("timezone_offset", $timezone_offset);
			$newuser->setVar("uorder", $uorder);
			$newuser->setVar("umode", $umode);
			// RMV-NOTIFY
			$newuser->setVar("notify_method", $notify_method);
			$newuser->setVar("notify_mode", $notify_mode);
			$newuser->setVar("bio", $bio);
			$newuser->setVar("rank", $rank);
			$newuser->setVar("level", 1);
			$newuser->setVar("user_occ", $user_occ);
			$newuser->setVar("user_intrest", $user_intrest);
			$newuser->setVar('user_mailok', $user_mailok);
			if (!$member_handler->insertUser($newuser)) {
				$adduser_errormsg = _AM_CNRNU;
			} else {
				if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newuser->getVar('uid'))) {
					$adduser_errormsg = _AM_CNRNU2;
				} else {
					redirect_header("admin.php?fct=users",1,_AM_DBUPDATED);
					exit();
				}
			}
		}
	}
	xoops_cp_header();
	xoops_error($adduser_errormsg);
	xoops_cp_footer();
	break;
case "synchronize":
	synchronize($id, $type);
	break;
case "reactivate":
	$result=$xoopsDB->query("UPDATE ".$xoopsDB->prefix("users")." SET level=1 WHERE uid=".$uid);
	if(!$result){
		exit();
	}
	redirect_header("admin.php?fct=users&amp;op=modifyUser&amp;uid=".$uid,1,_AM_DBUPDATED);
	break;
case "mod_users":
default:
	include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
	displayUsers();
	break;
}
?>
