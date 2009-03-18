<?php
// $Id: checklogin.php,v 1.15 2003/09/16 13:06:51 okazu Exp $
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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/user.php';
$uname = !isset($HTTP_POST_VARS['uname']) ? '' : trim($HTTP_POST_VARS['uname']);
$pass = !isset($HTTP_POST_VARS['pass']) ? '' : trim($HTTP_POST_VARS['pass']);
if ($uname == '' || $pass == '') {
	redirect_header(XOOPS_URL.'/user.php', 1, _US_INCORRECTLOGIN);
	exit();
}
$member_handler =& xoops_gethandler('member');
$myts =& MyTextsanitizer::getInstance();
$user =& $member_handler->loginUser(addslashes($myts->stripSlashesGPC($uname)), addslashes($myts->stripSlashesGPC($pass)));
if (false != $user) {
	if (0 == $user->getVar('level')) {
		redirect_header(XOOPS_URL.'/index.php', 5, _US_NOACTTPADM);
		exit();
	}
	if ($xoopsConfig['closesite'] == 1) {
		$allowed = false;
		foreach ($user->getGroups() as $group) {
			if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
				$allowed = true;
				break;
			}
		}
		if (!$allowed) {
			redirect_header(XOOPS_URL.'/index.php', 1, _NOPERM);
			exit();
		}
	}
	$user->setVar('last_login', time());
	if (!$member_handler->insertUser($user)) {
	}
	$HTTP_SESSION_VARS = array();
	$HTTP_SESSION_VARS['xoopsUserId'] = $user->getVar('uid');
	$HTTP_SESSION_VARS['xoopsUserGroups'] = $user->getGroups();
	if ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
		setcookie($xoopsConfig['session_name'], session_id(), time()+(60 * $xoopsConfig['session_expire']), '/',  '', 0);
	}
	$user_theme = $user->getVar('theme');
	if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
		$HTTP_SESSION_VARS['xoopsUserTheme'] = $user_theme;
	}
	if (!empty($HTTP_POST_VARS['xoops_redirect']) && !strpos($HTTP_POST_VARS['xoops_redirect'], 'register')) {
		$parsed = parse_url(XOOPS_URL);
		$url = isset($parsed['scheme']) ? $parsed['scheme'].'://' : 'http://';
		if (isset($parsed['host'])) {
			$url .= isset($parsed['port']) ?$parsed['host'].':'.$parsed['port'].trim($HTTP_POST_VARS['xoops_redirect']): $parsed['host'].trim($HTTP_POST_VARS['xoops_redirect']);
		} else {
			$url = xoops_getenv('HTTP_HOST').trim($HTTP_POST_VARS['xoops_redirect']);
		}
	} else {
		$url = XOOPS_URL.'/index.php';
	}

	// set cookie for autologin
	//if (!empty($HTTP_POST_VARS['rememberme'])) {
	//	$expire = time() + $xoopsConfig['session_expire'] * 60;
	//	setcookie('autologin_uname', $uname, $expire, '/', '', 0);
	//	setcookie('autologin_pass', md5($pass), $expire, '/', '', 0);
	//}

	// RMV-NOTIFY
	// Perform some maintenance of notification records
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->doLoginMaintenance($user->getVar('uid'));

	redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')));
} else {

	redirect_header(XOOPS_URL.'/user.php',1,_US_INCORRECTLOGIN);
}
exit();
?>
