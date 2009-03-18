<?php
// ------------------------------------------------------------------------- //
//						 C-JAY Content							             //
//				         Version:  V2				  	  					 //
//						  Module for										 //
//				XOOPS - PHP Content Management System				 		 //
//					<http://www.xoops.org/>						  			 //
// ------------------------------------------------------------------------- //
// Author: Christoph forlon Brecht          								 //
// Purpose: Module to wrap html or php-content into nice Xoops design.	     //
// email: master@c-jay.net										  			 //
// Site: http://c-jay.net													 //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify	 //
//  it under the terms of the GNU General Public License as published by	 //
//  the Free Software Foundation; either version 2 of the License, or 	     //
//  (at your option) any later version. 							         //
//															                 //
//  This program is distributed in the hope that it will be useful,		     //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of		     //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the		     //
//  GNU General Public License for more details.						     //
// ------------------------------------------------------------------------- //


include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}

/*********************************************************/
/*                           cjaycontet - Admin                                  */
/*********************************************************/
$form_title = $_POST["form_title"];
$form_adress = $_POST["form_adress"];
$form_comment = $_POST["form_comment"];
$form_hits = $_POST["form_hits"];
$form_id = $_POST["form_id"];
$cont = $_POST["cont"]; 
$form_hide = $_POST["form_hide"];
$submitter = $_POST["submitter"];
$op = $_POST["op"];
$mainm = $_POST["mainm"];
$neww = $_POST["neww"];
$i = $_POST["i"];
$c = $_POST["c"];
$id = $_POST["id"];
$m = $_POST["m"];
$add = $_POST["add"];

if(ISSET($HTTP_POST_VARS['delsubmit'])) 
{ 
$op1 = "delconfirm";
$iddel = $_POST["iddel"][key($_POST["delsubmit"])];
} 
if (ISSET($HTTP_POST_VARS['edsubmit'])) 
{
$op1 = "edconfirm";
$idedit = $_POST["idedit"][key($_POST["edsubmit"])]; 
} 


xoops_cp_header();
$myts =& MyTextSanitizer::getInstance();
switch($op) {
		

        case "editdb":
		$form_title = $myts->makeTboxData4Save($form_title);
		$form_comment = $myts->makeTboxData4Save($form_comment);
		$form_address = $myts->makeTboxData4Save($form_address);
                if ($form_hide){
                        $form_hide = 1;
                }
                else{
                        $form_hide = 0;
                }

                $q = "UPDATE ".$xoopsDB->prefix()."_cjaycontent SET title='".$form_title."', adress='".$form_adress."', comment='".$form_comment."', hide='".$form_hide."', hits='".$form_hits."' WHERE id='".$form_id."'";

                if ($xoopsDB->query($q)){
                        fc_admin_message(_CC_EDIT_DONE,0,"");
                }
                else{
                        fc_admin_message(_CC_EDIT_DBERROR,1,"");
                }

                fc_admin_list();
                fc_admin_add();
				mainmenu_admin();
				fc_footer();
                break;

        case "del":

                if($xoopsDB->query("DELETE FROM ".$xoopsDB->prefix()."_cjaycontent WHERE id=".$id."")){
                        fc_admin_message(_CC_DEL_OK,0,"");
                }
                else{
                        fc_admin_message(_CC_EDIT_DBERROR,1,"");
                }
                fc_admin_list();
                fc_admin_add();
				mainmenu_admin();
				fc_footer();
                break;
   
        case "add":
				if(!isset($HTTP_POST_VARS['submitter'])) {
                $submitter = $xoopsUser->uid();
    				}else{
					$submitter = intval($HTTP_POST_VARS['submitter']);}
				$mcontent = implode ('',file("./../content/$form_adress"));
				preg_match('=<body[^>]*>(.*)</body\s*>=smi',$mcontent,$cont);
				$form_title = $myts->makeTboxData4Save($form_title);
                $form_comment = $myts->makeTboxData4Save($form_comment);
		        $form_address = $myts->makeTboxData4Save($form_address);
				$cont = $myts->makeTboxData4Save($cont[1]);
				$content = addslashes("$cont");
				$date = time();
                if ($form_hide){
                        $form_hide = 1;
                }
                else{
                        $form_hide = 0;
                }
                $q = "INSERT INTO ".$xoopsDB->prefix()."_cjaycontent (title, adress, comment,content, hide, date, submitter) VALUES ('".$form_title."', '".$form_adress."', '".$form_comment."', '".$content."', ".$form_hide.", ".$date." , ".$submitter.")";
                if ($xoopsDB->query($q)){
                        fc_admin_message(_CC_EDIT_DONE,0,"");
						
                }
                else{
                        fc_admin_message(_CC_EDIT_DBERROR,1, "");
                }

                fc_admin_list();
                fc_admin_add();
				mainmenu_admin();
				fc_footer();
                break;
				
		case "mainmenu":
		       		if ($mainm=="yes") {
						$xoopsDB->query("UPDATE ".$xoopsDB->prefix()."_modules SET weight='99' WHERE dirname='cjaycontent'");
					for ($i=1;$i<$c;$i++){
						$xoopsDB->query("UPDATE ".$xoopsDB->prefix()."_cjaycontent SET weight='".$neww[$i]."' WHERE id='".$id[$i]."'");		
						}
					$result = $xoopsDB->query("SELECT id,title,weight FROM ".$xoopsDB->prefix()."_cjaycontent WHERE NOT (weight=0) AND NOT (title LIKE '..%') ORDER BY weight");
					$oldfile = "./../xoops_version.php";
					$old = fopen($oldfile, "r");
					$new = fopen($oldfile.".new", "w");
					while($line = fgets($old, 4096)) {
					if (preg_match("['sub']",$line) or preg_match("[\?>]",$line))
    				continue;  
  					fputs($new, $line);
					fclose($old);
					unlink($oldfile);
					fclose($new);
					rename($oldfile.".new", $oldfile);
					$y=1;
					//Write xoops_version.php					
					$fp=fopen("./../xoops_version.php","a+");
					
					fwrite($fp,"\$modversion['name'] = _CC_MOD_NAME;\n");
					fwrite($fp,"\$modversion['version'] = \"2\";\n");
					fwrite($fp,"\$modversion['description'] = _CC_MOD_DESC;\n");
					fwrite($fp,"\$modversion['credits'] = \"C-Jay Content by forlon - check http://c-jay.net for updates\";\n");
					fwrite($fp,"\$modversion['author'] = \"Christoph forlon Brecht\";\n");
					fwrite($fp,"\$modversion['help'] = \"help.html\";\n");
					fwrite($fp,"\$modversion['license'] = \"GPL see LICENSE\";\n");
					fwrite($fp,"\$modversion['official'] = 2;\n");
					fwrite($fp,"\$modversion['image'] = \"cc_slogo.gif\";\n");
					fwrite($fp,"\$modversion['dirname'] = \"cjaycontent\";\n");

					// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
					// All tables should not have any prefix!
					fwrite($fp,"\$modversion['sqlfile']['mysql'] = \"sql/mysql.sql\";\n");
					
					
					// Tables created by sql file (without prefix!)
					fwrite($fp,"\$modversion['tables'][0] = \"cjaycontent\";\n");
					
					// Blocks
					fwrite($fp,"\$modversion['blocks'][1]['file'] = \"cjaycontent.php\";\n");
					fwrite($fp,"\$modversion['blocks'][1]['name'] = _CC_MOD_NAME;\n");
					fwrite($fp,"\$modversion['blocks'][1]['description'] = \"C-jaycontent - Link Display\";\n");
					fwrite($fp,"\$modversion['blocks'][1]['show_func'] = \"b_cjaycontent_show\";\n");
					//fwrite($fp,"\$modversion['blocks'][1]['edit_func'] = "b_freecontent_edit"\";\n");
					//fwrite($fp,"\$modversion['blocks'][1]['options'] = "all|new"\";\n");
					
					// Search
					fwrite($fp,"\$modversion['hasSearch'] = 1;\n");
					fwrite($fp,"\$modversion['search']['file']=\"include/search.inc.php\";\n");
					fwrite($fp,"\$modversion['search']['func']=\"cjaycontent_search\";\n");
					
					// Admin things
					fwrite($fp,"\$modversion['hasAdmin'] = 1;\n");
					fwrite($fp,"\$modversion['adminindex'] = \"admin/index.php\";\n");
					fwrite($fp,"\$modversion['adminmenu'] = \"admin/menu.php\";\n");
					
					// Menu
					fwrite($fp,"\$modversion['hasMain'] = 1;\n");
					while($mm_item = $xoopsDB->fetchArray($result)){
								fwrite($fp,"\$modversion['sub'][$y]['name']=\"".$myts->makeTboxData4Show($mm_item['title'])."\";\n");
								fwrite($fp,"\$modversion['sub'][$y]['url']=\"index.php?id=".$myts->makeTboxData4Show($mm_item['id'])."\";\n");
								$y++;
							}
					fwrite($fp,"?>");	
					fclose($fp);		
						}
						}
					else {
					$xoopsDB->query("UPDATE ".$xoopsDB->prefix()."_modules SET weight='0' WHERE dirname='cjaycontent'");
					}
											 
				fc_admin_list();
                fc_admin_add();
				mainmenu_admin();
				fc_footer();
                break;
					
       case "":
                fc_admin_list();
                fc_admin_add();
				mainmenu_admin();
				fc_footer();
                break;
	                }
switch($op1){							
  		case "delconfirm":
                OpenTable();
                $result = $xoopsDB->queryF("SELECT id, title, comment FROM ".$xoopsDB->prefix()."_cjaycontent WHERE id='".$iddel."'",1);
                $fc_item = $xoopsDB->fetcharray($result);
                echo "<center><h4>"._CC_DEL_REALLY."</h4><br>".$fc_item['id']." <b>|</b> ".$myts->makeTboxData4Show($fc_item['title'])." <b>|</b> ".$myts->makeTboxData4Show($fc_item['comment'])."<br><form action='index.php' method='post'><input type='hidden' name='id' value='".$iddel."' /><input type='hidden' name='op' value='del' /><input type='submit' value='"._CC_DELETE."' />&nbsp;<input type='button' value='"._CANCEL."' onclick='javascript:history.go(-1);' /></form></center>";
                CloseTable();
                fc_footer();
                break;
		case "edconfirm":
        	global $xoopsConfig, $xoopsDB;
			$myts =& MyTextSanitizer::getInstance();
        	$result = $xoopsDB->query("SELECT title, adress, comment, hide, hits FROM ".$xoopsDB->prefix()."_cjaycontent WHERE id='".$idedit."'");
       	 	$fc_item = $xoopsDB->fetcharray($result);

	        if ( $fc_item['hide'] == 0){
                $hide_checked = "";
    	    }
        	else{
                $hide_checked = "checked";
        	}
        	OpenTable();
        	echo "<form name=\"Edit Content\" action=\"./index.php\" method=\"post\"><div align=\"center\"><h4>"._CC_EDIT_HEADER."</h4></div><table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"95%\">
                <tr>
                        <td align=\"right\">"._CC_ID.":</td>
                                                <td><input type=\"text\" value=\"".$idedit."\" name=\"form_id\" size=\"5\" readonly> </td>
                </tr>
                <tr>
                        <td align=\"right\">"._CC_TITLE.":</td>
                                                <td><input type=\"text\" value=\"".$myts->makeTboxData4Edit($fc_item['title'])."\" name=\"form_title\" size=\"50\" tabindex=\"1\"> </td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_ADRESS.":</td>
                                                <td><input type=\"text\" name=\"form_adress\" size=\"20\" maxlength=\"40\"  value=\"".$myts->makeTboxData4Edit($fc_item['adress'])."\" tabindex=\"2\"></td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_COMMENT.":</td>
                                                <td><input type=\"text\" value=\"".$myts->makeTboxData4Edit($fc_item['comment'])."\" name=\"form_comment\" size=\"100\" tabindex=\"3\"></td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_HIDE.":</td>
                                                <td><input type=\"checkbox\" value=\"1\" name=\"form_hide\" tabindex=\"4\" ".$hide_checked."> "._CC_ADD_HIDELONG."</td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_HITS.":</td>
                                                <td><input type=\"text\" value=\"".$fc_item['hits']."\" name=\"form_hits\" size=\"11\" tabindex=\"5\"></td>
                </tr>
                        <tr height=\"10\">
                                                <td align=\"right\" height=\"10\"></td>
                                                <td height=\"10\"><input type=\"hidden\" value=\"editdb\" name=\"op\"></td>
                </tr>
                <tr>
                                                <td align=\"right\"></td>
                                                <td><input type=\"submit\" name=\"add\" tabindex=\"6\" value=\""._CC_SUBMIT_UPD."\"> <input type=\"reset\" tabindex=\"7\" value=\""._CC_ADD_SUBMIT_RESET."\"></td>
                </tr></table></form>";
        CloseTable();
		        break;
				}	

				
xoops_cp_footer();

//*****************************************************************************************
//*** Functions-declaration ***************************************************************
//*****************************************************************************************




function fc_admin_list(){
        global $xoopsDB, $xoopsConfig;
		$myts =& MyTextSanitizer::getInstance();
		$edcount=0;
		$delcount=0;
        OpenTable();
		echo "<form name=\"list\" action=\"./index.php\" method=\"post\">";
		echo "<table border=0 cellpadding=2 cellspacing=2 width=\"95%\" align=\"center\"><tr><td colspan=8><div align=\"center\">
	<h4>"._CC_LIST_HEADER."</h4></div></td></tr>
	<tr><td colspan=8><center>"._CC_LIST_DEL."</center></td></tr>
	<tr><td colspan=8><hr></td></tr>
	<tr><td><b><i>"._CC_ID."</i></b></td><td><b><i>"._CC_TITLE."</i></b></td><td><b><i>"._CC_ADRESS."</i></b></td><td><b><i>"._CC_COMMENT."</i></b></td><td><b><i>"._CC_HIDE."</i></b></td><td><b><i>"._CC_HITS."</i></b></td><td><b><i>"._CC_EDIT."</i></b></td><td><b><i>"._CC_DELETE."</i></b></td></tr>";
        // get all rows from db
	$result = $xoopsDB->query("SELECT id, title, adress, comment, hide, hits FROM ".$xoopsDB->prefix()."_cjaycontent WHERE NOT (id=1) ORDER BY id ");
        while($fc_item = $xoopsDB->fetchArray($result)) {
		echo "<tr><td>".$fc_item['id']."</td>
		<td>".$myts->makeTboxData4Show($fc_item['title'])."</td>
		<td>".$myts->makeTboxData4Show($fc_item['adress'])."</td>
		<td>".$myts->makeTboxData4Show($fc_item['comment'])."</td>
		<td>".$fc_item['hide']."</td>
		<td>".$fc_item['hits']."</td>
		<td><input type=\"hidden\" value=\"".$fc_item['id']."\" name=\"idedit[".$edcount."]\"><input type=\"hidden\" value=\"edconfirm\" name=\"op[".$edcount."]\"><input type=\"submit\" value=\""._CC_EDIT."\" name=\"edsubmit[".$edcount."]\"></input></td>
		<td><input type=\"hidden\" value=\"".$fc_item['id']."\" name=\"iddel[".$delcount."]\"><input type=\"hidden\" value=\"delconfirm\" name=\"op[".$delcount."]\"><input type=\"submit\" value=\""._CC_DELETE."\" name=\"delsubmit[".$delcount."]\"></input></td></tr>";
		$edcount++;
		$delcount++;
		}
	echo "</table>";
	echo "</form>";
	CloseTable();
	echo "<br />";
}

function fc_admin_add(){
        global $xoopsConfig;
        OpenTable();
        echo "<form name=\"Add Content\" action=\"./index.php\" method=\"post\"><div align=\"center\">
                         <h4>"._CC_ADD_HEADER."</h4><br>
						 "._CC_ADD_HEADER2."
                </div><table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"95%\" align=\"center\">
				<tr><td colspan=2><hr></td></tr>
                <tr>
                        <td align=\"right\">"._CC_TITLE.":</td>
                                                <td><input type=\"text\" name=\"form_title\" size=\"50\" tabindex=\"1\"> </td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_ADRESS.":</td>
                                                <td>
												<select name=\"form_adress\" size=\"1\" tabindex=\"2\">";
 if ($dir= opendir('./../content/')){
 while  (($file = readdir($dir)) !==false) {
 	if (is_file('./../content/'.$file) and ($file !="DO_NOT_DELETE.php")){
 	echo "<option value=\"$file\">$file</option>\n";
 	}//if
	} // while
	closedir($dir);
 }
echo "</select>";
echo "</td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_COMMENT.":</td>
                                                <td><input type=\"text\" name=\"form_comment\" size=\"100\" tabindex=\"3\"></td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_HIDE.":</td>
                                                <td><input type=\"checkbox\" value=\"checkboxValue\" name=\"form_hide\" tabindex=\"4\"> "._CC_ADD_HIDELONG."</td>
                </tr>
                        <tr height=\"10\">
                                                <td align=\"right\" height=\"10\"></td>
                                                <td height=\"10\"><input type=\"hidden\" value=\"add\" name=\"op\"></td>
                </tr>
                <tr>
                                                <td align=\"right\"></td>
                                                <td><input type=\"submit\" name=\"add\" tabindex=\"5\" value=\""._CC_ADD_SUBMIT_ADD."\"> <input type=\"reset\" tabindex=\"6\" value=\""._CC_ADD_SUBMIT_RESET."\"></td>
                </tr></table></form>";
        CloseTable();
		echo "<br />";
}

/*function fc_admin_edit($idedit){
        global $xoopsConfig, $xoopsDB;
		$myts =& MyTextSanitizer::getInstance();
        $result = $xoopsDB->query("SELECT title, adress, comment, hide, hits FROM ".$xoopsDB->prefix()."_cjaycontent WHERE id='".$idedit."'");
        $fc_item = $xoopsDB->fetcharray($result);

        if ( $fc_item['hide'] == 0){
                $hide_checked = "";
        }
        else{
                $hide_checked = "checked";
        }
        OpenTable();
        echo "<form name=\"Edit Content\" action=\"./index.php\" method=\"post\"><div align=\"center\"><h4>"._CC_EDIT_HEADER."</h4></div><table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"95%\">
                <tr>
                        <td align=\"right\">"._CC_ID.":</td>
                                                <td><input type=\"text\" value=\"".$idedit."\" name=\"form_id\" size=\"5\" readonly> </td>
                </tr>
                <tr>
                        <td align=\"right\">"._CC_TITLE.":</td>
                                                <td><input type=\"text\" value=\"".$myts->makeTboxData4Edit($fc_item['title'])."\" name=\"form_title\" size=\"50\" tabindex=\"1\"> </td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_ADRESS.":</td>
                                                <td><input type=\"text\" name=\"form_adress\" size=\"20\" maxlength=\"40\"  value=\"".$myts->makeTboxData4Edit($fc_item['adress'])."\" tabindex=\"2\"></td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_COMMENT.":</td>
                                                <td><input type=\"text\" value=\"".$myts->makeTboxData4Edit($fc_item['comment'])."\" name=\"form_comment\" size=\"100\" tabindex=\"3\"></td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_HIDE.":</td>
                                                <td><input type=\"checkbox\" value=\"1\" name=\"form_hide\" tabindex=\"4\" ".$hide_checked."> "._CC_ADD_HIDELONG."</td>
                </tr>
                <tr>
                                                <td align=\"right\">"._CC_HITS.":</td>
                                                <td><input type=\"text\" value=\"".$fc_item['hits']."\" name=\"form_hits\" size=\"11\" tabindex=\"5\"></td>
                </tr>
                        <tr height=\"10\">
                                                <td align=\"right\" height=\"10\"></td>
                                                <td height=\"10\"><input type=\"hidden\" value=\"editdb\" name=\"op\"></td>
                </tr>
                <tr>
                                                <td align=\"right\"></td>
                                                <td><input type=\"submit\" name=\"add\" tabindex=\"6\" value=\""._CC_SUBMIT_UPD."\"> <input type=\"reset\" tabindex=\"7\" value=\""._CC_ADD_SUBMIT_RESET."\"></td>
                </tr></table></form>";
        CloseTable();
		echo "<br />";
}
*/
function mainmenu_admin (){
		global $xoopsDB, $xoopsConfig;
		$myts =& MyTextSanitizer::getInstance();
		$c=1;
		$result = $xoopsDB->query("SELECT id,title,comment,adress, weight FROM ".$xoopsDB->prefix()."_cjaycontent WHERE NOT (id=1) ORDER BY id");
		OpenTable();
		echo "<center><table width=\"95%\"><tr><td colspan=4><div align=\"center\"><h4>"._CC_MM_MMSETT."</h4></div></td></tr>";
		echo "<form name=\"menu\" action=\"./index.php\" method=\"post\">";
		echo "<tr><td></td><td><b>"._CC_MM_MMQ."</b></td><td><input type=\"radio\" name=\"mainm\" value=\"yes\">"._CC_MM_QYES."</td><td><input type=\"radio\" name=\"mainm\" value=\"no\" checked>"._CC_MM_QNO."</td><td></td></tr>";
		echo "<tr><td colspan=4><hr></td></tr>";
		echo "<tr><td><b><i>"._CC_TITLE."</i></b></td><td><b><i>"._CC_COMMENT."</i></b></td><td><b><i>"._CC_ADRESS."</i></b></td><td><b><i><center>"._CC_MM_WEIGHT."</center></i></b></td></tr>";
		while($cc_item = $xoopsDB->fetchArray($result)) {
		
		echo "<tr>
		<td>".$myts->makeTboxData4Show($cc_item['title'])."</td>
		<td>".$myts->makeTboxData4Show($cc_item['comment'])."</td>
		<td>".$myts->makeTboxData4Show($cc_item['adress'])."</td>
		<td><center><input type=\"hidden\" name=\"id[".$c."]\" value=\"".$cc_item['id']."\"><input name=\"neww[".$c."]\" type=\"text\" size=\"3\" maxlength=\"3\" value=\"".$cc_item['weight']."\"></center></td></tr>";
		$c++;
		}
		echo "<tr><td colspan=4><center><input type=\"hidden\" value=\"$c\" name=\"c\"><input type=\"hidden\" value=\"mainmenu\" name=\"op\"><input type=\"submit\" name=\"add\" value=\""._CC_MM_SUBMIT."\"></center></td></tr>";
		echo "</form>";
		echo "</table></center>";
		CloseTable();
		echo "<br />";
}


function fc_admin_message($message_text, $error_color, $additional_text){
        OpenTable();
        if ($error_color == 0){
                //Good News
                echo "<center><br><h4>".$message_text."</h4><br>".$additional_text."</center>";
        }
        else{
                //Bad News
                echo "<center><br><font color=\"red\"><h4>".$message_text."</h4><br></font>".$additional_text."</center>";
        }
        CloseTable();
		echo "<br />";
}



function fc_footer(){
        echo "<b><br>C-Jay Content is free software and released under the <a target\"_blank\" href=\"http://www.gnu.org\">GNU/GPL license.</a><br>Find updates and infos here: <a target\"_blank\" href=\"http://c-jay.net\">http://c-jay.net</a></b><br>C-Jay Content is based on freecontent written by <a href=\"mailto:tiger@sibserag.de\">Stefan SIBSERAG Oeser</a>";
}
?>
