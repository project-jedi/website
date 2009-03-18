<?php
// $Id: registerform.php,v 1.7 2003/09/26 14:26:05 okazu Exp $
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

include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

// initialize form vars
$uname = isset($HTTP_POST_VARS['uname']) ? $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['uname']) : "";
$email = isset($HTTP_POST_VARS['email']) ? $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['email']) : "";
$user_viewemail = isset($HTTP_POST_VARS['user_viewemail']) ? $HTTP_POST_VARS['user_viewemail'] : "";
$url = isset($HTTP_POST_VARS['url']) ? $myts->makeTboxData4PreviewInForm($HTTP_POST_VARS['url']) : "";
$timezone_offset = isset($HTTP_POST_VARS['timezone_offset']) ? $HTTP_POST_VARS['timezone_offset'] : "";
//$user_avatar = isset($HTTP_POST_VARS['user_avatar']) ? $HTTP_POST_VARS['user_avatar'] : "blank.gif";

$email_tray = new XoopsFormElementTray(_US_EMAIL, "<br />");
$email_text = new XoopsFormText("", "email", 25, 60, $email);
$email_option = new XoopsFormCheckBox("", "user_viewemail", $user_viewemail);
$email_option->addOption(1, _US_ALLOWVIEWEMAIL);
$email_tray->addElement($email_text);
$email_tray->addElement($email_option);

//$avatar_select = new XoopsFormSelect("", "user_avatar", $user_avatar);
//$avatar_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/images/avatar/");
//$avatar_select->addOptionArray($avatar_array);
//$a_dirlist =& XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH."/images/avatar/");
//$a_dir_labels = array();
//$a_count = 0;
//$a_dir_link = "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/misc.php?action=showpopups&amp;type=avatars&amp;start=".$a_count."','avatars',600,400);\">XOOPS</a>";
//$a_count = $a_count + count($avatar_array);
//$a_dir_labels[] = new XoopsFormLabel("", $a_dir_link);
//foreach ($a_dirlist as $a_dir) {
//	if ( $a_dir == "users" ) {
//		continue;
//	}
//	$avatars_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/images/avatar/".$a_dir."/", $a_dir."/");
//	$avatar_select->addOptionArray($avatars_array);
//	$a_dir_link = "<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/misc.php?action=showpopups&amp;type=avatars&amp;subdir=".$a_dir."&amp;start=".$a_count."','avatars',600,400);\">".$a_dir."</a>";
//	$a_dir_labels[] = new XoopsFormLabel("", $a_dir_link);
//	$a_count = $a_count + count($avatars_array);
//}
//$avatar_select->setExtra("onchange='showImgSelected(\"avatar\", \"user_avatar\", \"images/avatar\", \"\", \"".XOOPS_URL."\")'");
//$avatar_label = new XoopsFormLabel("", "<img src='images/avatar/blank.gif' name='avatar' id='avatar' alt='' />");
//$avatar_tray = new XoopsFormElementTray(_US_AVATAR, "&nbsp;");
//$avatar_tray->addElement($avatar_select);
//$avatar_tray->addElement($avatar_label);
//foreach ($a_dir_labels as $a_dir_label) {
//	$avatar_tray->addElement($a_dir_label);
//}

$reg_form = new XoopsThemeForm(_US_USERREG, "userinfo", "register.php");
$reg_form->addElement(new XoopsFormText(_US_NICKNAME, "uname", 26, 25, $uname), true);
$reg_form->addElement($email_tray);
$reg_form->addElement(new XoopsFormText(_US_WEBSITE, "url", 25, 255, $url));
$tzselected = ($timezone_offset != "") ? $timezone_offset : $xoopsConfig['default_TZ'];
$reg_form->addElement(new XoopsFormSelectTimezone(_US_TIMEZONE, "timezone_offset", $tzselected));
//$reg_form->addElement($avatar_tray);
$reg_form->addElement(new XoopsFormPassword(_US_PASSWORD, "pass", 10, 20), true);
$reg_form->addElement(new XoopsFormPassword(_US_VERIFYPASS, "vpass", 10, 20), true);
$reg_form->addElement(new XoopsFormRadioYN(_US_MAILOK, 'user_mailok', 1));
if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
	$disc_tray = new XoopsFormElementTray(_US_DISCLAIMER, '<br />');
	$disc_text = new XoopsFormTextarea('', 'disclaimer', $xoopsConfigUser['reg_disclaimer'], 8);
	$disc_text->setExtra('readonly="readonly"');
	$disc_tray->addElement($disc_text);
	$agree_chk = new XoopsFormCheckBox('', 'agree_disc', 0);
	$agree_chk->addOption(1, _US_IAGREE);
	$disc_tray->addElement($agree_chk);
	$reg_form->addElement($disc_tray);
}
$reg_form->addElement(new XoopsFormHidden("op", "newuser"));
$reg_form->addElement(new XoopsFormButton("", "submit", _US_SUBMIT, "submit"));
$reg_form->setRequired($email_text);
?>