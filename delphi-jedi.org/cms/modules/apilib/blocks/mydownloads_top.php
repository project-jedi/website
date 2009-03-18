<?php
// $Id: mydownloads_top.php,v 1.8 2003/03/28 04:04:50 w4z004 Exp $
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

/******************************************************************************
 * Function: b_mydownloads_top_show
 * Input   : $options[0] = date for the most recent downloads
 *                    hits for the most popular downloads
 *           $block['content'] = The optional above content
 *           $options[1]   = How many downloads are displayes
 * Output  : Returns the most recent or most popular downloads
 ******************************************************************************/
function b_mydownloads_top_show($options) {
	global $xoopsDB;
	$block = array();
	$myts =& MyTextSanitizer::getInstance();
	//$order = date for most recent reviews
	//$order = hits for most popular reviews
	$result = $xoopsDB->query("SELECT lid, cid, title, date, hits FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE status>0 ORDER BY ".$options[0]." DESC",$options[1],0);
	while($myrow=$xoopsDB->fetchArray($result)){
		$download = array();
		$title = $myts->makeTboxData4Show($myrow["title"]);
		if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($myrow['title']) >= $options[2]) {
				$title = $myts->makeTboxData4Show(substr($myrow['title'],0,($options[2] -1)))."...";
			}
		}
		$download['id'] = $myrow['lid'];
		$download['cid'] = $myrow['cid'];
		$download['title'] = $title;
		if($options[0] == "date"){
			$download['date'] = formatTimestamp($myrow['date'],"s");
		}elseif($options[0] == "hits"){
			$download['hits'] = $myrow['hits'];
		}
		$block['downloads'][] = $download;
	}
	return $block;
}

function b_mydownloads_top_edit($options) {
	$form = ""._MB_MYDOWNLOADS_DISP."&nbsp;";
	$form .= "<input type=\"hidden\" name=\"options[]\" value=\"";
	if($options[0] == "date"){
		$form .= "date\"";
	}else {
		$form .= "hits\"";
	}
	$form .= " />";
	$form .= "<input type=\"text\" name=\"options[]\" value=\"".$options[1]."\" />&nbsp;"._MB_MYDOWNLOADS_FILES."";
	$form .= "&nbsp;<br>"._MB_MYDOWNLOADS_CHARS."&nbsp;<input type='text' name='options[]' value='".$options[2]."' />&nbsp;"._MB_MYDOWNLOADS_LENGTH."";
	return $form;
}
?>
