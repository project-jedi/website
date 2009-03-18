<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 listtutorials.php                                                 //
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
        $colcount = 0;
        $xoopsOption['show_rblock'] = 1;
        include XOOPS_ROOT_PATH."/header.php";
        $cid = $HTTP_GET_VARS['cid'];
        $xcid = $cid;
    OpenTable();
        $result = $db->query("select scid, cname, cimg, cimgwidth, cimgheight from ".$db->prefix("tutorials_categorys")." where cid=$cid");
        list($scid, $cname, $cimg, $cimgwidth, $cimgheight) = $db->fetch_row($result);
        $cname = $myts->makeTboxData4Show($cname);
        $cimg = $myts->makeTboxData4Show($cimg);
        echo "<center>";
        if ($cimg != "") {
                if (ereg("http://",$cimg)) {
                        $imgpath = $cimg;
                } else {
                        $imgpath = "".IMAGE_URL."/$cimg";
                }
                echo "<img src=\"".$imgpath."\" border=\"1\"><br><br>";
        }
    printf(_MD_THISISCATEGORY,$cname);
        echo "<br><br><br>";
// List all Subcaregorys //
    $result = $db->query("select cid, cname, cdesc, cimg from ".$db->prefix("tutorials_categorys")." where scid=$cid order by cname");
        if ($columnset == 1) {
                echo "<table width=80% cellspacing=4 cellpadding=0 border=0 valign=top><tr>";
        } else {
                echo "<table width=100% cellspacing=4 cellpadding=0 border=0 valign=top><tr>";
        }
        $subcats = $db->num_rows($result);
        while (list($cid, $cname, $cdesc, $cimg) = $db->fetch_row($result)){
                $cname = $myts->makeTboxData4Show($cname);
                $cdesc = $myts->makeTboxData4Show($cdesc);
                $cimg = $myts->makeTboxData4Show($cimg);
                $result2 = $db->query("select tid from ".$db->prefix(tutorials)." where cid=$cid and status=1 or status=3");
                $number = $db->num_rows($result2);
                if ($category_visdefault == 1) {
                        $show = $category_visualize;
                } else {
                        $show = $category_default;
                }
                $arr=array();
                $arr=$mytree->getFirstChild($cid, "cname");
                $space = 0;
                $chcount = 0;
                $subcat = "";
                foreach($arr as $ele){
                        $chtitle=$myts->makeTboxData4Show($ele['cname']);
                        if ($chcount > $maxsubcatshow){
                                $subcat .= "...";
                                break;
                        }
                        if ($space>0) {
                        $subcat .= ", ";
                }
                       $subcat .= "<a href=\"".XOOPS_URL."/modules/tutorials/listtutorials.php?cid=".$ele['cid']."\">".$chtitle."</a>";
                $space++;
                        $chcount++;
                }
                if ($cimg != "") {
                        if (ereg("http://",$cimg)) {
                                $imgpath = $cimg;
                        } else {
                                $imgpath = "".IMAGE_URL."/$cimg";
                        }
                        if ($cimgwidth > 0 && $cimgheight > 0){
                                $setsize ="width=".$cimgwidth." height=".$cimgheight;
                        } else {
                                 $setsize ="";
                         }
                        $show = str_replace("[image]","<a href=listtutorials.php?cid=$cid><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\"></a>",$show);
                        $show = str_replace("[image left]","<a href=listtutorials.php?cid=$cid><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\" align=\"left\"></a>",$show);
                        $show = str_replace("[image right]","<a href=listtutorials.php?cid=$cid><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\" align=\"right\"></a>",$show);
                } else {
                        $show = str_replace("[image]","",$show);
                }
                $show = str_replace("[title]","<a href=\"listtutorials.php?cid=$cid\">".$cname."</a>",$show);
                $show = str_replace("[subcat]","$subcat",$show);
                $show = str_replace("[description]","$cdesc",$show);
                if($number == 1){
                        $count = "&nbsp;<small>(".sprintf(_MD_TUTORIALCOUNTONE,$number).")</small>";
                }else{
                        $count = "&nbsp;<small>(".sprintf(_MD_TUTORIALCOUNTMORE,$number).")</small>";
                }
                $show = str_replace("[count]","$count",$show);
                $show = str_replace("[link]","<a href=listtutorials.php?cid=$cid>"._MD_LETSGO."</a>",$show);
                if ($columnset == 1) {
                        echo "<td>".$show."</td>";
                        echo "</tr><tr>";
                } else {
                        echo "<td width=\"50%\" valign=\"top\">".$show."</td>";
                        if (++$colcount == 2) {
                                $colcount = 0;
                                echo "</tr><tr>";
                        }
                }
        }
        if ($colcount == 1 && $columnset == 2) {
                echo "<td></td>";
        }
    echo "</tr></table></center>";
    if ($subcats != 0) {
            echo "<hr>";
    }
//-----------------------------------------------------//
        $gid = 0;
        echo "<center>";
        include(XOOPS_ROOT_PATH."/modules/tutorials/include/showlist.php");
        echo "</center>";
        $result3 = $db->query("select gid, cid, pos, gname from ".$db->prefix("tutorials_groups")." where cid=$xcid order by pos");
        while (list($gid, $cid ,$pos, $gname) = $db->fetch_row($result3)) {
                $gname = $myts->makeTboxData4Show($gname);
                echo "<font size=\"3\"><b>".$gname."</b></font><hr>";
                echo "<center>";
                include(XOOPS_ROOT_PATH."/modules/tutorials/include/showlist.php");
                echo "</center>";
        }
        if ($scid > 0) {
                $result = $db->query("select cid, cname from ".$db->prefix("tutorials_categorys")." where cid=$scid");
                list($cid, $cname) = $db->fetch_row($result);
                $cname = $myts->makeTboxData4Show($cname);
                $back = "<a href=listtutorials.php?cid=$cid>".sprintf(_MD_BACK2CATEGORY,$cname)."</a> | ";
        } else {
                $back = "";
        }
    echo "<br /><br /><br /><center>[ ".$back."<a href=index.php>"._MD_RETURN2INDEX."</a> ]</center>";
    CloseTable();
    include XOOPS_ROOT_PATH."/footer.php";

?>