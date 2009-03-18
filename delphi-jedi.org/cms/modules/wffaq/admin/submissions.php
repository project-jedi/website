<?php
/* 
* $Id: submissions.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include 'admin_header.php';

$op="";

Global $xoopsUser, $xoopsUser, $xoopsConfig;

$myts =& MyTextSanitizer::getInstance();

foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

foreach ($HTTP_GET_VARS as $k => $v) {
	${$k} = $v;
}

if (isset($HTTP_GET_VARS['op'])) $op=$HTTP_GET_VARS['op'];
if (isset($HTTP_POST_VARS['op'])) $op=$HTTP_POST_VARS['op'];

switch($op){

case "view":
   
        Global $xoopsUser, $xoopsDB;
		//if (empty($c)) $c = 1;
        // Display the answer
        $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID = $t");
        list($topicID, $catID, $question, $answer, $summary, $uid, $submit, $datesub) = mysql_fetch_row($result);
        
		$result2 = $xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix("faqcategories")." WHERE catID = '$c'");
        list($cat) = $xoopsDB->getRowsNum($result2);

        $answer = str_replace("\r\n", "<br>", $answer);
        $answer = str_replace("\n", "<br>", $answer);

       	if ($uid) {
			$user = new xoopsUser($uid);
				$poster = $user->getVar("uname");
				$submitter = "<a href='".XOOPS_URL."/userinfo.php?uid=".$uid."'>$poster</a>";
			} else {
				$submitter = "Guest";
		}
				
		$datesub = formatTimestamp($datesub,"D, d-M-Y, H:i");
		
		xoops_cp_header();	
        echo "<table border='0' width='100%' cellspacing='1' cellpadding='2'>";
		echo "<tr valign='middle' class='b4'>";
		echo "<td align='left' colspan='3' class='bg3'><b>"._AM_SUBPREVIEW."</b></td></tr>";
  		echo "<tr>";
    	echo "<td width='100%'><br><br>"._AM_SUBADMINPREV."</td>";
  		echo "</tr>";
  		echo "</table>";
		echo "<table border='0' width='100%' cellspacing='1' cellpadding='2'>";
		echo "<tr>"; 
		echo "<td class='bg3' colspan='2'><b>&nbsp;"._MD_FAQ.": $question</td>";
		echo "</tr>";
		echo "<tr><td class='head'>"._AM_AUTHOR.": $submitter";
  		echo "<br>"._AM_PUBLISHED.": $datesub</td></tr>";
		echo "<td><br />$answer<br /><br /></td>";
		echo "</tr>";
		echo "<tr><td class='even'  align = 'center'><b>&nbsp<a href='submissions.php?op=allow&t=$t&c=$c'>"._AM_SUBALLOW."</a> <a href='index.php?op=del&subm=1&t=$topicID''>"._AM_SUBDELETE."</a></b></td></tr>";
		echo "<tr><td class='head' colspan='2' align = 'center'><a href='submissions.php?op=cat'>"._AM_SUBRETURNTO."</a></td></tr>";
  		echo "</table>";
 		exit();
	break;   

case "allow";	
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("faqtopics")." SET submit = '1'  WHERE topicID = $t" );
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("faqcategories")." SET total = total + 1 WHERE catID = $c" );
		redirect_header("submissions.php",1, _AM_DBUPDATED);
		exit();
	break;

case "cat":
default:

        Global $xoopsUser, $xoopDB, $xoopsConfig;
		
		$results = $xoopsDB->query('SELECT * FROM '.$xoopsDB->prefix("faqtopics").' WHERE submit = 0 ORDER BY datesub');
		$totalfiles = $xoopsDB->getRowsNum($results);
		       
        if (intval($totalfiles) == 0) {
			redirect_header("category.php?op=default",1, 'There are no FAQ for validation.');
            exit();
		} else {
			xoops_cp_header();
            // Display the questions
			
			faqlinks();
			echo "<br />";
			echo "<table border='0' width='100%'  cellspacing='1' cellpadding='4' class = 'outer'>";
			echo "<tr valign='middle' >"; 
			echo "<td align='left' class='bg3' colspan =5><b>"._AM_FVAL."</b></td>";
			echo "</tr></table>";
			echo "<br />";
			echo "<div width='100%' colspan =5 ><b>New Submissions</b></div>";
			echo "<br />";			
			echo "<table border='0' width='100%' cellspacing='1' cellpadding='2' class = 'outer'>";
			echo "<td width='5%' align='center' valign='middle' class='bg3'><b>ID</b></td>";
			echo "<td width='25%' align='left' valign='middle' class='bg3'><b>Title</b></td>";
			echo "<td width='25%' align='center' valign='middle' class='bg3'><b>Author</b></td>";
			echo "<td width='25%' align='center' valign='middle' class='bg3'><b>Submitted</b></td>";
			echo "<td width='25%' align='center' colspan='2' class='bg3'><b>Action</b></td>";
			echo "</tr>";
						
			while (list($topicID, $catID, $question, $answer, $summary, $uid, $submit, $datesub) = $xoopsDB->fetchRow($results)) {
          	
           	if ($uid) {
			$user = new xoopsUser($uid);
				$poster = $user->getVar("uname");
				$submitter = "<a href='".XOOPS_URL."/userinfo.php?uid=".$uid."'>$poster</a>"; //$thisUser->getVar("uname");
			} else {
				$submitter = "Guest";
			}
			$datesub = formatTimestamp($datesub,"D, d-M-Y, H:i");
			
			echo "<tr>";
			echo "<td class='head' align = 'center'>$topicID</td>";
			echo "<td class='even'><a href='submissions.php?op=view&t=$topicID&c=$catID'>$question</a></td>";
			echo "<td class='even'><p align='center'>$submitter</td>";
			echo "<td class='even'><p align='center'>$datesub</td>";
			echo "<td align='center' class='even' > <a href='submissions.php?op=allow&t=$topicID&c=$catID'>"._AM_SUBALLOW."</a></td>";
			echo "<td align='center' class='even' > <a href='index.php?op=del&subm=1&t=$topicID'>"._AM_SUBDELETE."</a>";
			echo "</td></tr>";
            }
		echo "<tr>";
		echo "<td colspan='6' align = 'center' class='head' ><a href='index.php'>"._AM_SUBRETURN."</a></td>";
		echo "</tr></table>";
		echo "</td>";
		echo "</tr></table>";	
	}
    break;
}
wffaqfooter();
xoops_cp_footer();
?>