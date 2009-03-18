<?php
// $Id: index.php,v 1.11 2003/03/27 12:11:05 w4z004 Exp $
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

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix("mydownloads_cat"),"cid","pid");
$xoopsOption['template_main'] = 'mydownloads_index.html';
include XOOPS_ROOT_PATH."/header.php";

$sql = "SELECT cid, title, imgurl FROM ".$xoopsDB->prefix("mydownloads_cat")." WHERE pid = 0 ORDER BY title";
$result=$xoopsDB->query($sql);

$count = 1;
while($myrow = $xoopsDB->fetchArray($result)) {
	$title = $myts->makeTboxData4Show($myrow['title']);
	if ($myrow['imgurl'] && $myrow['imgurl'] != "http://"){
		$imgurl = $myts->makeTboxData4Edit($myrow['imgurl']);
	} else {
		$imgurl = '';
	}
	$totaldownload = getTotalItems($myrow['cid'], 1);

	// get child category objects
	$arr=array();
	$arr=$mytree->getFirstChild($myrow['cid'], "title");
	$space = 0;
	$chcount = 0;
	$subcategories = "";
	foreach($arr as $ele){
		$chtitle=$myts->makeTboxData4Show($ele['title']);
		if ($chcount>5){
			$subcategories .= "...";
			break;
		}
		if ($space>0) {
			$subcategories .= ", ";
		}
		$subcategories .= "<a href=\"".XOOPS_URL."/modules/mydownloads/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
		$space++;
		$chcount++;
	}	
	$xoopsTpl->append('categories', array('image' => $imgurl, 'id' => $myrow['cid'], 'title' => $myts->makeTboxData4Show($myrow['title']), 'subcategories' => $subcategories, 'totaldownloads' => $totaldownload, 'count' => $count));
	$count++;	
}
list($numrows)=$xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE status>0"));
$xoopsTpl->assign('lang_thereare', sprintf(_MD_THEREARE,$numrows));
if ($xoopsModuleConfig['useshots'] == 1) {
	$xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
	$xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
	$xoopsTpl->assign('show_screenshot', true);
	$xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
}
if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}
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
$xoopsTpl->assign('lang_latestlistings' , _MD_LATESTLIST);
$xoopsTpl->assign('lang_comments' , _COMMENTS);
$result = $xoopsDB->query("SELECT d.lid, d.cid, d.title, d.url, d.homepage, d.version, d.size, d.platform, d.logourl, d.status, d.date, d.hits, d.rating, d.votes, d.comments, t.description FROM ".$xoopsDB->prefix("mydownloads_downloads")." d, ".$xoopsDB->prefix("mydownloads_text")." t WHERE d.status>0 AND d.lid=t.lid ORDER BY date DESC", $xoopsModuleConfig['newdownloads'], 0);
while(list($lid, $cid, $dtitle, $url, $homepage, $version, $size, $platform, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description)=$xoopsDB->fetchRow($result)) {
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
		$adminlink = '<a href="'.XOOPS_URL.'/modules/mydownloads/admin/index.php?lid='.$lid.'&fct=mydownloads&op=modDownload"><img src="'.XOOPS_URL.'/modules/mydownloads/images/editicon.gif" border="0" alt="'._MD_EDITTHISDL.'" /></a>';
	} else {
		$adminlink = '';
	}
	if ($votes == 1) {
		$votestring = _MD_ONEVOTE;
	} else {
		$votestring = sprintf(_MD_NUMVOTES,$votes);
	}
		$xoopsTpl->append('file', array('id' => $lid,'cid'=>$cid,'rating' => $rating,'title' => $dtitle.$new.$pop,'logourl' => $logourl,'updated' => $datetime,'description' => $description,'adminlink' => $adminlink,'hits' => $hits,'votes' => $votestring, 'comments' => $comments, 'platform' => $platform,'size'  => $size,'homepage' => $homepage,'version'  => $version,'category'  => $path,'lang_dltimes' => sprintf(_MD_DLTIMES,$hits),'mail_subject' => rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename'])),'mail_body' => rawurlencode(sprintf(_MD_INTFILEFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/mydownloads/singlefile.php?cid='.$cid.'&amp;lid='.$lid)));
}

include XOOPS_ROOT_PATH."/modules/mydownloads/footer.php";
?>
