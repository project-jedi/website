<?php
// $Id: index.php,v 1.8 2003/04/11 13:00:49 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
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
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}
/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function sections() {
    global $xoopsConfig, $xoopsDB, $xoopsModule;
	xoops_cp_header();
	echo "<h4>"._AM_SECCONF."</h4>";
	$result = $xoopsDB->query("select secid, secname from ".$xoopsDB->prefix("sections")." order by secid");
	if ($xoopsDB->getRowsNum($result) > 0) {
		$myts =& MyTextSanitizer::getInstance();
		echo "<hr>
		<b><center>"._MD_CURACTIVESEC."</b><br />"._MD_CLICK2EDIT."<br />
		<table border=0 width=100% align=center cellpadding=1><tr><td align=center>";
		echo "<ul>";
		while(list($secid, $secname) = $xoopsDB->fetchRow($result)) {
			$secname=$myts->makeTboxData4Show($secname);
			echo "<li><a href=\"index.php?op=sectionedit&secid=".$secid."\">".$secname."</a></li>";
		}
		echo "</ul>";
		echo "</td></tr></table>";
	?>
		<br />
		<hr><h4><?php echo _MD_ADDARTICLE; ?></h4>
		<br /><br />
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?><br />
	<b><?php echo _MD_TITLEC; ?></b><br />
	<input class=textbox type="text" name="title" size=60 value=""><br /><br />
	<?php
	$result = $xoopsDB->query("select secid, secname from ".$xoopsDB->prefix("sections")." order by secid");
	$checked = "checked"; // select first section by default
	while(list($secid, $secname) = $xoopsDB->fetchRow($result)) {
		$secname=$myts->makeTboxData4Show($secname);
		echo "<input type=radio name=secid value=$secid $checked>$secname<br />";
		$checked = '';
	} ?>
	<br />
	<b><?php echo _MD_CONTENTC; ?></b><br />
	<textarea class=textbox name="content" cols=60 rows=10></textarea><br /><br />
	<?php echo _MULTIPAGE ?><br/><br />
	<input type=hidden name=op value=secarticleadd>
	<input type="submit" value="<?php echo _MD_DOADDARTICLE; ?>">
	</form>
	<br />
	<hr><h4><?php echo _MD_LAST20ART; ?></h4>
	<br /><br />
	<ul>
	<?php
	$result = $xoopsDB->query("select artid, secid, title from ".$xoopsDB->prefix("seccont")." order by artid desc",20,0);
	while ( list($artid, $secid, $title) = $xoopsDB->fetchRow($result) ) {
		$title = $myts->makeTboxData4Show($title);
	    $result2 = $xoopsDB->query("select secid, secname from ".$xoopsDB->prefix("sections")." where secid='$secid'");
	    list($secid, $secname) = $xoopsDB->fetchRow($result2);
		$secname = $myts->makeTboxData4Show($secname);
	    echo "<li>$title ($secname) [ <a href=index.php?op=secartedit&artid=$artid>"._MD_EDIT."</a> ]";
	} ?>
	</ul>
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?>
	<?php echo _MD_EDITARTID; ?> <input class=textbox type="text" NAME="artid" SIZE=10>
	<input type=hidden name=op value="secartedit">
	<input type="submit" value="<?php echo _MD_GO;?>">
	</form>
<?php
	}
	echo "<br />"; 	?>
	<hr><h4><?php echo _MD_ADDNEWSEC; ?></h4>
	<br /><br />
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?><br />
	<b><?php echo _MD_SECNAMEC; ?></b>  <?php echo _MD_MAXCHAR; ?><br />
	<input class=textbox type="text" name="secname" size=40 maxlength=40><br /><br />
	<b><?php echo _MD_SECIMAGEC; ?></b>&nbsp;<?php echo _MD_EXIMAGE; ?><br />
	<input class=textbox type="text" name="image" size=40 maxlength=50><br /><br />
	<input type=hidden name=op value=sectionmake>
	<input type="submit" value="<?php echo _MD_GOADDSECTION; ?>">
	</form>
<?php
}

function secarticleadd($secid, $title, $content) {
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$title = $myts->makeTboxData4Save($title);
	$content = $myts->makeTareaData4Save($content);
	$newid = $xoopsDB->genId($xoopsDB->prefix("seccont")."_artid_seq");
	$success = $xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("seccont")." (artid, secid, title, content, counter) VALUES ($newid,$secid,'$title','$content',0)");
	if ( !$success ) { 
		xoops_cp_header();
		echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
		echo "<a href='./index.php'><h4>"._AM_SECCONF."</h4></a>"; 
		echo _MD_DBNOTUPDATED; 
		echo"</td></tr></table>"; 
		xoops_cp_footer(); 
		exit();
	}
	redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
	exit();
}

function secartedit($artid) {
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	$myts =& MyTextSanitizer::getInstance();
	xoops_cp_header();
	echo "<h4>"._AM_SECCONF."</h4>";
	$result = $xoopsDB->query("select artid, secid, title, content from ".$xoopsDB->prefix("seccont")." where artid='$artid'");
	list($artid, $secid, $title, $content) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Edit($title);
	$content = $myts->makeTareaData4Edit($content);
	?>
	<hr><h4><?php echo _MD_EDITARTICLE; ?></h4>
	<br /><br />
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?><br />
	<b><?php echo _MD_TITLEC; ?></b><br />
	<input class=textbox type="text" name="title" size=60 value="<?php echo "$title"; ?>"><br /><br />
	<?php
	$result2 = $xoopsDB->query("select secid, secname from ".$xoopsDB->prefix("sections")." order by secname");
	while(list($secid2, $secname) = $xoopsDB->fetchRow($result2)) {
	$secname = $myts->makeTboxData4Show($secname);
	    if ($secid2==$secid) { $che = "checked"; }
	    echo "<input type=radio name=secid value=$secid2 $che>$secname<br />";
	    $che = "";
	} ?>
	<br />
	<b><?php echo _MD_CONTENTC; ?></b><br />
	<textarea class=textbox name=content cols=60 rows=10><?php echo "$content"; ?></textarea>
	<input type=hidden name=artid value="<?php echo "$artid"; ?>">
	<input type=hidden name=op value=secartchange>
	<table border=0><tr><td>
	<input type="submit" value="<?php echo _MD_SAVECHANGES; ?>">
	</form></td><td>
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?>
	<input type=hidden name=artid value="<?php echo "$artid"; ?>">
	<input type=hidden name=op value=secartdelete>
	<input type="submit" value="<?php echo _MD_DELETE; ?>">
	</form></td></tr></table>
<?php
}

function sectionmake($secname, $image) {
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$secname = $myts->makeTboxData4Save($secname);
	if (empty($secname)) {
		redirect_header("index.php", 2, _MD_ERRORSECNAME);
	}
	$image = $myts->makeTboxData4Save($image);
	$newid = $xoopsDB->genId($xoopsDB->prefix("sections")."_secid_seq");
	$xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("sections")." (secid, secname, image) VALUES ($newid,'$secname', '$image')");
	redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
	exit();
}

function sectionedit($secid) {
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	xoops_cp_header();
	echo "<h4>"._AM_SECCONF."</h4><br />";
	$myts =& MyTextSanitizer::getInstance();
	$result = $xoopsDB->query("select secid, secname, image from ".$xoopsDB->prefix("sections")." where secid=$secid");
	list($secid, $secname, $image) = $xoopsDB->fetchRow($result);
	$secname = $myts->makeTboxData4Edit($secname);
	$image = $myts->makeTboxData4Edit($image);
	$result2 = $xoopsDB->query("select artid from ".$xoopsDB->prefix("seccont")." where secid=$secid");
	$number = $xoopsDB->getRowsNum($result2);
	?>
	<?php echo "<img src=\"".$xoopsConfig[xoops_url]."/modules/sections/images/".$image."\" border=0><br /><br />"; ?>
	<h4><?php printf(_MD_EDITTHISSEC,$secname); ?></h4>
	<br />
     <?php
      $help = sprintf(_MD_THISSECHAS,$number);
      echo "$help";
     ?>
	<br /><br />
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?><br />
	<b><?php echo _MD_SECNAMEC; ?></b> <?php echo _MD_MAXCHAR; ?><br />
	<input class=textbox type="text" name="secname" size=40 maxlength=40 value="<?php echo "$secname"; ?>"><br /><br />
	<b><?php echo _MD_SECIMAGEC; ?></b> <?php echo _MD_EXIMAGE; ?><br />
	<input class=textbox type="text" name="image" size=40 maxlength=50 value="<?php echo "$image"; ?>"><br /><br />
	<input type=hidden name=secid value="<?php echo "$secid"; ?>">
	<input type=hidden name=op value=sectionchange>
	<table border=0><tr><td>
	<INPUT type="submit" value="<?php echo _MD_SAVECHANGES; ?>">
	</form></td><td>
	<?php echo "<form action=\"index.php\" method=\"post\">"; ?>
	<input type=hidden name=secid value="<?php echo "$secid"; ?>">
	<input type=hidden name=op value=sectiondelete>
	<INPUT type="submit" value="<?php echo _MD_DELETE; ?>">
	</form></td></tr></table>
<?php
}

function sectionchange($secid, $secname, $image) {
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$secname = $myts->makeTboxData4Save($secname);
	if (empty($secname)) {
		redirect_header("index.php", 2, _MD_ERRORSECNAME);
	}
	$image = $myts->makeTboxData4Save($image);
	$xoopsDB->query("update ".$xoopsDB->prefix("sections")." set secname='$secname', image='$image' where secid=$secid");
	redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
	exit();
}

function secartchange($artid, $secid, $title, $content) {
	global $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
	$title = $myts->makeTboxData4Save($title);
	$content = $myts->makeTareaData4Save($content);
	$xoopsDB->query("update ".$xoopsDB->prefix("seccont")." set secid='$secid', title='$title', content='$content' where artid=$artid");
	redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
	exit();
}

function sectiondelete($secid, $ok=0) {
	global $xoopsDB, $xoopsConfig, $xoopsModule;

	if ( $ok == 1 ) {
		$sql = sprintf("DELETE FROM %s WHERE secid = %u", $xoopsDB->prefix("seccont"), $secid);
		$xoopsDB->query($sql);
		$sql = sprintf("DELETE FROM %s WHERE secid = %u", $xoopsDB->prefix("sections"), $secid);
		$xoopsDB->query($sql);
		redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
		exit();
	} else {
		xoops_cp_header();
		echo "<h4>"._AM_SECCONF."</h4>";
	    $myts =& MyTextSanitizer::getInstance();
	    $result=$xoopsDB->query("select secname from ".$xoopsDB->prefix("sections")." where secid=$secid");
	    list($secname) = $xoopsDB->fetchRow($result);
		$secname = $myts->makeTboxData4Show($secname);
		xoops_confirm(array('op' => 'sectiondelete', 'secid' => $secid, 'ok' => 1), 'index.php', _MD_RUSUREDELSEC.'<br />'._MD_THISDELETESALL);
	}
}

function secartdelete($artid, $ok=0) {
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	if ( $ok == 1 ) {
		$sql = sprintf("DELETE FROM %s WHERE artid = %u", $xoopsDB->prefix("seccont"), $artid);
	    $xoopsDB->query($sql);
	    redirect_header("index.php?op=sections",2,_MD_DBUPDATED);
		exit();
	} else {
		xoops_cp_header();
		echo "<h4>"._AM_SECCONF."</h4>";
	    $myts =& MyTextSanitizer::getInstance();
	    $result = $xoopsDB->query("select title from ".$xoopsDB->prefix("seccont")." where artid=$artid");
	    list($title) = $xoopsDB->fetchRow($result);
	    $title = $myts->makeTboxData4Show($title);
		xoops_confirm(array('op' => 'secartdelete', 'artid' => $artid, 'ok' => 1), 'index.php', sprintf(_MD_DELETETHISART,$title).'<br /><br />'._MD_RUSUREDELART);
	}
}

$op = '';
if (isset($HTTP_POST_VARS)) {
	foreach ($HTTP_POST_VARS as $k => $v) {
		${$k} = $v;
	}
}

if (isset($HTTP_GET_VARS['op'])) {
	$op = trim($HTTP_GET_VARS['op']);
	if (isset($HTTP_GET_VARS['artid'])) {
		$artid = intval($HTTP_GET_VARS['artid']);
	}
	if (isset($HTTP_GET_VARS['secid'])) {
		$secid = intval($HTTP_GET_VARS['secid']);
	}
}

switch ($op) {
case "sections":
	sections();
	break;
case "sectionedit":
	sectionedit($secid);
	break;
case "sectionmake":
	sectionmake($secname, $image);
	break;
case "sectiondelete":
	sectiondelete($secid, $ok);
	break;
case "sectionchange":
	sectionchange($secid, $secname, $image);
	break;
case "secarticleadd":
	secarticleadd($secid, $title, $content);
	break;
case "secartedit":
	secartedit($artid);
	break;
case "secartchange":
	secartchange($artid, $secid, $title, $content);
	break;
case "secartdelete":
	secartdelete($artid, $ok);
	break;
default:
	sections();
	break;
}
xoops_cp_footer();
?>
