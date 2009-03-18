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

// folder_delete.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

if ($HTTP_POST_VARS["hdn_delete_folder_confirm"])
  {
  // Contract the folder
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_exp_folders");
  $sql_query .= " WHERE user_id='".$xoopsUser->getVar('uid')."' and folder_id='".$HTTP_POST_VARS['hdn_folder_id']."'";
  mysql_query($sql_query);
  
  // Make sure that this folder cannot be marked as active
  $sql_query  = "DELETE FROM ".$xoopsDB->prefix("dms_active_folder");
  $sql_query .= " WHERE user_id='".$xoopsUser->getVar('uid')."' and folder_id='".$HTTP_POST_VARS['hdn_folder_id']."'";
  mysql_query($sql_query);
    
  // Mark the folder as deleted
  $query = sprintf("UPDATE %s SET obj_status='2' WHERE obj_id='%s'",$xoopsDB->prefix("dms_objects"),$HTTP_POST_VARS["hdn_folder_id"]);
  $xoopsDB->query($query);
  
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("location='index.php';");
  print("</SCRIPT>");  
  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Get folder information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["folder_id"]."'";  
  $result = mysql_fetch_object(mysql_query($query));
  
  print "<form method='post' action='folder_delete.php'>\r";
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Delete Folder:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>Folder Name:&nbsp;&nbsp;&nbsp;";
  print "        ".$result->obj_name."</td>\r";
  print "  </tr>\r";
  /*
  print "  <tr>\r";
  print "    <td colspan='2'>Folder Description:&nbsp;&nbsp;&nbsp;";
  print "        ".mysql_result($result,'obj_descript')."</td>\r";
  print "  </tr>\r";
  */
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Delete'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"folder_options.php?obj_id=".$HTTP_GET_VARS["folder_id"]."\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_delete_folder_confirm' value='confim'>\r";
  print "<input type='hidden' name='hdn_folder_id' value='".$HTTP_GET_VARS["folder_id"]."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
