<?php
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
// ------------------------------------------------------------------------- //
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
include '../../mainfile.php';

if ( file_exists("language/".$xoopsConfig['language']."/modinfo.php") ) {
	include("language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include("language/english/modinfo.php");
}

$id = isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']) : 0;
if ( empty($id) ) {
	redirect_header("index.php");
}

	global $xoopsConfig, $xoopsModule, $xoopsDB;
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
	echo '<html><head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset='._CHARSET.'" />';
	echo '<title>'.$xoopsConfig['sitename'].'</title>';
	echo '<meta name="AUTHOR" content="'.$xoopsConfig['sitename'].'" />';
	echo '<meta name="COPYRIGHT" content="Copyright (c) 2001 by '.$xoopsConfig['sitename'].'" />';
	echo '<meta name="DESCRIPTION" content="'.$xoopsConfig['slogan'].'" />';
	echo '<meta name="GENERATOR" content="'.XOOPS_VERSION.'" />';

	$result = $xoopsDB->queryF("SELECT storyid, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address FROM ".$xoopsDB->prefix(_MI_JGRAPHICS_PREFIX)." WHERE storyid=$id");
	list($storyid,$title,$text,$visible,$nohtml,$nosmiley,$nobreaks,$nocomments,$link,$address) = $xoopsDB->fetchRow($result);
	echo '<body bgcolor="#ffffff" text="#000000" onload="window.print()">
    	<table border="0"><tr><td align="center">
    	<table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
    	<table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td align="center">
    	<img src="'.XOOPS_URL.'/images/logo.gif" border="0" alt="" /><br /><br />
    	<h3>'.$title.'</h3></td></tr>';
	echo '<tr valign="top"><td>';

	if ($link == 1) {
		$includeContent = XOOPS_ROOT_PATH."/modules/"._MI_JGRAPHICS_DIR_NAME."/content/".$address;
		if (file_exists($includeContent)){
		  ob_start();
	      include($includeContent);
	      $content = ob_get_contents();
          ob_end_clean();
		 }
		echo $content;
	} else {
		echo $text;
	}

	echo '</td></tr></table></td></tr></table><br /><br />';
	printf(_JGRAPHICS_THISCOMESFROM,$xoopsConfig['sitename']);
	echo '<br /><a href="'.XOOPS_URL.'/">'.XOOPS_URL.'</a><br /><br />'._JGRAPHICS_URLFORSTORY.'<br />
    	<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/index.php?id='.$id.'">'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/index.php?id='.$id.'</a>
    	</td></tr></table>
    	</body>
    	</html>
    	';

?>