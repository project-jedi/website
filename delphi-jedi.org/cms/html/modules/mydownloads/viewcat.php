<?php
// $Id: viewcat.php,v 1.14 2003/06/23 02:27:25 okazu Exp $
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
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$xoopsOption['template_main'] = 'mydownloads_viewcat.html';
$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("mydownloads_cat"),"cid","pid");

$cid = intval($HTTP_GET_VARS['cid']);
include XOOPS_ROOT_PATH."/header.php";

if (isset($HTTP_GET_VARS['show']) && $HTTP_GET_VARS['show']!="") {
	$show = intval($HTTP_GET_VARS['show']);
} else {
	$show = $xoopsModuleConfig['perpage'];
}
$min = isset($HTTP_GET_VARS['min']) ? intval($HTTP_GET_VARS['min']) : 0;
if (!isset($max)) {
	$max = $min + $show;
}
if(isset($HTTP_GET_VARS['orderby'])) {
	$orderby = convertorderbyin($HTTP_GET_VARS['orderby']);
} else {
	$orderby = "title ASC";
}


$pathstring = "<a href='index.php'>"._MD_MAIN."</a>&nbsp;:&nbsp;";
$pathstring .= $mytree->getNicePathFromId($cid, "title", "viewcat.php?op=");
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);
// get child category objects
$arr=array();
$arr=$mytree->getFirstChild($cid, "title");
if ( count($arr) > 0 ) {
	$scount = 1;
	foreach($arr as $ele){
		$sub_arr=array();
		$sub_arr=$mytree->getFirstChild($ele['cid'], "title");
		$space = 0;
		$chcount = 0;
		$infercategories = "";
		foreach($sub_arr as $sub_ele){
			$chtitle=$myts->makeTboxData4Show($sub_ele['title']);
			if ($chcount>5){
				$infercategories .= "...";
				break;
			}
			if ($space>0) {
				$infercategories .= ", ";
			}
			$infercategories .= "<a href=\"".XOOPS_URL."/modules/mydownloads/viewcat.php?cid=".$sub_ele['cid']."\">".$chtitle."</a>";
			$space++;
			$chcount++;
		}
		$xoopsTpl->append('subcategories', array('title' => $myts->makeTboxData4Show($ele['title']), 'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallinks' => getTotalItems($ele['cid'], 1), 'count' => $scount));
		$scount++;
	}
}

if ($xoopsModuleConfig['useshots'] == 1) {
	$xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
	$xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
	$xoopsTpl->assign('show_screenshot', true);
	$xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
}

if (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}
$fullcountresult=$xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE cid=$cid AND status>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);
$page_nav = '';
if($numrows>0){
	$xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
	$xoopsTpl->assign('lang_lastupdate', _MD_LASTUPDATEC);
	$xoopsTpl->assign('lang_hits', _MD_HITSC);
	$xoopsTpl->assign('lang_ratingc', _MD_RATINGC);
	$xoopsTpl->assign('lang_email', _MD_EMAILC);
	$xoopsTpl->assign('lang_ratethissite', _MD_RATETHISFILE);
	$xoopsTpl->assign('lang_reportbroken', _MD_REPORTBROKEN);
	$xoopsTpl->assign('lang_tellafriend', _MD_TELLAFRIEND);
	$xoopsTpl->assign('lang_modify', _MD_MODIFY);
	$xoopsTpl->assign('lang_version' , _MD_VERSION);
	$xoopsTpl->assign('lang_subdate' , _MD_SUBMITDATE);
	$xoopsTpl->assign('lang_dlnow' , _MD_DLNOW);
	$xoopsTpl->assign('lang_category' , _MD_CATEGORYC);
	$xoopsTpl->assign('lang_size' , _MD_FILESIZE);
	$xoopsTpl->assign('lang_platform' , _MD_SUPPORTEDPLAT);
	$xoopsTpl->assign('lang_homepage' , _MD_HOMEPAGE);
	$xoopsTpl->assign('lang_comments' , _COMMENTS);
	$xoopsTpl->assign('show_links', true);
	$q = "SELECT d.lid, d.title, d.url, d.homepage, d.version, d.size, d.platform, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, t.description FROM ".$xoopsDB->prefix("mydownloads_downloads")." d, ".$xoopsDB->prefix("mydownloads_text")." t WHERE cid=".$cid." AND d.status>0 AND d.lid=t.lid ORDER BY ".$orderby."";
	$result = $xoopsDB->query($q,$show,$min);

//if 2 or more items in result, show the sort menu
	if($numrows>1){
		$xoopsTpl->assign('show_nav', true);
		$orderbyTrans = convertorderbytrans($orderby);
		$xoopsTpl->assign('lang_sortby', _MD_SORTBY);
		$xoopsTpl->assign('lang_title', _MD_TITLE);
		$xoopsTpl->assign('lang_date', _MD_DATE);
		$xoopsTpl->assign('lang_rating', _MD_RATING);
		$xoopsTpl->assign('lang_popularity', _MD_POPULARITY);
		$xoopsTpl->assign('lang_cursortedby', sprintf(_MD_CURSORTBY, convertorderbytrans($orderby)));
	}
	while(list($lid, $dtitle, $url, $homepage, $version, $size, $platform, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description)=$xoopsDB->fetchRow($result)) {
		$path = $mytree->getPathFromId($cid, "title");
		$path = substr($path, 1);
		$path = str_replace("/"," <img src='".XOOPS_URL."/modules/mydownloads/images/arrow.gif' board='0' alt=''> ",$path);
		$rating = number_format($rating, 2);
		$dtitle = $myts->makeTboxData4Show($dtitle);
		$url = $myts->makeTboxData4Show($url);
		$homepage = $myts->makeTboxData4Show($homepage);
		$version = $myts->makeTboxData4Show($version);
		$size = PrettySize($myts->makeTboxData4Show($size));
		$platform = $myts->makeTboxData4Show($platform);
		$logourl = $myts->makeTboxData4Show($logourl);
		$datetime = formatTimestamp($time,"s");
		$description = $myts->makeTareaData4Show($description,0); //no html
		$new = newdownloadgraphic($time, $status);
		$pop = popgraphic($hits);
		if ($isadmin) {
			$adminlink = '<a href="'.XOOPS_URL.'/modules/mydownloads/admin/index.php?lid='.$lid.'&amp;fct=mydownloads&amp;op=modDownload"><img src="'.XOOPS_URL.'/modules/mydownloads/images/editicon.gif" border="0" alt="'._MD_EDITTHISDL.'" /></a>';
		} else {
			$adminlink = '';
		}
		if ($votes == 1) {
			$votestring = _MD_ONEVOTE;
		} else {
			$votestring = sprintf(_MD_NUMVOTES,$votes);
		}
		$xoopsTpl->append('file', array('id' => $lid, 'cid' => $cid, 'rating' => $rating,'title' => $dtitle.$new.$pop,'logourl' => $logourl,'updated' => $datetime,'description' => $description,'adminlink' => $adminlink,'hits' => $hits,'votes' => $votestring, 'comments' => $comments, 'platform' => $platform,'size'  => $size,'homepage' => $homepage,'version'  => $version,'category'  => $path,'lang_dltimes' => sprintf(_MD_DLTIMES,$hits),'mail_subject' => rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename'])),'mail_body' => rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/mydownloads/singlefile.php?cid='.$cid.'&amp;lid='.$lid)));
	}
	$orderby = convertorderbyout($orderby);
//Calculates how many pages exist.  Which page one should be on, etc...
	$downpages = ceil($numrows / $show);
//Page Numbering
	if ($downpages!=1 && $downpages!=0) {
		$prev = $min - $show;
		if ($prev>=0) {
			$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show'><b><u>&laquo</u></b></a>&nbsp;";
		}
		$counter = 1;
		$currentpage = ($max / $show);
		while ( $counter<=$downpages ) {
			$mintemp = ($show * $counter) - $show;
			if ($counter == $currentpage) {
			$page_nav .= "<b>($counter)</b>&nbsp;";
			} else {
				$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show'>$counter</a>&nbsp;";
			}
				$counter++;
		}
		if ( $numrows>$max ) {
			$page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$max&amp;orderby=$orderby&amp;show=$show'>";
				$page_nav .= "<b><u>&raquo;</u></b></a>";
		}
	}
}
$xoopsTpl->assign('page_nav', $page_nav);
include XOOPS_ROOT_PATH."/modules/mydownloads/footer.php";
?>
