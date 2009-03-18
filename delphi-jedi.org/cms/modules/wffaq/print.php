<?php
/* 
* $Id: print.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include '../../mainfile.php';

$storyid = isset($HTTP_GET_VARS['storyid']) ? intval($HTTP_GET_VARS['storyid']) : 0;
if ( empty($storyid) ) {
	redirect_header("index.php");
}
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.newsstory.php';

function PrintPage($storyid)
{
	global $xoopsConfig, $xoopsModule;
	$story = new NewsStory($storyid);
    $datetime = formatTimestamp($story->published());
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
	echo '<html><head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset='._CHARSET.'" />';
	echo '<title>'.$xoopsConfig['sitename'].'</title>';
	echo '<meta name="AUTHOR" content="'.$xoopsConfig['sitename'].'" />';
	echo '<meta name="COPYRIGHT" content="Copyright (c) 2001 by '.$xoopsConfig['sitename'].'" />';
	echo '<meta name="DESCRIPTION" content="'.$xoopsConfig['slogan'].'" />';
	echo '<meta name="GENERATOR" content="'.XOOPS_VERSION.'" />';
	echo '<body bgcolor="#ffffff" text="#000000" onload="window.print()">
    	<table border="0"><tr><td align="center">
    	<table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
    	<table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td align="center">
    	<img src="'.XOOPS_URL.'/images/logo.gif" border="0" alt="" /><br /><br />
    	<h3>'.$story->title().'</h3>
    	<small><b>'._NW_DATE.'</b>&nbsp;'.$datetime.' | <b>'._NW_TOPICC.'</b>&nbsp;'.$story->topic_title().'</small><br /><br /></td></tr>';
	echo '<tr valign="top" style="font:12px;"><td>'.$story->hometext().'<br />';
	$bodytext = $story->bodytext();
	$bodytext = str_replace("[pagebreak]","<br style=\"page-break-after:always;\">",$bodytext);
	if ( $bodytext != '' ){
    		echo $bodytext.'<br /><br />';
	}
	echo '</td></tr></table></td></tr></table>
	<br /><br />';
	printf(_NW_THISCOMESFROM,$xoopsConfig['sitename']);
	echo '<br /><a href="'.XOOPS_URL.'/">'.XOOPS_URL.'</a><br /><br />
    	'._NW_URLFORSTORY.' <!-- Tag below can be used to display Permalink image --><!--img src="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/images/x.gif" /--><br />
    	<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/article.php?storyid='.$story->storyid().'">'.XOOPS_URL.'/article.php?storyid='.$story->storyid().'</a>
    	</td></tr></table>
    	</body>
    	</html>
    	';
}
PrintPage($storyid);
?>