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
// lifecycle_apply.php

include '../../mainfile.php';
include_once 'inc_dms_functions.php';

$function="";
$lifecycle_id = "";
$lifecycle_stage_0_flag = "FALSE";

if($HTTP_POST_VARS["hdn_function"]) 
  {
  $function = $HTTP_POST_VARS["hdn_function"];
  $file_id = $HTTP_POST_VARS["hdn_file_id"];
  $lifecycle_id = $HTTP_POST_VARS["rad_lifecycle_id"];
  }
else 
  {
  $file_id = $HTTP_GET_VARS["file_id"];
  }
 
if($HTTP_POST_VARS["hdn_function"] == "APPLY")
  {
  // Get the destination information for the first stage of the lifecycle
  $query  = "SELECT lifecycle_id, lifecycle_stage, new_obj_location ";
  $query .= "FROM ".$xoopsDB->prefix('dms_lifecycle_stages')." ";
  $query .= "WHERE lifecycle_id='".$lifecycle_id."' ";
  $query .= "ORDER BY lifecycle_stage";
  $result = mysql_fetch_object(mysql_query($query));
    
  // Move the file to the folder for the first stage and add the lifecycle id and stage.
  $query  = "UPDATE ".$xoopsDB->prefix('dms_objects')." ";
  $query .= "SET ";
  $query .= "obj_owner='".$result->new_obj_location."', ";
  $query .= "lifecycle_id='".$result->lifecycle_id."', ";
  $query .= "lifecycle_stage='".$result->lifecycle_stage."' ";
  $query .= "WHERE obj_id='".$file_id."'";
  $xoopsDB->query($query);
  
  // Return to the main screen
  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "  location=\"index.php\";\r";
  print "</SCRIPT>\r";  
  
  exit(0);
  }

  include XOOPS_ROOT_PATH.'/header.php';

  print "<SCRIPT LANGUAGE='Javascript'>\r";
  print "  function apply_lifecycle()\r";
  print "    {\r";
  print "    frm_lifecycle_apply.submit();\r";
  print "    }\r";
  print "</SCRIPT>\r";  
  
  print "<form method='post' name='frm_lifecycle_apply' action='lifecycle_apply.php'>\r";
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
  print "                  <input type='button' name='btn_apply' value='Apply' onclick='apply_lifecycle();'>\r";
  print "                </td>\r";
  
  print "                <td align='center' class='cContentSection'>\r";
  print "                  <input type='button' name='btn_exit' value='Exit' onclick='location=\"file_options.php?obj_id=".$file_id."\";'>\r";
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
  print "            <center><b><font size='2'>Apply Lifecycle</font></b></center>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
  
  print "      <BR>\r";

  print "      <table>\r";
  print "        <tr>\r";
  print "          <td colspan='1' class='cSubHeader'>\r";
  print "            <b>Select Lifecycle</b>\r";
  print "          </td>\r";
  print "        </tr>\r";
  print "      </table>\r";
    
  $query = "SELECT lifecycle_id, lifecycle_name, lifecycle_descript FROM ".$xoopsDB->prefix('dms_lifecycles');
  $result = $xoopsDB->query($query);
  
  print "      <table width='100%' border='1' class='cContentSection'>\r";
 
  print "        <tr>\r";
  
  print "          <td width='10%' class='cContentSection'>\r";
  print "            <b>Selection</b>\r";
  print "          </td>\r";
  
  print "          <td class='cContentSection'>\r";
  print "            <b>Name</b>\r";
  print "          </td>\r";
    
  print "          <td class='cContentSection'>\r";
  print "            <b>Description</b>\r";
  print "          </td>\r";
  
  print "        </tr>\r";
   
  while($result_data = mysql_fetch_array($result))
    {
    print "        <tr>\r";
    
	print "          <td>\r";
	print "            <input type='radio' name='rad_lifecycle_id' value='".$result_data['lifecycle_id']."'>\r";
	print "          </td>\r";	
	
	print "          <td>\r";
    print "            ".$result_data['lifecycle_name'];
    print "          </td>\r";
    
    print "          <td>\r";
    print "            ".$result_data['lifecycle_name'];
    print "          </td>\r";
  
    print "        </tr>\r";
	}
  
  print "      </table>\r";
  print "    </td>\r";
  
  print "  </tr>\r";
  print "</table>\r";
  
  print "<input type='hidden' name='hdn_function' value='APPLY'>\r";
  print "<input type='hidden' name='hdn_file_id' value='".$file_id."'>\r";
    
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';

  
?>
