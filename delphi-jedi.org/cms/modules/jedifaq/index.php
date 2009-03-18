<?php
// $Id: index.php,v 1.7 2003/03/17 05:40:40 okazu Exp $
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

include 'header.php';
$myts =& MyTextSanitizer::getInstance();
$cat_id = isset($HTTP_GET_VARS['cat_id']) ? intval($HTTP_GET_VARS['cat_id']) : 0;
if ($cat_id < 1) {
	// this page uses smarty template
	// this must be set before including main header.php
	$xoopsOption['template_main'] = 'jedifaq_index.html';
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsTpl->assign('lang_faq', _XD_DOCS);
	$xoopsTpl->assign('lang_categories', _XD_CATEGORIES);
	$result = $xoopsDB->query('SELECT category_id, category_title FROM '.$xoopsDB->prefix('jedifaq_categories').' ORDER BY category_order ASC');
	while (list($id, $name) = $xoopsDB->fetchRow($result)) {
		$category = array();
		$category['name'] = $myts->makeTboxData4Show($name);
		$category['id'] = $id;
		$sql = 'SELECT contents_id, contents_title FROM '.$xoopsDB->prefix('jedifaq_contents').' WHERE contents_visible=1 AND category_id='.$id.' ORDER BY contents_order ASC';
		$result2 = $xoopsDB->query($sql);
		while ($myrow = $xoopsDB->fetchArray($result2)) {
			$category['questions'][] = array('id' => $myrow['contents_id'], 'title' => $myts->makeTboxData4Show($myrow['contents_title']));
		}
		$xoopsTpl->append_by_ref('categories', $category);
		unset($category);
	}
} else {
	// this page uses smarty template
	// this must be set before including main header.php
	$xoopsOption['template_main'] = 'jedifaq_category.html';
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsTpl->assign('lang_faq', _XD_DOCS);
	$xoopsTpl->assign('lang_toc', _XD_TOC);
	$xoopsTpl->assign('lang_main', _XD_MAIN);
	$xoopsTpl->assign('lang_backtotop', _XD_BACKTOTOP);
	$xoopsTpl->assign('lang_backtoindex', _XD_BACKTOINDEX);
	$result = $xoopsDB->query('SELECT category_title FROM '.$xoopsDB->prefix('jedifaq_categories').' WHERE category_id='.$cat_id);
	list($name) = $xoopsDB->fetchRow($result);
	$xoopsTpl->assign('category_name', $myts->makeTboxData4Show($name));

	$result = $xoopsDB->query('SELECT contents_id, category_id, contents_title, contents_contents, contents_time, contents_nohtml, contents_nosmiley, contents_noxcode FROM '.$xoopsDB->prefix('jedifaq_contents').' WHERE contents_visible=1 AND category_id='.$cat_id.' ORDER BY contents_order ASC');
	$question = array();
	while (list($id, $cat_id, $title, $contents, $time, $nohtml, $nosmiley, $noxcode) = $xoopsDB->fetchRow($result)) {
		$title = $myts->makeTboxData4Show($title);
		$question['title'] = $title;
		$question['id'] = $id;
		$html = !empty($nohtml) ? 0 :1;
		$smiley = !empty($nosmiley) ? 0 :1;
		$xcode = !empty($noxcode) ? 0 :1;
		$question['answer'] = $myts->makeTareaData4Show($contents, $html, $smiley, $xcode);
		$xoopsTpl->append('questions', $question);
	}
	include XOOPS_ROOT_PATH.'/include/comment_view.php';
}

include XOOPS_ROOT_PATH.'/footer.php';
?>