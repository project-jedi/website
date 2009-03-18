<?php
//-------------------------------------------------------------------------- //
//  Update from Tutorials Version 2.0 to V2.1                                       //
//                                                                                                                             //
//        Author: Thomas (Todi) Wolf                                                                     //
//        Mail:        todi@dark-side.de                                                                     //
//        Homepage: http://www.mytutorials.info                                             //
//                                                                                                                             //
//        for Xoops RC3                                                                                             //
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
include(XOOPS_ROOT_PATH."/header.php");
global $myts;

if ( file_exists("language/".$xoopsConfig['language']."/update.php") ) {
        include_once("language/".$xoopsConfig['language']."/update.php");
} elseif ( file_exists("language/english/update.php") ) {
        include_once("language/english/update.php");
}

echo "<h3>"._UPDATE_00."</h3>";
echo "<table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\"><tr><td align=\"left\">";
$status = 0;
$status2 = 0;
$error = 0;
$errortxt = "";
# create dir field ########################
$result = @mysql_query("select dir from ".$db->prefix("tutorials")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials")." ADD dir int(10) NOT NULL DEFAULT '0'");
        if ($result) {
                $result = $db->query("select tid, tname, date from ".$db->prefix("tutorials")."");
                while ($myrow = $db->fetch_array($result)){
                        $date = formatTimestamp($myrow['date'],"m");
                        $tid = $myrow['tid'];
                        $tname = $myts->makeTboxData4Show($myrow['tname']);
                        $result2 = $db->queryF("UPDATE ".$db->prefix("tutorials")." SET dir='$date' where tid=$tid");
                        if ($result2){
                                echo $tname."(".$tid.") -> ".$date." "._UPDATE_03."<br>";
                        } else {
                                echo $tname."(".$tid.") -> ".$date." "._UPDATE_04."<br>";
                        }
                }
                $status = 1;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"dir",$db->prefix("tutorials"));
        }
} else {
        echo sprintf(_UPDATE_02,"dir",$db->prefix("tutorials"));
        $status2 = 1;
}

# create logowidth field ########################
$result = @mysql_query("select logowidth from ".$db->prefix("tutorials")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials")." ADD logowidth int(6) NOT NULL DEFAULT '0'");
        if ($result) {
                $status += 10;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"logowidth",$db->prefix("tutorials"));
        }
} else {
        echo sprintf(_UPDATE_02,"logowidth",$db->prefix("tutorials"));
        $status2 += 10;
}

# create logoheight field ########################
$result = @mysql_query("select logoheight from ".$db->prefix("tutorials")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials")." ADD logoheight int(6) NOT NULL DEFAULT '0'");
        if ($result) {
                $status += 100;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"logoheight",$db->prefix("tutorials"));
        }
} else {
        echo sprintf(_UPDATE_02,"logoheight",$db->prefix("tutorials"));
        $status2 += 100;
}

# create timgwidth field ########################
$result = @mysql_query("select timgwidth from ".$db->prefix("tutorials")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials")." ADD timgwidth int(6) NOT NULL DEFAULT '0'");
        if ($result) {
                $status += 1000;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"timgwidth",$db->prefix("tutorials"));
        }
} else {
        echo sprintf(_UPDATE_02,"timgwidth",$db->prefix("tutorials"));
        $status2 += 1000;
}

# create timgheight field ########################
$result = @mysql_query("select timgheight from ".$db->prefix("tutorials")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials")." ADD timgheight int(6) NOT NULL DEFAULT '0'");
        if ($result) {
                $status += 10000;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"timgheight",$db->prefix("tutorials"));
        }
} else {
        echo sprintf(_UPDATE_02,"timgheight",$db->prefix("tutorials"));
        $status2 += 10000;
}

# create cimgwidth field ########################
$result = @mysql_query("select cimgwidth from ".$db->prefix("tutorials_categorys")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials_categorys")." ADD cimgwidth int(6) NOT NULL DEFAULT '0'");
        if ($result) {
        $status += 100000;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"cimgwidth",$db->prefix("tutorials_categorys"));
        }
} else {
        echo sprintf(_UPDATE_02,"cimgwidth",$db->prefix("tutorials_categorys"));
        $status2 += 100000;
}

# create cimgheight field ########################
$result = @mysql_query("select cimgheight from ".$db->prefix("tutorials_categorys")."");
if (!$result) {
        $result = $db->queryF("ALTER TABLE ".$db->prefix("tutorials_categorys")." ADD cimgheight int(6) NOT NULL DEFAULT '0'");
        if ($result) {
        $status += 1000000;
        } else {
                $error += 1;
                $errortxt .= sprintf(_UPDATE_01,"cimgheight",$db->prefix("tutorials_categorys"));
        }
} else {
        echo sprintf(_UPDATE_02,"cimgheight",$db->prefix("tutorials_categorys"));
        $status2 += 1000000;
}

if ($status2 == 1111111){
        echo _UPDATE_07;
} elseif ($status == 1111111){
        echo _UPDATE_06;
} elseif ($error > 0){
        echo sprintf(_UPDATE_05,$error);
        echo $errortxt;
}
if ($status != 0) {
        $status += $status2;
        if ($status == 1111111 && $error == 0){
                echo _UPDATE_08;
        }
}

echo "</td></tr></table>";
include (XOOPS_ROOT_PATH."/footer.php");
?>