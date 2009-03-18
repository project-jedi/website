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

// file_route.php

include '../../mainfile.php';

include_once 'defines.php';
include_once 'inc_dms_functions.php';

import_request_variables("P","post_");


print "<SCRIPT LANGUAGE='Javascript'>\r";

print "  function route_file()\r";
print "    {\r";
print "    var index;\r";
print "    for ( index = 0; index < frm_routing_select.elements['slct_routing_list[]'].length; index++)\r";
print "      {\r";
print "      frm_routing_select.elements['slct_routing_list[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    frm_routing_select.hdn_route_file_confirm.value = 'TRUE';\r";
print "    frm_routing_select.submit();\r";
print "    }\r";

print "  function select_group()\r";
print "    {\r";
print "    var index;\r";
print "    frm_routing_select.hdn_selected_group.value = frm_slct_group.slct_group.value;\r";
print "    for ( index = 0; index < frm_routing_select.elements['slct_routing_list[]'].length; index++)\r";
print "      {\r";
print "      frm_routing_select.elements['slct_routing_list[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    frm_routing_select.submit();\r";
print "    }\r";

print "  function add_user()\r";
print "    {\r";
print "    var index, item, new_flag;\r";
print "    new_flag = \"TRUE\";\r";
print "    item = frm_routing_select.slct_user_list.options[frm_routing_select.slct_user_list.selectedIndex].text;\r";
print "    for ( index = 0; index < frm_routing_select.elements['slct_routing_list[]'].length; index++)\r";
print "      {\r";
print "      if (item == frm_routing_select.elements['slct_routing_list[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     frm_routing_select.elements['slct_routing_list[]'].options[frm_routing_select.elements['slct_routing_list[]'].length]\r";
print "      = new Option (item);\r";
print "    }\r";

print "  function remove_user()\r";
print "    {\r";
print "    if (frm_routing_select.elements['slct_routing_list[]'].selectedIndex >= 0)\r";
print "     frm_routing_select.elements['slct_routing_list[]'].options[frm_routing_select.elements['slct_routing_list[]'].selectedIndex] = null;\r";
print "    }\r";

print "</SCRIPT>\r";  

if ($HTTP_POST_VARS["hdn_file_id"]) $file_id = $HTTP_POST_VARS['hdn_file_id'];
else $file_id = $HTTP_GET_VARS['file_id'];

if ($HTTP_POST_VARS['hdn_selected_group']) $selected_group = $HTTP_POST_VARS['hdn_selected_group'];
else $selected_group = 1;

if ($HTTP_POST_VARS["hdn_route_file_confirm"]=="TRUE")
  {
  
  $index = 0;
  while ( strlen($post_slct_routing_list[$index]) > 0)
    {
    // Get the dest_user_id by using the user name in $post_slct_routing_list[]
    $query  = "SELECT uid FROM ".$xoopsDB->prefix('users')." ";
    $query .= "WHERE uname='".$post_slct_routing_list[$index]."'";
    
	//print $query;
	
	$dest_user_id = mysql_fetch_object(mysql_query($query));
    
    // Get Destination Inbox obj_id (this will be the object_owner of the new object)
    $query  = "SELECT obj_id "; //, obj_type, obj_status, ";
//	$query .= "user_id, user_perms ";
	$query .= "FROM ".$xoopsDB->prefix("dms_object_perms")." ";
	$query .= "INNER JOIN ".$xoopsDB->prefix("dms_objects")." ON ";
	$query .= $xoopsDB->prefix("dms_object_perms").".ptr_obj_id = obj_id ";
	$query .= "WHERE (obj_type='".INBOXEMPTY."' OR obj_type='".INBOXFULL."') ";
	$query .= "AND (user_id='".$dest_user_id->uid."') ";
	$query .= "AND (user_perms='".OWNER."')";
    
	//print $query;
	$result = mysql_query($query);
	$result_rows = mysql_num_rows($result);
	
	if($result_rows > 0) $dest_inbox = mysql_fetch_object(mysql_query($query));
	
	//print $query;
	//exit(0);
	
    // Only route the document if an inbox actually exists.
    if ($dest_inbox->obj_id)  
      {
      // Create an object in the destination inbox that links to the source object
      $query  = "INSERT INTO ".$xoopsDB->prefix('dms_objects')." (obj_type,ptr_obj_id,obj_owner)";
      $query .= " VALUES ('";
      $query .= DOCLINK."','";
      $query .= $file_id."','";
      $query .= $dest_inbox->obj_id."')";
	  mysql_query($query) or die(mysql_error());

	  $link_obj_id = mysql_insert_id();
	  
	  // Create a permissions entry (this is unused and is only used to satisfy the main SQL query in index.php)
	  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_object_perms')." (ptr_obj_id, user_id, user_perms)";
	  $query .= " VALUES ('";
	  $query .= $link_obj_id."','";
	  $query .= $dest_user_id->uid."','";
	  $query .= OWNER."')";
	  
	  ///print $query;
	  //;exit(0);
	  mysql_query($query) or die(mysql_error());
	    
      // Set the destination inbox status to full
      $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." SET obj_type='".INBOXFULL."' ";
      $query .= "WHERE obj_id='".$dest_inbox->obj_id."'";  
      mysql_query($query);
      }    

    $index++;
    }
  
  
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "  location='index.php';\r";
  print "</SCRIPT>\r";  
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
  
  print "<table width='100%'>\r";
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  display_dms_header();
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2'><b>Route Document:</b></td></tr>\r";
  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>File Name:&nbsp;&nbsp;&nbsp;";
  print "        ".$first_result->obj_name."</td>\r";
  print "  </tr>\r";
  print "  <tr>\r";
  print "    <td colspan='2'>File Description:&nbsp;&nbsp;&nbsp;";
  print "        ".$second_result->obj_descript."</td>\r";
  print "  </tr>\r";
  
  print "  <tr><td colspan='2'><BR></td></tr>\r";

  print "  <tr><td colspan='2'><table>\r";
    
  print "  <tr>";
  print "    <td colspan='4'>";
  print "<form name='frm_slct_group'>\r";
  print "      <select name='slct_group' onchange='select_group();'>\r";
  
  // Code taken from Xoops /xoops/modules/system/admin/groups/groups.php and modified.
  $member_handler =& xoops_gethandler('member');
  $groups =& $member_handler->getGroups();
  $count = count($groups);
  for ($i = 0; $i < $count; $i++) 
    {
    $id = $groups[$i]->getVar('groupid');
    
	if ($id == $selected_group) $selected_text = "selected";
	else $selected_text = "";
	
	print "        <option value=".$id." ".$selected_text.">".$groups[$i]->getVar('name')."</option>\r";
	}
  
  print "      </select>\r";
  print "</form>\r";
  print "    </td>\r";
  print "  </tr>\r";

  // Code taken from Xoops /xoops/modules/system/admin/groups/groups.php and modified.
  $member_handler =& xoops_gethandler('member');
  $members =& $member_handler->getUsersByGroup($selected_group, true);
  $mlist= array();
  $mcount = count($members);
  for ($i = 0; $i < $mcount; $i++) 
    {
    $mlist[$members[$i]->getVar('uid')] = $members[$i]->getVar('uname');
    }
  
  print "<form name='frm_routing_select' action=file_route.php method=post>\r";   
  print "  <tr>\r";
  print "    <td>\r";
  print "      <select name='slct_user_list' size='10'>\r";
  
  foreach ($mlist as $u_id => $u_name)
    {
	print "        <option value='".$u_id."'>".$u_name."</option>\r";
	}  
  
  print "      </select>\r";
  print "    </td>\r";
  
  print "    <td align='center' valign='middle' width='25%'>\r";
  print "      <input type='button' name='btn_add_user' value='Add&nbsp;&gt;&gt;' onclick='add_user();'> <BR><BR><BR>\r";
  print "      <input type='button' name='btn_remove_user' value='&lt;&lt;&nbsp;Remove' onclick='remove_user();'>\r";
  print "    </td>\r";
    
  print "    <td>\r";
  print "      <select name='slct_routing_list[]' size='10' multiple>\r";
  
  $index=0;
  while ( strlen($post_slct_routing_list[$index]) > 0)
    {
	print "<option>".$post_slct_routing_list[$index]."</option>";
	$index++;
	}

  print "      </select>\r";
  print "    </td>\r";
  print "    <td width='100%'></td>\r";
  print "  </tr>\r";
  
  print "  </table></td></tr>\r";

  print "  <tr><td colspan='2'><BR></td></tr>\r";
  print "  <tr><td colspan='2'><input type=button name='btn_route' value='Route' onclick='route_file();'>";
  print "                      <input type=button name='btn_cancel' value='Cancel' onclick='location=\"index.php\";'></td></tr>\r";
  
  print "</table>\r";
  
  print "  <input type='hidden' name='hdn_route_file_confirm' value=''>\r";
  print "  <input type='hidden' name='hdn_file_id' value='".$file_id."'>\r";
  print "  <input type='hidden' name='hdn_selected_group' value=''>\r";
  print "  <input type='hidden' name='hdn_stored_user_names' value=''>\r";
  
  print "</form>\r";

  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
