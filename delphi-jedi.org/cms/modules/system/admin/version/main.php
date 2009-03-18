<?php
// $Id: main.php,v 1.5 2003/02/12 11:38:41 okazu Exp $
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
error_reporting(E_ALL);
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->getVar('mid')) || !isset($HTTP_GET_VARS['mid'])) {
	exit("Access Denied");
}

if (is_numeric($HTTP_GET_VARS['mid'])) {
	$module_handler =& xoops_gethandler('module');
	$versioninfo =& $module_handler->get($HTTP_GET_VARS['mid']);
} elseif (file_exists(XOOPS_ROOT_PATH.'/modules/'.trim($HTTP_GET_VARS['mid']).'/xoops_version.php')) {
		$module_handler =& xoops_gethandler('module');
		$versioninfo =& $module_handler->create();
		$versioninfo->loadInfo(trim($HTTP_GET_VARS['mid']));
} else {
	exit();
}

if (!is_object($versioninfo)) {
	exit();
}

//$css = getCss($theme);
echo "<html>\n<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset="._CHARSET."\"></meta>\n";
echo "<title>".$xoopsConfig['sitename']."</title>\n";

?>
<script type="text/javascript">
<!--//
scrollID=0;
vPos=0;

function onWard() {
   vPos+=2;
   window.scroll(0,vPos);
   vPos%=1000;
   scrollID=setTimeout("onWard()",30);
   }
function stop(){
   clearTimeout(scrollID);
}
//-->
</script>
<?php
/*
if($css){
   	echo "<link rel=\"stylesheet\" href=\"".$css."\" type=\"text/css\">\n\n";
}
*/
echo "</head>\n";
echo "<body onLoad=\"if(window.scroll)onWard()\" onmouseover=\"stop()\" onmouseout=\"if(window.scroll)onWard()\">\n";
echo "<div>";
echo "<table width=\"100%\"><tr><td align=\"center\">";
echo "<br /><br /><br /><br /><br />";
echo "<img src=\"".XOOPS_URL."/modules/".$versioninfo->getInfo('dirname')."/".$versioninfo->getInfo('image')."\" border=\"0\" /><br />";
echo "<big><b>".$versioninfo->getInfo('name')."</b></big>";

echo "<br /><br />";
echo "<u>Version</u><br />";
echo round($versioninfo->getInfo('version'), 2);

echo "<br /><br />";
echo "<u>Description</u><br />";
echo $versioninfo->getInfo('description');

echo "<br /><br />";
echo "<u>Author</u><br />";
echo $versioninfo->getInfo('author');

echo "<br /><br />";
echo "<u>Credits</u><br />";
echo $versioninfo->getInfo('credits');

echo "<br /><br />";
echo "<u>License</u><br />";
echo $versioninfo->getInfo('license');

echo "<br /><br /><br /><br /><br />";
echo "<br /><br /><br /><br /><br />";
echo "<a href=\"javascript:window.close();\">Close</a>";
echo "<br /><br /><br /><br /><br /><br />";
echo "</td></tr></table></div>";
echo "</body></html>";
?>