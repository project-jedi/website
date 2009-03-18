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

// file_checkin.php

include '../../mainfile.php';
include_once 'inc_dest_path_and_file.php';
include_once 'inc_dms_functions.php';
include_once 'defines.php';

if ($HTTP_POST_VARS["hdn_checkin_file_confirm"])
  {
  if (    ($HTTP_POST_VARS['hdn_new_major_version']      == $HTTP_POST_VARS['hdn_current_major_version'])
       && ($HTTP_POST_VARS['hdn_new_minor_version']      == $HTTP_POST_VARS['hdn_current_minor_version'])
       && ($HTTP_POST_VARS['hdn_new_sub_minor_version']  == $HTTP_POST_VARS['hdn_current_sub_minor_version']) )
    {
	// *** Version # is same as current version #
	
	// Determine the path and filename of the new file
  
    // Get the document path and filename of the destination file
    $query  = "SELECT file_path,row_id from ".$xoopsDB->prefix("dms_object_versions")." ";
	$query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."' ";
	$query .= "AND   major_version='".$HTTP_POST_VARS['hdn_current_major_version']."' ";
	$query .= "AND   minor_version='".$HTTP_POST_VARS['hdn_current_minor_version']."' ";
	$query .= "AND   sub_minor_version='".$HTTP_POST_VARS['hdn_current_sub_minor_version']."'";
    $result = mysql_fetch_object(mysql_query($query));

	$partial_path_and_file = $result->file_path;
	$db_row_id = $result->row_id;
		
    // Get the path of the document repository
    $query = "SELECT data from ".$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
    $file_sys_root = mysql_result(mysql_query($query),'data');
    
    // Get the document path and filename of the destination file
    $dest_path_and_file = $file_sys_root."/".$partial_path_and_file;
  
    // Get the source path and filename
    $source_path_and_file = $_FILES['upload_file']['tmp_name'];
    
    // Move the file
    if(is_uploaded_file($source_path_and_file))
     move_uploaded_file($source_path_and_file,$dest_path_and_file) or die("Error:  Unable to move file.");
    else die("Error:  Uploaded File inaccessable.");
     
    // Update the entry in dms_object_versions and store the appropriate information.
    $query  = "UPDATE ".$xoopsDB->prefix('dms_object_versions')." SET ";
	$query .= "file_name='".$_FILES['upload_file']['name']."',";
	$query .= "file_type='".$_FILES['upload_file']['type']."',";
	$query .= "file_size='".$_FILES['upload_file']['size']."' ";
	$query .= "WHERE row_id='".$db_row_id."'";
	mysql_query($query);  
	 
    // Set file status as normal
    $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
    $query .= "SET ";
    $query .= "obj_status='0',";
    $query .= "obj_checked_out_user_id='0' ";
    $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."'";
    
    $xoopsDB->query($query);
	}
  else
	{
    // *** Version # is different from current version #
	
	// Determine the path and filename of the new file
  
    // Get the document path and filename of the destination file
    $partial_path_and_file = dest_path_and_file();

    // Get the path of the document repository
    $query = "SELECT data from ".$xoopsDB->prefix("dms_config")." WHERE name='doc_path'";
    $file_sys_root = mysql_result(mysql_query($query),'data');
    
    // Get the document path and filename of the destination file
    $dest_path_and_file = $file_sys_root."/".$partial_path_and_file;
  
    // Get the source path and filename
    $source_path_and_file = $_FILES['upload_file']['tmp_name'];
    
    // Move the file
    if(is_uploaded_file($source_path_and_file))
     move_uploaded_file($source_path_and_file,$dest_path_and_file) or die("Error:  Unable to move file.");
    else die("Error:  Uploaded File inaccessable.");

    // Add a new version of this file to dms_object_versions
    // Create an entry in dms_object_versions and store the appropriate information.
    $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_versions')." (obj_id,file_path,file_name,file_type,file_size,";
    $query .= "major_version,minor_version,sub_minor_version)";
    $query .= " VALUES ('";
    $query .= $HTTP_POST_VARS['hdn_file_id']."','";
    $query .= $partial_path_and_file."','";
    $query .= $_FILES['upload_file']['name']."','";
    $query .= $_FILES['upload_file']['type']."','";
    $query .= $_FILES['upload_file']['size']."','";
    $query .= $HTTP_POST_VARS['hdn_new_major_version']."','";
    $query .= $HTTP_POST_VARS['hdn_new_minor_version']."','";
    $query .= $HTTP_POST_VARS['hdn_new_sub_minor_version']."')";

    mysql_query($query);  
  
    // Find the row_id of the entry just created in dms_object_versions.
    $dms_object_versions_row_id = mysql_insert_id();
      
    // Set file status as normal and change the current_version_row_id
    $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
    $query .= "SET ";
    $query .= "obj_status='0',";
    $query .= "obj_checked_out_user_id='0',";
    $query .= "current_version_row_id='".$dms_object_versions_row_id."' ";
    $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."'";
    
    $xoopsDB->query($query);
  
    }
  
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("location='index.php';");
  print("</SCRIPT>");  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Set up JavaScript
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "function select_version(selected_version)\r";
  print "{\r";
  
  print "  frm_checkin.hdn_new_major_version.value     = frm_checkin.hdn_current_major_version.value;\r";
  print "  frm_checkin.hdn_new_minor_version.value     = frm_checkin.hdn_current_minor_version.value;\r";
  print "  frm_checkin.hdn_new_sub_minor_version.value = frm_checkin.hdn_current_sub_minor_version.value;\r";

  print "  switch(selected_version)\r";
  print "  {\r";
  print "    case ".SAME.":\r";
  print "    {\r";
  print "    break;\r";
  print "    }\r";

  print "    case ".INCSUB.":\r";
  print "    {\r";
  print "    frm_checkin.hdn_new_sub_minor_version.value++;\r";
  print "    break;\r";
  print "    }\r";
  
  print "    case ".INCMINOR.":\r";
  print "    {\r";
  print "    frm_checkin.hdn_new_minor_version.value++;\r";
  print "    frm_checkin.hdn_new_sub_minor_version.value=0;\r";
  print "    break;\r";
  print "    }\r";

  print "    case ".INCMAJOR.":\r";
  print "    {\r";
  print "    frm_checkin.hdn_new_major_version.value++;\r";
  print "    frm_checkin.hdn_new_minor_version.value = 0;\r";
  print "    frm_checkin.hdn_new_sub_minor_version.value = 0;\r";
  print "    break;\r";
  print "    }\r";

  print "    default:\r";
  print "    {\r";
  print "    }\r";
          
  print "  }\r";
  print "}\r";
  print "</SCRIPT>\r";  
    
  // Get file information
  $query  = "SELECT obj_name,current_version_row_id from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["file_id"]."'";  
  $first_result = mysql_fetch_object(mysql_query($query));

  $query  = "SELECT obj_descript from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["file_id"]."'";  
  $second_result = mysql_fetch_object(mysql_query($query));
  
  $query  = "SELECT major_version,minor_version,sub_minor_version from ".$xoopsDB->prefix('dms_object_versions')." ";
  $query .= "WHERE row_id='".$first_result->current_version_row_id."'";
  $version_number = mysql_fetch_object(mysql_query($query));
  
  print "<form name='frm_checkin' method='post' action='file_checkin.php' enctype='multipart/form-data'>\r";
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Check-in File:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>File Name:&nbsp;&nbsp;&nbsp;";
  print "        ".$first_result->obj_name."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>File Description:&nbsp;&nbsp;&nbsp;";
  print "        ".$second_result->obj_descript."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>Current Version:&nbsp;&nbsp;&nbsp;";
  print        $version_number->major_version.".";
  print        $version_number->minor_version;
  print        $version_number->sub_minor_version."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left' valign='top' nowrap>New Version:<BR>\r";
  
  display_spaces(20);
  
  print "      <input type='radio' name='rad_new_version_number' onclick='select_version(".SAME.");'>";
  print $version_number->major_version.".".$version_number->minor_version.$version_number->sub_minor_version;
  print "&nbsp;&nbsp;&nbsp(same)<BR>\r";

  if ( ($version_number->sub_minor_version +1) <= 9)
    {
    display_spaces(20);
    
    print "      <input type='radio' name='rad_new_version_number' onclick='select_version(".INCSUB.");'>";
    print $version_number->major_version.".".$version_number->minor_version.($version_number->sub_minor_version +1 );
    print "<BR>\r";
    }
  
  if ( ($version_number->minor_version + 1) <= 9)
    {
    display_spaces(20);
  
    print "      <input type='radio' name='rad_new_version_number' onclick='select_version(".INCMINOR.");'>";
    print $version_number->major_version.".".($version_number->minor_version + 1)."0";
    print "<BR>\r";
    }
	
  display_spaces(20);
    
  print "      <input type='radio' name='rad_new_version_number' onclick='select_version(".INCMAJOR.");'>";
  print ($version_number->major_version +1 ).".00";
  print "<BR>\r";
  
  print "  </tr>\r";

  print "  <tr>\r";
  print "    <td align='left'>Select File:&nbsp;&nbsp;&nbsp;";
  print "        <input name='upload_file' type='file'></td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Check-in'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_current_major_version' value='".$version_number->major_version."'>\r";
  print "<input type='hidden' name='hdn_current_minor_version' value='".$version_number->minor_version."'>\r";
  print "<input type='hidden' name='hdn_current_sub_minor_version' value='".$version_number->sub_minor_version."'>\r"; 
  print "<input type='hidden' name='hdn_new_major_version' value='0'>\r";
  print "<input type='hidden' name='hdn_new_minor_version' value='0'>\r";
  print "<input type='hidden' name='hdn_new_sub_minor_version' value='0'>\r";
  print "<input type='hidden' name='hdn_checkin_file_confirm' value='confim'>\r";
  print "<input type='hidden' name='hdn_time_stamp' value='".time()."'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$HTTP_GET_VARS["file_id"]."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
