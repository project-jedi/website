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

// folder_expand.php

include '../../mainfile.php';
//include XOOPS_ROOT_PATH.'/header.php';

if ($HTTP_GET_VARS["ret_location"]) $location=$HTTP_GET_VARS["ret_location"];
else $location="index.php";

if ($HTTP_GET_VARS["file_id"]) $location .= "?file_id=".$HTTP_GET_VARS["file_id"];
else $location="index.php";

if ($HTTP_GET_VARS["active"] == "FALSE") $skip_change_active_folder = "FALSE";
else $change_active_folder = "TRUE";

if ($HTTP_GET_VARS["folder_id"])
  {
  //Make sure that this folder is not marked as expanded in order to prevent multiple entries.
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_exp_folders");
  $sql_query .= " WHERE user_id='".$xoopsUser->getVar('uid')."' and folder_id='".$HTTP_GET_VARS["folder_id"]."'";
  mysql_query($sql_query);
  
  // Make sure that this folder, or any other folder, is not marked as active.
  if ($change_active_folder == "TRUE")
    {
    $sql_query = "DELETE FROM ".$xoopsDB->prefix("dms_active_folder")." WHERE user_id='".$xoopsUser->getVar('uid')."'";
    mysql_query($sql_query);
    }
    
  // Set the folder as expanded
  $sql_query  = "INSERT INTO ".$xoopsDB->prefix("dms_exp_folders")." (user_id,folder_id) VALUES ('".$xoopsUser->getVar('uid')."','".$HTTP_GET_VARS["folder_id"]."')";
  mysql_query($sql_query);
    
  // Set the folder as active
  if ($change_active_folder == "TRUE")
    {
    $sql_query = "INSERT INTO ".$xoopsDB->prefix("dms_active_folder")." (user_id,folder_id) VALUES ('".$xoopsUser->getVar('uid')."','".$HTTP_GET_VARS["folder_id"]."')";
    mysql_query($sql_query);
    }
  
  } 
else
  {
  print "Error:  Please contact your system administrator.";
  }

print "<SCRIPT LANGUAGE='Javascript'>\r";
print "location='".$location."';";
print "</SCRIPT>";  
    
//include_once XOOPS_ROOT_PATH.'/footer.php';
?>
