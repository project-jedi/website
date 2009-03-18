<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 5/13/2003                                //
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

// link_options.php

include '../../mainfile.php';
include_once 'defines.php';
include_once 'inc_dms_functions.php';
include_once 'inc_perms_check.php';

include XOOPS_ROOT_PATH.'/header.php';
  
// Add the version_view() javascript function
print "<SCRIPT LANGUAGE='Javascript'>\r";
print "function version_view()\r";
print "  {\r";
print "  if (frm_options.slct_version_view.value == 0) return;\r";
print "  var url = 'version_view.php?ver_id=';\r";
print "  url = url + frm_options.slct_version_view.value;\r";
print "  window.open(url);\r";
print "  }\r";
print "</SCRIPT>\r";

// Get actual object ID
$query  = "SELECT ptr_obj_id from ".$xoopsDB->prefix('dms_objects')." ";
$query .= "WHERE obj_id='".$HTTP_GET_VARS["obj_id"]."'";  
$result = mysql_fetch_object(mysql_query($query));
$obj_id = $result->ptr_obj_id;  
  
// Get object information
$query  = "SELECT obj_name, obj_status, obj_checked_out_user_id from ".$xoopsDB->prefix("dms_objects")." ";
$query .= "WHERE obj_id='".$obj_id."'";  
$first_result = mysql_fetch_object(mysql_query($query));

$query  = "SELECT obj_descript,obj_keywords,obj_authors,obj_mms_nums from ".$xoopsDB->prefix("dms_object_properties")." ";
$query .= "WHERE obj_id='".$obj_id."'";  
$second_result = mysql_fetch_object(mysql_query($query));

// Get permissions
$perm = perms_level($obj_id);
  
print "<form method='post' name='frm_options' action='file_options.php'>\r";
print "<table width='100%'>\r";
//print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
display_dms_header();

print "  <tr><td colspan='2'><BR></td></tr>\r";

print "  <table border='0'>\r";
print "    <tr>\r";
print "      <td>\r";
print "        <table border='0'>\r";

if ($perm >= BROWSE)    
  {
  // Display the properties
  print "  <tr><td colspan='2' align='left'><u>Properties:</u></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td width='20%' align='left'>&nbsp;&nbsp;&nbsp;Name:</td>";
  print "    <td align='left'>".$first_result->obj_name."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>&nbsp;&nbsp;&nbsp;Description:</td>";
  print "    <td align='left'>".$second_result->obj_descript."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>&nbsp;&nbsp;&nbsp;Keywords:</td>";
  print "    <td align='left'>".$second_result->obj_keywords."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>&nbsp;&nbsp;&nbsp;Authors:</td>";
  print "    <td align='left'>".$second_result->obj_authors."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>&nbsp;&nbsp;&nbsp;MMS Numbers:";
  print "    <td align='left'>".$second_result->obj_mms_nums."</td>\r";
  print "  </tr>\r";
  
  if ($first_result->obj_status == CHECKEDOUT)
    {
    // Display the name of the user who has this document checked out.
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><u>Checked-Out By:</u></td></tr>\r";
	
	$query = "SELECT uid,uname from ".$xoopsDB->prefix("users")." WHERE uid='".$first_result->obj_checked_out_user_id."'";
	$result = mysql_fetch_object(mysql_query($query));

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	
	print "  <tr></tr><td align='left'>&nbsp;&nbsp;&nbsp;".$result->uname."</td></tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	}
  }
else
  { 
  print "  <tr>\r";
  print "    <td align='center'>You do not have any permission to access this document.</td>\r";
  print "  </tr>\r";
  }

if ($perm > BROWSE)
  {  
  // Display the "view version or rendition" select box
  print "  <tr><td colspan='2' align='left'><u>View a version of this document:</u></td></tr>\r";
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  
  print "  <tr>\r";
  print "    <td align='left'>&nbsp;&nbsp;&nbsp;\r"; 
  print "      <select name='slct_version_view' onchange='version_view();'>\r";
  print "        <option value='0'>None</option>\r";
  
  $query  = "SELECT row_id,major_version,minor_version,sub_minor_version FROM ".$xoopsDB->prefix('dms_object_versions')." ";
  $query .= "WHERE obj_id='".$obj_id."'";
  $result = $xoopsDB->query($query);
 
  while($result_data = mysql_fetch_array($result))
    {
    print "        <option value='".$result_data['row_id']."'>";
    print $result_data['major_version'].".".$result_data['minor_version'].".".$result_data['sub_minor_version'];
    print "</option>\r";
    }
    
  print "      </select>\r";
  print "    </td>\r";
  print "  </tr>\r";

  print "  <tr><td colspan='2'><BR></td></tr>\r";
  }
    
print "        </table>\r";
print "      </td>\r";
print "      <td valign='top'>\r";
print "        <table border='0'>\r";

print "  <tr>\r";
print "    <td colspan='2' align='left'>\r";
print "      <input type=button name='btn_delete' value='Delete' onclick='location=\"link_delete.php?link_id=".$HTTP_GET_VARS["obj_id"]."\";'>\r";
print "    </td>\r";
print "  </tr>\r";

print "  <tr><td colspan='2'><BR></td></tr>\r";

print "  <tr>\r";
print "    <td colspan='2' align='left'>\r";
print "      <input type=button name='btn_cancel' value='Exit' onclick='location=\"index.php\";'>\r";
print "    </td>\r";
print "  </tr>\r";

print "        </table>\r";
print "      </td>\r";
print "    </tr>\r";

print "</table>\r";
print "</form>\r";
  
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
