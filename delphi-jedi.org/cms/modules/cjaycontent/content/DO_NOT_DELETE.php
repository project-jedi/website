<?php
// ------------------------------------------------------------------------- //
//						 C-JAY Content							             //
//				         Version:  V2				  	  					 //
//						  Module for										 //
//				XOOPS - PHP Content Management System				 		 //
//					<http://www.xoops.org/>						  			 //
// ------------------------------------------------------------------------- //
// Author: Christoph forlon Brecht          								 //
// Purpose: Module to wrap html or php-content into nice Xoops design.	     //
// email: master@c-jay.net										  			 //
// Site: http://c-jay.net													 //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify	 //
//  it under the terms of the GNU General Public License as published by	 //
//  the Free Software Foundation; either version 2 of the License, or 	     //
//  (at your option) any later version. 							         //
//															                 //
//  This program is distributed in the hope that it will be useful,		     //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of		     //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the		     //
//  GNU General Public License for more details.						     //
// ------------------------------------------------------------------------- //
$myts =& MyTextSanitizer::getInstance();
$result1 = $xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix()."_modules WHERE dirname='cjaycontent'");
$result2 = $xoopsDB->query("SELECT id, title, comment, weight FROM ".$xoopsDB->prefix()."_cjaycontent WHERE NOT (weight='0') AND NOT (title LIKE '..%') ORDER BY weight");
$cc_name = $xoopsDB->fetchArray($result1);
$x=1;
echo "<table align=\"center\" width=\"95%\" border=\"0\">";
echo "<tr><td colspan=\"2\"><center><h1>".$myts->makeTboxData4Show($cc_name['name'])."</h1></center></td></tr>";
while($cc_start = $xoopsDB->fetchArray($result2)) {
		if ($x%2 !=0) {
		echo "<tr align=\"center\" class=\"odd\">
		<td><a href='".XOOPS_URL."/modules/cjaycontent/index.php?id=".$myts->makeTboxData4Show($cc_start['id'])."'>".$myts->makeTboxData4Show($cc_start['title'])."</a></td>
		<td>".$myts->makeTboxData4Show($cc_start['comment'])."</td>
		</tr>";
		}
		else {
		echo "<tr align=\"center\" class=\"even\">
		<td><a href='".XOOPS_URL."/modules/cjaycontent/index.php?id=".$myts->makeTboxData4Show($cc_start['id'])."'>".$myts->makeTboxData4Show($cc_start['title'])."</a></td>
		<td>".$myts->makeTboxData4Show($cc_start['comment'])."</td>
		</tr>";
		}
		$x++;
		}
echo "</table>";		
?>