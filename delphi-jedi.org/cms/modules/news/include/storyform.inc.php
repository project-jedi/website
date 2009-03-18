<?php
// $Id: storyform.inc.php,v 1.4 2003/02/12 11:37:52 okazu Exp $
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

include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$sform = new XoopsThemeForm(_NW_SUBMITNEWS, "storyform", xoops_getenv('PHP_SELF'));
$sform->addElement(new XoopsFormText(_NW_TITLE, 'subject', 50, 80, $subject), true);
ob_start();
$xt->makeTopicSelBox(0);
$sform->addElement(new XoopsFormLabel(_NW_TOPIC, ob_get_contents()));
ob_end_clean();
$sform->addElement($topic_select);

global $xoopsModuleConfig;
if ($xoopsModuleConfig['news_user_wysiwyg'] == '1') {
	// SPAW Config
	$spaw_root = XOOPS_ROOT_PATH.'/modules/news/admin/spaw/';
	include $spaw_root.'spaw_control.class.php';

	if (checkBrowser()) {
		ob_start();
		$sw = new SPAW_Wysiwyg('message',$message);
		$sw->show();
		$sform->addElement(new XoopsFormLabel(_NW_TOPIC, ob_get_contents()));
		ob_end_clean();
	} else {
		$sform->addElement(new XoopsFormDhtmlTextArea(_NW_THESCOOP, 'message', $message, 15, 60), true);
	}
} else {
	$sform->addElement(new XoopsFormDhtmlTextArea(_NW_THESCOOP, 'message', $message, 15, 60), true);
}

$option_tray = new XoopsFormElementTray(_OPTIONS,'<br />');
if ($xoopsUser) {
	if ($xoopsConfig['anonpost'] == 1) {
		$noname_checkbox = new XoopsFormCheckBox('', 'noname', $noname);
		$noname_checkbox->addOption(1, _POSTANON);
		$option_tray->addElement($noname_checkbox);
	}
	$notify_checkbox = new XoopsFormCheckBox('', 'notifypub', $notifypub);
	$notify_checkbox->addOption(1, _NW_NOTIFYPUBLISH);
	$option_tray->addElement($notify_checkbox);
	if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
		$nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);
		$nohtml_checkbox->addOption(1, _DISABLEHTML);
		$option_tray->addElement($nohtml_checkbox);
	}
}
$smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);
$sform->addElement($option_tray);
$button_tray = new XoopsFormElementTray('' ,'');
$button_tray->addElement(new XoopsFormButton('', 'preview', _PREVIEW, 'submit'));
$button_tray->addElement(new XoopsFormButton('', 'post', _NW_POST, 'submit'));
$sform->addElement($button_tray);
$sform->display();

  // checks browser compatibility with the control
  function checkBrowser() {
    global $HTTP_SERVER_VARS;
    $browser = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
    // check if msie
    if (eregi("MSIE[^;]*",$browser,$msie)) {
      // get version 
      if (eregi("[0-9]+\.[0-9]+",$msie[0],$version)) {
        // check version
        if ((float)$version[0]>=5.5) {
          // finally check if it's not opera impersonating ie
          if (!eregi("opera",$browser)) {
            return true;
          }
        }
      }
    }
    return false;
  }

?>