<?php
// $Id: index.php,v 1.7 2003/10/04 08:41:50 okazu Exp $
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
include 'header.php';

function listsections()
{
	global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsModule;
	include XOOPS_ROOT_PATH.'/header.php';
	$myts =& MyTextSanitizer::getInstance();
    $result = $xoopsDB->query("SELECT secid, secname, image FROM ".$xoopsDB->prefix("sections")." ORDER BY secname");
	echo "<div style='text-align: center;'>";
    printf(_MD_WELCOMETOSEC,$xoopsConfig['sitename']);
	echo "<br /><br />";
    echo _MD_HEREUCANFIND.'<br /><br /><table border="0">';
	$count = 0;
    while ( list($secid, $secname, $image) = $xoopsDB->fetchRow($result) ) {
		$secname = $myts->makeTboxData4Show($secname);
		$image = $myts->makeTboxData4Show($image);
		if ( $count == 2 ) {
			echo "<tr>";
			$count = 0;
		}
		echo "<td><a href='index.php?op=listarticles&secid=$secid'><img src='images/$image' border='0' alt='$secname'></a>";
		$count++;
		if ( $count == 2 ) {
			echo "</tr>";
		}
		echo "</td>";
	}
	echo "</table></div>";
	include '../../footer.php';
}

function listarticles($secid)
{
	global $xoopsConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule;
	include '../../header.php';
	$myts =& MyTextSanitizer::getInstance();
	$result = $xoopsDB->query("SELECT secname, image FROM ".$xoopsDB->prefix("sections")." WHERE secid=$secid");
	list($secname, $image) = $xoopsDB->fetchRow($result);
	$secname = $myts->makeTboxData4Show($secname);
	$image = $myts->makeTboxData4Show($image);
	$result = $xoopsDB->query("SELECT artid, secid, title, content, counter FROM ".$xoopsDB->prefix("seccont")." WHERE secid=$secid");
	echo "<div><img src='images/$image' border='0'><br /><br />";
	printf(_MD_THISISSECTION,$secname);
	echo "<br />"._MD_THEFOLLOWING."<br /><br /><table border='0'>";
	while ( list($artid, $secid, $title, $content, $counter) = $xoopsDB->fetchRow($result) ) {
		$title = $myts->makeTboxData4Show($title);
		$content = $myts->makeTareaData4Show($content);
		echo "<tr><td align='left'>&nbsp;&nbsp;<strong><big>&middot;</big></strong>&nbsp;<a href='index.php?op=viewarticle&artid=$artid'>$title</a>";
		printf(" (read: %s times)",$counter);
		echo "<a href='index.php?op=printpage&artid=$artid'>&nbsp;&nbsp;<img src='".XOOPS_URL."/modules/sections/images/print.gif' border='0' alt='' . _MD_PRINTERPAGE.' /></a></td></tr>";
	}
    echo "</table><br /><br /><br />[ <a href=index.php>"._MD_RETURN2INDEX."</a> ]</div>";
	include '../../footer.php';
}

function viewarticle($artid,$page)
{
	global $xoopsConfig, $xoopsUser, $xoopsDB, $xoopsTheme, $xoopsLogger, $xoopsModule;
	include '../../header.php';
	$myts =& MyTextSanitizer::getInstance();
    $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("seccont")." SET counter=counter+1 WHERE artid=$artid");
	$result = $xoopsDB->query("SELECT artid, secid, title, content, counter FROM ".$xoopsDB->prefix("seccont")." WHERE artid=$artid");
	list($artid, $secid, $title, $content, $counter) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Show($title);
	$content = $myts->makeTareaData4Show($content);
    $result2 = $xoopsDB->query("SELECT secid, secname FROM ".$xoopsDB->prefix("sections")." WHERE secid=$secid");
	list($secid, $secname) = $xoopsDB->fetchRow($result2);
	$secname = $myts->makeTboxData4Show($secname);
    $words = count(explode(" ", $content));
    //echo "<center>";
	/* Rip the article into pages. Delimiter string is "[pagebreak]"  */
	$contentpages = explode( "[pagebreak]", $content);
	$pageno = count($contentpages);
	/* Define the current page	*/
	if ( $page=="" || $page < 1 ) {
		$page = 1;
	}
	if ( $page > $pageno ) {
	  	$page = $pageno;
	}
	$arrayelement = (int)$page;
	$arrayelement --;
	echo "<table width='100%'><tr><td><b>$title</b><br /><br />";
	if ( $page >= $pageno ) {
		$next_page = '<a href="index.php">' ._MD_RETURN2INDEX.'</a>';
	} else {
		$next_pagenumber = $page + 1;
	  	$next_page = "<a href='index.php?op=viewarticle&artid=$artid&page=$next_pagenumber'>"._MD_NEXTPAGE." ".sprintf("(%s/%s)",$next_pagenumber,$pageno)." >></a>";
    }
	if( $page <= 1 ) {
		$previous_page = '<a href="index.php">' ._MD_RETURN2INDEX.'</a>';
	} else {
		$previous_pagenumber = $page -1;
   	  	$previous_page = "<a href='index.php?op=viewarticle&artid=$artid&page=$previous_pagenumber'><< "._MD_PREVPAGE." ".sprintf("(%s/%s)",$previous_pagenumber,$pageno)."</a>";
	}
    echo ($contentpages[$arrayelement]);
	echo "<br /><table width='100%' border='0' cellspacing='0' cellpadding='2'><tr><td>$previous_page</td>        <td align='right'>$next_page</td></tr></table>";
	echo "</td></tr>
	<tr><td align='center'>[ <a href='index.php?op=listarticles&secid=$secid'>".sprintf(_MD_BACK2SEC,$secname)."</a> |
        <a href='index.php'>"._MD_RETURN2INDEX."</a> | <a href='index.php?op=printpage&artid=$artid'><img src='".XOOPS_URL."/modules/sections/images/print.gif' border='0' alt='" . _MD_PRINTERPAGE."' /></a>]</td></tr></table>";
    include '../../footer.php';
}

function PrintSecPage($artid)
{
	global $xoopsConfig, $xoopsUser, $xoopsDB;
	$myts =& MyTextSanitizer::getInstance();
    $result=$xoopsDB->query("SELECT title, content FROM ".$xoopsDB->prefix("seccont")." WHERE artid=$artid");
	list($title, $content) = $xoopsDB->fetchRow($result);
	$title = $myts->makeTboxData4Show($title);
	$content = $myts->makeTareaData4Show($content);
    echo "
        <html>
        <head><title>".$xoopsConfig['sitename']."</title></head>
        <body>
        <table border='0'><tr><td>
        <table border='0' width='640' cellpadding='0' cellspacing='1' bgcolor='#000000'><tr><td>
        <table border='0' width='640' cellpadding='20' cellspacing='1' bgcolor='#ffffff'><tr><td>
        <img src='".XOOPS_URL."/images/logo.gif' border='0' alt='' /><br /><br />
        <b>$title</b><br />
        ".str_replace("[pagebreak]","",$content)."<br /><br />";
        echo "</td></tr></table></td></tr></table>";
        echo "<br /><br />";
        printf(_MD_COMESFROM, $xoopsConfig['sitename']);
		echo "<br /><a href='".XOOPS_URL."'>".XOOPS_URL."</a><br /><br />";
        echo _MD_URLFORTHIS."<br />
        <a href='".XOOPS_URL."/modules/sections/index.php?op=viewarticle&artid=$artid'>".XOOPS_URL."/modules/sections/index.php?op=viewarticle&artid=$artid</a>
        </td></tr></table>
        </body>
        </html>";
}

$op = isset($HTTP_GET_VARS['op']) ? trim($HTTP_GET_VARS['op']) : '';
$secid = isset($HTTP_GET_VARS['secid']) ? intval($HTTP_GET_VARS['secid']) : 0;
$page = isset($HTTP_GET_VARS['page']) ? intval($HTTP_GET_VARS['page']) : 0;
$artid = isset($HTTP_GET_VARS['artid']) ? intval($HTTP_GET_VARS['artid']) : 0;


switch ( $op ) {
case "viewarticle":
	viewarticle($artid, $page);
    break;
case "listarticles":
	listarticles($secid);
	break;
case "printpage":
	PrintSecPage($artid);
	break;
default:
	listsections();
	break;
}
?>