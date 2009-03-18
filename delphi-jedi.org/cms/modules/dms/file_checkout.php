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

// file_checkout.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

if ($HTTP_POST_VARS["hdn_checkout_file_confirm"])
  {
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "SET ";
  $query .= "obj_status='1', ";
  $query .= "obj_checked_out_user_id='".$xoopsUser->getVar('uid')."' ";
  $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_file_id']."'";
  $xoopsDB->query($query);
   
  print("<SCRIPT LANGUAGE='Javascript'>\r");
  print("window.open('file_view.php?file_id=".$HTTP_POST_VARS['hdn_file_id']."');");
  print("location='index.php';");
  print("</SCRIPT>");  
  
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Get file information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["file_id"]."'";  
  $first_result = mysql_query($query);

  $query  = "SELECT obj_descript from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["file_id"]."'";  
  $second_result = mysql_query($query);
  
  print "<form method='post' action='file_checkout.php'>\r";
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2' align='left'><b>Edit File:</b></td></tr>\r";
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
  print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='Checkout'>";
  print "                               <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_checkout_file_confirm' value='confim'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$HTTP_GET_VARS["file_id"]."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
