<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                        Copyright 7/22/2003                                //
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

// Main Menu
// lifecycle_manager.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

$function="";
$lifecycle_id = "";
$lifecycle_stage_0_flag = "FALSE";

if($HTTP_POST_VARS["hdn_function"]) 
  {
  $function = $HTTP_POST_VARS["hdn_function"];
  $lifecycle_id = $HTTP_POST_VARS["hdn_lifecycle_id"];
  }
else 
  {
  $function = $HTTP_GET_VARS["function"];
  $lifecycle_id = $HTTP_GET_VARS["lifecycle_id"];
  }
 
if($lifecycle_id == "")
  {
  print "Invalid Lifecycle ID...Operation Terminated";
  exit(0);
  }  
   
if ($function=="NEW")
  {
  // Create a new lifecycle.
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_lifecycle_stages')." (lifecycle_id)";
  $query .= " VALUES ('".$lifecycle_id."')";

  mysql_query($query);
  }

if ($function=="DELETE")
  {
  // Delete the lifecycle stage
  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."' AND ";
  $query .= "lifecycle_stage='".$HTTP_POST_VARS["hdn_lifecycle_stage"]."'";
  mysql_query($query);

  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycles_doc_perms')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."' AND ";
  $query .= "lifecycle_stage='".$HTTP_POST_VARS["hdn_lifecycle_stage"]."'";
  mysql_query($query);
  }
  
if ($function=="UPDATE")
  {
  // Update the lifecycle properties
  $query  = "UPDATE ".$xoopsDB->prefix('dms_lifecycles')." SET ";
  $query .= "lifecycle_name='".$HTTP_POST_VARS["txt_lifecycle_name"]."', ";
  $query .= "lifecycle_descript='".$HTTP_POST_VARS["txt_lifecycle_descript"]."' ";
  $query .= "WHERE lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);
  }
  
  include XOOPS_ROOT_PATH.'/header.php';

  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "  function new_lifecycle_stage()\r";
  print "    {\r";
  print "    if (frm_lifecycle_editor.hdn_lifecycle_stage_0_flag.value=='FALSE')\r";
  print "      {\r";
  print "      frm_lifecycle_editor.hdn_function.value='NEW';\r";
  print "      frm_lifecycle_editor.submit();\r";
  print "      }\r";
  print "    else alert(\"A new lifecycle stage already exists.  Please edit this stage before creating any additional stages.\");\r";
  print "    }\r";
  print "  function delete_lifecycle_stage(lifecycle_stage)\r";
  print "    {\r";
  print "    frm_lifecycle_editor.hdn_function.value='DELETE';\r";
  print "    frm_lifecycle_editor.hdn_lifecycle_stage.value=lifecycle_stage;\r";
  print "    frm_lifecycle_editor.submit();\r";
  print "    }\r";
  print "  function update_lifecycle_properties()\r";
  print "    {\r";
  print "    frm_lifecycle_editor.hdn_function.value='UPDATE';\r";
  print "    frm_lifecycle_editor.submit();\r";
  print "    }\r";
  print "</SCRIPT>\r";  
  
  print "<form method='post' name='frm_lifecycle_editor' action='lifecycle_editor.php'>\r";
  print "<table width='100%'>\r";
  
//  display_dms_header();
  
  print "  <tr>\r";
  
  // Gadget Column  
  print "    <td valign='top' width='260'>\r";
  print "      <table width='100%' cellspacing='0' cellpadding='0'>\r";
  print "        <tr>\r";
  print "          <td align='center' class='cNarrowHeader'>\r";
  print "            <table width='100%'>\r";
  print "              <tr>\r";
  print "                <td align='center' class='cNarrowHeader'>\r";
  print "                  Options\r";
  print "                </td>\r";
  print "              </tr>\r";
  print "            </table>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "        <tr>\r";
  print "          <td align='center' class='cNarrowContentSection'>\r";
  print "            <table width='100%' cellspacing='4' cellpadding='0'>\r";
  print "              <tr>\r";
  print "                <td align='center' class='cContentSection'>\r";
  print "                  <input type='button' name='btn_new' value='New' onclick='new_lifecycle_stage();'>\r";
  print "                </td>\r";
  
  print "                <td align='center' class='cContentSection'>\r";
  print "                  <input type='button' name='btn_exit' value='Exit' onclick='location=\"lifecycle_manager.php\";'>\r";
  print "                </td>\r";
  
  print "              </tr>\r";
  print "            </table>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "      </table>\r";
  print "    </td>\r";
  
  // Content
  print "    <td valign='top'>\r";
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cHeader'>\r";
  print "            <center><b><font size='2'>Lifecycle Editor</font></b></center>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
  
  print "      <BR>\r";

  $query  = "SELECT lifecycle_name, lifecycle_descript FROM ".$xoopsDB->prefix('dms_lifecycles')." ";
  $query .= "WHERE lifecycle_id='".$lifecycle_id."'";
  $result = mysql_fetch_array(mysql_query($query));
  
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Lifecycle Properties</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";

  print "      <table>\r";
  print "        <tr>\r";
  print "          <td>\r";
  print "            Name:&nbsp;&nbsp;&nbsp;\r";
  print "          </td>\r";
  
  print "          <td width='100%'>\r";
  print "            <input type='text' name='txt_lifecycle_name' value='".$result["lifecycle_name"]."' class='cContentSection' size='20' maxlength='250'>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "        <tr>\r";
  print "          <td>\r";
  print "            Description:&nbsp;&nbsp;&nbsp;\r";
  print "          </td>\r";
  
  print "          <td>\r";
  print "            <input type='text' name='txt_lifecycle_descript' value='".$result["lifecycle_descript"]."' class='cContentSection' size='50' maxlength='250'>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "        <tr>\r";
  print "          <td colspan='2'>\r";
  print "            <input type='button' name='btn_update' value='Update' class='cContentSection' onclick='update_lifecycle_properties()();'>\r";
  print "          </td>\r";
  print "        </tr>\r";
  
  print "      </table>\r";
  
  print "      <BR>\r";
  
  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Lifecycle Stages</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";

    
  $query  = "SELECT lifecycle_stage ";
  $query .= "FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." ";
  $query .= "WHERE lifecycle_id='".$lifecycle_id."' ";
  $query .= "ORDER BY lifecycle_stage";
  $result = $xoopsDB->query($query);
  
  print "      <table width='100%' border='1' class='cContentSection'>\r";
 
  print "        <tr>\r";
  print "          <td class='cContentSection'>\r";
  print "            <b>Stage</b>\r";
  print "          </td>\r";

  print "          <td width='20%' class='cContentSection'>\r";
  print "            <b>Options</b>\r";
  print "          </td>\r";
  
  print "        </tr>\r";
   
  while($result_data = mysql_fetch_array($result))
    {
    if ($result_data['lifecycle_stage']==0) $lifecycle_stage_0_flag = "TRUE";
	
	print "        <tr>\r";
    print "          <td>\r";
    
	if ($result_data['lifecycle_stage'] == 0) 
	 print "            NEW";
	else
	 print "            ".$result_data['lifecycle_stage'];
    
	print "          </td>\r";

    print "          <td>\r";
    print "            <a href='lifecycle_stage_editor.php?lifecycle_id=".$lifecycle_id."&lifecycle_stage=".$result_data['lifecycle_stage']."'>Edit</a>";
	print "            &nbsp;&nbsp;&nbsp;";
	print "            <a href='javascript:delete_lifecycle_stage(".$result_data['lifecycle_stage'].");'>Delete</a>\r"; 
    print "          </td>\r";
  
    print "        </tr>\r";
	}
  
  print "      </table>\r";
  print "    </td>\r";
  
  print "  </tr>\r";
  print "</table>\r";
  
  print "<input type='hidden' name='hdn_function' value=''>\r";
  print "<input type='hidden' name='hdn_lifecycle_id' value='".$lifecycle_id."'>\r";
  print "<input type='hidden' name='hdn_lifecycle_stage' value=''>\r";
  print "<input type='hidden' name='hdn_lifecycle_stage_0_flag' value='".$lifecycle_stage_0_flag."'>\r";
    
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';

  
?>
