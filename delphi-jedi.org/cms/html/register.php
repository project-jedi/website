<?php
// $Id: register.php,v 1.10 2003/09/21 22:06:08 mvandam Exp $
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

$xoopsOption['pagetype'] = 'user';

include 'mainfile.php';
$myts =& MyTextSanitizer::getInstance();

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

if (empty($xoopsConfigUser['allow_register'])) {
	redirect_header('index.php', 6, _US_NOREGISTER);
	exit();
}

function userCheck($uname, $email, $pass, $vpass)
{
	global $xoopsConfigUser;
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$stop = '';
	if ( !checkEmail($email) ) {
		$stop .= _US_INVALIDMAIL.'<br />';
	}
	foreach ($xoopsConfigUser['bad_emails'] as $be) {
		if ( !empty($be) && preg_match("/".$be."/i", $email) ) {
			$stop .= _US_INVALIDMAIL.'<br />';
			break;
		}
	}
	if ( strrpos($email,' ') > 0 ) {
		$stop .= _US_EMAILNOSPACES.'<br />';
	}
	$uname = $myts->oopsStripSlashesGPC($uname);
	$strict = 'a-zA-Z0-9_-';
	$medium = $strict."<>,.$%#@!'\"";
	$loose = $medium."?{}[]()^&*`~;:\\+=";
	switch ( $xoopsConfigUser['uname_test_level'] ) {
	case 0:
		$restriction = $strict;
		break;
	case 1:
		$restriction = $medium;
		break;
	case 2:
		$restriction = $loose;
		break;
	}
	if (!isset($uname) || $uname == '' || preg_match("/[^".preg_quote($restriction)."]/",$uname)) {
		$stop .= _US_INVALIDNICKNAME."<br />";
	}
	if ( strlen($uname) > $xoopsConfigUser['maxuname'] ) {
		$stop .= sprintf(_US_NICKNAMETOOLONG, $xoopsConfigUser['maxuname'])."<br />";
	}
	if ( strlen($uname) < $xoopsConfigUser['minuname'] ) {
		$stop .= sprintf(_US_NICKNAMETOOSHORT, $xoopsConfigUser['minuname'])."<br />";
	}
	foreach ($xoopsConfigUser['bad_unames'] as $bu) {
		if ( !empty($bu) && preg_match("/".$bu."/i", $uname) ) {
			$stop .= _US_NAMERESERVED."<br />";
			break;
		}
	}
	if ( strrpos($uname,' ') > 0 ) {
		$stop .= _US_NICKNAMENOSPACES."<br />";
	}
	$sql = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('users')." WHERE uname='".addslashes($uname)."'";
	$result = $xoopsDB->query($sql);
	list($count) = $xoopsDB->fetchRow($result);
	if ( $count > 0 ) {
		$stop .= _US_NICKNAMETAKEN."<br />";
	}
	$count = 0;
	if ( $email ) {
		$sql = "SELECT COUNT(*) FROM ".$xoopsDB->prefix('users')." where email='".$myts->makeTboxData4Save($email)."'";
		$result = $xoopsDB->query($sql);
		list($count) = $xoopsDB->fetchRow($result);
		if ( $count > 0 ) {
			$stop .= _US_EMAILTAKEN."<br />";
		}
	}
	if ( !isset($pass) || $pass == '' || !isset($vpass) || $vpass == '' ) {
		$stop .= _US_ENTERPWD.'<br />';
	}
	if ( (isset($pass)) && ($pass != $vpass) ) {
		$stop .= _US_PASSNOTSAME.'<br />';
	} elseif ( ($pass != '') && (strlen($pass) < $xoopsConfigUser['minpass']) ) {
		$stop .= sprintf(_US_PWDTOOSHORT,$xoopsConfigUser['minpass'])."<br />";
	}
	return $stop;
}

$op = 'register';
foreach ( $HTTP_POST_VARS as $k => $v ) {
	$$k = $v;
}
switch ( $op ) {
case 'newuser':
	include 'header.php';
	if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
		if (empty($agree_disc)) {
			redirect_header('register.php', 5, _US_UNEEDAGREE);
			exit();
		}
	}
	$uname = trim($uname);
	$email = trim($email);
	$pass = trim($pass);
	$vpass = trim($vpass);
	$stop = userCheck($uname, $email, $pass, $vpass);
	if ( empty($stop) ) {
//		OpenTable();
		echo _US_USERNAME.": ".$myts->makeTboxData4Preview($uname)."<br />";
		echo _US_EMAIL.": ".$myts->makeTboxData4Preview($email)."<br />";
		//if ( $user_avatar != '' ) {
		//	echo _US_AVATAR.": <img src='uploads/".$user_avatar."' alt='' /><br />";
		//}
		if ( isset($url) && $url != '' ) {
			$url = formatURL($myts->makeTboxData4Preview($url));
			echo _US_WEBSITE.": $url<br />";
		}
		$f_timezone = ($timezone_offset < 0) ? 'GMT '.$timezone_offset : 'GMT +'.$timezone_offset;
		echo _US_TIMEZONE.": $f_timezone<br />";
		echo "<form action='register.php' method='post'>
		<input type='hidden' name='uname' value='".$myts->makeTboxData4PreviewInForm($uname)."' />
		<input type='hidden' name='email' value='".$myts->makeTboxData4PreviewInForm($email)."' />";
		//echo "<input type='hidden' name='user_avatar' value='".$myts->makeTboxData4PreviewInForm($user_avatar)."' />";
		$user_viewemail = isset($user_viewemail) ? intval($user_viewemail) : 0;
		echo "<input type='hidden' name='user_viewemail' value='".$user_viewemail."' />
		<input type='hidden' name='timezone_offset' value='".(float)$timezone_offset."' />
		<input type='hidden' name='url' value='".$myts->makeTboxData4PreviewInForm($url)."' />
		<input type='hidden' name='pass' value='".$myts->makeTboxData4PreviewInForm($pass)."' />
		<input type='hidden' name='vpass' value='".$myts->makeTboxData4PreviewInForm($vpass)."' />
		<input type='hidden' name='user_mailok' value='".intval($user_mailok)."' />
		<br /><br /><input type='hidden' name='op' value='finish' /><input type='submit' value='". _US_FINISH ."' /></form>";
//		CloseTable();
	} else {
		echo "<span style='color:#ff0000;'>$stop</span>";
		include 'include/registerform.php';
		$reg_form->display();
	}
	include 'footer.php';
	break;
case 'finish':
	include 'header.php';
	$uname = trim($uname);
	$email = trim($email);
	$pass = trim($pass);
	$vpass = trim($vpass);
	$stop = userCheck($uname, $email, $pass, $vpass);
	if ( empty($stop) ) {
		$member_handler =& xoops_gethandler('member');
		$newuser =& $member_handler->createUser();
		if ( isset($user_viewemail) ) {
			$newuser->setVar('user_viewemail',$user_viewemail);
		}
		if ( isset($attachsig) ) {
			$newuser->setVar('attachsig',$attachsig);
		}
		$name = isset($name) ? $name : '';
		$newuser->setVar('name', $name);
		$newuser->setVar('uname', $uname);
		$newuser->setVar('email', $email);
		if ( isset($url) && $url!='' ) {
			$newuser->setVar('url', formatURL($url));
		}

		$newuser->setVar('user_avatar','blank.gif');
		$actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$newuser->setVar('actkey',$actkey);
		$newuser->setVar('pass', md5($pass));
		$newuser->setVar('timezone_offset', $timezone_offset);
		$newuser->setVar('user_regdate', time());
		$newuser->setVar('uorder',$xoopsConfig['com_order']);
		$newuser->setVar('umode',$xoopsConfig['com_mode']);
		$newuser->setVar('user_mailok',$user_mailok);
		if ($xoopsConfigUser['activation_type'] == 1) {
			$newuser->setVar('level', 1);
		}
		if (!$member_handler->insertUser($newuser)) {
			echo _US_REGISTERNG;
			include 'footer.php';
			exit();
		}
		$newid = $newuser->getVar('uid');
		if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
			echo _US_REGISTERNG;
			include 'footer.php';
			exit();
		}
		if ($xoopsConfigUser['activation_type'] == 1) {
			redirect_header('index.php', 4, _US_ACTLOGIN);
			exit();
		}
		if ($xoopsConfigUser['activation_type'] == 0) {
			$myts =& MyTextSanitizer::getInstance();
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('register.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->setToUsers(new XoopsUser($newid));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR,$myts->oopsStripSlashesGPC($uname)));
//			OpenTable();
			if ( !$xoopsMailer->send() ) {
				echo _US_YOURREGMAILNG;
			} else {
				echo _US_YOURREGISTERED;
			}
//			CloseTable();
		} elseif ($xoopsConfigUser['activation_type'] == 2) {
			$myts =& MyTextSanitizer::getInstance();
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('adminactivate.tpl');
			$xoopsMailer->assign('USERNAME', $myts->oopsStripSlashesGPC($uname));
			$xoopsMailer->assign('USEREMAIL', $myts->oopsStripSlashesGPC($email));
			$xoopsMailer->assign('USERACTLINK', XOOPS_URL.'/user.php?op=actv&id='.$newid.'&actkey='.$actkey);
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$member_handler =& xoops_gethandler('member');
			$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['activation_group']));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR,$myts->oopsStripSlashesGPC($uname)));
//			OpenTable();
			if ( !$xoopsMailer->send() ) {
				echo _US_YOURREGMAILNG;
			} else {
				echo _US_YOURREGISTERED2;
			}
//			CloseTable();
		}
		if ($xoopsConfigUser['new_user_notify'] == 1 && !empty($xoopsConfigUser['new_user_notify_group'])) {
			$myts =& MyTextSanitizer::getInstance();
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$member_handler =& xoops_gethandler('member');
			$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT,$xoopsConfig['sitename']));
			$xoopsMailer->setBody(sprintf(_US_HASJUSTREG,$myts->oopsStripSlashesGPC($uname)));
			$xoopsMailer->send();
		}
	} else {
		echo "<span style='color:#ff0000; font-weight:bold;'>$stop</span>";
		include 'include/registerform.php';
		$reg_form->display();
	}
	include 'footer.php';
	break;
case 'register':
default:
	include 'header.php';
	//OpenTable();
	include 'include/registerform.php';
	$reg_form->display();
	//CloseTable();
	include 'footer.php';
	break;
}
?>
