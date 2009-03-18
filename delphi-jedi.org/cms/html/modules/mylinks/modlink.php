<?php
// $Id: modlink.php,v 1.7 2003/03/27 12:11:07 w4z004 Exp $
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

include "header.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$mytree = new XoopsTree($xoopsDB->prefix("mylinks_cat"),"cid","pid");

if (!empty($HTTP_POST_VARS['submit'])) {
	$eh = new ErrorHandler; //ErrorHandler object
	if (empty($xoopsUser)) {
		redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
		exit();
	} else {
		$user = $xoopsUser->getVar('uid');
	}
   	$lid = intval($HTTP_POST_VARS["lid"]);

	// Check if Title exist
   	if ($HTTP_POST_VARS["title"]=="") {
       	$eh->show("1001");
   	}
	// Check if URL exist
   	if ($HTTP_POST_VARS["url"]=="") {
       	$eh->show("1016");
   	}
	// Check if Description exist
   	if ($HTTP_POST_VARS['description']=="") {
       	$eh->show("1008");
   	}

	$url = $myts->makeTboxData4Save($HTTP_POST_VARS["url"]);
	$logourl = $myts->makeTboxData4Save($HTTP_POST_VARS["logourl"]);
	$cid = intval($HTTP_POST_VARS["cid"]);
	$title = $myts->makeTboxData4Save($HTTP_POST_VARS["title"]);
	$description = $myts->makeTareaData4Save($HTTP_POST_VARS["description"]);
	$newid = $xoopsDB->genId($xoopsDB->prefix("mylinks_mod")."_requestid_seq");
	$sql = sprintf("INSERT INTO %s (requestid, lid, cid, title, url, logourl, description, modifysubmitter) VALUES (%u, %u, %u, '%s', '%s', '%s', '%s', %u)", $xoopsDB->prefix("mylinks_mod"), $newid, $lid, $cid, $title, $url, $logourl, $description, $user);
	$xoopsDB->query($sql) or $eh->show("0013");
    $tags = array();
	$tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'link_modify', $tags);
	redirect_header("index.php",2,_MD_THANKSFORINFO);
	exit();
} else {
	$lid = intval($HTTP_GET_VARS['lid']);
	if (empty($xoopsUser)) {
		redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
		exit();
	}
	$xoopsOption['template_main'] = 'mylinks_modlink.html';
	include XOOPS_ROOT_PATH."/header.php";
	$result = $xoopsDB->query("select cid, title, url, logourl from ".$xoopsDB->prefix("mylinks_links")." where lid=$lid and status>0");
	$xoopsTpl->assign('lang_requestmod', _MD_REQUESTMOD);
	list($cid, $title, $url, $logourl) = $xoopsDB->fetchRow($result);
	$result2 = $xoopsDB->query("SELECT description FROM ".$xoopsDB->prefix("mylinks_text")." WHERE lid=$lid");
	list($description)=$xoopsDB->fetchRow($result2);
	$xoopsTpl->assign('link', array('id' => $lid, 'rating' => number_format($rating, 2), 'title' => $myts->htmlSpecialChars($title), 'url' => $myts->htmlSpecialChars($url), '$logourl' => $myts->htmlSpecialChars($logourl), 'updated' => formatTimestamp($time,"m"), 'description' => $myts->htmlSpecialChars($description), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring));
	$xoopsTpl->assign('lang_linkid', _MD_LINKID);
	$xoopsTpl->assign('lang_sitetitle', _MD_SITETITLE);
	$xoopsTpl->assign('lang_siteurl', _MD_SITEURL);
	$xoopsTpl->assign('lang_category', _MD_CATEGORYC);
	ob_start();
	$mytree->makeMySelBox("title", "title", $cid);
	$selbox = ob_get_contents();
	ob_end_clean();
	$xoopsTpl->assign('category_selbox', $selbox);
	$xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
	$xoopsTpl->assign('lang_sendrequest', _MD_SENDREQUEST);
	$xoopsTpl->assign('lang_cancel', _CANCEL);
	include XOOPS_ROOT_PATH.'/footer.php';
}
?>
