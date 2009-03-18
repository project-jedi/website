<?php
// $Id: index.php,v 1.6 2003/03/10 19:26:46 okazu Exp $
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
// Author: Raul Recio (AKA UNFOR)                                            //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include '../../../include/cp_header.php';

function partnersAdmin()
{
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
	echo "<h4>"._MD_PARTNERADMIN."</h4>
	<form action='index.php' method='post' name='reorderform'>
	<table width='100%' border='0' cellspacing='1' cellpadding='0' class='outer'><tr>
	<th width='10%' align='center'>" ._MD_TITLE."</th>
	<th width='3%' align='center'>" ._MD_IMAGE."</th>
	<th>" ._MD_DESCRIPTION."</th>
	<th width='3%' align='center'>" ._MD_ACTIVE."</th>
	<th width='3%' align='center'>" ._MD_WEIGHT."</th>
	<th width='3%' align='center'>" ._MD_HITS."</th>
	<th>&nbsp;</th></tr>";
	$result = $xoopsDB->query("SELECT id, hits, url, weight, image, title, description, status FROM ".$xoopsDB->prefix("partners")." ORDER BY status DESC, weight ASC, title DESC");
	$class = 'even';
	while (list($id, $hits, $url, $weight, $image, $title, $description, $status) = $xoopsDB->fetchrow($result)) {
		$url          = formatURL($myts->makeTboxData4Show($url));
		$image        = formatURL($myts->makeTboxData4Show($image));
		$title        = $myts->makeTboxData4Show($title);
		$description  = $myts->makeTboxData4Show($description);
		$imageInfo    = @getimagesize($image);
		$imageWidth   = $imageInfo[0];
		$imageHeight  = $imageInfo[1];
		$check1 = "";
		$check2 = "";
		if( $status == 1){ $check1 = "selected='selected'"; }else{ $check2 = "selected='selected'"; }
		if( $imageWidth >= 110 or $imageHeight >= 50 ){
			$errorMsg = "<br />"._MD_IMAGE_ERROR;
		} else {
			$errorMsg = "";
		}
		echo "<tr>
		<td class='$class' width='10%' align='center' valign='middle'><a href='".$url."' target='_blank'>".$title."</a></td>
		<td class='$class' width='3%' align='center'>";
		if ( !empty($image) ) {
			echo "<img src='".$image."' alt='".$title."' width='102' height='47' />".$errorMsg;
		}
		echo "</td><td class='$class'>".$description."</td>
		<td class='$class' width='3%' align='center'>
		<select size='1' name='status[$id]'> <option value='1' ".$check1.">"._MD_YES."</option><option value='0' ".$check2.">"._MD_NO."</option></select>
		<td class='$class' width='3%' align='center'>";
		echo "<input type='text' name='weight[$id]' value='$weight' size='3' maxlength='3' style='text-align: center;'>";
		echo "</td><td class='$class' width='3%' align='center'>".$hits."</td>
		<td class='$class' width='3%' align='center'>
		<a href='index.php?op=editPartner&amp;id=".$id."'>"._MD_EDIT."</a><br />--<br /><a href='index.php?op=delPartner&amp;id=".$id."'>"._MD_DELETE."</a>
		</td></tr>";
		$class = ($class == 'odd') ? 'even' : 'odd';
	}
	echo "<tr><td class='foot' colspan='7' align='right'>
	<input type='hidden' name='op' value='reorderPartners' />
	<input type='button' name='button' onclick=\"location='index.php?op=partnersAdminAdd'\" value='"._MD_PARTNERS_ADD."'>
	<input type='button' name='button' onclick=\"location='index.php?op=reorderAutoPartners'\" value='"._MD_AUTOMATIC_SORT."'>
	<input type='submit' name='submit' value='"._MD_REORDER."'>
	</td></tr></table></form>";
	xoops_cp_footer();
}

function reorderPartners($weight,$status)
{
	$xoopsDB =& Database::getInstance();
	foreach ($weight as $id=>$order) {
		if ( isset($id) && is_numeric($id) && isset($order) ) {
		if ( !is_numeric($order) or empty($order)) { $order = 0; }
			$xoopsDB->query("UPDATE ".$xoopsDB->prefix("partners")." SET weight='$order', status='$status[$id]' WHERE id=$id");
		}
	}
	redirect_header("./index.php", 1, _MD_UPDATED);
	exit();
}

function reorderAutoPartners()
{
	$xoopsDB =& Database::getInstance();
	$weight = 0;
	$result = $xoopsDB->query("SELECT id FROM ".$xoopsDB->prefix("partners")." ORDER BY weight ASC");
	while (list($id) = $xoopsDB->fetchrow($result)) {
		$weight++;
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("partners")." SET weight='$weight' WHERE id=$id");
	}
	redirect_header("./index.php", 1, _MD_UPDATED);
	exit();
}

function partnersAdminAdd()
{
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
    echo "<h4>"._MD_PARTNERADMIN."</h4>";
	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$form       = new XoopsThemeForm(_MD_ADDPARTNER, "addform", "index.php");
	$formweight = new XoopsFormText(_MD_WEIGHT, "weight", 3, 10, 0);
	$formimage  = new XoopsFormText(_MD_IMAGE, "image", 50, 150, 'http://');
	$formurl    = new XoopsFormText(_MD_URL, "url", 50, 150, 'http://');
	$formtitle  = new XoopsFormText(_MD_TITLE, "title", 50, 50);
	$formdesc   = new XoopsFormTextArea(_MD_DESCRIPTION, "description","", 10, "60");
	$formstat   = new XoopsFormSelect(_MD_STATUS, "status");
	$formstat->addOption("1", _MD_ACTIVE);
	$formstat->addOption("0", _MD_INACTIVE);
	$op_hidden  = new XoopsFormHidden("op", "addPartner");
	$submit_button = new XoopsFormButton("", "submir", _MD_ADDPARTNER, "submit");
	$form->addElement($formweight);
	$form->addElement($formimage);
	$form->addElement($formurl, true);
	$form->addElement($formtitle, true);
	$form->addElement($formdesc, true);
	$form->addElement($formstat);
	$form->addElement($op_hidden);
	$form->addElement($submit_button);
	$form->display();
	xoops_cp_footer();
}

function addPartner($weight, $url, $image, $title, $description, $status)
{
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$title = isset($title) ? trim($title) : '';
	$image = isset($image) ? trim($image) : '';
	$url = isset($url) ? trim($url) : '';
	$description = isset($description) ? trim($description) : '';
	if ($title == '' || $url == '' || $description == '') {
		redirect_header("index.php", 1, _MD_BESURE);
		exit();
	}
	$url          = $myts->makeTboxData4Save(formatURL($url));
	$image        = $myts->makeTboxData4Save(formatURL($image));
	$title        = $myts->makeTboxData4Save($title);
	$description  = $myts->makeTboxData4Save($description);
	$image_info = @getimagesize($image);
	$status  = isset($status) ? intval($status) : 0;
	$type = $image_info[2];
	/*
	if ($type == 0) {
		redirect_header("index.php", 1, _MD_NOEXITS);
		exit();
	}
	*/
	$sql = "INSERT INTO ".$xoopsDB->prefix("partners")." VALUES (NULL, ".intval($weight).", 0, '$url', '$image', '$title', '$description', $status)";
	if ($xoopsDB->query($sql)) {
		redirect_header("index.php", 1, _MD_UPDATED);
	} else {
		redirect_header("index.php", 1, _MD_NOTUPDATED);
	}
	exit();
}

function editPartner($id)
{
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
	echo "<h4>"._MD_PARTNERADMIN."</h4>";
	$result = $xoopsDB->query("SELECT weight, hits, url, image, title, description, status FROM ".$xoopsDB->prefix("partners")." WHERE id=$id");
	list($weight, $hits, $url, $image, $title, $description, $status) = $xoopsDB->fetchrow($result);
	$url          = $myts->makeTboxData4Edit($url);
	$image        = $myts->makeTboxData4Edit($image);
	$title        = $myts->makeTboxData4Edit($title);
	$description  = $myts->makeTboxData4Edit($description);
	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$form         = new XoopsThemeForm(_MD_EDITPARTNER, "editform", "index.php");
	$formweight   = new XoopsFormText(_MD_WEIGHT, "weight", 3, 10, $weight);
	$formhits     = new XoopsFormText(_MD_HITS, "hits", 3, 10, $hits);
	$formimage    = new XoopsFormText(_MD_IMAGE, "image", 50, 150, $image);
	$formurl      = new XoopsFormText(_MD_URL, "url", 50, 150, $url);
	$formtitle    = new XoopsFormText(_MD_TITLE, "title", 50, 50, $title);
	$formdesc     = new XoopsFormTextArea(_MD_DESCRIPTION, "description", $description, 10, "100%");
	$formstat     = new XoopsFormSelect(_MD_STATUS, "status", $status);
	$formstat->addOption("1", _MD_ACTIVE);
	$formstat->addOption("0", _MD_INACTIVE);
	$id_hidden    = new XoopsFormHidden("id", $id);
	$op_hidden    = new XoopsFormHidden("op", "updatePartner");
	$submit_button = new XoopsFormButton("", "submit", _MD_EDITPARTNER, "submit");
	$form->addElement($formweight);
	$form->addElement($formhits);
	$form->addElement($formimage);
	$form->addElement($formurl, true);
	$form->addElement($formtitle, true);
	$form->addElement($formdesc, true);
	$form->addElement($formstat);
	$form->addElement($id_hidden);
	$form->addElement($op_hidden);
	$form->addElement($submit_button);
	$form->display();
	xoops_cp_footer();
}

function updatePartner($id, $weight, $hits, $url, $image, $title, $description, $status)
{
	$xoopsDB =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$title = isset($title) ? trim($title) : '';
	$image = isset($image) ? trim($image) : '';
	$url = isset($url) ? trim($url) : '';
	$description = isset($description) ? trim($description) : '';
	$id = intval($id);
	$status  = isset($status) ? intval($status) : 0;
	if ($title == '' || $url == '' || empty($id) || $description == '') {
		redirect_header("index.php?op=edit_partner&id=$id", 1, _MD_BESURE);
		exit();
	}
	$url          = $myts->makeTboxData4Save(formatURL($url));
	$image        = $myts->makeTboxData4Save(formatURL($image));
	$title        = $myts->makeTboxData4Save($title);
	$description  = $myts->makeTboxData4Save($description);
	$image_info = @getimagesize($image);
	$type = $image_info[2];
	if ($type == 0) {
		redirect_header("index.php?op=edit_partner&id=$id", 1, _MD_NOEXITS);
		exit();
	}
	if ($xoopsDB->query("UPDATE ".$xoopsDB->prefix("partners")." SET hits=".intval($hits).", weight=".intval($weight).", url='$url', image='$image', title='$title', description='$description', status=$status WHERE id=$id")) {
		redirect_header("index.php", 1, _MD_UPDATED);
	} else {
		redirect_header("index.php", 1, _MD_NOTUPDATED);
	}
	exit();
}

function delPartner($id, $del=0)
{
	$xoopsDB =& Database::getInstance();
	if ( $del == 1 ) {
		$sql = sprintf("DELETE FROM %s WHERE id = %u", $xoopsDB->prefix("partners"), $id);
		if ( $xoopsDB->query($sql) ) {
			redirect_header("index.php", 1, _MD_UPDATED);
		} else {
			redirect_header("index.php", 1, _MD_NOTUPDATED);
		}
		exit();
	} else {
		xoops_cp_header();
		echo "<h4>"._MD_PARTNERADMIN."</h4>";
		xoops_confirm(array('op' => 'delPartner', 'id' => $id, 'del' => 1), 'index.php', _MD_SUREDELETE);
		xoops_cp_footer();
	}
}
$op = '';
foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

if (isset($HTTP_GET_VARS['op'])) {
	$op = $HTTP_GET_VARS['op'];
	if (isset($HTTP_GET_VARS['id'])) {
		$id = intval($HTTP_GET_VARS['id']);
	}
}
switch ($op) {
case "partnersAdminAdd":
	partnersAdminAdd();
	break;
case "updatePartner":
	updatePartner($id, $weight, $hits, $url, $image, $title, $description, $status);
	break;
case "addPartner":
	addPartner($weight, $url, $image, $title, $description, $status);
	break;
case "delPartner":
	delPartner($id, $del);
	break;
case "editPartner":
	editPartner($id);
	break;
case "reorderPartners":
	reorderPartners($weight,$status);
	break;
case "reorderAutoPartners":
	reorderAutoPartners();
	break;
default:
	partnersAdmin();
	break;
}
?>
