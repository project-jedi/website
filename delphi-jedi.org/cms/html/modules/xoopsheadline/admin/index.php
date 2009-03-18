<?php
// $Id: index.php,v 1.1 2003/04/11 12:55:58 okazu Exp $
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
include '../../../include/cp_header.php';
include XOOPS_ROOT_PATH.'/modules/xoopsheadline/include/functions.php';
$op = 'list';

if (isset($HTTP_GET_VARS['op']) && ($HTTP_GET_VARS['op'] == 'delete' || $HTTP_GET_VARS['op'] == 'edit')) {
	$op = $HTTP_GET_VARS['op'];
	$headline_id = intval($HTTP_GET_VARS['headline_id']);
}

if (isset($HTTP_POST_VARS)) {
	foreach ($HTTP_POST_VARS as $k => $v) {
		${$k} = $v;
	}
}

if ($op == 'list') {
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$hlman =& xoops_getmodulehandler('headline');;
	$headlines =& $hlman->getObjects();
	$count = count($headlines);
	xoops_cp_header();
	echo "<h4>"._AM_HEADLINES."</h4>";
	echo '<form name="headline_form" action="index.php" method="post"><table><tr><td>'._AM_SITENAME.'</td><td>'._AM_CACHETIME.'</td><td>'._AM_ENCODING.'</td><td>'._AM_DISPLAY.'</td><td>'._AM_ASBLOCK.'</td><td>'._AM_ORDER.'</td><td>&nbsp;</td></tr>';
	for ($i = 0; $i < $count; $i++) {
		echo '<tr><td>'.$headlines[$i]->getVar('headline_name').'</td>
		<td><select name="headline_cachetime[]">';
		$cachetime = array('3600' => sprintf(_HOUR, 1), '18000' => sprintf(_HOURS, 5), '86400' => sprintf(_DAY, 1), '259200' => sprintf(_DAYS, 3), '604800' => sprintf(_WEEK, 1), '2592000' => sprintf(_MONTH, 1));
		foreach ($cachetime as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $headlines[$i]->getVar('headline_cachetime')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>
		<td><select name="headline_encoding[]">';
		$encodings = array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII');
		foreach ($encodings as $value => $name) {
			echo '<option value="'.$value.'"';
			if ($value == $headlines[$i]->getVar('headline_encoding')) {
				echo ' selected="selecetd"';
			}
			echo '>'.$name.'</option>';
		}
		echo '</select></td>';
		echo '<td><input type="checkbox" value="1" name="headline_display['.$headlines[$i]->getVar('headline_id').']"';
		if (1 == $headlines[$i]->getVar('headline_display')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="checkbox" value="1" name="headline_asblock['.$headlines[$i]->getVar('headline_id').']"';
		if (1 == $headlines[$i]->getVar('headline_asblock')) {
			echo ' checked="checked"';
		}
		echo ' /></td>';
		echo '<td><input type="text" maxlength="3" size="4" name="headline_weight[]" value="'.$headlines[$i]->getVar('headline_weight').'" /><td><a href="index.php?op=edit&amp;headline_id='.$headlines[$i]->getVar('headline_id').'">'._EDIT.'</a>&nbsp;<a href="index.php?op=delete&amp;headline_id='.$headlines[$i]->getVar('headline_id').'">'._DELETE.'</a><input type="hidden" name="headline_id[]" value="'.$headlines[$i]->getVar('headline_id').'" /></td></tr>';
	}
	echo '</table><div style="text-align:center"><input type="hidden" name="op" value="update" /><input type="submit" name="headline_submit" value="'._SUBMIT.'" /></div></form>';
	$form = new XoopsThemeForm(_AM_ADDHEADL, 'headline_form', 'index.php');
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'headline_name', 50, 255), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'headline_url', 50, 255, 'http://'), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, 'http://'), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'headline_weight', 4, 3, 0));	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', 'utf-8');
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'headline_cachetime', 86400);
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);

	$form->insertBreak(_AM_MAINSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'headline_display', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_mainimg', 0, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'headline_mainfull', 0, _YES, _NO));
	$mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_mainmax', 10);
	$mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($mmax_sel);

	$form->insertBreak(_AM_BLOCKSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'headline_asblock', 1, _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_blockimg', 0, _YES, _NO));
	$bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_blockmax', 5);
	$bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($bmax_sel);


	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('op', 'addgo'));
	$form->addElement(new XoopsFormButton('', 'headline_submit2', _SUBMIT, 'submit'));
	$form->display();
	xoops_cp_footer();
	exit();
}

if ($op == 'update') {
	$hlman =& xoops_getmodulehandler('headline');;
	$i = 0;
	$msg = '';
	foreach ($headline_id as $id) {
		$hl =& $hlman->get($id);
		if (!is_object($hl)) {
			$i++;
			continue;
		}
		$headline_display[$id] = empty($headline_display[$id]) ? 0 : $headline_display[$id];
		$headline_asblock[$id] = empty($headline_asblock[$id]) ? 0 : $headline_asblock[$id];
		$old_cachetime = $hl->getVar('headline_cachetime');
		$hl->setVar('headline_cachetime', $headline_cachetime[$i]);
		$old_display = $hl->getVar('headline_display');
		$hl->setVar('headline_display', $headline_display[$id]);
		$hl->setVar('headline_weight', $headline_weight[$i]);
		$old_asblock = $hl->getVar('headline_asblock');
		$hl->setVar('headline_asblock', $headline_asblock[$id]);
		$old_encoding = $hl->getVar('headline_encoding');
		if (!$hlman->insert($hl)) {
			$msg .= '<br />'.sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
		} else {
			if ($hl->getVar('headline_xml') == '') {
				$renderer =& xoopsheadline_getrenderer($hl);
				$renderer->updateCache();
			}
		}
		$i++;
	}
	if ($msg != '') {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'addgo') {
	$hlman =& xoops_getmodulehandler('headline');;
	$hl =& $hlman->create();
	$hl->setVar('headline_name', $headline_name);
	$hl->setVar('headline_url', $headline_url);
	$hl->setVar('headline_rssurl', $headline_rssurl);
	$hl->setVar('headline_display', $headline_display);
	$hl->setVar('headline_weight', $headline_weight);
	$hl->setVar('headline_asblock', $headline_asblock);
	$hl->setVar('headline_encoding', $headline_encoding);
	$hl->setVar('headline_cachetime', $headline_cachetime);
	$hl->setVar('headline_mainfull', $headline_mainfull);
	$hl->setVar('headline_mainimg', $headline_mainimg);
	$hl->setVar('headline_mainmax)', $headline_mainmax);
	$hl->setVar('headline_blockimg', $headline_blockimg);
	$hl->setVar('headline_blockmax', $headline_blockmax);
	if (!$hlman->insert($hl)) {
		$msg = sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
		$msg .= '<br />'.$hl->getErrors();
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	} else {
		if ($hl->getVar('headline_xml') == '') {
			$renderer =& xoopsheadline_getrenderer($hl);
			$renderer->updateCache();
		}
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'edit') {
	if ($headline_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('headline');;
	$hl =& $hlman->get($headline_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$form = new XoopsThemeForm(_AM_EDITHEADL, 'headline_form', 'index.php');
	$form->addElement(new XoopsFormText(_AM_SITENAME, 'headline_name', 50, 255, $hl->getVar('headline_name')), true);
	$form->addElement(new XoopsFormText(_AM_URL, 'headline_url', 50, 255, $hl->getVar('headline_url')), true);
	$form->addElement(new XoopsFormText(_AM_URLEDFXML, 'headline_rssurl', 50, 255, $hl->getVar('headline_rssurl')), true);
	$form->addElement(new XoopsFormText(_AM_ORDER, 'headline_weight', 4, 3, $hl->getVar('headline_weight')));
	$enc_sel = new XoopsFormSelect(_AM_ENCODING, 'headline_encoding', $hl->getVar('headline_encoding'));
	$enc_sel->addOptionArray(array('utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'));
	$form->addElement($enc_sel);
	$cache_sel = new XoopsFormSelect(_AM_CACHETIME, 'headline_cachetime', $hl->getVar('headline_cachetime'));
	$cache_sel->addOptionArray(array('3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
	$form->addElement($cache_sel);

	$form->insertBreak(_AM_MAINSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_DISPLAY, 'headline_display', $hl->getVar('headline_display'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_mainimg', $hl->getVar('headline_mainimg'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPFULL, 'headline_mainfull', $hl->getVar('headline_mainfull'), _YES, _NO));
	$mmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_mainmax', $hl->getVar('headline_mainmax'));
	$mmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($mmax_sel);

	$form->insertBreak(_AM_BLOCKSETT);
	$form->addElement(new XoopsFormRadioYN(_AM_ASBLOCK, 'headline_asblock', $hl->getVar('headline_asblock'), _YES, _NO));
	$form->addElement(new XoopsFormRadioYN(_AM_DISPIMG, 'headline_blockimg', $hl->getVar('headline_blockimg'), _YES, _NO));
	$bmax_sel = new XoopsFormSelect(_AM_DISPMAX, 'headline_blockmax', $hl->getVar('headline_blockmax'));
	$bmax_sel->addOptionArray(array('1' => 1, '5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30));
	$form->addElement($bmax_sel);
	$form->insertBreak();
	$form->addElement(new XoopsFormHidden('headline_id', $hl->getVar('headline_id')));
	$form->addElement(new XoopsFormHidden('op', 'editgo'));
	$form->addElement(new XoopsFormButton('', 'headline_submit', _SUBMIT, 'submit'));
	xoops_cp_header();
	echo "<h4>"._AM_HEADLINES."</h4><br />";
	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$hl->getVar('headline_name').'<br /><br />';
	$form->display();
	xoops_cp_footer();
	exit();
}

if ($op == 'editgo') {
	$headline_id = intval($headline_id);
	if ($headline_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('headline');;
	$hl =& $hlman->get($headline_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	$hl->setVar('headline_name', $headline_name);
	$hl->setVar('headline_url', $headline_url);
	$hl->setVar('headline_encoding', $headline_encoding);
	$hl->setVar('headline_rssurl', $headline_rssurl);
	$hl->setVar('headline_display', $headline_display);
	$hl->setVar('headline_weight', $headline_weight);
	$hl->setVar('headline_asblock', $headline_asblock);
	$hl->setVar('headline_cachetime', $headline_cachetime);
	$hl->setVar('headline_mainfull', $headline_mainfull);
	$hl->setVar('headline_mainimg', $headline_mainimg);
	$hl->setVar('headline_mainmax', $headline_mainmax);
	$hl->setVar('headline_blockimg', $headline_blockimg);
	$hl->setVar('headline_blockmax', $headline_blockmax);
	if (!$hlman->insert($hl)) {
		$msg = sprintf(_AM_FAILUPDATE, $hl->getVar('headline_name'));
		$msg .= '<br />'.$hl->getErrors();
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error($msg);
		xoops_cp_footer();
		exit();
	} else {
		if ($hl->getVar('headline_xml') == '') {
			$renderer =& xoopsheadline_getrenderer($hl);
			$renderer->updateCache();
		}
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

if ($op == 'delete') {
	if ($headline_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('headline');;
	$hl =& $hlman->get($headline_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	xoops_cp_header();
	$name = $hl->getVar('headline_name');
	echo "<h4>"._AM_HEADLINES."</h4>";
	//echo '<a href="index.php">'. _AM_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br /><br />';
	xoops_confirm(array('op' => 'deletego', 'headline_id' => $hl->getVar('headline_id')), 'index.php', sprintf(_AM_WANTDEL, $name));
	xoops_cp_footer();
	exit();
}

if ($op == 'deletego') {
	$headline_id = intval($headline_id);
	if ($headline_id <= 0) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_INVALIDID);
		xoops_cp_footer();
		exit();
	}
	$hlman =& xoops_getmodulehandler('headline');;
	$hl =& $hlman->get($headline_id);
	if (!is_object($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(_AM_OBJECTNG);
		xoops_cp_footer();
		exit();
	}
	if (!$hlman->delete($hl)) {
		xoops_cp_header();
		echo "<h4>"._AM_HEADLINES."</h4>";
		xoops_error(sprintf(_AM_FAILDELETE, $hl->getVar('headline_name')));
		xoops_cp_footer();
		exit();
	}
	redirect_header('index.php', 2, _AM_DBUPDATED);
}

?>