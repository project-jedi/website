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

// lifecycle_demote.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';
include_once 'defines.php';

if($HTTP_POST_VARS["hdn_function"]) 
  {
  $function = $HTTP_POST_VARS["hdn_function"];
  $file_id = $HTTP_POST_VARS["hdn_file_id"];
  }
else 
  {
  $file_id = $HTTP_GET_VARS["file_id"];
  }

if ($function == "DEMOTE")
  {
  // get current lifecycle info
  $query  = "SELECT obj_owner, lifecycle_id, lifecycle_stage ";
  $query .= "FROM ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "WHERE obj_id='".$file_id."' ";
  $result = mysql_fetch_object(mysql_query($query));

  $current_lifecycle_id = $result->lifecycle_id;
  $current_lifecycle_stage = $result->lifecycle_stage;
  $current_object_location = $result->obj_owner;
        
  $query  = "SELECT lifecycle_stage, new_obj_location ";
  $query .= "FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." ";
  $query .= "WHERE lifecycle_id='".$current_lifecycle_id."' ";
  $query .= "ORDER BY lifecycle_stage DESC";
  $result = $xoopsDB->query($query);
 
  $first_lifecycle_stage = 0;
  $lifecycle_flag = "SEARCHING";
  
  $new_lifecycle_stage = 0;
  
  // find next lifecycle
  while($result_data = mysql_fetch_array($result))
    {  
print "S:".$lifecycle_flag."<BR>";
	if ($lifecycle_flag == "FOUND") 
	  {
	  $new_lifecycle_stage = $result_data['lifecycle_stage'];  
	  $new_obj_location = $result_data['new_obj_location'];
	  $lifecycle_flag = "CHANGE";
	  }
	    
	if ($result_data['lifecycle_stage'] == $current_lifecycle_stage) $lifecycle_flag = "FOUND"; 
    
	$first_lifecycle_stage = $result_data['lifecycle_stage'];
print "E:".$lifecycle_flag."<BR>";

	}

print "new_lifecycle_stage=".$new_lifecycle_stage."<BR>";
print "new_obj_location=".$new_obj_location."<BR>";
	
  // if there are no more lifecycles, set the document to the first stage in the lifecycle.
  if ( ($lifecycle_flag != "CHANGE") || ($first_lifecycle_stage == $new_lifecycle_stage) )
    {
print "Set to first stage<BR>";
	// Get the destination information for the first stage of the lifecycle
    $query  = "SELECT lifecycle_id, lifecycle_stage, new_obj_location ";
	$query .= "FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." ";
	$query .= "WHERE lifecycle_id='".$current_lifecycle_id."' ";
    $query .= "ORDER BY lifecycle_stage";
print $query."<BR>";
	$result = $xoopsDB->query($query);
    $result_data = mysql_fetch_array($result);
	
	$new_lifecycle_stage = $result_data['lifecycle_stage'];
	if($lifecycle_flag != "CHANGE") $new_obj_location = $result_data['new_obj_location'];	
	}

  // Update the file properties.
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "SET ";
  $query .= "obj_owner='".$new_obj_location."', ";
  $query .= "lifecycle_id='".$current_lifecycle_id."', ";
  $query .= "lifecycle_stage='".$new_lifecycle_stage."' ";
  $query .= "WHERE obj_id='".$file_id."'";
print $query;

  $xoopsDB->query($query);
    
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "location=\"index.php\";";
  print "</SCRIPT>";  
  exit(0);
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Get file information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$file_id."'";  
  $first_result = mysql_fetch_object(mysql_query($query));

  $query  = "SELECT obj_descript from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$file_id."'";  
  $second_result = mysql_fetch_object(mysql_query($query));
  
  print "<form method='post' action='lifecycle_demote.php'>\r";
  print "<table width='100%'>\r";
  
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Demote Document:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>File Name:&nbsp;&nbsp;&nbsp;";
  print "        ".$first_result->obj_name."</td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Demote'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_function' value='DEMOTE'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$file_id."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
