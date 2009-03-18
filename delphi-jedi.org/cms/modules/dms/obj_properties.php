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

// obj_properties.php

include '../../mainfile.php';

if ($HTTP_POST_VARS["hdn_update_obj"])
  {
  //$query = sprintf("UPDATE %s SET obj_status='2' WHERE obj_id='%s'",$xoopsDB->prefix("dms_objects"),$HTTP_POST_VARS["hdn_obj_id"]);
  //$xoopsDB->query($query);
  
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." SET ";
  $query .= "obj_name='".$HTTP_POST_VARS['hdn_obj_name']."' ";
  $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_obj_id']."'";
  $xoopsDB->query($query);
     
  $query  = "UPDATE ".$xoopsDB->prefix('dms_object_properties')." SET ";
  $query .= "obj_descript='".$HTTP_POST_VARS['hdn_obj_descript']."', ";
  $query .= "obj_keywords='".$HTTP_POST_VARS['hdn_obj_keywords']."', ";
  $query .= "obj_authors='".$HTTP_POST_VARS['hdn_obj_authors']."', ";
  $query .= "obj_mms_nums='".$HTTP_POST_VARS['hdn_obj_mms_nums']."' ";
  $query .= "WHERE obj_id='".$HTTP_POST_VARS['hdn_obj_id']."'";
  $xoopsDB->query($query);
   
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "location='obj_properties.php?obj_id=".$HTTP_POST_VARS['hdn_obj_id']."';";
  print "</SCRIPT>";  
  }
else
  {  
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Get object information
  $query  = "SELECT obj_name from ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["obj_id"]."'";  
  $first_result = mysql_fetch_object(mysql_query($query));

  $query  = "SELECT obj_descript,obj_keywords,obj_authors,obj_mms_nums from ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$HTTP_GET_VARS["obj_id"]."'";  
  $second_result = mysql_fetch_object(mysql_query($query));
  
  print "<form method='post' action='obj_properties.php'>\r";
  print "<table width='100%'>\r";
  print "  <tr><td colspan='2' class='cHeader'><center><b>Not Used?</b></center></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2'>Properties:</td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>Name:&nbsp;&nbsp;&nbsp;";
  print "        <input type='text' name=hdn_obj_name value='".$first_result->obj_name."'></td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>Description:&nbsp;&nbsp;&nbsp;";
  print "        <input type='text' name=hdn_obj_descript value='".$second_result->obj_descript."'></td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>Keywords:&nbsp;&nbsp;&nbsp;";
  print "        <input type='text' name=hdn_obj_keywords value='".$second_result->obj_keywords."'></td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>Authors:&nbsp;&nbsp;&nbsp;";
  print "        <input type='text' name=hdn_obj_authors value='".$second_result->obj_authors."'></td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>MMS Numbers:&nbsp;&nbsp;&nbsp;";
  print "        <input type='text' name=hdn_obj_mms_nums value='".$second_result->obj_mms_nums."'></td>\r";
  print "  </tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <td colspan='2'><input type=submit name='btn_submit' value='Update'>";
  print "                  <input type=button name='btn_cancel' value='Exit' onclick='location=\"index.php\";'></td>\r";
  print "</table>\r";
  print "<input type='hidden' name='hdn_update_obj' value='confim'>\r";
  print "<input type='hidden' name='hdn_obj_id' value='".$HTTP_GET_VARS["obj_id"]."'>\r";
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
