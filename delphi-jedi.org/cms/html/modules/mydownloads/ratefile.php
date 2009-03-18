<?php
// $Id: ratefile.php,v 1.9 2003/03/27 12:11:05 w4z004 Exp $
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
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

if(!empty($HTTP_POST_VARS['submit'])) {
	$eh = new ErrorHandler; //ErrorHandler object
	if(empty($xoopsUser)){
		$ratinguser = 0;
	}else{
		$ratinguser = $xoopsUser->getVar('uid');
	}

	//Make sure only 1 anonymous from an IP in a single day.
	$anonwaitdays = 1;
	$ip = getenv("REMOTE_ADDR");
	$lid = intval($HTTP_POST_VARS['lid']);
	$cid = intval($HTTP_POST_VARS['cid']);
	$rating = intval($HTTP_POST_VARS['rating']);

	// Check if Rating is Null
	if ($rating=="--") {
		redirect_header("ratefile.php?cid=".$cid."&amp;lid=".$lid."",4,_MD_NORATING);
		exit();
	}

	// Check if Download POSTER is voting (UNLESS Anonymous users allowed to post)
	if ($ratinguser != 0) {
		$result=$xoopsDB->query("SELECT submitter FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE lid=$lid");
		while(list($ratinguserDB)=$xoopsDB->fetchRow($result)) {
			if ($ratinguserDB==$ratinguser) {
				redirect_header("index.php",4,_MD_CANTVOTEOWN);
				exit();
			}
		}

		// Check if REG user is trying to vote twice.
		$result=$xoopsDB->query("SELECT ratinguser FROM ".$xoopsDB->prefix("mydownloads_votedata")." WHERE lid=$lid");
		while(list($ratinguserDB)=$xoopsDB->fetchRow($result)) {
			if ($ratinguserDB==$ratinguser) {
				redirect_header("index.php",4,_MD_VOTEONCE);
				exit();
			}
		}

	} else {

		// Check if ANONYMOUS user is trying to vote more than once per day.
		$yesterday = (time()-(86400 * $anonwaitdays));
		$result=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("mydownloads_votedata")." WHERE lid=$lid AND ratinguser=0 AND ratinghostname = '$ip'  AND ratingtimestamp > $yesterday");
		list($anonvotecount) = $xoopsDB->fetchRow($result);
		if ($anonvotecount >= 1) {
			redirect_header("index.php",4,_MD_VOTEONCE);
			exit();
		}
	}

	//All is well.  Add to Line Item Rate to DB.
	$newid = $xoopsDB->genId($xoopsDB->prefix("mydownloads_votedata")."_ratingid_seq");
	$datetime = time();
	$sql = sprintf("INSERT INTO %s (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix("mydownloads_votedata"), $newid, $lid, $ratinguser, $rating, $ip, $datetime);
	$xoopsDB->query($sql) or $eh("0013");

	//All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
	updaterating($lid);
	$ratemessage = _MD_VOTEAPPRE."<br />".sprintf(_MD_THANKYOU,$xoopsConfig[sitename]);
	redirect_header("index.php",4,$ratemessage);
	exit();

} else {

	$xoopsOption['template_main'] = 'mydownloads_ratefile.html';
    include XOOPS_ROOT_PATH."/header.php";
    $lid = intval($HTTP_GET_VARS['lid']);
    $result=$xoopsDB->query("SELECT title FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE lid=$lid");
    list($title) = $xoopsDB->fetchRow($result);
    $title = $myts->makeTboxData4Show($title);
    $xoopsTpl->assign('file', array('id' => $lid, 'cid' => $cid, 'title' => $myts->htmlSpecialChars($title)));
    $xoopsTpl->assign('lang_voteonce', _MD_VOTEONCE);
    $xoopsTpl->assign('lang_ratingscale', _MD_RATINGSCALE);
    $xoopsTpl->assign('lang_beobjective', _MD_BEOBJECTIVE);
    $xoopsTpl->assign('lang_donotvote', _MD_DONOTVOTE);
    $xoopsTpl->assign('lang_rateit', _MD_RATEIT);
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    include XOOPS_ROOT_PATH.'/footer.php';

}
include "footer.php";
?>
