<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 6/24/2003                                //
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

// DMS Functions
// inc_dms_functions.php


function display_dms_header($number_of_columns=3)
{
global $xoopsDB;

$query = "SELECT data from ".$xoopsDB->prefix('dms_config')." WHERE name='dms_title'";
$title = mysql_result(mysql_query($query),'data');

print "  <tr><td colspan='".$number_of_columns."' class='cHeader'><center><b><font size='2'>".$title."</font></b></center></td></tr>\r";
}

function display_spaces($number_of_spaces=1)
{
$index=0;

while($index < $number_of_spaces)
  {
  print "&nbsp;";
  
  $index++;
  }
}

function select_version_number($select_box_naming = 'slct_version',$major_num = 1, $minor_num = 0, $sub_minor_num = 0)
{
  print "<select name='".$select_box_naming."_major'>\r";
  $index=0;
  while($index < 10)
    {
	print "<option value='".$index."' ";
	if ($index == $major_num) print " selected";
	print ">".$index."</option> \r";
	
	$index++;
	}
  print "</select>\r";  
  
  print "&nbsp;.&nbsp;\r";
  
  print "<select name='".$select_box_naming."_minor'>\r";
  $index=0;
  while($index < 10)
    {
	print "<option value='".$index."' ";
	if ($index == $minor_num) print " selected";
	print ">".$index."</option> \r";
	
	$index++;
	}
  print "</select>\r";  
  
  print "&nbsp;.&nbsp;\r";
  
  print "<select name='".$select_box_naming."_sub_minor'>\r";
  $index=0;
  while($index < 10)
    {
	print "<option value='".$index."' ";
	if ($index == $sub_minor_num) print " selected";
	print ">".$index."</option> \r";
	
	$index++;
	}
  print "</select>\r";  
}

?>
