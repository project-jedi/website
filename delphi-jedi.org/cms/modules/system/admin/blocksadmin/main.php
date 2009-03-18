<?php
// $Id: main.php,v 1.11 2003/09/30 06:44:49 okazu Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
	exit("Access Denied");
}
include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
include XOOPS_ROOT_PATH."/modules/system/admin/blocksadmin/blocksadmin.php";

$op = "list";
if ( isset($HTTP_POST_VARS) ) {
	foreach ( $HTTP_POST_VARS as $k => $v ) {
		$$k = $v;
  	}
}

if ( isset($HTTP_GET_VARS['op']) ) {
	if ($HTTP_GET_VARS['op'] == "edit" || $HTTP_GET_VARS['op'] == "delete" || $HTTP_GET_VARS['op'] == "delete_ok" || $HTTP_GET_VARS['op'] == "clone" || $HTTP_GET_VARS['op'] == 'previewpopup') {
		$op = $HTTP_GET_VARS['op'];
		$bid = isset($HTTP_GET_VARS['bid']) ? intval($HTTP_GET_VARS['bid']) : 0;
	}
}

if (isset($previewblock)) {
	xoops_cp_header();
	include_once XOOPS_ROOT_PATH.'/class/template.php';
	$xoopsTpl = new XoopsTpl();
	$xoopsTpl->xoops_setCaching(0);
	if (isset($bid)) {
		$block['bid'] = $bid;
		$block['form_title'] = _AM_EDITBLOCK;
		$myblock = new XoopsBlock($bid);
		$block['name'] = $myblock->getVar('name');
	} else {
		if ($op == 'save') {
			$block['form_title'] = _AM_ADDBLOCK;
		} else {
			$block['form_title'] = _AM_CLONEBLOCK;
		}
		$myblock = new XoopsBlock();
		$myblock->setVar('block_type', 'C');
	}
	$myts =& MyTextSanitizer::getInstance();
	$myblock->setVar('title', $myts->stripSlashesGPC($btitle));
	$myblock->setVar('content', $myts->stripSlashesGPC($bcontent));
	$dummyhtml = '<html><head><meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" /><meta http-equiv="content-language" content="'._LANGCODE.'" /><title>'.$xoopsConfig['sitename'].'</title><link rel="stylesheet" type="text/css" media="all" href="'.getcss($xoopsConfig['theme_set']).'" /></head><body><table><tr><th>'.$myblock->getVar('title').'</th></tr><tr><td>'.$myblock->getContent('S', $bctype).'</td></tr></table></body></html>';

	$dummyfile = '_dummyfile_'.time().'.html';
	$fp = fopen(XOOPS_CACHE_PATH.'/'.$dummyfile, 'w');
	fwrite($fp, $dummyhtml);
	fclose($fp);
	$block['edit_form'] = false;
	$block['template'] = '';
	$block['op'] = $op;
	$block['side'] = $bside;
	$block['weight'] = $bweight;
	$block['visible'] = $bvisible;
	$block['title'] = $myblock->getVar('title', 'E');
	$block['content'] = $myblock->getVar('content', 'E');
	$block['modules'] =& $bmodule;
	$block['ctype'] = isset($bctype) ? $bctype : $myblock->getVar('c_type');
	$block['is_custom'] = true;
	echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$block['form_title'].'<br /><br />';
	include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
	$form->display();
	xoops_cp_footer();
	echo '<script type="text/javascript">
	<!--//
	preview_window = openWithSelfMain("'.XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin&op=previewpopup&file='.$dummyfile.'", "popup", 250, 200);
	//-->
	</script>';
	exit();
}

if ($op == 'previewpopup') {
	$file = str_replace('..', '', XOOPS_CACHE_PATH.'/'.trim($HTTP_GET_VARS['file']));
	if (file_exists($file)) {
		include $file;
		@unlink($file);
	}
	exit();
}

if ( $op == "list" ) {
	xoops_cp_header();
	list_blocks();
	xoops_cp_footer();
	exit();
}

if ( $op == "order" ) {
	foreach (array_keys($bid) as $i) {
		if ( $oldweight[$i] != $weight[$i] || $oldvisible[$i] != $visible[$i] || $oldside[$i] != $side[$i] )
		order_block($bid[$i], $weight[$i], $visible[$i], $side[$i]);
	}
	redirect_header("admin.php?fct=blocksadmin",1,_AM_DBUPDATED);
	exit();
}

if ( $op == "save" ) {
	save_block($bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bmodule, $bcachetime);
	exit();
}

if ( $op == "update" ) {
	$bcachetime = isset($bcachetime) ? intval($bcachetime) : 0;
	$options = isset($options) ? $options : array();
	$bcontent = isset($bcontent) ? $bcontent : '';
	$bctype = isset($bctype) ? $bctype : '';
	update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options);
}


if ( $op == "delete_ok" ) {
    delete_block_ok($bid);
	exit();
}

if ( $op == "delete" ) {
    xoops_cp_header();
	delete_block($bid);
    xoops_cp_footer();
	exit();
}

if ( $op == "edit" ) {
    xoops_cp_header();
	edit_block($bid);
    xoops_cp_footer();
	exit();
}
/*
if ($op == 'clone') {
	clone_block($bid);
}

if ($op == 'clone_ok') {
	clone_block_ok($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options);
}
*/
?>