<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 2003                                     //
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

// file_copy.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';
include_once 'inc_dest_path_and_file.php';

if ($HTTP_POST_VARS["hdn_file_copy"])
  {
  $location = "file_options.php?obj_id=".$HTTP_POST_VARS['hdn_file_id'];

  $partial_path_and_file = dest_path_and_file();
  
  // Get the location of the document repository
  $query = "SELECT data FROM ".$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
  $file_sys_root = mysql_result(mysql_query($query),'data');
  
  $dest_path_and_file    = $file_sys_root."/".$partial_path_and_file;
    
  // Get the name, path and file of the source file.
  $query  = "SELECT obj_name, current_version_row_id FROM ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."'";
  $source_object = mysql_fetch_object(mysql_query($query));
  
  $query  = "SELECT file_path, file_name, file_type, file_size FROM ".$xoopsDB->prefix("dms_object_versions")." ";
  $query .= "WHERE row_id='".$source_object->current_version_row_id."'";
  $source_file_info = mysql_fetch_object(mysql_query($query));
  
  $source_path_and_file = $file_sys_root."/".$source_file_info->file_path;

  // Copy the file.
  if (!copy($source_path_and_file,$dest_path_and_file))
    {
	print "Error:  Failure to copy file.  Either source path or destination path is not accessable.\r";
	exit(0);
	}

  // Create the new object in dms_objects
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_objects')." (obj_type,obj_name,obj_owner)";
  $query .= " VALUES ('";
  $query .= "0','";
  $query .= $source_object->obj_name."','";
  $query .= $HTTP_POST_VARS['rad_dest_folder_id']."')";
  
  mysql_query($query);
  
  // Get the obj_id of the new object
  $obj_id = mysql_insert_id();
  
  // Store the owner permissions in dms_object_perms  
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
  $query .= ""."')";

  mysql_query($query);

  // Create an entry in dms_object_versions and store the appropriate information.
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_versions')." (obj_id,file_path,file_name,file_type,file_size,";
  $query .= "major_version,minor_version,sub_minor_version)";
  $query .= " VALUES ('";
  $query .= $obj_id."','";
  $query .= $partial_path_and_file."','";
  $query .= $source_file_info->file_name."','";
  $query .= $source_file_info->file_type."','";
  $query .= $source_file_info->file_size."','";
  $query .= "1"."','";
  $query .= "0"."','";
  $query .= "0"."')";

  mysql_query($query);  
  
  // Find the row_id of the entry just created in dms_object_versions.
  $dms_object_versions_row_id = mysql_insert_id();
  
  // Enter the row_id of the entry for the current version into dms_objects
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects');
  $query .= " SET current_version_row_id='".$dms_object_versions_row_id."' ";
  $query .= " WHERE obj_id='".$obj_id."'";  
  
  mysql_query($query);
   
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "location='".$location."';";
  print "</SCRIPT>";  
  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  $file_id=$HTTP_GET_VARS["file_id"];
    
  // Get file information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$file_id."'";  
  $first_result = mysql_query($query);

  $query  = "SELECT obj_descript from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$file_id."'";  
  $second_result = mysql_query($query);
  
  print "<form method='post' name='frm_select_dest' action='file_copy.php'>\r";
  display_dms_header(2);
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Copy File:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>File Name:&nbsp;&nbsp;&nbsp;";
  print "        ".mysql_result($first_result,'obj_name')."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>File Description:&nbsp;&nbsp;&nbsp;";
  print "        ".mysql_result($second_result,'obj_descript')."</td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>\r";
  
  include "inc_folder_select.php";
    
  print "    </td>\r";
  print "  </tr>\r";
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
    
  print "  <td colspan='2' align='left'><input type=button name='btn_submit' value='Copy' onclick='check_for_dest();'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"file_options.php?obj_id=".$file_id."\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_file_copy' value='confim'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$file_id."'>\r";
  print "<input type='hidden' name='hdn_destination_folder_id' value=''>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>



