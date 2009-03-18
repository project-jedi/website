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
$regnum = $_POST["regnum"];
$numfiles = $_POST["numfiles"];
$sendfiles = $_POST["sendfiles"];
$regnum1 = $_POST["regnum1"];
$numfiles1 = $_POST["numfiles1"];
$sendfiles1 = $_POST["sendfiles1"];
$filename = $_POST["filename"];
$filename2 = $_POST["filename2"];
$op	= $_POST["op"];

xoops_cp_header();
if ((isset ($op))) {
switch($op) {
			case "del":
                if(unlink("./../content/$filename")){
                        fc_admin_message(_CC_DEL_FILE_OK,0,"");
                }
                else{
                        fc_admin_message(_CC_DEL_FILE_ERROR,1,"");
                }
				        break;
			case "del2":
                if(unlink("./../images/$filename2")){
                        fc_admin_message(_CC_DEL_FILE_OK,0,"");
                }
                else{
                        fc_admin_message(_CC_DEL_FILE_ERROR,1,"");
                }
				        break;			
				}
		}		


####################################################################################################
//START SOURCEFILE


OpenTable();
echo "<table border=0 cellpadding=5 width=95%>";
echo "<tr><td colspan=3><center><h3>"._CC_TITLE_SOURCE."</h3></center></td></tr>";
echo "<tr><td width=33%><b>"._CC_NUM_SOURCE."</b></td><td><b>"._CC_SEL_SOURCE."</b></td><td><b>"._CC_OLD_SOURCE."</b></td></tr>";
echo"<tr><td valign=\"top\" >";
echo "<form action=\"./fm.php\" method=\"post\">";

for ($i=1;$i<6;$i++){
echo "<input type=\"radio\" name=\"numfiles\" value=\"$i\">$i\n";
}

echo "<br><input type=\"Submit\" value=\""._CC_SUB_SOURCE."\" name=\"regnum\">";
echo "</form></td>";
echo "<td valign=\"top\" width=33%><form enctype=\"multipart/form-data\" method=\"post\" action=\"./fm.php\">";

if (isset($regnum)) {
		
	for ($k=1; $k<=$numfiles; $k++){
		echo "<input type=\"File\" name=\"myfile$k\"><br>\n";	
	}
	echo "<input type=\"Submit\" name=\"sendfiles\" value=\""._CC_UP."\">";    
}
if (isset ($sendfiles)) {
	$numsendfiles = count($HTTP_POST_FILES);
	echo "<b>$numsendfiles&nbsp;</b> ";
	echo $numsendfiles == 1 ?  ''._CC_FILE_UP.'':''._CC_FILES_UP.'';
	foreach($HTTP_POST_FILES as $strFieldName => $arrPostFiles)
    {
         if ($arrPostFiles['size'] > 0) // if upload was successfully
         {
             // catch data
             $strFileName = $arrPostFiles['name'];
             $intFileSize = $arrPostFiles['size'];
             $strFileMIME = $arrPostFiles['type'];
             $strFileTemp = $arrPostFiles['tmp_name'];
             copy ($strFileTemp, "./../content/$strFileName");
             echo "<p><b>$strFileName</b> "._CC_SUCC."";
             echo "<ul>";
             echo "<li>"._CC_SIZE.": $intFileSize Bytes<br>";
             echo "<li>MIME: $strFileMIME<br>";
             echo "</ul>";
         }  // end if
     } // end foreach
}
echo "</form></td><td valign=\"top\" width=33%>";

 if ($dir= opendir('./../content/')){
 while  (($file = readdir($dir)) !==false) {
 	if (is_file('./../content/'.$file)){		//no folders
 	echo "$file<br>\n";
 	}//if
	} // while
	closedir($dir);
 }
echo "</td></tr></table>";
CLoseTable();
echo "<br />";
//END SOURCEFILE
//START IMAGEFILE
OpenTable();
echo "<table border=0 cellpadding=5 width=95%>";
echo "<tr><td colspan=3><center><h3>"._CC_TITLE_IMAGE."</h3></center></td></tr>";
echo "<tr><td width=33%><b>"._CC_NUM_IMAGE."</b></td><td><b>"._CC_SEL_IMAGE."</b></td><td><b>"._CC_OLD_IMAGE."</b></td></tr>";
echo"<tr><td valign=\"top\">";
echo "<form action=\"./fm.php\" method=\"post\">";

for ($j=1;$j<6;$j++){
echo "<input type=\"radio\" name=\"numfiles1\" value=\"$j\">$j\n";
}

echo "<br><input type=\"Submit\" value=\""._CC_SUB_IMAGE."\" name=\"regnum1\">";
echo "</form></td>";
echo "<td valign=\"top\" width=33%><form enctype=\"multipart/form-data\" method=\"post\" action=\"./fm.php\">";

if (isset($regnum1)) {
		
	for ($l=1; $l<=$numfiles1; $l++){
		echo "<input type=\"File\" name=\"myfile$l\"><br>\n";	
	}
	echo "<input type=\"Submit\" name=\"sendfiles1\" value=\""._CC_UP."\">";    
}
if (isset ($sendfiles1)) {
	$numsendfiles = count($HTTP_POST_FILES);
	echo "<b>$numsendfiles&nbsp;</b> ";
	echo $numsendfiles == 1 ?  ''._CC_FILE_UP.'':''._CC_FILES_UP.'';
    foreach($HTTP_POST_FILES as $strFieldName => $arrPostFiles)
    {
         if ($arrPostFiles['size'] > 0) // if upload was successfully
         {
             // catch data
             $strFileName = $arrPostFiles['name'];
             $intFileSize = $arrPostFiles['size'];
             $strFileMIME = $arrPostFiles['type'];
             $strFileTemp = $arrPostFiles['tmp_name'];
             copy ($strFileTemp, "./../images/$strFileName");
             echo "<p><b>$strFileName</b> "._CC_SUCC."";
             echo "<ul>";
             echo "<li>"._CC_SIZE.": $intFileSize Bytes<br>";
             echo "<li>MIME: $strFileMIME<br>";
             echo "</ul>";
         }  // end if
     } // end foreach
}
echo "</form></td><td valign=\"top\" width=33%>";

 if ($dir= opendir('./../images/')){
 while  (($file = readdir($dir)) !==false) {
 	if (is_file('./../images/'.$file)){		//no folders
 	echo "$file<br>\n";
	}//if
 	} // while
	closedir($dir);
 }
echo "</td></tr></table>";
CloseTable();
echo "<br />";
//END IMAGEFILE
OpenTable();
echo "<table border=0 width=95%>";
echo "<tr><td colspan=2><center><h3>"._CC_TITLE_DEL."</h3></center></td></tr>";
echo "<tr><td colspan=2><center><font color=red><h4>"._CC_WARN."</h4></font></center></td></tr>";
echo "<tr><td width=50% valign=top><h4>"._CC_SOURCE."</h4><table>";
GetDirArray();
echo "</table></td><td width=50% valign=top><h4>"._CC_IMAGE."</h4><table>";
GetDirArray2();
echo "</table></td></tr></table>";
CloseTable();
echo "<br />";
fc_footer();
xoops_cp_footer();



#######################################################################################################

function GetDirArray()
{
//Load Directory Into Array
$path1='./../content/';
$handle=opendir($path1);
$retVal=array();
while ($file1 = readdir($handle))
if (is_file($path1.$file1)){
$retVal[count($retVal)] = $file1;
}
//Clean up and sort
closedir($handle);
sort($retVal);
$num = count($retVal);
//echo "Total Number of File: $num<br><br>";
for ($i=0;$i<$num;$i++){
echo "<tr><td valign=top>$retVal[$i]</td><td><form action=\"fm.php\" method=\"post\"><input type=\"hidden\" name=\"filename\" value=\"$retVal[$i]\" /><input type=\"hidden\" name=\"op\" value=\"del\" /><input type=\"submit\" value=\""._CC_DEL."\" /></td></tr></form>";
}
return $retVal;
}

function GetDirArray2()
{

//Load Directory Into Array
$path2 = './../images/';
$handle2=opendir($path2);
$retVal2=array();
while ($file2 = readdir($handle2))
if (is_file($path2.$file2)) {
 $retVal2[count($retVal2)] = $file2;
   
	}

//Clean up and sort
closedir($handle2);
sort($retVal2);
$num2 = count($retVal2);
//echo "Total Number of File: $num2<br><br>";
for ($n=0;$n<$num2;$n++){
echo "<tr><td valign=top>$retVal2[$n]</td><td><form action=\"fm.php\" method=\"post\"><input type=\"hidden\" name=\"filename2\" value=\"$retVal2[$n]\" /><input type=\"hidden\" name=\"op\" value=\"del2\" /><input type=\"submit\" value=\""._CC_DEL."\" /></td></tr></form>";
}
return $retVal2;
}

function fc_admin_message($message_text, $error_color, $additional_text){
        OpenTable();
        if ($error_color == 0){
                //Good News
                echo "<center><br><h3>".$message_text."</h3><br>".$additional_text."</center>";
        }
        else{
                //Bad News
                echo "<center><br><font color=\"red\"><h3>".$message_text."</h3><br></font>".$additional_text."</center>";
        }
        CloseTable();
}
function fc_footer(){
        echo "<b><br>C-Jay Content is free software and released under the <a target\"_blank\" href=\"http://www.gnu.org\">GNU/GPL license.</a><br>Find updates and infos here: <a target\"_blank\" href=\"http://c-jay.net\">http://c-jay.net</a></b><br>C-Jay Content is based on freecontent written by <a href=\"mailto:tiger@sibserag.de\">Stefan SIBSERAG Oeser</a>";
}
?>