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

// file_move.php
$location="file_move.php";

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

if ($HTTP_POST_VARS["hdn_file_move"])
  {
  $location = "file_options.php?obj_id=".$HTTP_POST_VARS['hdn_file_id'];
  
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "SET ";
  $query .= "obj_owner='".$HTTP_POST_VARS['rad_dest_folder_id']."' ";
  $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."'";
  $xoopsDB->query($query);
   
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
  
  print "<form method='post' name='frm_select_dest' action='file_move.php'>\r";
  display_dms_header(2);
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Move File:</b></td></tr>\r";
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
    
  print "  <td colspan='2' align='left'><input type=button name='btn_submit' value='Move' onclick='check_for_dest();'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"file_options.php?obj_id=".$file_id."\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_file_move' value='confim'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$file_id."'>\r";
  print "<input type='hidden' name='hdn_destination_folder_id' value=''>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>



