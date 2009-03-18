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

// inc_perms_set.php

include_once 'defines.php';

/*
//The following lines must be at the beginning of any file using inc_permissions.php:

import_request_variables("P","post_");
$this_file = "";  // Add the filename of this file here.

if ($HTTP_POST_VARS["hdn_obj_id"]) $obj_id = $HTTP_POST_VARS['hdn_obj_id'];
else $obj_id = $HTTP_GET_VARS['obj_id'];

*/


print "<SCRIPT LANGUAGE='Javascript'>\r";

print "  function add_group_ro()\r";
print "    {\r";
print "    var index, item, new_flag, value;\r";
print "    new_flag = \"TRUE\";\r";
print "    item  = frm_perms.slct_group_none.options[frm_perms.slct_group_none.selectedIndex].text;\r";
print "    value = frm_perms.slct_group_none.options[frm_perms.slct_group_none.selectedIndex].value;\r";
print "    for ( index = 0; index < frm_perms.elements['slct_group_ro[]'].length; index++)\r";
print "      {\r";
print "      if (item == frm_perms.elements['slct_group_ro[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     frm_perms.elements['slct_group_ro[]'].options[frm_perms.elements['slct_group_ro[]'].length]\r";
print "      = new Option (item,value);\r";
print "    }\r";

print "  function remove_group_ro()\r";
print "    {\r";
print "    if (frm_perms.elements['slct_group_ro[]'].selectedIndex >= 0)\r";
print "     frm_perms.elements['slct_group_ro[]'].options[frm_perms.elements['slct_group_ro[]'].selectedIndex] = null;\r";
print "    }\r";

print "  function add_group_e()\r";
print "    {\r";
print "    var index, item, new_flag, value;\r";
print "    new_flag = \"TRUE\";\r";
print "    item  = frm_perms.slct_group_none.options[frm_perms.slct_group_none.selectedIndex].text;\r";
print "    value = frm_perms.slct_group_none.options[frm_perms.slct_group_none.selectedIndex].value;\r";
print "    for ( index = 0; index < frm_perms.elements['slct_group_e[]'].length; index++)\r";
print "      {\r";
print "      if (item == frm_perms.elements['slct_group_e[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     frm_perms.elements['slct_group_e[]'].options[frm_perms.elements['slct_group_e[]'].length]\r";
print "      = new Option (item,value);\r";
print "    }\r";

print "  function remove_group_e()\r";
print "    {\r";
print "    if (frm_perms.elements['slct_group_e[]'].selectedIndex >= 0)\r";
print "     frm_perms.elements['slct_group_e[]'].options[frm_perms.elements['slct_group_e[]'].selectedIndex] = null;\r";
print "    }\r";

print "  function add_user_ro()\r";
print "    {\r";
print "    var index, item, new_flag, value;\r";
print "    new_flag = \"TRUE\";\r";
print "    item  = frm_perms.slct_user_none.options[frm_perms.slct_user_none.selectedIndex].text;\r";
print "    value = frm_perms.slct_user_none.options[frm_perms.slct_user_none.selectedIndex].value;\r";
print "    for ( index = 0; index < frm_perms.elements['slct_user_ro[]'].length; index++)\r";
print "      {\r";
print "      if (item == frm_perms.elements['slct_user_ro[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     frm_perms.elements['slct_user_ro[]'].options[frm_perms.elements['slct_user_ro[]'].length]\r";
print "      = new Option (item,value);\r";
print "    }\r";

print "  function remove_user_ro()\r";
print "    {\r";
print "    if (frm_perms.elements['slct_user_ro[]'].selectedIndex >= 0)\r";
print "     frm_perms.elements['slct_user_ro[]'].options[frm_perms.elements['slct_user_ro[]'].selectedIndex] = null;\r";
print "    }\r";

print "  function add_user_e()\r";
print "    {\r";
print "    var index, item, new_flag, value;\r";
print "    new_flag = \"TRUE\";\r";
print "    item  = frm_perms.slct_user_none.options[frm_perms.slct_user_none.selectedIndex].text;\r";
print "    value = frm_perms.slct_user_none.options[frm_perms.slct_user_none.selectedIndex].value;\r";
print "    for ( index = 0; index < frm_perms.elements['slct_user_e[]'].length; index++)\r";
print "      {\r";
print "      if (item == frm_perms.elements['slct_user_e[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     frm_perms.elements['slct_user_e[]'].options[frm_perms.elements['slct_user_e[]'].length]\r";
print "      = new Option (item,value);\r";
print "    }\r";

print "  function remove_user_e()\r";
print "    {\r";
print "    if (frm_perms.elements['slct_user_e[]'].selectedIndex >= 0)\r";
print "     frm_perms.elements['slct_user_e[]'].options[frm_perms.elements['slct_user_e[]'].selectedIndex] = null;\r";
print "    }\r";

print "  function change_user_group()\r";
print "    {\r";
print "    frm_perms.hdn_change_users_group.value='TRUE';\r";
print "    update_perms();\r";
print "    }\r";

print "  function update_perms()\r";
print "    {\r";
print "    var index;\r";

print "    for ( index = 0; index < frm_perms.elements['slct_group_ro[]'].length; index++)\r";
print "      {\r";
print "      frm_perms.elements['slct_group_ro[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    for ( index = 0; index < frm_perms.elements['slct_group_e[]'].length; index++)\r";
print "      {\r";
print "      frm_perms.elements['slct_group_e[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    for ( index = 0; index < frm_perms.elements['slct_user_ro[]'].length; index++)\r";
print "      {\r";
print "      frm_perms.elements['slct_user_ro[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    for ( index = 0; index < frm_perms.elements['slct_user_e[]'].length; index++)\r";
print "      {\r";
print "      frm_perms.elements['slct_user_e[]'].options[index].selected = 'TRUE';\r";
print "      }\r";

print "    frm_perms.hdn_update_perms.value = 'TRUE';\r";
print "    frm_perms.submit();\r";
print "    }\r";

print "</SCRIPT>\r";  

function change_owner_perms($current_owner_id)
  {
  global $xoopsDB;
  
  $query = "SELECT uid,uname from ".$xoopsDB->prefix("users")." ORDER BY uname";
  $result = $xoopsDB->query($query);
  
  print "<select name='hdn_owner_id'>\r";
  while($result_data = mysql_fetch_array($result))
    {
	print "<option value='".$result_data['uid']."' ";
	if ($current_owner_id == $result_data['uid']) print "selected";
	print ">".$result_data['uname']."</option>";
	}
  print "</select>\r";
  }

if ($HTTP_POST_VARS["hdn_obj_id"]) $obj_id = $HTTP_POST_VARS['hdn_obj_id'];
else $obj_id = $HTTP_GET_VARS['obj_id'];

if ($HTTP_POST_VARS["hdn_update_perms"] == "TRUE")
  {
  // Delete all permissions for this object
  $query  = "DELETE FROM ".$xoopsDB->prefix("dms_object_perms")." ";
  $query .= "WHERE ptr_obj_id='".$obj_id."'";
  mysql_query($query);
  
  // Add owner permission, if applicable
  if ($post_hdn_owner_id > 0)
    {
    $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
    $query .= "(ptr_obj_id,user_id,user_perms) VALUES ('";
    $query .= $obj_id."','";
	$query .= $post_hdn_owner_id."','";
	$query .= OWNER."')";
	mysql_query($query);
	}
	
  // Add everyone permissions
  $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
  $query .= "(ptr_obj_id,everyone_perms) VALUES ('";
  $query .= $obj_id."','";
  $query .= $post_slct_everyone."')";
  mysql_query($query);
  
  // Add groups permissions
  $index = 0;
  while ( strlen($post_slct_group_ro[$index]) > 0)
    {
    $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
    $query .= "(ptr_obj_id,group_id,group_perms) VALUES ('";
    $query .= $obj_id."','";
    $query .= $post_slct_group_ro[$index]."','";
	$query .= READONLY."')";
	mysql_query($query);
	
	$index++;
	}
  $index = 0;
  while ( strlen($post_slct_group_e[$index]) > 0)
    {
    $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
    $query .= "(ptr_obj_id,group_id,group_perms) VALUES ('";
    $query .= $obj_id."','";
    $query .= $post_slct_group_e[$index]."','";
	$query .= EDIT."')";
	mysql_query($query);
	
	$index++;
	}
	  
  // Add users permissions
  $index = 0;
  while ( strlen($post_slct_user_ro[$index]) > 0)
    {
    $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
    $query .= "(ptr_obj_id,user_id,user_perms) VALUES ('";
    $query .= $obj_id."','";
    $query .= $post_slct_user_ro[$index]."','";
	$query .= READONLY."')";
	mysql_query($query);
	
	$index++;
	}
 
  $index = 0;
  while ( strlen($post_slct_user_e[$index]) > 0)
    {
    $query  = "INSERT INTO ".$xoopsDB->prefix("dms_object_perms")." ";
    $query .= "(ptr_obj_id,user_id,user_perms) VALUES ('";
    $query .= $obj_id."','";
    $query .= $post_slct_user_e[$index]."','";
	$query .= EDIT."')";
	mysql_query($query);
	
	$index++;
	}
  }

if ($HTTP_POST_VARS["hdn_change_users_group"] == "TRUE")
  {
  $selected_group = $HTTP_POST_VARS['slct_user_groups'];
  }
else $selected_group = 1;

  // Get object permissions
  $query  = "SELECT user_id, group_id, user_perms, group_perms, everyone_perms FROM ".$xoopsDB->prefix("dms_object_perms")." ";
  $query .= "WHERE ptr_obj_id='".$obj_id."'";
  $perms = mysql_query($query);
  
  $perms_row_count = mysql_num_rows($perms);
  
  $perms_user_id        = array();
  $perms_group_id       = array();
  $perms_user_perms     = array();
  $perms_group_perms    = array();
  $perms_everyone_perms = array();

  $perms_owner = '0';
  
  $slct_everyone_perms = array(" selected","","","");
  
  $group_array_index = 0;
  $user_array_index = 0;
    
  while($perms_data = mysql_fetch_array($perms))
    {
	// Determine Owner (there is, at most, one entry)
	if ($perms_data['user_perms'] == OWNER) $perms_owner = $perms_data['user_id'];
	else
	  {
	// Determine User Permissions
	  if ($perms_data['user_id'] > NONE)
	    {
		$perms_user_id[$user_array_index] = $perms_data['user_id'];
		$perms_user_perms[$user_array_index] = $perms_data['user_perms'];
		$user_array_index++;
	    }
	  }
	  
	// Determine Group Permissions
	if ($perms_data['group_id'] > NONE)
	  {
	  $perms_group_id[$group_array_index] = $perms_data['group_id'];
	  $perms_group_perms[$group_array_index] = $perms_data['group_perms'];
	  $group_array_index++;
	  }
	  
	// Determine Everyone permissions (there is, at most, one entry)
	if ($perms_data['everyone_perms'] > NONE)
	  {
	  $slct_everyone_perms[$perms_data['everyone_perms']] = " selected";
	  $slct_everyone_perms[0] = "";
	  }
	}

  print "      <table width='100%' border='0'>\r";
  
  print "      <form name='frm_perms' action='".$this_file."' method='post'>\r";
  
  print "        <tr><td colspan='5' align='left'><u>Permissions:</u></td></tr>\r";
  print "        <tr><td colspan='5' align='left'><BR></td></tr>\r";
  print "        <tr><td colspan='5' align='left'>&nbsp;&nbsp;&nbsp;Owner:</td></tr>\r";
  print "        <tr>\r";
  print "          <td align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r";
  
  if ($perms_owner > '0') 
    {
	if ($xoopsUser->isAdmin())
	  {
	  change_owner_perms($perms_owner);
	  }
	else
	  {
	  print $xoopsUser->getUnameFromId($perms_owner);
	  print "<input type='hidden' name='hdn_owner_id' value='".$perms_owner."'>\r";
	  }
	}
  else 
    {
	if ($xoopsUser->isAdmin())
	  {
	  change_owner_perms(1);
	  }  
	else
	  {
	  print "None";
      print "<input type='hidden' name='hdn_owner_id' value='0'>\r";
	  }
	}
	
  print "          </td>\r";
  print "        </tr>\r";    
  print "        </td></tr>\r";
  
  print "        <tr><td colspan='5' align='left'><BR></td></tr>\r";
  
  print "        <tr>\r";
  print "          <td colspan='5' align='left'>&nbsp;&nbsp;&nbsp;Everyone:</td>";
  print "        </tr>\r";
  print "        <tr>\r";
  print "          <td colspan='5' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  print "            <select name='slct_everyone'>\r";
  print "              <option value=0 ".$slct_everyone_perms[0].">None</option>\r";
  print "              <option value=1 ".$slct_everyone_perms[1].">Browse</option>\r";
  print "              <option value=2 ".$slct_everyone_perms[2].">Read Only</option>\r";
  print "              <option value=3 ".$slct_everyone_perms[3].">Edit</option>\r";
  print "            </select>\r";
  print "          </td>\r";
  print "        </tr>\r";

  print "        <tr><td colspan='5' align='left'><BR></td></tr>\r";
   
  print "        <tr><td colspan='5' align='left' valign='top'>&nbsp;&nbsp;&nbsp;Groups:</td></tr>\r";
  print "        <tr><td colspan='1' align='left' valign='top'>\r";
  print "              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  print "              <select name='slct_group_none' size='10'>\r";
  
  // Code taken from Xoops /xoops/modules/system/admin/groups/groups.php and modified.
  $member_handler =& xoops_gethandler('member');
  $groups =& $member_handler->getGroups();
  $count = count($groups);
  for ($i = 0; $i < $count; $i++) 
    {
    $id = $groups[$i]->getVar('groupid');
	//if ($id == $selected_group) $selected_text = "selected";
	//else $selected_text = "";
	print "                <option value=".$id." ".$selected_text.">".$groups[$i]->getVar('name')."</option>\r";
    }
  
  print "              </select>\r";
  print "            </td>\r";
  print "            <td colspan='1' width='2%'><BR></td>\r";
  print "            <td colspan='1' align='left' valign='top'>\r";
  print "              Read Only:<BR>";
  print "              <select name='slct_group_ro[]' size='10' multiple>\r";
  
  $group_array_index = 0;
  while ($perms_group_id[$group_array_index])
    {
	if ($perms_group_perms[$group_array_index]== READONLY)
	  {
	  $member_handler = & xoops_gethandler('member');
	  $group = & $member_handler->getGroup($perms_group_id[$group_array_index]);
	  $group_name = $group->getVar('name');
	  
	  print "                <option value='".$perms_group_id[$group_array_index]."'>".$group_name."</option>\r";
	  }
	  
	$group_array_index++;
	}
  
  print "              </select><BR>\r";
  print "              <input type='button' value='&nbsp;Add&nbsp;' onclick='add_group_ro();'><BR>";
  print "              <input type='button' value='Remove' onclick='remove_group_ro();'>";
  print "            </td>\r";
  print "            <td colspan='1' width='2%'><BR></td>\r";
  print "            <td colspan='1' align='left' valign='top'>\r";
  print "              Edit:<BR>";
  print "              <select name='slct_group_e[]' size='10' multiple>\r";
     
  $group_array_index = 0;
  while ($perms_group_id[$group_array_index])
    {
	if ($perms_group_perms[$group_array_index]== EDIT)
	  { 
	  $member_handler = & xoops_gethandler('member');
	  $group = & $member_handler->getGroup($perms_group_id[$group_array_index]);
	  $group_name = $group->getVar('name');
	 
	  print "                <option value='".$perms_group_id[$group_array_index]."'>".$group_name."</option>\r";
	  }
	
	$group_array_index++;
	}
  
  print "              </select>&nbsp;&nbsp;<BR>\r";
  print "              <input type='button' value='&nbsp;Add&nbsp;' onclick='add_group_e();'><BR>";
  print "              <input type='button' value='Remove' onclick='remove_group_e();'>";
  print "            </td>\r";
  print "        </tr>";
  
  print "        <tr><td colspan='5'><BR></td></tr>\r";

  //$selected_group = 1;  
  // Code taken from Xoops /xoops/modules/system/admin/groups/groups.php and modified.
  $member_handler =& xoops_gethandler('member');
  $members =& $member_handler->getUsersByGroup($selected_group, true);
  $mlist= array();
  $mcount = count($members);
  for ($i = 0; $i < $mcount; $i++) 
    {
    $mlist[$members[$i]->getVar('uid')] = $members[$i]->getVar('uname');
    }
    
  print "        <tr><td colspan='5' align='left' valign='top'>&nbsp;&nbsp;&nbsp;Users:</td></tr>\r";
  print "        <tr><td colspan='1' valign='top' align='left'>\r";
  print "              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  print "              <select name='slct_user_none' size='10'>\r";

  foreach ($mlist as $u_id => $u_name)
    {
	print "        <option value='".$u_id."'>".$u_name."</option>\r";
	}  
	
  print "              </select>\r";
  print "              <BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  print "              <select name='slct_user_groups' onchange='change_user_group();'>\r";

  // Code taken from Xoops /xoops/modules/system/admin/groups/groups.php and modified.
  $member_handler =& xoops_gethandler('member');
  $groups =& $member_handler->getGroups();
  $count = count($groups);
  for ($i = 0; $i < $count; $i++) 
    {
    $id = $groups[$i]->getVar('groupid');
    
	if ($id == $selected_group) $selected_text = "selected";
	else $selected_text = "";
	
	print "                <option value=".$id." ".$selected_text.">".$groups[$i]->getVar('name')."</option>\r";
    }
  
  print "              </select>\r";
  
  print "            </td>\r";
  print "            <td colspan='1' width='2%'><BR></td>\r";
  print "            <td colspan='1' align='left' valign='top'>\r";
  print "              Read Only:<BR>";
  print "              <select name='slct_user_ro[]' size='10' multiple>\r";

  $user_array_index = 0;
  while ($perms_user_id[$user_array_index])
    {
	if ($perms_user_perms[$user_array_index]== READONLY)
	 print "                <option value='".$perms_user_id[$user_array_index]."'>".$xoopsUser->getUnameFromId($perms_user_id[$user_array_index])."</option>\r";
	$user_array_index++;
	}
  
  print "              </select><BR>\r";
  print "              <input type='button' value='&nbsp;Add&nbsp;' onclick='add_user_ro();'><BR>";
  print "              <input type='button' value='Remove' onclick='remove_user_ro();'>\r";
  print "            </td>\r";
  print "            <td colspan='1' width='2%'><BR></td>\r";
  print "            <td colspan='1' align='left' valign='top'>\r";
  print "              Edit:<BR>";
  print "              <select name='slct_user_e[]' size='10' multiple>\r";
  
  $user_array_index = 0;
  while ($perms_user_id[$user_array_index])
    {
	if ($perms_user_perms[$user_array_index]== EDIT)
	 print "                <option value='".$perms_user_id[$user_array_index]."'>".$xoopsUser->getUnameFromId($perms_user_id[$user_array_index])."</option>\r";
	$user_array_index++;
	}
  
  print "              </select>&nbsp;&nbsp;<BR>\r";
  print "              <input type='button' value='&nbsp;Add&nbsp;' onclick='add_user_e();'><BR>";
  print "              <input type='button' value='Remove' onclick='remove_user_e();'>";
  print "            </td>\r";
  print "        </tr>\r";
  
  print "        <tr><td colspan=5><BR></td></tr>\r";
  print "        <tr><td colspan=5 align='left'>&nbsp;&nbsp;&nbsp;<input type='button' name='btn_update_perms' value='Update Permissions' onclick='update_perms();'></td></tr>\r";
  
  print "        <input type='hidden' name='hdn_update_perms' value='FALSE'>\r";
  print "        <input type='hidden' name='hdn_change_users_group' value='FALSE'>\r";
  print "        <input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
  print "        </form>\r";
    
  print "      </table>\r";

?>
