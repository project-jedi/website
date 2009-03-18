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

if($HTTP_POST_VARS["hdn_function"]) $function = $HTTP_POST_VARS["hdn_function"];
else $function = $HTTP_GET_VARS["function"];

if ($function=="NEW")
  {
  // Create a new lifecycle.
  $query  = "INSERT INTO ".$xoopsDB->prefix('dms_lifecycles')." (lifecycle_name,lifecycle_descript)";
  $query .= " VALUES ('__New','__New')";

  mysql_query($query);
      
  }

if ($function=="DELETE")
  {
  // Delete the lifecycle
  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycles')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);

  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);

  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycle_apply_perms')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);
  
  $query  = "DELETE FROM ".$xoopsDB->prefix('dms_lifecycles_doc_perms')." WHERE ";
  $query .= "lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);
  
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." SET ";
  $query .= "lifecycle_id='0', ";
  $query .= "lifecycle_stage='0', ";
  $query .= "lifecycle_suspend_flag='0' ";
  $query .= "WHERE lifecycle_id='".$HTTP_POST_VARS["hdn_lifecycle_id"]."'";
  mysql_query($query);
  }
  

  include XOOPS_ROOT_PATH.'/header.php';

  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "  function new_lifecycle()\r";
  print "    {\r";
  print "    frm_lifecycle_mgr.hdn_function.value='NEW';\r";
  print "    frm_lifecycle_mgr.submit();\r";
  print "    }\r";
  print "  function delete_lifecycle(lifecycle_id)\r";
  print "    {\r";
  print "    frm_lifecycle_mgr.hdn_function.value='DELETE';\r";
  print "    frm_lifecycle_mgr.hdn_lifecycle_id.value=lifecycle_id;\r";
  print "    frm_lifecycle_mgr.submit();\r";
  print "    }\r";
  print "</SCRIPT>\r";  
  
  print "<form method='post' name='frm_lifecycle_mgr' action='lifecycle_manager.php'>\r";
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
  print "                  <input type='button' name='btn_new' value='New' onclick='new_lifecycle();'>\r";
  print "                </td>\r";
  
  print "                <td align='center' class='cContentSection'>\r";
  print "                  <input type='button' name='btn_exit' value='Exit' onclick='location=\"index.php\";'>\r";
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
  print "            <center><b><font size='2'>Lifecycle Management</font></b></center>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
  
  print "      <BR>\r";

  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Lifecycles</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
    
  $query = "SELECT lifecycle_id, lifecycle_name, lifecycle_descript FROM ".$xoopsDB->prefix('dms_lifecycles');
  $result = $xoopsDB->query($query);
  
  print "      <table width='100%' border='1' class='cContentSection'>\r";
 
  print "        <tr>\r";
  print "          <td class='cContentSection'>\r";
  print "            <b>Name</b>\r";
  print "          </td>\r";

  print "          <td width='20%' class='cContentSection'>\r";
  print "            <b>Options</b>\r";
  print "          </td>\r";
    
  print "          <td class='cContentSection'>\r";
  print "            <b>Description</b>\r";
  print "          </td>\r";
  
  print "        </tr>\r";
   
  while($result_data = mysql_fetch_array($result))
    {
    print "        <tr>\r";
    print "          <td>\r";
    print "            ".$result_data['lifecycle_name'];
    print "          </td>\r";

    print "          <td>\r";
    print "            <a href='lifecycle_editor.php?lifecycle_id=".$result_data['lifecycle_id']."'>Edit</a>";
	print "            &nbsp;&nbsp;&nbsp;";
	print "            <a href='javascript:delete_lifecycle(".$result_data['lifecycle_id'].");'>Delete</a>\r"; 
    print "          </td>\r";
    
    print "          <td>\r";
    print "            ".$result_data['lifecycle_descript'];
    print "          </td>\r";
  
    print "        </tr>\r";
	}
  
  print "      </table>\r";
  print "    </td>\r";
  
  print "  </tr>\r";
  print "</table>\r";
  
  print "<input type='hidden' name='hdn_function' value=''>\r";
  print "<input type='hidden' name='hdn_lifecycle_id' value=''>\r";
    
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';

  
?>
