<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 printpage.php                                                   //
//                                                                                                                             //
//        Author: Thomas (Todi) Wolf                                                                     //
//        Mail:        todi@dark-side.de                                                                     //
//        Homepage: http://www.mytutorials.info                                             //
//                                                                                                                             //
//        for Xoops                                                                                                //
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

include "header.php";
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mytree = new XoopsTree($db->prefix("tutorials_categorys"),"cid","scid");
global $myts;
define("IMAGE_PATH",XOOPS_ROOT_PATH."/modules/tutorials/images");
define("IMAGE_URL",XOOPS_URL."/modules/tutorials/images");
// -----------------------------------------------------------------------------------------------------------//
           $tid = $HTTP_GET_VARS['tid'];
        $result=$db->query("select tname, timg, tcont, codes, timgwidth, timgheight from ".$db->prefix("tutorials")." where tid=$tid");
    list($tname, $timg, $tcont, $codes, $timgwidth, $timgheight) = $db->fetch_row($result);
        $tname = $myts->makeTboxData4Show($tname);

        if ($codes >= 10) {
                $codes -= 10;
                $framebrowse = 1;
        } else {
                $framebrowse = 0;
        }
        if ($codes == 0) {
                $html = 1;
                $smiley = 1;
        } elseif ($codes == 1) {
                $html = 0;
                $smiley = 1;
        } elseif ($codes == 2) {
                $html = 1;
                $smiley = 0;
        } else {
                $html = 0;
                $smiley = 0;
        }

        $tcont = $myts->makeTareaData4Show($tcont,$html,$smiley,1);
        $tcont = str_replace("_IMGURL_",IMAGE_URL,$tcont);
        echo "
        <html>
        <head><title>".$meta['title']."</title></head>
        <body bgcolor=FFFFFF text=000000>
        <table border=0><tr><td>
        <table border=0 width=640 cellpadding=0 cellspacing=1 bgcolor=000000><tr><td>
        <table border=0 width=640 cellpadding=20 cellspacing=1 bgcolor=FFFFFF><tr><td>
        <center>";
        echo "<h4>".$tname."</h4>";
        if ($timg != "") {
                if ($timgwidth < 1 || $timgheight < 1) {
                        $timgsize = '';
                } else {
                        $timgsize = " width=\"".$timgwidth."\" height=\"".$timgheight."\"";
                }
                echo "<img src=\"".IMAGE_URL."/$timg\"".$timgsize." border=\"1\" alt=\"\" /><br /><br />";
        }
        echo "</center><font size=2>
        ".str_replace("[pagebreak]","",$tcont)."</font><br><br>";
        echo "</td></tr></table></td></tr></table>";
        echo "<br><br><center>";
        printf(_MD_COMESFROM,$meta['title']);
        echo "<br><a href=".XOOPS_URL.">".XOOPS_URL."</a><br><br>";
        echo _MD_URLFORTHIS."<br>
        <a href=\"".XOOPS_URL."/modules/tutorials/viewtutorial.php?tid=$tid\">".XOOPS_URL."/modules/tutorials/viewtutorial.php?tid=$tid</a>
        </td></tr></table>
        </body>
        </html>";

?>