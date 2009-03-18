<?php
// $Id: brokenlink.php,v 1.8 2003/03/27 12:11:06 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
include "header.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

if (!empty($HTTP_POST_VARS['submit'])) {
	if (empty($xoopsUser)) {
		$sender = 0;
	} else {
		$sender = $xoopsUser->getVar('uid');
	}
	$lid = intval($HTTP_POST_VARS['lid']);
	$ip = getenv("REMOTE_ADDR");
	if ($sender != 0) {
		// Check if REG user is trying to report twice.
   		$result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("mylinks_broken")." WHERE lid=".$lid." AND sender=".$sender."");
       	list($count)=$xoopsDB->fetchRow($result);
       	if ($count > 0) {
			redirect_header("index.php",2,_MD_ALREADYREPORTED);
			exit();
        }
  	} else {
   		// Check if the sender is trying to vote more than once.
    	$result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("mylinks_broken")." WHERE lid=$lid AND ip = '$ip'");
   		list($count)=$xoopsDB->fetchRow($result);
    	if ($count > 0) {
			redirect_header("index.php",2,_MD_ALREADYREPORTED);
			exit();
    	}
	}
	$newid = $xoopsDB->genId($xoopsDB->prefix("mylinks_broken")."_reportid_seq");
	$sql = sprintf("INSERT INTO %s (reportid, lid, sender, ip) VALUES (%u, %u, %u, '%s')", $xoopsDB->prefix("mylinks_broken"), $newid, $lid, $sender, $ip);
	$xoopsDB->query($sql) or exit();
	$tags = array();
	$tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listBrokenLinks';
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('global', 0, 'link_broken', $tags);
	redirect_header("index.php",2,_MD_THANKSFORINFO);
	exit();
} else {
	$xoopsOption['template_main'] = 'mylinks_brokenlink.html';
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsTpl->assign('lang_reportbroken', _MD_REPORTBROKEN);
	$xoopsTpl->assign('link_id', intval($HTTP_GET_VARS['lid']));
	$xoopsTpl->assign('lang_thanksforhelp', _MD_THANKSFORHELP);
	$xoopsTpl->assign('lang_forsecurity', _MD_FORSECURITY);
	$xoopsTpl->assign('lang_cancel', _MD_CANCEL);
	include_once XOOPS_ROOT_PATH.'/footer.php';
}
?>
