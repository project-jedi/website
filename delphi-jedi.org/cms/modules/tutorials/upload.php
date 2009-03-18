<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 Upload Functions  		                         //
//												                             //
//	Author: Thomas (Todi) Wolf					                             //
//	Mail:	todi@dark-side.de					                             //
//	Homepage: http://www.mytutorials.info		                             //
//												                             //
//	for Xoops RC3								                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/modules/tutorials/cache/config.php");
define("IMAGE_PATH",XOOPS_ROOT_PATH."/modules/tutorials/images");
define("IMAGE_URL",XOOPS_URL."/modules/tutorials/images");

$mimetype = array("image/gif","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash");
$filetype = array("*.gif","*.jpg","*.png","*.bmp","*.swf");

	echo "<!DOCTYPE html PUBLIC '//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'._LANGCODE.'" lang="'._LANGCODE.'">
	<head>
	<meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" />
	<meta http-equiv="content-language" content="'._LANGCODE.'" />
	<meta name="robots" content="'.$xoopsConfigMetaFooter['meta_robots'].'" />
	<meta name="keywords" content="'.$xoopsConfigMetaFooter['meta_keywords'].'" />
	<meta name="description" content="'.$xoopsConfigMetaFooter['meta_desc'].'" />
	<meta name="rating" content="'.$xoopsConfigMetaFooter['meta_rating'].'" />
	<meta name="author" content="'.$xoopsConfigMetaFooter['meta_author'].'" />
	<meta name="copyright" content="'.$xoopsConfigMetaFooter['meta_copyright'].'" />
	<meta name="generator" content="XOOPS" />
	<title>'.$xoopsConfig['sitename'].'</title>
	';

	echo "<meta http-equiv=\"cache-control\" content=\"no-cache\">";

?>

<script type="text/javascript">

<!--

resizeTo(500,500);

function xoopsGetElementById(id){
	if (document.getElementById) {
		return (document.getElementById(id));
	} else if (document.all) {
		return (document.all[id]);
	} else {
		if ((navigator.appname.indexOf("Netscape") != -1) && parseInt(navigator.appversion == 4)) {
			return (document.layers[id]);
		}
	}
}

function insertImage(addImage,target,logo,target2,target3,width,height) {
	if ( addImage != null && addImage != "" ) {
		var currentMessage = window.opener.xoopsGetElementById(target).value;
		if (logo == 0) {
			var text2 = prompt("<?php echo _ENTERIMGPOS;?>\n<?php echo _IMGPOSRORL;?>", "");
			while ( ( text2 != "" ) && ( text2 != "r" ) && ( text2 != "R" ) && ( text2 != "l" ) && ( text2 != "L" ) && ( text2 != null ) ) {
				text2 = prompt("<?php echo _ERRORIMGPOS;?>\n<?php echo _IMGPOSRORL;?>","");
			}
			if ( text2 == "l" || text2 == "L" ) {
				text2 = " align=left";
			} else if ( text2 == "r" || text2 == "R" ) {
			text2 = " align=right";
			} else {
			text2 = "";
			}
			var result = "[img" + text2 + "]" + addImage + "[/img]";
			var currentMessage = window.opener.xoopsGetElementById(target).value;
			window.opener.xoopsGetElementById(target).value = currentMessage+result;
		} else {
			var result = addImage;
			window.opener.xoopsGetElementById(target).value = result;
			var result = width;
			window.opener.xoopsGetElementById(target2).value = result;
			var result = height;
			window.opener.xoopsGetElementById(target3).value = result;
/*			window.close();               */
		}
		return;
	}
}
//-->
</script>
<?php

echo "<style>
#info {
	background-color:#cccccc;
	border:1px solid #000;
	font-family:Tahoma,Sans-serif;
	font-size:12px;
	padding-left:8pt;
	padding-top:4pt;
	padding-right:8pt;
	padding-bottom:4pt;
}
body {
	background-color:#d0d0e0;
	margin: 0px;
	padding: 0;
}
input {
	border: solid 1px black;
}
</style>";
echo "</head><body>";

$path_to_temp = XOOPS_ROOT_PATH."/modules/tutorials/cache/image.tmp";
if (!empty($path)) {
	if (!copy($path_to_temp,$path)) {
		echo _MD_ERRORUPLOAD;
	}
}

echo "\n\n<script language=\"javascript\">
var progressEnd = 36;		// set to number of progress <span>'s.
var progressColor = '#8a8aB5';	// set to progress bar color
var progressInterval = 250;	// set to time between updates (milli-seconds)

var progressAt = progressEnd;
var progressTimer;
function progress_clear() {
	for (var i = 1; i <= progressEnd; i++) document.getElementById('progress'+i).style.backgroundColor = 'transparent';
	progressAt = 0;
}
function progress_update() {
	document.getElementById(\"waitupload\").style.visibility = \"visible\";
	progressAt++;
	if (progressAt > progressEnd) progress_clear();
	else document.getElementById('progress'+progressAt).style.backgroundColor = progressColor;
	progressTimer = setTimeout('progress_update()',progressInterval);
}
function progress_stop() {
	clearTimeout(progressTimer);
	progress_clear();
	document.getElementById(\"waitupload\").style.visibility = \"hidden\";

}
function waitfor_save() {
	document.getElementById(\"waitsave\").style.visibility = \"visible\";
}
</script>\n\n";


echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";

echo "<tr><td height=35 style=\"background-color:#b0b0c0; font-family:Tahoma,Sans-serif; font-size: 16px; text-align:center; border-top:solid 1px #c0c0c0; border-bottom:solid 1px black; vertical-align:middle;\"><b>";
if ($logo == 0){
	echo _MD_UPLOADIMAGE;
} else {
	echo _MD_UPLOADLOGO;
}
echo "</b></td></tr></table>";

echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">";
echo "<tr><td height=35 style=\"background-color:#b0b0c0;text-align:center; border-top:solid 1px #c0c0c0; border-bottom:solid 1px black;\">";
echo "<span title='Supported file types:";
foreach ($filetype as $type){
	echo "\n  ".$type;
}
echo "'style=\"font-family:Tahoma,Sans-serif;font-size:13px;font-weight: bold;\">? </span>";
echo "<input type=\"file\" name=\"file\" size=\"35\" value=\"\" onchange=\"submit(); javascript:progress_update();\">";
if (!empty($HTTP_GET_VARS['file'])){
	$file = $HTTP_GET_VARS['file'];
	echo "<input type=\"hidden\" name=\"file\" value=\"$file\">";
}
echo "<input type=\"hidden\" name=\"img_path\" value=\"$img_path\">";
echo "<input type=\"hidden\" name=\"target\" value=\"$target\">";
#if (!empty($HTTP_GET_VARS['target2'])){
	echo "<input type=\"hidden\" name=\"target2\" value=\"$target2\">";
#}
#if (!empty($HTTP_GET_VARS['target3'])){
	echo "<input type=\"hidden\" name=\"target3\" value=\"$target3\">";
#}
echo "<input type=\"hidden\" name=\"logo\" value=\"$logo\">";

echo "</td></tr>\n";
echo "</form>";
echo "</table>\n";

echo "<table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=center><tr><td height=363 valign=top>";
echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id='waitupload' style='position:absolute;visibility:hidden;top:86%;left:5%;'><tr><td>";
echo "<center><div>";
echo "<table align=\"center\"><tr><td>"._PLEASEWAIT."<br />";
echo "<div style=\"background-color:#c9c9c9;font-size:5pt;padding:1px;border:solid black 1px\">";


for ($i=1;$i <= 36; $i++) {
	echo "<span id=\"progress".$i."\">&nbsp; &nbsp;</span>\n";
}

echo "</div>";
echo "</td></tr></table></div>";
echo "</center></td></tr></table>";
echo "<div id='waitsave' style='position:absolute;visibility:hidden;left:30%;top:50%;color:#9999aa;background-color:#c0c0d0;border:solid 1px black;padding:4px;'>";
echo "<h1>"._PLEASEWAIT."</h1></div>";

if(!empty($file)) {
########### Set the path to your file ################
$error = 0;
$temp_file = $HTTP_POST_FILES['file']['tmp_name'];

	if (in_array($file_type,$mimetype)) {
		if ($file_size <= $maxfilesize) {
			$path_to_file = "$img_path".$file_name;
			if (is_uploaded_file($temp_file)) {
				unlink(XOOPS_ROOT_PATH."/modules/tutorials/cache/image.tmp");
				move_uploaded_file($temp_file, XOOPS_ROOT_PATH."/modules/tutorials/cache/image.tmp");
				$size=getimagesize(XOOPS_ROOT_PATH."/modules/tutorials/cache/image.tmp");
				$width=$size[0];
				$height=$size[1];
				if ($logo == 1){
					if ($width > $imgwidth || $height > $imgheight){
						$error = 1;
					}
				} else {
					if ($width > $maximgwidth || $height > $maximgheight){
						$error = 1;
					}
				}
				if ($error == 0) {
					# calculate tumbnail
					$maxwidth=200;	# max tumbnailwidth
					$maxheight=200; # max tumbnailheight
					$fc=1;
					if ($width > $maxwidth) {
						$fc1 = $width/$maxwidth;
					} else {
						$fc1=1;
					}
					if ($height > $maxheight) {
						$fc2 = $height/$maxheight;
					} else {
						$fc2=1;
					}
					if ($fc2 > $fc1) {
						$fc=$fc2;
					} else {
						$fc=$fc1;
					}
					$tumbwidth = intval($width/$fc);
					$tumbheight = intval($height/$fc);

					echo "<br><table id='info' width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align='center'><tr><td style=\"font-family:Tahoma,Sans-serif; font-size:11px;\">";
					echo "<img src=\"".XOOPS_URL."/modules/tutorials/cache/image.tmp\" width=\"$tumbwidth\" height=\"$tumbheight\" border=\"1\" align=right><br>";

					echo _MD_FILENAME."$file_name <br>";
					echo _MD_FILESIZE."$file_size <br>";
					echo _MD_FILETYPE."$file_type <br>";
					echo _MD_IMAGEWIDTH." = $width<br />"._MD_IMAGEHEIGHT." = $height<br />";
					echo "</td></tr>";
					if ($logo == 0) {
						$imagepath = "_IMGURL_/$path_to_file";
					} else {
						$imagepath = $path_to_file;
					}
					echo "<tr><td valign=middle align=center><br>";
					if ($logo == 0) {
						echo "<input type='button' value='"._MD_SAVEINSERT."' onclick='insertImage(\"$imagepath\",\"$target\",$logo); waitfor_save(); location.href=\"upload.php?img_path=$img_path&target=$target&logo=$logo&path=".IMAGE_PATH."/$path_to_file\"'>";
					} else {
						echo "<input type='button' value='"._MD_SAVEINSERT."' onclick='location.href=\"upload.php?path=".IMAGE_PATH."/$path_to_file\"; insertImage(\"$imagepath\",\"$target\",$logo,\"$target2\",\"$target3\",$width,$height); self.close()'>";
					}
					echo "</td></tr></table>";
				}
			} else {
				echo _MD_ERRORUPLOAD;
			}
		} else {
			echo "<br><br><br><center>Datei ist größer als ".sprintf ("%01.2f",$maxfilesize/1024)." KBytes.</center><br><br><br>";
		}
	} else {
		echo "<br><br><br><center>".sprintf(_MD_NOTALLOWED,"<br><br>($file_type)<br><br>")."</center><br><br><br>";
	}
	if ($error == 1 && $logo == 1){
		echo "<br><br><br><center>"._MD_PICSIZEIS."".$imgwidth." x ".$imgheight."!</center><br><br><br>";
	} elseif($error == 1 && $logo == 0){
		echo "<br><br><br><center>"._MD_PICSIZEIS."".$maximgwidth." x ".$maximgheight."!</center><br><br><br>";
	}
#######################################
}
echo "</td></tr></table>";
echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" style=\"background-color:#b0b0c0; border-top:solid 1px black; border-bottom:solid 1px black; text-align:center;\"><tr><td height=25 style=\"text-align:center;\">";
echo "<input type=\"button\" value=\""._MD_CLOSEWIN."\" onclick=\"javascript:self.close();\"> </td></tr></table>";

xoops_footer();
?>