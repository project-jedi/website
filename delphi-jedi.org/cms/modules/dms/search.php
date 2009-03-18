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

// search.php

include '../../mainfile.php';
include 'defines.php';
include 'inc_dms_functions.php';

function where_clause($operator, $field, $string)
{
  if ($operator == IS)          $where_clause = "(".$field."='".$string."')";
  
  if ($operator == CONTAINS)    $where_clause = "(".$field." LIKE '%".$string."%')";
  
  if ($operator == STARTSWITH)  $where_clause = "(".$field." LIKE '%".$string."')";
  
  if ($operator == ISANYOF){};
  
  if ($operator == ISALLOF){};
  
  return($where_clause);  
}

if ($HTTP_POST_VARS["hdn_search"])
  {
  $where_clause = "";
  $and_flag = FALSE;
  
  if (strlen($HTTP_POST_VARS["txt_srch_doc_name"])>2)
    {
	$where_clause .= "(";
	$where_clause .= where_clause($HTTP_POST_VARS["slct_srch_doc_name"],$xoopsDB->prefix("dms_objects").".obj_name",$HTTP_POST_VARS["txt_srch_doc_name"]);
	$where_clause .= ")";
	$and_flag = TRUE;
	}  
  
  if (strlen($HTTP_POST_VARS["txt_srch_doc_owner"])>2)
    {
	if ($and_flag == TRUE) $where_clause .= " AND ";
	$where_clause .= "(";

	$where_clause .= ")";
	$and_flag = TRUE;
	}  
  
  if (strlen($HTTP_POST_VARS["txt_srch_descript"])>2)
    {
	if ($and_flag == TRUE) $where_clause .= " AND ";
	$where_clause .= "(";
	
	$where_clause .= ")";
	$and_flag = TRUE;
	}  
  
  if (strlen($HTTP_POST_VARS["txt_srch_keywords"])>2)
    {
	if ($and_flag == TRUE) $where_clause .= " AND ";
	$where_clause .= "(";
	
	$where_clause .= ")";
	$and_flag = TRUE;
	}  
  
  if (strlen($HTTP_POST_VARS["txt_srch_mms_nums"])>2)
    {
	if ($and_flag == TRUE) $where_clause .= " AND ";
	$where_clause .= "(";

	$where_clause .= ")";
	$and_flag = TRUE;
	}  

	
	
print $where_clause;
exit(0);
	
  }
else
  {
  include XOOPS_ROOT_PATH.'/header.php';

  print "<script language='javascript'>\r";
  print "  function exit()\r";
  print "    {\r";
  print "    location=\"index.php\";";
  print "    }\r";

  print "  function search()\r";
  print "    {\r";
  print "    frm_search.submit();";
  print "    }\r";
  print "</script>\r";

      
  print "<form name='frm_search' method='post' action='search.php'>\r";
  print "<table width='100%'>\r";
  
  display_dms_header(4);
  //print "  <tr><td colspan='2' class='cHeader'><center><b><font size='2'>Title Goes Here</font></b></center></td></tr>\r";
  print "  <tr><td colspan='3' align='left'><BR></td></tr>\r";
  print "  <tr><td colspan='3' align='left'><b>Search:</b></td></tr>\r";
  print "  <tr><td colspan='3' align='left'><BR></td></tr>\r";
  
  print "  <tr>\r";
  print "    <td align='left' nowrap>\r";
  print "      Document Name&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <select name='slct_srch_doc_name'>\r";
  print "        <option value='1'>is</option>\r";
  print "        <option value='2'>contains</option>\r";
  print "        <option value='3'>starts with</option>\r";
  print "      </select>&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <input type='text' name='txt_srch_doc_name' size='40' maxlength='100'>\r";
  print "    </td>\r";
  print "    <td width='100%'><BR></td>\r";
  print "  </tr>\r";
 
  print "  <tr>\r";
  print "    <td align='left' nowrap>\r";
  print "      Document Owner&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <select name='slct_srch_doc_owner'>\r";
  print "        <option value='1'>is</option>\r";
  print "        <option value='4'>is any of</option>\r";
  //print "        <option value='5'>is all of</option>\r";  
  print "        <option value='2'>contains</option>\r";
  print "        <option value='3'>starts with</option>\r";
  print "      </select>&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <input type='text' name='txt_srch_doc_owner' size='40' maxlength='100'>\r";
  print "    </td>\r";
  print "    <td width='100%'><BR></td>\r";
  print "  </tr>\r";
     
  print "  <tr>\r";
  print "    <td align='left' nowrap>\r";
  print "      Description&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <select name='slct_srch_descript'>\r";
  print "        <option value='1'>is</option>\r";
  print "        <option value='2'>contains</option>\r";
  print "        <option value='3'>starts with</option>\r";
  print "      </select>&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <input type='text' name='txt_srch_descript' size='40' maxlength='100'>\r";
  print "    </td>\r";
  print "    <td width='100%'><BR></td>\r";
  print "  </tr>\r";
  
  print "  <tr>\r";
  print "    <td align='left' nowrap>\r";
  print "      Keyword(s)&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <select name='slct_srch_keywords'>\r";
  print "        <option value='4'>is any of</option>\r";
  //print "        <option value='5'>is all of</option>\r";  
  print "        <option value='2'>contains</option>\r";
  print "        <option value='3'>starts with</option>\r";
  print "      </select>&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <input type='text' name='txt_srch_keywords' size='40' maxlength='100'>\r";
  print "    </td>\r";
  print "    <td width='100%'><BR></td>\r";
  print "  </tr>\r";

  print "  <tr>\r";
  print "    <td align='left' nowrap>\r";
  print "      MMS Number(s)&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <select name='slct_srch_mms_nums'>\r";
  print "        <option value='1'>is</option>\r";
  print "        <option value='4'>is any of</option>\r";
  //print "        <option value='5'>is all of</option>\r";  
  print "        <option value='2'>contains</option>\r";
  print "        <option value='3'>starts with</option>\r";
  print "      </select>&nbsp;\r";
  print "    </td>\r";
  print "    <td align='left' nowrap>\r";
  print "      <input type='text' name='txt_srch_mms_nums' size='40' maxlength='100'>\r";
  print "    </td>\r";
  print "    <td width='100%'><BR></td>\r";
  print "  </tr>\r";

  print "  <tr><td colspan='3'><BR></td></tr>\r";
  
  print "  <tr>\r";
  print "    <td align='left' colspan='3'>\r";
  print "      <input type=button name=btn_search value='Search' onclick='search();'>\r";
  print "      &nbsp;\r";
  print "      <input type=button name=btn_exit value='Exit' onclick='exit();'>\r";
  print "    </td>\r";
  print "  </tr>\r";
  
  print "</table>\r";
  
  print "<input type='hidden' name='hdn_search' value='true'>";
  
  print "</form>\r";
  
  include_once XOOPS_ROOT_PATH.'/footer.php';
  }
?>
