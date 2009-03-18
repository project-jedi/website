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

// file_import.php

include '../../mainfile.php';
include_once 'inc_dest_path_and_file.php';
include_once 'inc_dms_functions.php';

// Initialize magic_number.  This number is used to create unique file names in order to guarantee that 2 file names
// will not be identical if 2 users upload a file at the exact same time.  100000 will allow almost 100000 users to use
// this system.  Ok, the odds of this happening are slim; but, I want the odds to be zero.
$magic_number = 100000;

if ($HTTP_POST_VARS["txt_file_name"])
  {
  $partial_path_and_file = dest_path_and_file();
  
  // Get the location of the document repository
  $query = "SELECT data from ".$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
  $file_sys_root = mysql_result(mysql_query($query),'data');
  
  $dest_path_and_file    = $file_sys_root."/".$partial_path_and_file;
    
  // Get the temporary path and file of the recent upload.
  $source_path_and_file  = $_FILES[$HTTP_POST_VARS['hdn_temp_file_name']]['tmp_name'];
  
  if(is_uploaded_file($source_path_and_file))
   move_uploaded_file($source_path_and_file,$dest_path_and_file) or die("Error:  Unable to move file.<BR>SP:".$source_path_and_file."<BR>DP:".$dest_path_and_file);
  else die("Error:  Uploaded File inaccessable.");
  
    // Create the new object in dms_objects
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_objects')." (obj_type,obj_name,obj_owner)";
  $query .= " VALUES ('";
  $query .= "0','";
  $query .= $HTTP_POST_VARS['txt_file_name']."','";
  $query .= $HTTP_POST_VARS['hdn_active_folder']."')";
  
  mysql_query($query);
  
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

    
  // Create an entry in dms_object_properties and store additional properties information.
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_properties')." (obj_id,obj_descript)";
  $query .= " VALUES ('";
  $query .= $obj_id."','";
  $query .= $HTTP_POST_VARS['txt_file_descript']."')";

  mysql_query($query);

  // Create an entry in dms_object_versions and store the appropriate information.
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_versions')." (obj_id,file_path,file_name,file_type,file_size,";
  $query .= "major_version,minor_version,sub_minor_version)";
  $query .= " VALUES ('";
  $query .= $obj_id."','";
  $query .= $partial_path_and_file."','";
  $query .= $_FILES[$HTTP_POST_VARS['hdn_temp_file_name']]['name']."','";
  $query .= $_FILES[$HTTP_POST_VARS['hdn_temp_file_name']]['type']."','";
  $query .= $_FILES[$HTTP_POST_VARS['hdn_temp_file_name']]['size']."','";
  $query .= $HTTP_POST_VARS['slct_version_major']."','";
  $query .= $HTTP_POST_VARS['slct_version_minor']."','";
  $query .= $HTTP_POST_VARS['slct_version_sub_minor']."')";

  mysql_query($query);  
  
  // Find the row_id of the entry just created in dms_object_versions.
  $dms_object_versions_row_id = mysql_insert_id();
  
  // Enter the row_id of the entry for the current version into dms_objects
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects');
  $query .= " SET current_version_row_id='".$dms_object_versions_row_id."' ";
  $query .= " WHERE obj_id='".$obj_id."'";  
  
  mysql_query($query);
      
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("location='index.php';");
  print("</SCRIPT>");  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  // Determine temporary file name to use for uploads.
  $temp_file_name = (string) time().(string) ($magic_number + $xoopsUser->getVar('uid'));
   
  // Get active folder
  $query = "SELECT folder_id from ".$xoopsDB->prefix("dms_active_folder")." WHERE user_id='".$xoopsUser->getVar('uid')."'";  
  $result = mysql_query($query);
  $active_folder = mysql_result($result,'folder_id');
  if(!$active_folder>=1) $active_folder=0;

  print "<form method='post' action='file_import.php' enctype='multipart/form-data'>\r";
  print "<table width='100%'>\r";
  
  display_dms_header();
  print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Import Document:</b></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>File Name:</td>\r";
  print "    <td align='left'><input type='text' name='txt_file_name'></td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>File Description:</td>\r";
  print "    <td align='left'><input type='text' name='txt_file_descript'</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>Initial Version:</td>\r";

  print "    <td align='left'>\r";
  select_version_number("slct_version",1,0,0);
  print "    </td>\r";
  
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td align='left'>Select File:</td>";
  print "    <td align='left'><input name='".$temp_file_name."' type='file'></td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Submit'>";
  print "    <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_temp_file_name' value='".$temp_file_name."'\r";
  print "<input type='hidden' name='hdn_time_stamp' value='".time()."'>\r";
  print "<input type='hidden' name='hdn_active_folder' value='".$active_folder."'>\r";
  print "<input type='hidden' name='MAX_FILE_SIZE' value='5000000'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
