<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                                                                           //
//                                                                           //
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

// folder_new.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';


if ($HTTP_POST_VARS["txt_folder_name"])
  {
  $doc_path = $HTTP_POST_VARS["doc_path"];
  $query = sprintf("INSERT INTO %s (obj_type,obj_name,obj_owner) VALUES ('1','%s','%d')",$xoopsDB->prefix("dms_objects"),$HTTP_POST_VARS["txt_folder_name"],$HTTP_POST_VARS["hdn_active_folder"]);
  mysql_query($query);
  //$xoopsDB->query($query);
  
  // Get the obj_id of the new object
  $obj_id = mysql_insert_id();
  
  // Store the owner permissions in dms_object_perms  TEMP CODE
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_perms')." ";
  $query .= "(ptr_obj_id,user_id, group_id, user_perms, group_perms, everyone_perms) VALUES ('";
  $query .= $obj_id."','";
  $query .= $xoopsUser->getVar('uid')."','";
  $query .= "0','";
  $query .= "4','";
  $query .= "0','";
  $query .= "0')";

  mysql_query($query);

    
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("location='index.php';");
  print("</SCRIPT>");  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Get active folder
  $query = "SELECT folder_id from ".$xoopsDB->prefix("dms_active_folder")." WHERE user_id='".$xoopsUser->getVar('uid')."'";  
  $result = mysql_query($query);
  $active_folder = mysql_result($result,'folder_id');
  if(!$active_folder>=1) $active_folder=0;

  print "<form method='post' action='folder_new.php'>\r";
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Add Folder:</b></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>Folder Name:</td>\r";
  print "    <td align='left'><input type='text' name='txt_folder_name'></td>\r";
  print "  </tr>\r";
  /*
  print "  <tr>\r";
  print "    <td>Folder Description:</td>\r";
  print "    <td><input type='text' name='txt_folder_descript'</td>\r";
  print "  </tr>\r";
  */
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Submit'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_active_folder' value='".$active_folder."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }

?>
