<?php
// $Id: blocksadmin.php,v 1.20 2003/10/02 07:16:46 okazu Exp $
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
// check if the user is authorised
if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
	include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';

	function list_blocks()
	{
		global $xoopsUser, $xoopsConfig, $HTTP_GET_VARS;
		include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
		//OpenTable();
		$selmod = isset($HTTP_GET_VARS['selmod']) ? intval($HTTP_GET_VARS['selmod']) : 0;
		$selvis = isset($HTTP_GET_VARS['selvis']) ? intval($HTTP_GET_VARS['selvis']) : 2;
		$selgrp = isset($HTTP_GET_VARS['selgrp']) ? intval($HTTP_GET_VARS['selgrp']) : XOOPS_GROUP_USERS;
		echo "
		<h4 style='text-align:left;'>"._AM_BADMIN."</h4>";
		echo '<form action="admin.php" method="get">';
		$form = "<select size=\"1\" name=\"selmod\" onchange=\"location='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;selvis=$selvis&amp;selgrp=$selgrp&amp;selmod='+this.options[this.selectedIndex].value\">";
		$module_handler =& xoops_gethandler('module');
		$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
		$criteria->add(new Criteria('isactive', 1));
		$module_list =& $module_handler->getList($criteria);
		$toponlyblock = false;
		$module_list[-1] = _AM_TOPPAGE;
		$selmod = isset($HTTP_GET_VARS['selmod']) ? intval($HTTP_GET_VARS['selmod']) : -1;
		ksort($module_list);
		foreach ($module_list as $k => $v) {
			$sel = '';
			if ($k == $selmod) {
				$sel = ' selected="selected"';
			}
			$form .= '<option value="'.$k.'"'.$sel.'>'.$v.'</option>';
		}
		$form .= '</select>&nbsp;<input type="hidden" name="fct" value="blocksadmin" />';
		printf(_AM_SVISIBLEIN, $form);
		$member_handler =& xoops_gethandler('member');
		$group_list =& $member_handler->getGroupList();
		$group_sel = _AM_GROUP." <select size=\"1\" name=\"selgrp\" onchange=\"location='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;selvis=$selvis&amp;selmod=$selmod&amp;selgrp='+this.options[this.selectedIndex].value\">";
		$group_list[0] = '#'._AM_UNASSIGNED; // fix for displaying blocks unassigned to any group
		foreach ($group_list as $k => $v) {
			$sel = '';
			if ($k == $selgrp) {
				$sel = ' selected="selected"';
			}
			$group_sel .= '<option value="'.$k.'"'.$sel.'>'.$v.'</option>';
		}
		$group_sel .= '</select> ';
		echo $group_sel;
		echo _AM_VISIBLE." <select size=\"1\" name=\"selvis\" onchange=\"location='".XOOPS_URL."/modules/system/admin.php?fct=blocksadmin&amp;selmod=$selmod&amp;selgrp=$selgrp&amp;selvis='+this.options[this.selectedIndex].value\">";
		$selvis0 = $selvis1 = $selvis2 = "";
		switch($selvis){
		case 0:
			$selvis0 = 'selected="selected"';
			break;
		case 1:
			$selvis1 = 'selected="selected"';
			break;
		case 2:
		default:
			$selvis2 = 'selected="selected"';
			break;
		}
		echo '<option value="0" '.$selvis0.'>'._NO.'</option>';
		echo '<option value="1" '.$selvis1.'>'._YES.'</option>';
		echo '<option value="2" '.$selvis2.'>'._ALL.'</option>';
		echo '</select> <input type="submit" value="'._GO.'" name="selsubmit" />';
		echo '</form>';
		echo "<form action='admin.php' name='blockadmin' method='post'>
		<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
		<tr valign='middle'><th width='20%'>"._AM_BLKDESC."</th><th>"._AM_TITLE."</th><th>"._AM_MODULE."</th><th align='center' nowrap='nowrap'>"._AM_SIDE."<br />"._LEFT."-"._CENTER."-"._RIGHT."</th><th align='center'>"._AM_WEIGHT."</th><th align='center'>"._AM_VISIBLE."</th><th align='right'>"._AM_ACTION."</th></tr>
		";
		if ($selvis == 2) $selvis = null;
		if ($selgrp == 0) {
			// get blocks that are not assigned to any groups
			$block_arr =& XoopsBlock::getNonGroupedBlocks($selmod, $toponlyblock, $selvis, 'b.side,b.weight,b.bid');
		} else {
			$block_arr =& XoopsBlock::getAllByGroupModule($selgrp, $selmod, $toponlyblock, $selvis, 'b.side,b.weight,b.bid');
		}
		$block_count = count($block_arr);
		$class = 'even';
		$module_list2 =& $module_handler->getList();
		// for custom blocks
		$module_list2[0] = '&nbsp;';
		foreach (array_keys($block_arr) as $i) {
			$sel0 = $sel1 = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = "";
			if ( $block_arr[$i]->getVar("visible") == 1 ) {
				$sel1 = " checked='checked'";
			} else {
				$sel0 = " checked='checked'";
			}
			if ( $block_arr[$i]->getVar("side") == XOOPS_SIDEBLOCK_LEFT){
				$ssel0 = " checked='checked'";
			} elseif ( $block_arr[$i]->getVar("side") == XOOPS_SIDEBLOCK_RIGHT ){
				$ssel1 = " checked='checked'";
			} elseif ( $block_arr[$i]->getVar("side") == XOOPS_CENTERBLOCK_LEFT ){
				$ssel2 = " checked='checked'";
			} elseif ( $block_arr[$i]->getVar("side") == XOOPS_CENTERBLOCK_RIGHT ){
				$ssel4 = " checked='checked'";
			} elseif ( $block_arr[$i]->getVar("side") == XOOPS_CENTERBLOCK_CENTER ){
				$ssel3 = " checked='checked'";
			}
			if ( $block_arr[$i]->getVar("title") == "" ) {
				$title = "&nbsp;";
			} else {
				$title = $block_arr[$i]->getVar("title");
			}
			$name = $block_arr[$i]->getVar("name");
			echo "<tr valign='top'><td class='$class'>".$name."</td><td class='$class'>".$title."</td><td class='$class'>".$module_list2[$block_arr[$i]->getVar('mid')]."</td><td class='$class' align='center' nowrap='nowrap'><input type='radio' name='side[$i]' value='".XOOPS_SIDEBLOCK_LEFT."'$ssel0 />-<input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_LEFT."'$ssel2 /><input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_CENTER."'$ssel3 /><input type='radio' name='side[$i]' value='".XOOPS_CENTERBLOCK_RIGHT."'$ssel4 />-<input type='radio' name='side[$i]' value='".XOOPS_SIDEBLOCK_RIGHT."'$ssel1 /></td><td class='$class' align='center'><input type='text' name='weight[$i]' value='".$block_arr[$i]->getVar("weight")."' size='5' maxlength='5' /></td><td class='$class' align='center' nowrap><input type='radio' name='visible[$i]' value='1'$sel1>"._YES."&nbsp;<input type='radio' name='visible[$i]' value='0'$sel0>"._NO."</td><td class='$class' align='right'><a href='admin.php?fct=blocksadmin&amp;op=edit&amp;bid=".$block_arr[$i]->getVar("bid")."'>"._EDIT."</a>";
			if ($block_arr[$i]->getVar('block_type') == 'C') {
				echo "&nbsp;<a href='admin.php?fct=blocksadmin&amp;op=delete&amp;bid=".$block_arr[$i]->getVar("bid")."'>"._DELETE."</a>";
			}
			echo "
			<input type='hidden' name='oldside[$i]' value='".$block_arr[$i]->getVar('side')."' />
			<input type='hidden' name='oldweight[$i]' value='".$block_arr[$i]->getVar('weight')."' />
			<input type='hidden' name='oldvisible[$i]' value='".$block_arr[$i]->getVar('visible')."' />
			<input type='hidden' name='bid[$i]' value='".$i."' />
			</td></tr>
			";
			$class = ($class == 'even') ? 'odd' : 'even';
		}
		echo "<tr><td class='foot' align='center' colspan='7'>
		<input type='hidden' name='fct' value='blocksadmin' />
		<input type='hidden' name='op' value='order' />
		<input type='submit' name='submit' value='"._SUBMIT."' />
		</td></tr></table>
		</form>
		<br /><br />";

		$block = array('form_title' => _AM_ADDBLOCK, 'side' => 0, 'weight' => 0, 'visible' => 1, 'title' => '', 'content' => '', 'modules' => array(-1), 'is_custom' => true, 'ctype' => 'H', 'cachetime' => 0, 'op' => 'save', 'edit_form' => false);
        include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
		$form->display();
	}

	function save_block($bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bmodule, $bcachetime)
	{
		global $xoopsUser;
		if (empty($bmodule)) {
			xoops_cp_header();
			xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
			xoops_cp_footer();
			exit();
		}
		$myblock = new XoopsBlock();
		$myblock->setVar('side', $bside);
		$myblock->setVar('weight', $bweight);
		$myblock->setVar('visible', $bvisible);
		$myblock->setVar('weight', $bweight);
		$myblock->setVar('title', $btitle);
		$myblock->setVar('content', $bcontent);
		$myblock->setVar('c_type', $bctype);
		$myblock->setVar('block_type', 'C');
		$myblock->setVar('bcachetime', $bcachetime);
		switch ($bctype) {
		case 'H':
			$name = _AM_CUSTOMHTML;
			break;
		case 'P':
			$name = _AM_CUSTOMPHP;
			break;
		case 'S':
			$name = _AM_CUSTOMSMILE;
			break;
		default:
			$name = _AM_CUSTOMNOSMILE;
			break;
		}
		$myblock->setVar('name', $name);
		$newid = $myblock->store();
		if (!$newid) {
			xoops_cp_header();
			$myblock->getHtmlErrors();
			xoops_cp_footer();
			exit();
		}
		$db =& Database::getInstance();
		foreach ($bmodule as $bmid) {
			$sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id) VALUES ('.$newid.', '.intval($bmid).')';
			$db->query($sql);
		}
		$groups =& $xoopsUser->getGroups();
		$count = count($groups);
		for ($i = 0; $i < $count; $i++) {
			$sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_name, gperm_modid) VALUES (".$groups[$i].", ".$newid.", 'block_read', 1)";
			$db->query($sql);
		}
		redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
		exit();
	}

	function edit_block($bid)
	{
		$myblock = new XoopsBlock($bid);
		$db =& Database::getInstance();
		$sql = 'SELECT module_id FROM '.$db->prefix('block_module_link').' WHERE block_id='.intval($bid);
		$result = $db->query($sql);
		$modules = array();
		while ($row = $db->fetchArray($result)) {
			$modules[] = intval($row['module_id']);
		}
		$is_custom = ($myblock->getVar('block_type') == 'C' || $myblock->getVar('block_type') == 'E') ? true : false;
		$block = array('form_title' => _AM_EDITBLOCK, 'name' => $myblock->getVar('name'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'title' => $myblock->getVar('title', 'E'), 'content' => $myblock->getVar('content', 'E'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'update', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'));
		echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_EDITBLOCK.'<br /><br />';
		include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
		$form->display();
	}

	function update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options=array())
	{
		global $xoopsConfig;
		if (empty($bmodule)) {
			xoops_cp_header();
			xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
			xoops_cp_footer();
			exit();
		}
		$myblock = new XoopsBlock($bid);
		$myblock->setVar('side', $bside);
		$myblock->setVar('weight', $bweight);
		$myblock->setVar('visible', $bvisible);
		$myblock->setVar('title', $btitle);
		$myblock->setVar('content', $bcontent);
		$myblock->setVar('bcachetime', $bcachetime);
		if ( isset($options) && (count($options) > 0) ) {
			$options = implode('|', $options);
			$myblock->setVar('options', $options);
		}
		if ($myblock->getVar('block_type') == 'C') {
			switch ($bctype) {
			case 'H':
				$name = _AM_CUSTOMHTML;
				break;
			case 'P':
				$name = _AM_CUSTOMPHP;
				break;
			case 'S':
				$name = _AM_CUSTOMSMILE;
				break;
			default:
				$name = _AM_CUSTOMNOSMILE;
				break;
			}
			$myblock->setVar('name', $name);
			$myblock->setVar('c_type', $bctype);
		} else {
			$myblock->setVar('c_type', 'H');
		}
		$msg = _AM_DBUPDATED;
		if ($myblock->store() != false) {
			$db =& Database::getInstance();
			$sql = sprintf("DELETE FROM %s WHERE block_id = %u", $db->prefix('block_module_link'), $bid);
			$db->query($sql);
			foreach ($bmodule as $bmid) {
				$sql = sprintf("INSERT INTO %s (block_id, module_id) VALUES (%u, %d)", $db->prefix('block_module_link'), $bid, intval($bmid));
				$db->query($sql);
			}
			include_once XOOPS_ROOT_PATH.'/class/template.php';
			$xoopsTpl = new XoopsTpl();
			$xoopsTpl->xoops_setCaching(2);
			if ($myblock->getVar('template') != '') {
				if ($xoopsTpl->is_cached('db:'.$myblock->getVar('template'))) {
					if (!$xoopsTpl->clear_cache('db:'.$myblock->getVar('template'))) {
						$msg = 'Unable to clear cache for block ID'.$bid;
					}
				}
			} else {
				if ($xoopsTpl->is_cached('db:system_dummy.html', 'block'.$bid)) {
					if (!$xoopsTpl->clear_cache('db:system_dummy.html', 'block'.$bid)) {
						$msg = 'Unable to clear cache for block ID'.$bid;
					}
				}
			}
		} else {
			$msg = 'Failed update of block. ID:'.$bid;
		}
		redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,$msg);
		exit();
	}

	function delete_block($bid)
	{
		$myblock = new XoopsBlock($bid);
		if ( $myblock->getVar('block_type') == 'S' ) {
			$message = _AM_SYSTEMCANT;
			redirect_header('admin.php?fct=blocksadmin',4,$message);
			exit();
		} elseif ($myblock->getVar('block_type') == 'M') {
			$message = _AM_MODULECANT;
			redirect_header('admin.php?fct=blocksadmin',4,$message);
			exit();
		} else {
			xoops_confirm(array('fct' => 'blocksadmin', 'op' => 'delete_ok', 'bid' => $myblock->getVar('bid')), 'admin.php', sprintf(_AM_RUSUREDEL,$myblock->getVar('title')));
		}
	}

	function delete_block_ok($bid)
	{
		$myblock = new XoopsBlock($bid);
		$myblock->delete();
		if ($myblock->getVar('template') != '') {
			$tplfile_handler =& xoops_gethandler('tplfile');
			$btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
			if (count($btemplate) > 0) {
				$tplman->delete($btemplate[0]);
			}
		}
		redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
		exit();
	}

	function order_block($bid, $weight, $visible, $side)
	{
		$myblock = new XoopsBlock($bid);
		$myblock->setVar('weight', $weight);
		$myblock->setVar('visible', $visible);
		$myblock->setVar('side', $side);
		$myblock->store();
	}

	function clone_block($bid)
	{
		global $xoopsConfig;
		xoops_cp_header();
		$myblock = new XoopsBlock($bid);
		$db =& Database::getInstance();
		$sql = 'SELECT module_id FROM '.$db->prefix('block_module_link').' WHERE block_id='.intval($bid);
		$result = $db->query($sql);
		$modules = array();
		while ($row = $db->fetchArray($result)) {
			$modules[] = intval($row['module_id']);
		}
		$is_custom = ($myblock->getVar('block_type') == 'C' || $myblock->getVar('block_type') == 'E') ? true : false;
		$block = array('form_title' => _AM_CLONEBLOCK, 'name' => $myblock->getVar('name'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'content' => $myblock->getVar('content', 'N'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'clone_ok', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'));
		echo '<a href="admin.php?fct=blocksadmin">'. _AM_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'._AM_CLONEBLOCK.'<br /><br />';
		include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
		$form->display();
		xoops_cp_footer();
		exit();
	}

	function clone_block_ok($bid, $bside, $bweight, $bvisible, $bcachetime, $bmodule, $options)
	{
		global $xoopsUser;
		$block = new XoopsBlock($bid);
		$clone =& $block->clone();
		if (empty($bmodule)) {
			xoops_cp_header();
			xoops_error(sprintf(_AM_NOTSELNG, _AM_VISIBLEIN));
			xoops_cp_footer();
			exit();
		}
		$clone->setVar('side', $bside);
		$clone->setVar('weight', $bweight);
		$clone->setVar('visible', $bvisible);
		$clone->setVar('content', $bcontent);
		//$clone->setVar('title', $btitle);
		$clone->setVar('bcachetime', $bcachetime);
		if ( isset($options) && (count($options) > 0) ) {
			$options = implode('|', $options);
			$clone->setVar('options', $options);
		}
		$clone->setVar('bid', 0);
		if ($block->getVar('block_type') == 'C' || $block->getVar('block_type') == 'E') {
			$clone->setVar('block_type', 'E');
		} else {
			$clone->setVar('block_type', 'D');
		}
		$newid = $clone->store();
		if (!$newid) {
			xoops_cp_header();
			$clone->getHtmlErrors();
			xoops_cp_footer();
			exit();
		}
		if ($clone->getVar('template') != '') {
			$tplfile_handler =& xoops_gethandler('tplfile');
			$btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
			if (count($btemplate) > 0) {
				$tplclone =& $btemplate[0]->clone();
				$tplclone->setVar('tpl_id', 0);
				$tplclone->setVar('tpl_refid', $newid);
				$tplman->insert($tplclone);
			}
		}
		$db =& Database::getInstance();
		foreach ($bmodule as $bmid) {
			$sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id) VALUES ('.$newid.', '.$bmid.')';
			$db->query($sql);
		}
		$groups =& $xoopsUser->getGroups();
		$count = count($groups);
		for ($i = 0; $i < $count; $i++) {
			$sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (".$groups[$i].", ".$newid.", 1, 'block_read')";
			$db->query($sql);
		}
		redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,_AM_DBUPDATED);
	}
} else {
	echo "Access Denied";
}
?>