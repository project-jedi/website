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

include "../../mainfile.php";

if ( file_exists("language/".$xoopsConfig['language']."/modinfo.php") ) {
	include("language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include("language/english/modinfo.php");
}

$id = isset($HTTP_GET_VARS['id']) ? intval($HTTP_GET_VARS['id']) : 0;

if ($id != 0) {
  $result = $xoopsDB->queryF("SELECT storyid, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address FROM ".$xoopsDB->prefix(_MIJS_JSTEERING_PREFIX)." WHERE storyid=$id");
}
else {
$result = $xoopsDB->queryF("SELECT storyid FROM ".$xoopsDB->prefix(_MIJS_JSTEERING_PREFIX)." WHERE homepage=1");
list($storyid) = $xoopsDB->fetchRow($result);
header("Location: $PHP_SELF?id=$storyid");
}
include_once XOOPS_ROOT_PATH.'/header.php';
list($storyid,$title,$text,$visible,$nohtml,$nosmiley,$nobreaks,$nocomments,$link,$address) = $xoopsDB->fetchRow($result);
	
      if ($link == 1) {
		// include external content
		
		$includeContent = XOOPS_ROOT_PATH."/modules/"._MIJS_DIR_NAME."/content/".$address;
		if (file_exists($includeContent)){
		  $xoopsOption['template_main'] = 'tc_index.html';
		
		  ob_start();
	      include($includeContent);
	      $content = ob_get_contents();
          ob_end_clean();
		  //$content = include($includeContent);

		  $xoopsTpl->assign('title', $title);
		  $xoopsTpl->assign('content', $content);
		  $xoopsTpl->assign('nocomments', $nocomments);
		  $xoopsTpl->assign('mail_link', 'mailto:?subject='.sprintf(_JTC_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_JTC_INTARTFOUND, $xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/jsteering/index.php?id='.$id);
		  $xoopsTpl->assign('lang_printerpage', _JTC_PRINTERFRIENDLY);
		  $xoopsTpl->assign('lang_sendstory', _JTC_SENDSTORY);
		  $xoopsTpl->assign('id', $id);
    	}
		else{
			redirect_header("index.php",1,_JTC_FILENOTFOUND);
		}
	  }
	  else {
        // JSteering Tiny Content
		$xoopsOption['template_main'] = 'tc_index.html';

	    if ($nohtml == 1) { $html = 0; } else { $html = 1; }
		if ($nosmiley == 1) { $smiley = 0; } else { $smiley = 1; }
		if ($nobreaks == 1) { $breaks = 0; } else { $breaks = 1; }

		$myts =& MyTextSanitizer::getInstance();
	    $text=$myts->displayTarea($text, $html, $smiley, 1, 1, $breaks);
		
		$xoopsTpl->assign('title', $title);
		$xoopsTpl->assign('content', $text);
  	    $xoopsTpl->assign('nocomments', $nocomments);
        $xoopsTpl->assign('mail_link', 'mailto:?subject='.sprintf(_JTC_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_JTC_INTARTFOUND, $xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/jsteering/index.php?id='.$id);
		$xoopsTpl->assign('lang_printerpage', _JTC_PRINTERFRIENDLY);
		$xoopsTpl->assign('lang_sendstory', _JTC_SENDSTORY);
		$xoopsTpl->assign('id', $id);
	  }

include XOOPS_ROOT_PATH.'/include/comment_view.php';
include_once XOOPS_ROOT_PATH.'/footer.php';
?>