<?php
// $Id: submit.php,v 1.12 2003/03/27 12:11:07 w4z004 Exp $
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
$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("mylinks_cat"),"cid","pid");

if (empty($xoopsUser) and !$xoopsModuleConfig['anonpost']) {
	redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
	exit();
}

if (!empty($HTTP_POST_VARS['submit'])) {

	$submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

	// RMV - why store submitter on form??
   	//if (!$HTTP_POST_VARS['submitter'] and $xoopsUser) {
    //   $submitter = $xoopsUser->uid();
   	//}elseif(!$HTTP_POST_VARS['submitter'] and !$xoopsUser) {
	//	$submitter = 0;
	//}else{
	//	$submitter = intval($HTTP_POST_VARS['submitter']);
	//}

	// Check if Title exist
   	if ($HTTP_POST_VARS["title"]=="") {
       	$eh->show("1001");
   	}

	// Check if URL exist
	$url = $HTTP_POST_VARS["url"];
   	if ($url=="" || !isset($url)) {
       	$eh->show("1016");
   	}

	// Check if Description exist
   	if ($HTTP_POST_VARS['message']=="") {
       	$eh->show("1008");
   	}

	$notify = !empty($HTTP_POST_VARS['notify']) ? 1 : 0;

	if ( !empty($HTTP_POST_VARS['cid']) ) {
   		$cid = intval($HTTP_POST_VARS['cid']);
	} else {
		$cid = 0;
	}

	//	$url = urlencode($url);
	$url = $myts->makeTboxData4Save($url);
	$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
    $description = $myts->makeTareaData4Save($HTTP_POST_VARS["message"]);
	$date = time();
	$newid = $xoopsDB->genId($xoopsDB->prefix("mylinks_links")."_lid_seq");
	if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
		// RMV-FIXME: shouldn't this be 'APPROVE' or something (also in mydl)?
		$status = $xoopsModuleConfig['autoapprove'];
	} else {
		$status = 0;
	}
	$sql = sprintf("INSERT INTO %s (lid, cid, title, url, logourl, submitter, status, date, hits, rating, votes, comments) VALUES (%u, %u, '%s', '%s', '%s', %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix("mylinks_links"), $newid, $cid, $title, $url, ' ', $submitter, $status, $date, 0, 0, 0, 0);
	$xoopsDB->query($sql) or $eh->show("0013");
	if ($newid == 0) {
		$newid = $xoopsDB->getInsertId();
	}
	$sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix("mylinks_text"), $newid, $description);
	$xoopsDB->query($sql) or $eh->show("0013");
	// RMV-NEW
	// Notify of new link (anywhere) and new link in category.
	$notification_handler =& xoops_gethandler('notification');
	$tags = array();
	$tags['LINK_NAME'] = $title;
	$tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&lid=' . $newid;
	$sql = "SELECT title FROM " . $xoopsDB->prefix("mylinks_cat") . " WHERE cid=" . $cid;
	$result = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($result);
	$tags['CATEGORY_NAME'] = $row['title'];
	$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
	if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
		$notification_handler->triggerEvent('global', 0, 'new_link', $tags);
		$notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
		redirect_header("index.php",2,_MD_RECEIVED."<br />"._MD_ISAPPROVED."");
	}else{
		$tags['WAITINGLINKS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewLinks';
		$notification_handler->triggerEvent('global', 0, 'link_submit', $tags);
		$notification_handler->triggerEvent('category', $cid, 'link_submit', $tags);
		if ($notify) {
			include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
			$notification_handler->subscribe('link', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
		}
		redirect_header("index.php",2,_MD_RECEIVED);
	}
	exit();

} else {

	$xoopsOption['template_main'] = 'mylinks_submit.html';
	include XOOPS_ROOT_PATH."/header.php";
	ob_start();
	xoopsCodeTarea("message",37,8);
	$xoopsTpl->assign('xoops_codes', ob_get_contents());
	ob_end_clean();
	ob_start();
	xoopsSmilies("message");
	$xoopsTpl->assign('xoops_smilies', ob_get_contents());
	ob_end_clean();
	$xoopsTpl->assign('notify_show', !empty($xoopsUser) && !$xoopsModuleConfig['autoapprove'] ? 1 : 0);
	$xoopsTpl->assign('lang_submitonce', _MD_SUBMITONCE);
	$xoopsTpl->assign('lang_submitlinkh', _MD_SUBMITLINKHEAD);
	$xoopsTpl->assign('lang_allpending', _MD_ALLPENDING);
	$xoopsTpl->assign('lang_dontabuse', _MD_DONTABUSE);
	$xoopsTpl->assign('lang_wetakeshot', _MD_TAKESHOT);
	$xoopsTpl->assign('lang_sitetitle', _MD_SITETITLE);
	$xoopsTpl->assign('lang_siteurl', _MD_SITEURL);
	$xoopsTpl->assign('lang_category', _MD_CATEGORYC);
	$xoopsTpl->assign('lang_options', _MD_OPTIONS);
	$xoopsTpl->assign('lang_notify', _MD_NOTIFYAPPROVE);
	$xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
	$xoopsTpl->assign('lang_submit', _SUBMIT);
	$xoopsTpl->assign('lang_cancel', _CANCEL);
	ob_start();
	$mytree->makeMySelBox("title", "title",0,1);
	$selbox = ob_get_contents();
	ob_end_clean();
	$xoopsTpl->assign('category_selbox', $selbox);
	include XOOPS_ROOT_PATH.'/footer.php';
}
?>
