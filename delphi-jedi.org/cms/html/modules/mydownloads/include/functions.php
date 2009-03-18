<?php
// $Id: functions.php,v 1.7 2003/02/20 12:57:01 okazu Exp $
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

function mainheader($mainlink=1) {
		echo "<br /><br /><p><a href=\"".XOOPS_URL."/modules/mydownloads/index.php\"><img src=\"".XOOPS_URL."/modules/mydownloads/images/logo-en.gif\" border=\"0\" alt\"\" /></a></p><br /><br />";
}

function newdownloadgraphic($time, $status) {
	$count = 7;
	$new = '';
	$startdate = (time()-(86400 * $count));
		if ($startdate < $time) {
		if($status==1){
			$new = "&nbsp;<img src=\"".XOOPS_URL."/modules/mydownloads/images/newred.gif\" alt=\""._MD_NEWTHISWEEK."\" />";
		}elseif($status==2){
			$new = "&nbsp;<img src=\"".XOOPS_URL."/modules/mydownloads/images/update.gif\" alt=\""._MD_UPTHISWEEK."\" />";
		}
	}
	return $new;
}

function popgraphic($hits) {
	global $xoopsModuleConfig;
	if ($hits >= $xoopsModuleConfig['popular']) {
		return "&nbsp;<img src =\"".XOOPS_URL."/modules/mydownloads/images/pop.gif\" alt=\""._MD_POPULAR."\" />";
	}
	return '';
}
//Reusable Link Sorting Functions
function convertorderbyin($orderby) {
	switch (trim($orderby)) {
	case "titleA":
		$orderby = "title ASC";
		break;
	case "dateA":
		$orderby = "date ASC";
		break;
	case "hitsA":
		$orderby = "hits ASC";
		break;
	case "ratingA":
		$orderby = "rating ASC";
		break;
	case "titleD":
		$orderby = "title DESC";
		break;
	case "hitsD":
		$orderby = "hits DESC";
		break;
	case "ratingD":
		$orderby = "rating DESC";
		break;
	case"dateD":
	default:
		$orderby = "date DESC";
		break;
	}
	return $orderby;
}
function convertorderbytrans($orderby) {
	if ($orderby == "hits ASC")   $orderbyTrans = _MD_POPULARITYLTOM;
	if ($orderby == "hits DESC")    $orderbyTrans = _MD_POPULARITYMTOL;
	if ($orderby == "title ASC")    $orderbyTrans = _MD_TITLEATOZ;
	if ($orderby == "title DESC")   $orderbyTrans = _MD_TITLEZTOA;
	if ($orderby == "date ASC") $orderbyTrans = _MD_DATEOLD;
	if ($orderby == "date DESC")   $orderbyTrans = _MD_DATENEW;
	if ($orderby == "rating ASC")  $orderbyTrans = _MD_RATINGLTOH;
	if ($orderby == "rating DESC") $orderbyTrans = _MD_RATINGHTOL;
	return $orderbyTrans;
}
function convertorderbyout($orderby) {
	if ($orderby == "title ASC")            $orderby = "titleA";
	if ($orderby == "date ASC")            $orderby = "dateA";
	if ($orderby == "hits ASC")          $orderby = "hitsA";
	if ($orderby == "rating ASC")        $orderby = "ratingA";
	if ($orderby == "title DESC")              $orderby = "titleD";
	if ($orderby == "date DESC")            $orderby = "dateD";
	if ($orderby == "hits DESC")          $orderby = "hitsD";
	if ($orderby == "rating DESC")        $orderby = "ratingD";
	return $orderby;
}

function PrettySize($size) {
	$mb = 1024*1024;
	if ( $size > $mb ) {
		$mysize = sprintf ("%01.2f",$size/$mb) . " MB";
	}
	elseif ( $size >= 1024 ) {
		$mysize = sprintf ("%01.2f",$size/1024) . " KB";
	}
	else {
	    $mysize = sprintf(_MD_NUMBYTES,$size);
	}
	return $mysize;
}

//updates rating data in itemtable for a given item
function updaterating($sel_id){
	global $xoopsDB;
	$query = "select rating FROM ".$xoopsDB->prefix("mydownloads_votedata")." WHERE lid = ".$sel_id."";
	$voteresult = $xoopsDB->query($query);
		$votesDB = $xoopsDB->getRowsNum($voteresult);
	$totalrating = 0;
		while(list($rating)=$xoopsDB->fetchRow($voteresult)){
		$totalrating += $rating;
	}
	$finalrating = $totalrating/$votesDB;
	$finalrating = number_format($finalrating, 4);
	$sql = sprintf("UPDATE %s SET rating = %u, votes = %u WHERE lid = %u", $xoopsDB->prefix("mydownloads_downloads"), $finalrating, $votesDB, $sel_id);
	$xoopsDB->query($sql);
}

//returns the total number of items in items table that are accociated with a given table $table id
function getTotalItems($sel_id, $status=""){
	global $xoopsDB, $mytree;
	$count = 0;
	$arr = array();
	$query = "select count(*) from ".$xoopsDB->prefix("mydownloads_downloads")." where cid=".$sel_id."";
	if($status!=""){
		$query .= " and status>=$status";
	}
	$result = $xoopsDB->query($query);
	list($thing) = $xoopsDB->fetchRow($result);
	$count = $thing;
	$arr = $mytree->getAllChildId($sel_id);
	$size = count($arr);
	for($i=0;$i<$size;$i++){
		$query2 = "select count(*) from ".$xoopsDB->prefix("mydownloads_downloads")." where cid=".$arr[$i]."";
		if($status!=""){
			$query2 .= " and status>=$status";
		}
		$result2 = $xoopsDB->query($query2);
		list($thing) = $xoopsDB->fetchRow($result2);
		$count += $thing;
	}
	return $count;
}
?>