<?php
/* 
* $Id: index.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include("admin_header.php");

$myts =& MyTextSanitizer::getInstance();

$op ='';

foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

foreach ($HTTP_GET_VARS as $k => $v) {
	${$k} = $v;
}

if (isset($HTTP_GET_VARS['op'])) $op=$HTTP_GET_VARS['op'];
if (isset($HTTP_POST_VARS['op'])) $op=$HTTP_POST_VARS['op'];

/**
* Check to see if any categories have been created
* if true continue script
* if false warns user that no categories have been created.
*/ 
$result = $xoopsDB->query("SELECT catID, name FROM ".$xoopsDB->prefix("faqcategories")." ORDER BY name");
if (mysql_num_rows($result) == '0') {
	redirect_header("category.php", '1' , _AM_NOTCTREATEDACAT);
	exit();
}

/*
* Function to edit and modify Topics
*/ 

function edittopic($topicID = '') {
		/*
		* Clear all variable before we start
		*/
		$question = '';
		$answer = '';
		$summary = '';
		$catid = 0;
		$oldid = 0;
		
		Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify;
		
		include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        /* 
		* checks to see if we are modifying a FAQ
		*/ 
		if ($modify) {
			/*
			* Get FAQ info from database
			*/ 
			$result = $xoopsDB->query("SELECT topicID, catID, question, answer, summary FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID = '$topicID'");
			list($topicID, $catID, $question, $answer, $summary) = $xoopsDB->fetchrow($result);
			$oldid = $catID;
			/* 
			* If no FAQ are found, tell user and exit
			*/ 
			if ($xoopsDB->getRowsNum($result) == 0) {
        		redirect_header("index.php",1, _AM_NOTOPICTOEDIT);
				exit();
			}

			$sform = new XoopsThemeForm(_AM_MODIFYEXSITFAQ, "op", xoops_getenv('PHP_SELF'));
		} else {
			$sform = new XoopsThemeForm(_AM_ADDFAQ, "op", xoops_getenv('PHP_SELF'));
		}
		/*
		* Get information for pulldown menu using XoopsTree.
		* First var is the database table
		* Second var is the unique field ID for the categories
		* Last one is not set as we do not have sub menus in WF-FAQ
		*/
		$mytree = new XoopsTree($xoopsDB->prefix("faqcategories"),"catid","0");
		
		/*
		* Get the mytree pulldown object for use with XoopsForm class 
		*/
		ob_start();
		$sform->addElement(new XoopsFormHidden('catid', $catid));
		$mytree->makeMySelBox("name", $catid);
		$sform->addElement(new XoopsFormLabel(_AM_CREATEIN, ob_get_contents()));
		ob_end_clean();
		/*
		* Set the user textboxs using XoopsForm Class for user input
		*/	
		$sform->addElement(new XoopsFormText(_AM_TOPICQ, 'question', 50, 80, $question), true);
		$sform->addElement(new XoopsFormDhtmlTextArea(_AM_TOPICA, 'answer', $answer, 15, 60), true);
		$sform->addElement(new XoopsFormTextArea(_AM_SUMMARY, 'summary', $summary, 7, 60));
		/*
		* XoopsFormHidden, pass on 'unseen' var's' 
		*/
		$sform->addElement(new XoopsFormHidden('topicID', $topicID));	
		$sform->addElement(new XoopsFormHidden('modify', $modify));
		$sform->addElement(new XoopsFormHidden('oldid', $oldid));
		
		/*
		* XoopsForm Class Buttons
		*/
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('op', 'save');
		$button_tray->addElement($hidden);
		/*
		* Switch to show different buttons for either when creating or modifying a FAQ.
		*/
		if (!$modify) {
			$button_tray->addElement(new XoopsFormButton('', 'create', _AM_CREATE, 'submit'));
		} else {
			$button_tray->addElement(new XoopsFormButton('', 'update', _AM_MODIFY, 'submit'));
		}
				 
		$sform->addElement($button_tray);
		$sform->display();
		unset($hidden); 
	 	/*
		*End of XoopsForm 
		*/
	 }
/*
* end function
*/


switch($op){
case "mod":
    	xoops_cp_header();
    	$modify = 1;
		faqlinks();
		edittopic($HTTP_POST_VARS['topicID']);
    	break;

case "del":
	Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
		
		if ($confirm) {
        	$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID = $topicID");
			redirect_header("index.php",1, sprintf(_AM_TOPICISDELETED, $question));
			exit();
		} else {
			if (!$subm){
				$topicID = $HTTP_POST_VARS['topicID'];
			} else {
				$topicID = $t;
			}
			$result = $xoopsDB->query("SELECT question FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID = $topicID");
        	list($question) = $xoopsDB->fetchrow($result);
				
			xoops_cp_header();
			echo"<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo "<h4>";
            echo ""._AM_DELETETHISTOPIC."</font></h4>$question<br /><br/>";
            echo "<table><tr><td>";
            echo myTextForm("index.php?op=del&topicID=".$topicID."&confirm=1&question=$question", _AM_YES);
            echo "</td><td>";
            echo myTextForm("category.php?op=default", _AM_NO);
            echo "</td></tr></table>";
            echo "</div><br /><br />";
            echo"</td></tr></table>";
			xoops_cp_footer();
		}
    exit();
	break;

case "save":
	global $xoopsUser, $xoopsDB;
	
	$cat = $myts->makeTboxData4Save($HTTP_POST_VARS['catid']);
	$question = $myts->makeTboxData4Save($HTTP_POST_VARS['question']);
	$answer = $myts->makeTboxData4Save($HTTP_POST_VARS['answer']);
	$summary = $myts->makeTboxData4Save($HTTP_POST_VARS['summary']);
	$topicID = $myts->makeTboxData4Save($HTTP_POST_VARS['topicID']);
	$oldid = $myts->makeTboxData4Save($HTTP_POST_VARS['oldid']);
	$question   = str_replace("\"", "&quot;", $question);
		
	// Define variables
    $error  = 0;
    $word   = NULL;
    $uid 	= $xoopsUser->uid();
    $submit = 1;
	$date = time();
	if (!$HTTP_POST_VARS['modify']) {
    	if ($xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("faqtopics")." (catID, question, answer, summary, uid, datesub, submit) VALUES ('$cat', '$question', '$answer', '$summary', '$uid', '$date', '$submit')")) {
			$xoopsDB->query("UPDATE ".$xoopsDB->prefix("faqcategories")." SET total = total + 1 WHERE catID = '$cat'" );
			redirect_header("index.php", '1' , _AM_FAQCREATED);
		} else {
			redirect_header("index.php", '1' , _AM_FAQNOTCREATED);
		}
	} else {
		if ($xoopsDB->query("UPDATE ".$xoopsDB->prefix("faqtopics")." SET question = '$question', answer = '$answer', summary = '$summary', catID = '$cat' WHERE topicID = $topicID")) {
            if ($cat != $oldid) {
                $xoopsDB->query("UPDATE ".$xoopsDB->prefix("faqcategories")." SET total = total - 1 WHERE catID = '$oldid'");
                $xoopsDB->query("UPDATE ".$xoopsDB->prefix("faqcategories")." SET total = total + 1 WHERE catID = '$cat'");
            }
			redirect_header("index.php", '1' , _AM_FAQMODIFY);
		} else {
			redirect_header("index.php", '1' , _AM_FAQNOTMODIFY);
		}
		
	}
	exit();
	break;


case "default":
     default:
	
xoops_cp_header();

Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
	echo "<div><h3>" . _AM_TOPICSADMIN . "</h3></div>";
	
	faqlinks();
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("faqtopics")."");
	$check = mysql_num_rows($result);	
	if ($check >= 1) {
		$mytree = new XoopsTree($xoopsDB->prefix("faqtopics"),"topicID","0");
		$sform = new XoopsThemeForm(_AM_MODIFYFAQ, "storyform", xoops_getenv('PHP_SELF'));
		
		//Modify Category
		ob_start();
		$sform->addElement(new XoopsFormHidden('topicID', ''));
		$mytree->makeMySelBox("question", "topicID");
		$sform->addElement(new XoopsFormLabel(_AM_MODIFYTHISFAQ, ob_get_contents()));
		ob_end_clean();
			
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('modify', 1);
		$hidden = new XoopsFormHidden('op', 'mod');
		$button_tray->addElement($hidden);
		$button_tray->addElement(new XoopsFormButton('', 'mod', _AM_MODIFY, 'submit'));
		$sform->addElement($button_tray);
		$sform->display();
		
		//Delete Category	
		$mytree2 = new XoopsTree($xoopsDB->prefix("faqtopics"),"topicID","0");
		$dform = new XoopsThemeForm(_AM_DELFAQ, "storyform", xoops_getenv('PHP_SELF'));

		ob_start();
		$dform->addElement(new XoopsFormHidden('topicID', ''));
		$mytree2->makeMySelBox("question", "topicID");
		$dform->addElement(new XoopsFormLabel(_AM_DELTHISFAQ, ob_get_contents()));
		ob_end_clean();
			
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('modify', 1);
		$hidden = new XoopsFormHidden('op', 'del');
		$button_tray->addElement($hidden);
		$button_tray->addElement(new XoopsFormButton('', 'mod', _AM_DELETE, 'submit'));
		$dform->addElement($button_tray);
		$dform->display();
	}
	edittopic(); 
	break;
}
wffaqfooter();
xoops_cp_footer();
?>