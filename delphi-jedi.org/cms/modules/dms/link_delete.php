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

// link_delete.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

if ($HTTP_POST_VARS["hdn_delete_link_confirm"])
  {
  // Delete the link
  $query = "DELETE from ".$xoopsDB->prefix('dms_objects')." WHERE obj_id='".$HTTP_POST_VARS["hdn_link_id"]."'";
  //$mysql_query($query);
  $xoopsDB->query($query);
  
  
  // Check to see if this is the last item in the inbox
  $query =  "SELECT obj_id FROM ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "WHERE obj_owner='".$HTTP_POST_VARS['hdn_obj_owner']."'";
  $result = mysql_query($query);
  
  // If this object was the last item in the inbox, set the inbox status to empty
  if (mysql_num_rows($result) == 0)
    {
    $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." SET obj_type='2' ";
	$query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_obj_owner']."'";
    $result = mysql_query($query);
	}
 
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("location='index.php';");
  print("</SCRIPT>");  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
    
  // Get actual object ID
  $query  = "SELECT ptr_obj_id,obj_owner from ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["link_id"]."'";  
  $result = mysql_fetch_object(mysql_query($query));
  $obj_id = $result->ptr_obj_id;  
    
  // Get actual file information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$obj_id."'";  
  $first_result = mysql_fetch_object(mysql_query($query));

  $query  = "SELECT obj_descript from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$obj_id."'";  
  $second_result = mysql_fetch_object(mysql_query($query));
  
  print "<form method='post' action='link_delete.php'>\r";
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Delete Link:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>Link Name:&nbsp;&nbsp;&nbsp;";
  print "        ".$first_result->obj_name."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2' align='left'>Link Description:&nbsp;&nbsp;&nbsp;";
  print "        ".$second_result->obj_descript."</td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Delete'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"link_options.php?obj_id=".$HTTP_GET_VARS["link_id"]."\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_delete_link_confirm' value='confim'>\r";
  print "<input type='hidden' name='hdn_link_id' value='".$HTTP_GET_VARS["link_id"]."'>\r";
  print "<input type='hidden' name='hdn_obj_owner' value='".$result->obj_owner."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
