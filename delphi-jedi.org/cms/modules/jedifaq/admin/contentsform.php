<?php
// $Id: contentsform.php,v 1.5 2003/02/12 11:38:58 okazu Exp $
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

echo "<form action='index.php' method='post'>
<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td class='bg2'>
<table width='100%' border='0' cellpadding='4' cellspacing='1'>
<tr><td nowrap='nowrap' class='bg3'>"._XD_QUESTION." </td><td class='bg1'><input type='text' name='contents_title' value='$contents_title' size='31' maxlength='1000' /></td></tr>
<tr><td nowrap='nowrap' class='bg3'>"._XD_ORDER." </td><td class='bg1'><input type='text' name='contents_order' value='".$contents_order."' size='4' maxlength='3' /></td></tr>";

$checked = ($contents_visible == 1) ? " checked='checked'" : "";

echo "<tr><td nowrap='nowrap' class='bg3'>"._XD_DISPLAY." </td><td class='bg1'><input type='checkbox' name='contents_visible' value='1'$checked /></td></tr>
<tr><td nowrap='nowrap' class='bg3'>"._XD_ANSWER." </td><td class='bg1'>";

include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

xoopsCodeTarea("contents_contents", 60, 20);
xoopsSmilies("contents_contents");

$checked = " checked='checked'";
echo "<br /><input type='checkbox' name='contents_nohtml' value='1'$checked />"._XD_NOHTML."<br />";

$checked = ($contents_nosmiley == 1) ? " checked='checked'" : "";
echo "<input type='checkbox' name='contents_nosmiley' value='1'$checked />"._XD_NOSMILEY."<br />";

$checked = ($contents_noxcode == 1) ? " checked='checked'" : "";
echo "<input type='checkbox' name='contents_noxcode' value='1'$checked />"._XD_NOXCODE."</td></tr>
<tr><td nowrap='nowrap' class='bg3'>&nbsp;</td><td class='bg1'><input type='hidden' name='category_id' value='".$category_id."' /><input type='hidden' name='contents_id' value='".$contents_id."' /><input type='hidden' name='op' value='$op' /><input type='submit' name='contents_preview' value='"._PREVIEW."' />&nbsp;<input type='submit' name='contents_submit' value='"._SUBMIT."' /></td></tr>
</table></td></tr></table>
</form>";

?>