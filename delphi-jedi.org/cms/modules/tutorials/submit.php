<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 User Submit Functions                                  //
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
include "header.php";
#include "cache/config.php";

include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/class/xoopsform/formdhtmltextarea.js.php";
include_once(XOOPS_ROOT_PATH."/class/xoopsform/formtextarea.php");
include_once XOOPS_ROOT_PATH."/include/functions.php";
include_once(XOOPS_ROOT_PATH ."/class/xoopsform/formdhtmltextarea.php");
global $myts;
$eh = new ErrorHandler;
$mytree = new XoopsTree($db->prefix("tutorials_categorys"),"cid","scid");
define("IMAGE_PATH",XOOPS_ROOT_PATH."/modules/tutorials/images");
define("IMAGE_URL",XOOPS_URL."/modules/tutorials/images");

if(!$xoopsUser){
        redirect_header (XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
        exit();
}
// -----------------------------------------------------------------------------------------------------------//
function Tutorials(){
        global $db, $xoopsConfig, $xoopsTheme, $mytree, $useruploads, $xoopsUser, $xoopsLogger, $xoopsMF;
        include XOOPS_ROOT_PATH."/header.php";
        OpenTable();
    $result=$db->query("select count(*) from ".$db->prefix("tutorials_categorys")."");
        list($numrows)=$db->fetch_row($result);
            if ($numrows>0) {
                        // Add new Tutorial ------------------//
                    echo "<center><form method=post action=submit.php><h4>"._MD_TUTORIAL."</h4><br>";
                    echo _MD_CHOICECATEGORY."<br><br>";
                    echo _MD_INCATEGORY;
                    $mytree->makeMySelBox("cname", "cname");
                        if ($useruploads == 1){
                                echo "<input type=hidden name=op value=questForPics>";
                        } else {
                                echo "<input type=hidden name=op value=addTutorial>";
                        }
                    echo "<input type=submit value="._MD_GO.">\n";
                        echo "</form></center>";
                } else {
                        echo _MD_NOCATEGORY;
                }
        CloseTable();
        include XOOPS_ROOT_PATH."/footer.php";
}
// -----------------------------------------------------------------------------------------------------------//
function questForPics() {
        global $HTTP_POST_VARS, $xoopsConfig, $xoopsTheme, $xoopsUser, $xoopsLogger, $xoopsMF;
        include(XOOPS_ROOT_PATH."/header.php");
    $cid = $HTTP_POST_VARS["cid"];
        OpenTable();
    echo "<p align=\"center\"><b>"._MD_QUESTPIC."</b></p>";
        echo "<table align=\"center\"><tr><td>";
        echo TextForm("submit.php?op=addTutorial&createdir=1&cid=$cid",_MD_YES);
        echo "</td><td>";
        echo TextForm("submit.php?op=addTutorial&createdir=0&cid=$cid",_MD_NO);
        echo "</td></tr></table>";
           CloseTable();
           include(XOOPS_ROOT_PATH."/footer.php");
}
// -----------------------------------------------------------------------------------------------------------//
function addTutorial(){
        global $db, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsMF, $HTTP_GET_VARS, $HTTP_POST_VARS, $myts, $xoopsTheme, $useruploads, $imgwidth, $imgheight;
        include(XOOPS_ROOT_PATH."/header.php");
                if ($useruploads == 1) {
            $cid = $HTTP_GET_VARS["cid"];
            $createdir = $HTTP_GET_VARS["createdir"];
        } else {
                $cid = $HTTP_POST_VARS["cid"];
                $createdir = 0;
        }
        // Add new Tutorial ------------------//
        $result=$db->query("select scid, cname, cdesc, cimg from ".$db->prefix("tutorials_categorys")." where cid=$cid");
        list($scid,$cname,$cdesc,$cimg) = $db->fetch_row($result);
        $cname=$myts->makeTboxData4Show($cname);
        $cdesc=$myts->makeTareaData4Show($cdesc);
        OpenTable();
        $time = time();
        $dir = $time;

        echo "<h4>"._MD_ADDTUTORIAL."</h4><hr>";
        if (file_exists(IMAGE_PATH."/$cid")) {
                $imgdirexists = 1;
                if ($createdir == 1) {
                    $path = IMAGE_PATH."/$cid/$dir";
                    if(mkdir($path,0777) == false){
                            echo "<p align=\"center\"><h4><font color=\"red\"><b>"._MD_ERRCREATEDIR."</b></font></h4></p>";
                            $imgsubdirexists = 0;
                    } else {
#                            echo "<p align=\"center\"><h5><font color=\"red\"><b>"._MD_DIRCREATED."</b></font></h4></p>";
                                $imgsubdirexists = 1;
                    }
                }
           } else {
                        $imgdirexists = 0;
                        $imgsubdirexists = 0;
    }
    $status = 0;
    $img_path = "$cid/";
        $img_path2 = "$cid/$dir/";
        $scriptname = "submit.php";
        $hits = 0;
        $rating = 0;
        $votes = 0;
        $framebrowse = 0;
        $submitter = $xoopsUser->uid();
        $tauthor = XoopsUser::getUnameFromId($submitter);
        include_once XOOPS_ROOT_PATH."/modules/tutorials/include/form.php";
           CloseTable();
           include(XOOPS_ROOT_PATH."/footer.php");
}
// -----------------------------------------------------------------------------------------------------------//
function PreviewTutorial() {
        global $db,$xoopsConfig, $xoopsTheme, $HTTP_POST_VARS, $myts, $mytree, $content_visdefault, $content_default, $content_visualize, $imgwidth, $imgheight, $useruploads, $xoopsUser, $xoopsLogger, $xoopsMF;
        include(XOOPS_ROOT_PATH."/header.php");

        if($HTTP_POST_VARS["tid"]) {
                $tid = $HTTP_POST_VARS["tid"];
        }
        $cid = $HTTP_POST_VARS["cid"];
        $gid = $HTTP_POST_VARS["gid"];
        $tname = $HTTP_POST_VARS["tname"];
        $tauthor = $HTTP_POST_VARS["tauthor"];
        $submitter = $HTTP_POST_VARS["submitter"];
        $timg = $HTTP_POST_VARS["timg"];
        $tdesc = $HTTP_POST_VARS["tdesc"];
        $tlink = $HTTP_POST_VARS["tlink"];
        $dir = $HTTP_POST_VARS["dir"];
        $time = $HTTP_POST_VARS["time"];
        $status = $HTTP_POST_VARS["status"];
        $hits = $HTTP_POST_VARS["hits"];
        $rating = $HTTP_POST_VARS["rating"];
        $votes = $HTTP_POST_VARS["votes"];
        $framebrowse = $HTTP_POST_VARS["framebrowse"];
        $timgwidth = $HTTP_POST_VARS["timgwidth"];
        $timgheight = $HTTP_POST_VARS["timgheight"];
        if ($HTTP_POST_VARS["maketdir"] == 1) {
                if (file_exists(IMAGE_PATH."/$cid")) {
                        $imgdirexists = 1;
                        $path = IMAGE_PATH."/$cid/$dir";
                    if(mkdir($path,0777) == false){
                            echo "<p align=\"center\"><h4><font color=\"red\"><b>"._MD_ERRCREATEDIR."</b></font></h4></p>";
                            $imgsubdirexists = 0;
                    } else {
                            echo "<p align=\"center\"><h5><font color=\"red\"><b>"._MD_DIRCREATED."</b></font></h4></p>";
                                $imgsubdirexists = 1;
                    }
            } else {
                        $imgdirexists = 0;
                        $imgsubdirexists = 0;
            }
        }
        if (ereg("http://",$timg)) {
                $timgwidth = "";
                $timgheight = "";
        }
// ShowPreview ---------------------//
        if ($tlink == "") {

                  if ( !empty($HTTP_POST_VARS['xsmiley']) && !empty($HTTP_POST_VARS['xhtml']) ){
                        $content = $myts->makeTareaData4Preview($HTTP_POST_VARS['tcont'],0,0,1);
                        $tcont = $myts->makeTareaData4PreviewInForm($HTTP_POST_VARS["tcont"],0,0,1);
                } elseif ( !empty($HTTP_POST_VARS['xhtml']) ) {
                        $content = $myts->makeTareaData4Preview($HTTP_POST_VARS['tcont'],0,1,1);
                        $tcont = $myts->makeTareaData4PreviewInForm($HTTP_POST_VARS["tcont"],0,1,1);
                } elseif ( !empty($HTTP_POST_VARS['xsmiley']) ) {
                        $content = $myts->makeTareaData4Preview($HTTP_POST_VARS['tcont'],1,0,1);
                        $tcont = $myts->makeTareaData4PreviewInForm($HTTP_POST_VARS["tcont"],1,0,1);
                } else {
                        $content = $myts->makeTareaData4Preview($HTTP_POST_VARS['tcont'],1,1,1);
                        $tcont = $myts->makeTareaData4PreviewInForm($HTTP_POST_VARS["tcont"],1,1,1);
                }

                $content = str_replace("_IMGURL_",IMAGE_URL,$content);
                $content = str_replace("[pagebreak]","<table width=100%><tr><td width=10%>"._MD_NEXTPAGE."</td><td width=90%><hr></td></tr></table>",$content);
                $date = formatTimestamp($time,"m");
                if(get_magic_quotes_gpc()){
                        $content = stripslashes($content);
                }
                OpenTable();
                echo "<h4>Preview</h4>";
                if ($content_visdefault == 1) {
                        $preview = $content_visualize;
                } else {
                        $preview = $content_default;
                }
                if ($timg != "") {
                        if (ereg("http://",$timg)) {
                                $imgpath = $timg;
                        } else {
                                $imgpath = "".IMAGE_URL."/$timg";
                        }
                        if ($timgwidth > 0 && $timgheight > 0){
                                $setsize = "width=".$timgwidth." height=".$timgheight;
                        } else {
                                 $setsize = "";
                         }
                        $preview = str_replace("[image]","<img src=\"".$imgpath."\" name=timage ".$setsize." border=1>",$preview);
                } else {
                        $preview = str_replace("[image]","",$preview);
                }
                $preview = str_replace("[title]","<h4>$tname</h4>",$preview);
                $preview = str_replace("[content]",$content,$preview);
                $preview = str_replace("[author]",sprintf(_MD_WRITTENBY,$tauthor),$preview);
                $preview = str_replace("[hits]","0"._MD_HITS,$preview);
                $preview = str_replace("[rating]",_MD_RATING.": 0",$preview);
                $preview = str_replace("[votes]","0"._MD_VOTES,$preview);
                $preview = str_replace("[date]","$date",$preview);
                echo "$preview";
        } else {
                OpenTable();
                if ($framebrowse == 1) {
                        echo "<iframe SRC=\"".$tlink."\" WIDTH=\"100%\"  HEIGHT=\"1200\"  FRAMESPACING=0 FRAMEBORDER=no  BORDER=0 SCROLLING=auto></iframe>";
                } else {
                        $content=$tlink;
                        echo "<center>"._MD_EXTLINK."$tlink&nbsp;->&nbsp;<a href=\"$tlink\" target=\"_blank\"><b>"._MD_SHOWLINK."</b></a></center><hr>";
                }
        }

// ShowForm ----------------------//

        $xsmiley = intval($HTTP_POST_VARS["xsmiley"]);
        $xhtml = intval($HTTP_POST_VARS["xhtml"]);
        $tdesc = $myts->makeTareaData4PreviewInForm($tdesc);
        $result=$db->query("select scid, cname, cdesc, cimg from ".$db->prefix("tutorials_categorys")." where cid=$cid");
        list($scid,$cname,$cdesc,$cimg) = $db->fetch_row($result);
        $cname=$myts->makeTboxData4Show($cname);
        $cdesc=$myts->makeTareaData4Show($cdesc);

        if (file_exists(IMAGE_PATH."/$cid")) {
                $imgdirexists = 1;
        } else {
                $imgdirexists = 0;
        }
        if (file_exists(IMAGE_PATH."/$cid/$dir")) {
                $imgsubdirexists = 1;
        } else {
                $imgsubdirexists = 0;
        }
    $img_path = "$cid/";
        $img_path2 = "$cid/$dir/";
        $scriptname = "submit.php";
        include (XOOPS_ROOT_PATH."/modules/tutorials/include/form.php");
           CloseTable();
           include(XOOPS_ROOT_PATH."/footer.php");
}
// -----------------------------------------------------------------------------------------------------------//
function SaveTutorial() {
    global $db,$xoopsConfig, $HTTP_POST_VARS, $myts, $eh, $xoopsUser, $xoopsLogger, $xoopsMF;

        if($HTTP_POST_VARS["tid"]) {
                $tid = $HTTP_POST_VARS["tid"];
        } else {
                $tid = 0;
        }
        $cid = $HTTP_POST_VARS["cid"];
        $smiley = intval($HTTP_POST_VARS["xsmiley"]);
        $html = intval($HTTP_POST_VARS["xhtml"]);
        $status = $HTTP_POST_VARS["status"];
        $dir = $HTTP_POST_VARS["dir"];
        $time = $HTTP_POST_VARS["time"];
        $hits = $HTTP_POST_VARS["hits"];
        $rating = $HTTP_POST_VARS["rating"];
        $votes = $HTTP_POST_VARS["votes"];
        $framebrowse = $HTTP_POST_VARS["framebrowse"];
        if (($html == 0) && ($smiley == 0)) {
                $codes = 0;
        } elseif (($html == 1) && ($smiley == 0)) {
                $codes = 1;
        } elseif (($html == 0) && ($smiley == 1)) {
                $codes = 2;
        } else {
                $codes = 3;
        }
        if ($framebrowse == 1) {
                $codes += 10;
        }
        if($HTTP_POST_VARS["gid"]) {
                $gid = $HTTP_POST_VARS["gid"];
        } else {
                $gid = 0;
        }
        $tname = $myts->makeTboxData4Save($HTTP_POST_VARS["tname"]);
        $tauthor = $myts->makeTboxData4Save($HTTP_POST_VARS["tauthor"]);
        $timg = $myts->makeTboxData4Save($HTTP_POST_VARS["timg"]);
        if (ereg("http://",$timg) || $timg == "") {
                $timgwidth = 0;
                $timgheight = 0;
        } else {
                $timgwidth = $HTTP_POST_VARS["timgwidth"];
                $timgheight = $HTTP_POST_VARS["timgheight"];
        }
        $tdesc = $myts->makeTboxData4Save($HTTP_POST_VARS["tdesc"]);
        $tcont = $myts->makeTareaData4Save($HTTP_POST_VARS["tcont"],$html,$smiley,1);
        $submitter = $HTTP_POST_VARS["submitter"];
        $message ="";
        if (!empty($HTTP_POST_VARS["tlink"])) {
                $tlink = $myts->makeTboxData4Save($HTTP_POST_VARS["tlink"]);
    }
// Check if Title exist
    if ($tname=="") {
                $message .= "<h4><font color=\"#ff0000\">";
                $message .= _MD_ERRORNAME."</font></h4><br>";
            $error =1;
    }
// Check if Description exist
        if ($tdesc=="") {
                $message .= "<h4><font color=\"#ff0000\">";
                $message .= _MD_ERRORDESC."</font></h4><br>";
            $error =1;
        }
// Check if Content exist
        if (($tcont=="") && ($tlink=="")) {
                $message .= "<h4><font color=\"#ff0000\">";
                $message .= _MD_ERRORCONT."</font></h4><br>";
            $error =1;
        }
        if($error == 1) {
                include(XOOPS_ROOT_PATH."/header.php");
                OpenTable();
                echo $message;
                echo "<center><input type=\"button\" value=\""._MD_GOBACK."\" onclick=\"javascript:history.go(-1)\"></center>";
                CloseTable();
                include(XOOPS_ROOT_PATH."/footer.php");
                exit();
        }
        if($tid == 0) {
                $newid = $db->genId("tutorials_tid_seq");
                   $db->query("INSERT INTO ".$db->prefix("tutorials")." (tid, cid, gid, tname, tdesc, timg, tcont, tlink, tauthor, status, codes, hits, rating, votes, date, submitter, dir, timgwidth, timgheight) VALUES ($newid, $cid, $gid, '$tname', '$tdesc', '$timg', '$tcont', '$tlink', '$tauthor', $status, $codes, 0, 0, 0, $time, $submitter, $dir, $timgwidth, $timgheight)") or $eh->show("0013");
        } elseif ($status == 0) {
                $db->query("UPDATE ".$db->prefix("tutorials")." set tid=$tid, cid=$cid, gid=$gid, tname='$tname', tdesc='$tdesc', timg='$timg', tcont='$tcont', tlink='$tlink', tauthor='$tauthor', status=$status, codes=$codes, hits=$hits, rating=$rating, votes=$votes, date=$time, timgwidth=$timgwidth, timgheight=$timgheight where tid=$tid") or $eh->show("0013");
        } elseif ($tid > 0 && $status >= 1) {
                $time = time();
                $db->query("UPDATE ".$db->prefix("tutorials")." set tid=$tid, cid=$cid, gid=$gid, tname='$tname', tdesc='$tdesc', timg='$timg', tcont='$tcont', tlink='$tlink', tauthor='$tauthor', status=$status, codes=$codes, hits=$hits, rating=$rating, votes=$votes, date=$time, timgwidth=$timgwidth, timgheight=$timgheight where tid=$tid") or $eh->show("0013");
        }
        redirect_header("index.php",1,_MD_RECEIVED."<br>"._MD_WHENAPPROVED."");
}
// -----------------------------------------------------------------------------------------------------------//
function editTutorial() {
        global $db,$xoopsConfig, $HTTP_GET_VARS, $myts, $eh, $mytree, $useruploads, $imgwidth, $imgheight, $xoopsUser, $xoopsLogger, $xoopsMF;
        include(XOOPS_ROOT_PATH."/header.php");

           $tid =  $HTTP_GET_VARS['tid'];
        $result=$db->query("select tid, cid, gid, tname,tdesc, timg, tcont, tlink, tauthor, status, codes, hits, rating, votes, date, submitter, dir, timgwidth, timgheight from ".$db->prefix("tutorials")." where tid=$tid");
        list($tid,$cid,$gid,$tname,$tdesc,$timg,$tcont,$tlink,$tauthor,$status,$codes,$hits,$rating,$votes,$time,$submitter,$dir,$timgwidth,$timgheight) = $db->fetch_row($result);

        if ($codes >= 10) {
                $codes -= 10;
                $framebrowse = 1;
        } else {
                $framebrowse = 0;
        }
        if ($codes == 0) {
                $xhtml = 0;
                $xsmiley = 0;
        } elseif ($codes == 1) {
                $xhtml = 1;
                $xsmiley = 0;
        } elseif ($codes == 2) {
                $xhtml = 0;
                $xsmiley = 1;
        } else {
                $xhtml = 1;
                $xsmiley = 1;
        }

        $tname = $myts->makeTboxData4PreviewInForm($tname);
        $tauthor = $myts->makeTboxData4PreviewInForm($tauthor);
        $timg = $myts->makeTboxData4PreviewInForm($timg);
        $tdesc = $myts->makeTboxData4PreviewInForm($tdesc);
        $tlink = $myts->makeTboxData4PreviewInForm($tlink);
        $tcont = $myts->makeTareaData4PreviewInForm($tcont,$html,$smiley,1);
        $result=$db->query("select scid, cname, cdesc, cimg from ".$db->prefix("tutorials_categorys")." where cid=$cid");
        list($scid,$cname,$cdesc,$cimg) = $db->fetch_row($result);
        $cname=$myts->makeTboxData4Show($cname);
        $cdesc=$myts->makeTareaData4Show($cdesc);

        OpenTable();
        echo "<h4>"._MD_EDITTUTORIAL."</h4><hr>";
        if (file_exists(IMAGE_PATH."/$cid")) {
                $imgdirexists = 1;
        } else {
                $imgdirexists = 0;
        }
        if (file_exists(IMAGE_PATH."/$cid/$dir")) {
                $imgsubdirexists = 1;
        } else {
                $imgsubdirexists = 0;
        }
        $status = 2;
        $img_path = "$cid/";
        $img_path2 = "$cid/$dir/";
        $scriptname = "submit.php";
        include XOOPS_ROOT_PATH."/modules/tutorials/include/form.php";
        CloseTable();
        include XOOPS_ROOT_PATH."/footer.php";
}
// -----------------------------------------------------------------------------------------------------------//

switch ($op) {
                default:
                        Tutorials();
                        break;
                case "questForPics":
                        questForPics();
                        break;
                case "addTutorial":
                        addTutorial();
                        break;
                case "PreviewTutorial":
                        PreviewTutorial();
                        break;
                case "SaveTutorial":
                        SaveTutorial();
                        break;
                case "editTutorial":
                        editTutorial();
                        break;
}


?>