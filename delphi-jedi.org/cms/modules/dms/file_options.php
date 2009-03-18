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

// file_options.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';
include 'defines.php';

import_request_variables("P","post_");
$this_file = "file_options.php";

if ($HTTP_POST_VARS["hdn_obj_id"]) $obj_id = $HTTP_POST_VARS['hdn_obj_id'];
else $obj_id = $HTTP_GET_VARS['obj_id'];


if ($HTTP_POST_VARS["hdn_update_options"])
  {
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." SET ";
  $query .= "obj_name='".$HTTP_POST_VARS['hdn_obj_name']."' ";
  $query .= "WHERE obj_id='".$obj_id."'";
  $xoopsDB->query($query);
     
  $query  = "UPDATE ".$xoopsDB->prefix('dms_object_properties')." SET ";
  $query .= "obj_descript='".$HTTP_POST_VARS['hdn_obj_descript']."', ";
  $query .= "obj_keywords='".$HTTP_POST_VARS['hdn_obj_keywords']."', ";
  $query .= "obj_authors='".$HTTP_POST_VARS['hdn_obj_authors']."', ";
  $query .= "obj_mms_nums='".$HTTP_POST_VARS['hdn_obj_mms_nums']."' ";
  $query .= "WHERE obj_id='".$obj_id."'";
  $xoopsDB->query($query);
   
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "location='file_options.php?obj_id=".$HTTP_POST_VARS['hdn_obj_id']."';";
  print "</SCRIPT>";  
  }
else
  {  
  include XOOPS_ROOT_PATH.'/header.php';
  
  // Add the version_view() javascript function
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "function version_view()\r";
  print "  {\r";
  print "  if (frm_ver_view.slct_version_view.value == 0) return;\r";
  print "  var url = 'version_view.php?ver_id=';\r";
  print "  url = url + frm_ver_view.slct_version_view.value;\r";
  print "  window.open(url);\r";
  print "  }\r";
  print "</SCRIPT>\r";
  
  // Get object information
  $query  = "SELECT obj_name,obj_status,obj_checked_out_user_id,lifecycle_id, lifecycle_stage, lifecycle_suspend_flag ";
  $query .= "FROM ".$xoopsDB->prefix("dms_objects")." ";
  $query .= "WHERE obj_id='".$obj_id."'";  
  $object = mysql_fetch_object(mysql_query($query));

  $query  = "SELECT obj_descript,obj_keywords,obj_authors,obj_mms_nums FROM ".$xoopsDB->prefix("dms_object_properties")." ";
  $query .= "WHERE obj_id='".$obj_id."'";  
  $properties = mysql_fetch_object(mysql_query($query));
  
  /*
  $query  = "SELECT user_id FROM ".$xoopsDB->prefix("dms_object_perms")." ";
  $query .= "WHERE ptr_obj_id='".$obj_id."' and user_perms='".OWNER."'";
  $permissions = mysql_fetch_object(mysql_query($query));
  */
    
  if ($object->obj_status == CHECKEDOUT) $checked_out = TRUE;
  else $checked_out = FALSE;
  
  print "<table width='100%' border='0'>\r";
  display_dms_header(2);
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  
  print "  <tr>\r";
  print "    <td>\r";
  print "      <table border='0'>\r";
  
  print "        <tr><td colspan='2' align='left'><b>Options:</b></td></tr>\r";
  print "        <tr><td colspan='2'><BR></td></tr>\r";

  // Display the properties
  print "        <form method='post' name='frm_options' action='file_options.php'>\r";

  print "        <tr><td colspan='2' align='left'><u>Properties:</u></td></tr>\r";
  print "        <tr><td colspan='2'><BR></td></tr>\r";
  print "        <tr>\r";
  print "          <td width='20%' align='left'>&nbsp;&nbsp;&nbsp;Name:</td>";
  print "          <td><input type='text' name=hdn_obj_name value='".$object->obj_name."'></td>\r";
  print "        </tr>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;Description:</td>";
  print "          <td><input type='text' name=hdn_obj_descript value='".$properties->obj_descript."'></td>\r";
  print "        </tr>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;Keywords:</td>";
  print "          <td><input type='text' name=hdn_obj_keywords value='".$properties->obj_keywords."'></td>\r";
  print "        </tr>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;Authors:</td>";
  print "          <td><input type='text' name=hdn_obj_authors value='".$properties->obj_authors."'></td>\r";
  print "        </tr>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;MMS Numbers:";
  print "          <td><input type='text' name=hdn_obj_mms_nums value='".$properties->obj_mms_nums."'></td>\r";
  print "        </tr>\r";
  print "        <tr><td colspan='2'><BR></td></tr>\r";
  print "        <tr>\r";
  print "          <td colspan='2' align='left'>\r";
  print "            &nbsp;&nbsp;&nbsp;<input type=submit name='btn_submit' value='Update Properties'>";
  print "          </td>\r";
  print "        </tr>\r";
  print "        <tr><td colspan='2'><BR></td></tr>\r";
  
  print "        <input type='hidden' name='hdn_update_options' value='confim'>\r";
  print "        <input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
  print "        <input type='hidden' name='hdn_cancel_checkout' value='false'>\r";
  print "        </form>\r";
  
    // Display the "view version or rendition" select box
  print "        <tr><td colspan='2' align='left'><u>View a version of this document:</u></td></tr>\r";
  print "        <tr><td colspan='2'><BR></td></tr>\r";
  print "        <form name='frm_ver_view'>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;\r";
  print "            <select name='slct_version_view' onchange='version_view();'>\r";
  print "              <option value='0'>None</option>\r";
  
  $query  = "SELECT row_id,major_version,minor_version,sub_minor_version FROM ".$xoopsDB->prefix('dms_object_versions')." ";
  $query .= "WHERE obj_id='".$obj_id."'";
  $result = $xoopsDB->query($query);
  
  while($result_data = mysql_fetch_array($result))
    {
	print "              <option value='".$result_data['row_id']."'>";
	print $result_data['major_version'].".".$result_data['minor_version'].$result_data['sub_minor_version'];
	print "</option>\r";
	}
    
  print "            </select>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "        </form>\r";
  
  print "        <tr><td colspan='2'><BR></td></tr>\r";

  
  print "      </table>\r";
  
  if ($checked_out == TRUE)
    {
    print "      <table>\r";
	print "        <tr><td colspan='2' align='left'><u>Checked-Out By:</u></td></tr>\r";

	$query = "SELECT uid,uname from ".$xoopsDB->prefix("users")." WHERE uid='".$object->obj_checked_out_user_id."'";
	$result = mysql_fetch_object(mysql_query($query));
	
	print "        <tr><td><BR></td></tr>\r";
	print "        <tr><td>&nbsp;&nbsp;&nbsp;".$result->uname."</td></tr>\r";
	print "        <tr><td><BR></td></tr>\r";
	print "      </table>\r";
    }
  
  print "    </td>\r";
	
  print "    <td valign='top'>\r";
  print "      <table valign='top' border='0'>\r";
  
  if (($checked_out==TRUE) && ( ($xoopsUser->isAdmin()) || ($object->obj_checked_out_user_id == $xoopsUser->getVar('uid')) ) )
    {
	print "      <tr><td align='left'><input type=button name='btn_cancel_checkout' value='Cancel Checkout' onclick='location=\"file_checkout_cancel.php?file_id=".$HTTP_GET_VARS["obj_id"]."\";'></td></tr>\r";
    print "      <tr><td><BR><BR></td></tr>\r";
	}
  
  if (($checked_out==FALSE) && ($object->lifecycle_id == 0))
  {
    print "      <tr><td align='left'><input type=button name='btn_copy' Value='Copy' onclick='location=\"file_copy.php?file_id=".$obj_id."\";'></td></tr>\r";
    print "      <tr><td align='left'><input type=button name='btn_move' value='Move' onclick='location=\"file_move.php?file_id=".$obj_id."\";'></td></tr>\r";
    print "      <tr><td><BR></td></tr>\r";
  }
  
  if (( $checked_out==FALSE) || ($xoopsUser->isAdmin()) )
    {
	print "      <tr><td align='left'><input type=button name='btn_delete' value='Delete' onclick='location=\"file_delete.php?file_id=".$HTTP_GET_VARS["obj_id"]."\";'></td></tr>\r";
    print "      <tr><td><BR></td></tr>\r";
    }

  if (( $checked_out==FALSE) && ($object->lifecycle_id == 0) )
    {
	print "      <tr><td align='left'><input type=button name='btn_lifecycle_apply' value='Lifecycle' onclick='location=\"lifecycle_apply.php?file_id=".$HTTP_GET_VARS["obj_id"]."\";'></td></tr>\r";
    print "      <tr><td><BR></td></tr>\r";
	}

  if (( $checked_out==FALSE) && ($object->lifecycle_id >0) )
    {
	print "      <tr><td align='left'><input type=button name='btn_lifecycle_promote' value='Promote' onclick='location=\"lifecycle_promote.php?file_id=".$HTTP_GET_VARS["obj_id"]."\";'></td></tr>\r";
	print "      <tr><td align='left'><input type=button name='btn_lifecycle_demote' value='Demote' onclick='location=\"lifecycle_demote.php?file_id=".$HTTP_GET_VARS["obj_id"]."\";'></td></tr>\r";
    print "      <tr><td><BR></td></tr>\r";
	}
		
  print "      <tr><td align='left'><input type=button name='btn_exit' value='Exit' onclick='location=\"index.php\";'></td></tr>\r";
  print "      </table>\r";
  print "    </td>\r";
  
  print "  </tr>\r";
   
  print "  <tr>\r";
  print "    <td>\r";

  include 'inc_perms_set.php';
 
  print "    </td>\r";
  print "  </tr>\r";
  print "</table>\r";
/* 
  foreach ($GLOBALS as $key=>$value)
    {
	print "\$GLOBALS[\"$key\"]==$value<br>";
	}
*/  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
