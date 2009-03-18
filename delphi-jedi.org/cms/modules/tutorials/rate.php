<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 rate.php                                                       //
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

include("../../mainfile.php");
include_once XOOPS_ROOT_PATH."/class/module.textsanitizer.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";

#if(file_exists(XOOPS_ROOT_PATH."/modules/tutorials/language/".$xoopsConfig['language']."/main.php")){
#        include(XOOPS_ROOT_PATH."/modules/tutorials/language/".$xoopsConfig['language']."/main.php");
#}else{
#        include(XOOPS_ROOT_PATH."/modules/tutorials/language/english/main.php");
#}

global $myts;


if($HTTP_POST_VARS['submit']) {
        $eh = new ErrorHandler; //ErrorHandler object
        if(!$xoopsUser){
                $ratinguser = 0;
        }else{
                $ratinguser = $xoopsUser->uid();
        }
    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip = getenv("REMOTE_ADDR");
        $tid = $HTTP_POST_VARS['tid'];
        $rating = $HTTP_POST_VARS['rating'];
           // Check if Rating is Null
           if ($rating=="--") {
                   redirect_header("rate.php?tid=".$tid."",4,_MD_NORATING);
        exit();
           }

        // Check if REG user is trying to vote twice.
    $result=$db->query("SELECT ratinguser FROM ".$db->prefix("tutorials_votedata")." WHERE tid=$tid");
    while(list($ratinguserDB)=$db->fetch_row($result)) {
            if ($ratinguserDB==$ratinguser) {
                redirect_header("index.php?op=listtutorials&cid=$cid",4,_MD_VOTEONCE);
                        exit();
        }
    }

        // Check if ANONYMOUS user is trying to vote more than once per day.
        if ($ratinguser==0){
            $yesterday = (time()-(86400 * $anonwaitdays));
        $result=$db->query("SELECT COUNT(*) FROM ".$db->prefix("tutorials_votedata")." WHERE tid=$tid AND ratinguser=0 AND ratinghostname = '$ip'  AND ratingtimestamp > $yesterday");
        list($anonvotecount) = $db->fetch_row($result);
        if ($anonvotecount >= 1) {
                redirect_header("index.php?op=listtutorials&cid=$cid",4,_MD_VOTEONCE);
                        exit();
        }
        }

        //All is well.  Add to Line Item Rate to DB.
        $newid = $db->genId("tutorials_votedata_ratingid_seq");
        $datetime = time();
    $db->query("INSERT INTO ".$db->prefix("tutorials_votedata")." (ratingid, tid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES ($newid, $tid, $ratinguser, $rating, '$ip', $datetime)") or $eh("0013");
    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.

        //updates rating data in itemtable for a given item
        $query = "select rating FROM ".$db->prefix("tutorials_votedata")." WHERE tid = $tid";
        $voteresult = $db->query($query);
           $votesDB = $db->num_rows($voteresult);
        $totalrating = 0;
    while(list($rating)=$db->fetch_row($voteresult)){
                $totalrating += $rating;
        }
        $finalrating = $totalrating/$votesDB;
        $finalrating = number_format($finalrating, 4);
        $query =  "UPDATE ".$db->prefix("tutorials")." SET rating=$finalrating, votes=$votesDB WHERE tid = $tid";
           $db->query($query);
        $ratemessage = _MD_VOTEAPPRE."<br>".sprintf(_MD_THANKYOU,$meta['title']);
        redirect_header("index.php?op=listtutorials&cid=$cid",4,$ratemessage);
        exit();
} else {
        include(XOOPS_ROOT_PATH."/header.php");
    OpenTable();
    $result=$db->query("SELECT cid, tname FROM ".$db->prefix("tutorials")." WHERE tid=$tid");
        list($cid, $tname) = $db->fetch_row($result);
        $tname = $myts->makeTboxData4Show($tname);
    echo "
            <hr />
                <table border=0 cellpadding=1 cellspacing=0 width=\"80%\"><tr><td>
            <h4>$tname</h4>
            <UL>
             <LI>"._MD_VOTEONCE."
             <LI>"._MD_RATINGSCALE."
             <LI>"._MD_BEOBJECTIVE."
             <LI>"._MD_DONOTVOTE."";
            echo "
             </UL>
             </td></tr>
             <tr><td align=\"center\">
             <form method=\"POST\" action=\"rate.php\">
             <input type=\"hidden\" name=\"tid\" value=\"$tid\">
                <input type=\"hidden\" name=\"cid\" value=\"$cid\">
             <select name=\"rating\">
                <option>--</option>";
             for($i=10;$i>0;$i--){
                        echo "<option value=\"".$i."\">".$i."</option>\n";
                }
             echo "</select><br><br><input type=\"submit\" name=\"submit\" value=\""._MD_RATEIT."\"\n>";
                echo "&nbsp;<input type=\"button\" value=\""._MD_CANCEL."\" onclick=\"javascript:history.go(-1)\">\n";
            echo "</form></td></tr></table>";
            CloseTable();
}
        include XOOPS_ROOT_PATH."/footer.php";
?>