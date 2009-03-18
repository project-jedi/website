<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 index.php                                                       //
//                                                                                                                             //
//        Author: Thomas (Todi) Wolf                                                                     //
//        Mail:        todi@dark-side.de                                                                     //
//        Homepage: http://www.mytutorials.info                                             //
//                                                                                                                             //
//        for Xoops                                                                                                 //
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
include_once(XOOPS_ROOT_PATH."/class/xoopstree.php");
$mytree = new XoopsTree($db->prefix("tutorials_categorys"),"cid","scid");
global $myts;
define("IMAGE_PATH",XOOPS_ROOT_PATH."/modules/tutorials/images");
define("IMAGE_URL",XOOPS_URL."/modules/tutorials/images");
// -----------------------------------------------------------------------------------------------------------//
$colcount = 0;
        if ($xoopsConfig['startpage'] == "tutorials"){
                $xoopsOption['show_rblock'] =1;
                include XOOPS_ROOT_PATH."/header.php";
                make_cblock();
        } else {
                $xoopsOption['show_rblock'] =0;
                include XOOPS_ROOT_PATH."/header.php";
        }
    OpenTable();
    $result = $db->query("select cid, cname, cdesc, cimg, cimgwidth, cimgheight from ".$db->prefix("tutorials_categorys")." where scid=0 order by cname");
        $catcount = $db->num_rows($result);
        echo "<center><img src=\"images/tutorials.gif\" border=\"0\" Alt=\"Tutorials\"><br /><br />";
        if ($heading == "") {
            printf(_MD_WELCOMETOTUTS,$meta['title']);
        } else {
                echo $heading;
        }
        echo "<br /><br />";
        echo "<table width=80% cellspacing=4 cellpadding=0 border=0 valign=top><tr>";
        while ($myrow = $db->fetch_array($result)){
                $cname = $myts->makeTboxData4Show($myrow['cname']);
                $cdesc = $myts->makeTboxData4Show($myrow['cdesc']);
                $cimg = $myts->makeTboxData4Show($myrow['cimg']);
                $number = getTotalItems($myrow['cid'], 1, 3);
                if ($category_visdefault == 1) {
                        $show = $category_visualize;
                } else {
                        $show = $category_default;
                }
                $arr=array();
                $arr=$mytree->getFirstChild($myrow['cid'], "cname");
                $space = 0;
                $chcount = 0;
                $subcats = "";
                foreach($arr as $ele){
                        $chtitle=$myts->makeTboxData4Show($ele['cname']);
                        if ($chcount > $maxsubcatshow){
                                $subcats .= "...";
                                break;
                        }
                        if ($space>0) {
                        $subcats .= ", ";
                }
                       $subcats .= "<a href=\"".XOOPS_URL."/modules/tutorials/listtutorials.php?cid=".$ele['cid']."\">".$chtitle."</a>";
                $space++;
                        $chcount++;
                }
                if ($cimg != "") {
                        if (ereg("http://",$cimg)) {
                                $imgpath = $cimg;
                        } else {
                                $imgpath = "".IMAGE_URL."/$cimg";
                        }
                        if ($myrow['cimgwidth'] > 0 && $myrow['cimgheight'] > 0){
                                $setsize ="width=".$myrow['cimgwidth']." height=".$myrow['cimgheight'];
                        } else {
                                 $setsize ="";
                         }
                        $show = str_replace("[image]","<a href=listtutorials.php?cid=".$myrow['cid']."><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\"></a>",$show);
                        $show = str_replace("[image right]","<a href=listtutorials.php?cid=".$myrow['cid']."><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\" align=\"right\"></a>",$show);
                        $show = str_replace("[image left]","<a href=listtutorials.php?cid=".$myrow['cid']."><img src=\"".$imgpath."\" ".$setsize." border=1 alt=\"$cname\" align=\"left\"></a>",$show);
                } else {
                        $show = str_replace("[image]","",$show);
                }
                $show = str_replace("[title]","<a href=listtutorials.php?cid=".$myrow['cid'].">".$cname."</a>",$show);
                $show = str_replace("[subcat]","$subcats",$show);
                $show = str_replace("[description]","$cdesc",$show);
                if($number == 1){
                        $count = "&nbsp;<small>(".sprintf(_MD_TUTORIALCOUNTONE,$number).")</small>";
                }else{
                        $count = "&nbsp;<small>(".sprintf(_MD_TUTORIALCOUNTMORE,$number).")</small>";
                }
                $show = str_replace("[count]","$count",$show);
                $show = str_replace("[link]","<a href=listtutorials.php?cid=".$myrow['cid'].">"._MD_LETSGO."</a>",$show);
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
    echo "</tr></table></center>";
    CloseTable();
    include XOOPS_ROOT_PATH."/footer.php";

?>