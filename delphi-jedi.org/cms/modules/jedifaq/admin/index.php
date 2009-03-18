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

include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
$op = "listcat";

if (isset($HTTP_GET_VARS)) {
	foreach ($HTTP_GET_VARS as $k => $v) {
		$$k = $v;
	}
}

if (isset($HTTP_POST_VARS)) {
	foreach ($HTTP_POST_VARS as $k => $v) {
		$$k = $v;
	}
}

if ( !empty($contents_preview) ) {
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();

	$html = empty($contents_nohtml) ? 1 : 0;
	$smiley = empty($contents_nosmiley) ? 1 : 0;
	$xcode = empty($contents_noxcode) ? 1 : 0;
	$p_title = $myts->makeTboxData4Preview($contents_title);
	$p_contents = $myts->makeTareaData4Preview($contents_contents, $html, $smiley, $xcode);
	echo"<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
	<table width='100%' border='0' cellpadding='4' cellspacing='1'>
	<tr class='bg3' align='center'><td align='left'>$p_title</td></tr><tr class='bg1'><td>$p_contents</td></tr></table></td></tr></table><br />";
	$contents_title = $myts->makeTboxData4PreviewInForm($contents_title);
	$contents_contents = $myts->makeTareaData4PreviewInForm($contents_contents);
	include "contentsform.php";

	xoops_cp_footer();
	exit();
}

if ($op == "listcat") {
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();

	echo "
	<h4 style='text-align:left;'>"._XD_DOCS."</h4>
	<form action='index.php' method='post'>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
	<table width='100%' border='0' cellpadding='4' cellspacing='1'>
	<tr class='bg3' align='center'><td align='left'>"._XD_CATEGORY."</td><td>"._XD_ORDER."</td><td>"._XD_CONTENTS."</td><td>&nbsp;</td></tr>";
	$result = $xoopsDB->query("SELECT c.category_id, c.category_title, c.category_order, COUNT(f.category_id) FROM ".$xoopsDB->prefix("jedifaq_categories")." c LEFT JOIN ".$xoopsDB->prefix("jedifaq_contents")." f ON f.category_id=c.category_id GROUP BY c.category_id ORDER BY c.category_order ASC");
	$count = 0;
	while ( list($cat_id, $category, $category_order, $faq_count) = $xoopsDB->fetchRow($result) ) {
		$category=$myts->makeTboxData4Edit($category);
		echo "<tr class='bg1'><td align='left'><input type='hidden' value='$cat_id' name='cat_id[]' /><input type='hidden' value='$category' name='oldcategory[]' /><input type='text' value='$category' name='newcategory[]' maxlength='255' size='20' /></td>
		<td align='center'><input type='hidden' value='$category_order' name='oldorder[]' /><input type='text' value='$category_order' name='neworder[]' maxlength='3' size='4' /></td>
		<td align='center'>$faq_count</td>
		<td align='right'><a href='index.php?op=listcontents&amp;cat_id=".$cat_id."'>" ._XD_CONTENTS."</a> | <a href='index.php?op=delcat&amp;cat_id=".$cat_id."&amp;ok=0'>"._DELETE."</a></td></tr>";
		$count++;
	}
	if ($count > 0) {
		echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='"._SUBMIT."' /><input type='hidden' name='op' value='editcatgo' /></td></tr>";
	}
	echo "</table></td></tr></table></form>
	<br /><br />
	<h4 style='text-align:left;'>"._XD_ADDCAT."</h4>
	<form action='index.php' method='post'>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'><table width='100%' border='0' cellpadding='4' cellspacing='1'><tr nowrap='nowrap'><td class='bg3'>"._XD_TITLE." </td><td class='bg1'><input type='text' name='category' size='30' maxlength='255' /></td></tr>
	<tr nowrap='nowrap'><td class='bg3'>"._XD_ORDER." </td><td class='bg1'><input type='text' name='order' size='4' maxlength='3' /></td></tr>
	<tr><td class='bg3'>&nbsp;</td><td class='bg1'><input type='hidden' name='op' value='addcatgo' /><input type='submit' value='"._SUBMIT."' /></td></tr>
	</table></td></tr></table></form>";

	xoops_cp_footer();
	exit();
}

if ($op == "addcatgo") {
	$myts =& MyTextSanitizer::getInstance();
	$category = $myts->makeTboxData4Save($category);
	$newid = $xoopsDB->genId($xoopsDB->prefix("jedifaq_categories")."_category_id_seq");
	$sql = "INSERT INTO ".$xoopsDB->prefix("jedifaq_categories")." (category_id, category_title, category_order) VALUES ($newid, '".$category."', ".intval($order).")";
	if (!$xoopsDB->query($sql)) {
		xoops_cp_header();
		echo "Could not add category";
		xoops_cp_footer();
	} else {
		redirect_header("index.php?op=listcat",1,_XD_DBSUCCESS);
	}
	exit();
}

if ($op == "editcatgo") {
	$myts =& MyTextSanitizer::getInstance();
	$count = count($newcategory);
	for ($i = 0; $i < $count; $i++) {
		if ( $newcategory[$i] != $oldcategory[$i] || $neworder[$i] != $oldorder[$i] ) {
			$category = $myts->makeTboxData4Save($newcategory[$i]);
			$sql = "UPDATE ".$xoopsDB->prefix("jedifaq_categories")." SET category_title='".$category."', category_order=".intval($neworder[$i])." WHERE category_id=".$cat_id[$i]."";
			$xoopsDB->query($sql);
		}
	}
	redirect_header("index.php?op=listcat",1,_XD_DBSUCCESS);
	exit();
}

if ($op == "listcontents") {
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
	$sql = "SELECT category_title FROM ".$xoopsDB->prefix("jedifaq_categories")." WHERE category_id='".$cat_id."'";
	$result = $xoopsDB->query($sql);
	list($category) = $xoopsDB->fetchRow($result);
	$category = $myts->makeTboxData4Show($category);

	echo "<a href='index.php'>" ._XD_MAIN."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;".$category."<br /><br />
	<h4 style='text-align:left;'>"._XD_CONTENTS."</h4>
	<form action='index.php' method='post'>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
	<table width='100%' border='0' cellpadding='4' cellspacing='1'>
	<tr class='bg3'><td>"._XD_TITLE."</td><td align='center'>"._XD_ORDER."</td><td align='center'>"._XD_DISPLAY."</td><td>&nbsp;</td></tr>";
	$result = $xoopsDB->query("SELECT contents_id, contents_title, contents_time, contents_order, contents_visible FROM ".$xoopsDB->prefix("jedifaq_contents")." WHERE category_id='".$cat_id."' ORDER BY contents_order");
	$count = 0;
	while(list($id, $title, $time, $order, $visible) = $xoopsDB->fetchRow($result)) {
		$title = $myts->makeTboxData4Show($title);
		echo "<tr class='bg1'><td><input type='hidden' value='$id' name='id[]' />".$title."</td>
		<td align='center'><input type='hidden' value='$order' name='oldorder[$id]' /><input type='text' value='$order' name='neworder[$id]' maxlength='3' size='4' /></td>";
		$checked = ($visible == 1) ? " checked='checked'" : "";
		echo "<td align='center'><input type='hidden' value='$visible' name='oldvisible[$id]' /><input type='checkbox' value='1' name='newvisible[$id]'".$checked." /></td>
		<td align='right'><a href='index.php?op=editcontents&amp;id=".$id."&amp;cat_id=".$cat_id."'>"._EDIT."</a> | <a href='index.php?op=delcontents&amp;id=".$id."&amp;ok=0&amp;cat_id=".$cat_id."'>"._DELETE."</a></td></tr>";
		$count++;
	}
	if ($count > 0) {
		echo "<tr align='center' class='bg3'><td colspan='4'><input type='submit' value='"._SUBMIT."' /><input type='hidden' name='op' value='quickeditcontents' /><input type='hidden' name='cat_id' value='".$cat_id."' /></td></tr>";
	}
	echo "</table></td></tr></table></form>
	<br /><br />
	<h4 style='text-align:left;'>"._XD_ADDCONTENTS."</h4>";
	$contents_title = "";
	$contents_contents = "";
	$contents_order = 0;
	$contents_visible = 1;
	$contents_nohtml = 0;
	$contents_nosmiley = 0;
	$contents_noxcode = 0;
	$contents_id = 0;
	$category_id = $cat_id;
	$op = "addcontentsgo";
	include "contentsform.php";

	xoops_cp_footer();
	exit();
}

if ($op == "quickeditcontents") {
	$myts =& MyTextSanitizer::getInstance();
	$count = count($id);
	for ($i = 0; $i < $count; $i++) {
		$index = $id[$i];
		if ( $neworder[$index] != $oldorder[$index] || $newvisible[$index] != $oldvisible[$index] ) {
			$xoopsDB->query("UPDATE ".$xoopsDB->prefix("jedifaq_contents")." SET contents_order=".intval($neworder[$index]).", contents_visible=".intval($newvisible[$index])." WHERE contents_id=".$index."");
		}
	}
	redirect_header("index.php?op=listcontents&amp;cat_id=$cat_id",1,_XD_DBSUCCESS);
	exit();
}

if ($op == "addcontentsgo") {
	$myts =& MyTextSanitizer::getInstance();
	$title = $myts->makeTboxData4Save($contents_title);
	$contents = $myts->makeTareaData4Save($contents_contents);
	$newid = $xoopsDB->genId($xoopsDB->prefix("jedifaq_contents")."_contents_id_seq");
	$sql = "INSERT INTO ".$xoopsDB->prefix("jedifaq_contents")." (contents_id, category_id, contents_title, contents_contents, contents_time, contents_order, contents_visible, contents_nohtml, contents_nosmiley, contents_noxcode) VALUES (".$newid.", ".$category_id.", '".$title."', '".$contents."', ".time().", ".intval($contents_order).", ".intval($contents_visible).", ".intval($contents_nohtml).", ".intval($contents_nosmiley).", ".intval($contents_noxcode).")";
	if (!$xoopsDB->query($sql)) {
		xoops_cp_header();
		echo "Could not add contents";
		xoops_cp_footer();
	} else {
		redirect_header("index.php?op=listcontents&amp;cat_id=$category_id",1,_XD_DBSUCCESS);
	}
	exit();
}

if ($op == "editcontents") {
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
	$sql = "SELECT category_title FROM ".$xoopsDB->prefix("jedifaq_categories")." WHERE category_id='".$cat_id."'";
	$result = $xoopsDB->query($sql);
	list($category) = $xoopsDB->fetchRow($result);
	$category = $myts->makeTboxData4Show($category);
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("jedifaq_contents")." WHERE contents_id='$id'");
	$myrow = $xoopsDB->fetchArray($result);
	$contents_title = $myts->makeTboxData4Edit($myrow['contents_title']);
	$contents_contents = $myts->makeTareaData4Edit($myrow['contents_contents']);
	$contents_order = $myrow['contents_order'];
	$contents_visible = $myrow['contents_visible'];
	$contents_nohtml = $myrow['contents_nohtml'];
	$contents_nosmiley = $myrow['contents_nosmiley'];
	$contents_noxcode = $myrow['contents_noxcode'];
	$contents_id = $myrow['contents_id'];
	$category_id = $myrow['category_id'];
	$op = "editcontentsgo";

	echo "<a href='index.php'>" ._XD_MAIN."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;<a href='index.php?op=listcontents&amp;cat_id=$cat_id'>".$category."</a>&nbsp;<span style='font-weight:bold;'>&raquo;&raquo;</span>&nbsp;"._XD_EDITCONTENTS."<br /><br />
	<h4 style='text-align:left;'>"._XD_EDITCONTENTS."</h4>";
	include "contentsform.php";

	xoops_cp_footer();
	exit();
}

if ($op == "editcontentsgo") {
	$myts =& MyTextSanitizer::getInstance();
	$title = $myts->makeTboxData4Save($contents_title);
	$contents = $myts->makeTareaData4Save($contents_contents);
	$nohtml = empty($contents_nohtml) ? 0 : 1;
	$nosmiley = empty($contents_nosmiley) ? 0 : 1;
	$noxcode = empty($contents_noxcode) ? 0 : 1;
	$sql = "UPDATE ".$xoopsDB->prefix("jedifaq_contents")." set contents_title='".$title."', contents_contents='".$contents."', contents_time=".time().", contents_order=".intval($contents_order).", contents_visible=".intval($contents_visible).", contents_nohtml=".$nohtml.", contents_nosmiley=".$nosmiley.", contents_noxcode=".$noxcode." WHERE contents_id=".$contents_id."";
	if (!$xoopsDB->query($sql)) {
		xoops_cp_header();
		echo "Could not update contents";
		xoops_cp_footer();
	} else {
		redirect_header("index.php?op=listcontents&amp;cat_id=$category_id",1,_XD_DBSUCCESS);
	}
	exit();
}

if ($op == "delcat") {
	if ($ok == 1) {
		$sql = sprintf("DELETE FROM %s WHERE category_id = %u", $xoopsDB->prefix("jedifaq_categories"), $cat_id);
		if (!$xoopsDB->query($sql)) {
			xoops_cp_header();
			echo "Could not delete category";
			xoops_cp_footer();
		} else {
			$sql = sprintf("DELETE FROM %s WHERE category_id = %u", $xoopsDB->prefix("jedifaq_contents"), $cat_id);
			$xoopsDB->query($sql);
			// delete comments
			xoops_comment_delete($xoopsModule->getVar('mid'), $cat_id);
			redirect_header("index.php?op=listcat",1,_XD_DBSUCCESS);
		}
		exit();
	} else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delcat', 'cat_id' => $cat_id, 'ok' => 1), 'index.php', _XD_RUSURECAT);
		xoops_cp_footer();
		exit();
	}
}

if ($op == "delcontents") {
	if ($ok == 1) {
		$sql = sprintf("DELETE FROM %s WHERE contents_id = %u", $xoopsDB->prefix("jedifaq_contents"), $id);
		if (!$xoopsDB->query($sql)) {
			xoops_cp_header();
			echo "Could not delete contents";
			xoops_cp_footer();
		} else {
			redirect_header("index.php?op=listcontents&amp;cat_id=$cat_id",1,_XD_DBSUCCESS);
		}
		exit();
	} else {
		xoops_cp_header();
		xoops_confirm(array('op' => 'delcontents', 'id' => $id, 'ok' => 1), 'index.php', _XD_RUSURECAT);
		xoops_cp_footer();
		exit();
	}
}
?>