<?php
/* 
* $Id: category.php v 1.0 12 July 2003 Catwolf Exp $
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

function editcat($catid = '') {
		
		$name = '';
		$description = '';
		
		Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify;
		
		include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        if ($modify) {
			$result = $xoopsDB->query("SELECT name, description FROM ".$xoopsDB->prefix("faqcategories")." WHERE catID = '$catid'");
			list($name, $description) = mysql_fetch_row($result);
     
    		if (mysql_num_rows($result) == 0) {
        		redirect_header("index.php",1, _AM_NOCATTOEDIT);
				exit();
			}
			$sform = new XoopsThemeForm(_AM_MODIFYCAT, "op", xoops_getenv('PHP_SELF'));
		} else {
			$sform = new XoopsThemeForm(_AM_ADDCAT, "op", xoops_getenv('PHP_SELF'));
		}
			
		$sform->addElement(new XoopsFormText(_AM_CATNAME, 'name', 50, 80, $name), true);
		$sform->addElement(new XoopsFormDhtmlTextArea(_AM_CATDESCRIPT, 'description', $description, 15, 60));
		$sform->addElement(new XoopsFormHidden('catid', $catid));
		$sform->addElement(new XoopsFormHidden('modify', $modify));	
		
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('op', 'addcat');
		$button_tray->addElement($hidden);
		
		if ($modify == '0') {
			$button_tray->addElement(new XoopsFormButton('', 'update', _AM_CREATE, 'submit'));
		} else {
			$button_tray->addElement(new XoopsFormButton('', 'update', _AM_MODIFY, 'submit'));
		}
		
		$sform->addElement($button_tray);
		$sform->display();
		unset($hidden); 
	 }


switch($op){

case "mod":
    	xoops_cp_header();
    	$modify = 1;
		faqlinks();
		editcat($HTTP_POST_VARS['catid']);
    	break;

case "addcat":	

		Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $myts;
		
			if (isset($HTTP_POST_VARS['catid'])) { 
				$catid = $HTTP_POST_VARS['catid'];
			}

			$name           = $myts->makeTboxData4Save($HTTP_POST_VARS['name']);
    	    $description    = $myts->makeTboxData4Save($HTTP_POST_VARS['description']);
			$description    = str_replace("\r\n", "", $description);
	        $name           = str_replace("\"", "&quot;", $name);
			
			echo $HTTP_POST_VARS['modify'];	
			
	        if (empty($name)) {
	   	     	redirect_header("javascript:history.go(-1)",1,"Unknown Error: File not renamed!");
			}
			if (empty($description)) {
	        	redirect_header("javascript:history.go(-1)",1,"Unknown Error: File not renamed!");   
			} 
	        // Run the query and update the data
			if ($HTTP_POST_VARS['modify'] == '0') {
				if ($xoopsDB->query("INSERT INTO ".$xoopsDB->prefix("faqcategories")." (catID, name, description, total) VALUES ('', '" . htmlspecialchars($name) . "', '" . htmlspecialchars($description) . "', '0')")) {
	        		redirect_header("category.php",1,_AM_CATCREATED);
         		} else {
					redirect_header("category.php",1,_AM_NOTUPDATED);
				}
			} else {
       			if ($xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("faqcategories")." SET name = '" . htmlspecialchars($name) . "', description = '" . htmlspecialchars($description) . "' WHERE catID = '$catid'")) {
					redirect_header("category.php",1,_AM_CATMODIFY);
				} else {
					redirect_header("category.php",1,_AM_NOTUPDATED);
				}
			}
		 //}

	exit();
	break; 
	
case "update":
        
		Global $xoopsUser, $xoopDB, $xoopsConfig, $myts;
		
		$catid = $myts->makeTboxData4Save($HTTP_POST_VARS['catid']);
		$name = $myts->makeTboxData4Save($HTTP_POST_VARS['name']);
        $description = $myts->makeTboxData4Save($HTTP_POST_VARS['description']);
			
        $description    = str_replace("\r\n", "", $description);
        $name           = str_replace("\"", "&quot;", $name);
		
		echo $HTTP_POST_VARS['modify'];
        
		if (empty($name)) {
			redirect_header("javascript:history.go(-1)",1,"Category name is empty!");
		} else if (empty($description)) {
        	redirect_header("javascript:history.go(-1)",1,"Category description is empty!");   
		}else {
       		
			if ($xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("faqcategories")." SET name = '" . htmlspecialchars($name) . "', description = '" . htmlspecialchars($description) . "' WHERE catID = '$catid'")) {
				redirect_header("category.php",1,_AM_UPDATED);
			} else {
				redirect_header("category.php",1,_AM_NOTUPDATED);
			}
        }
	exit();
	break;   

case "del":
        Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
		
		if ($confirm) {
			$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("faqcategories")." WHERE catID = '$catid'");
        	$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix("faqtopics")." WHERE catID = '$catid'");
			redirect_header("category.php",1, sprintf(_AM_CATISDELETED, $question));
			exit();
		} else {
			$catid = $HTTP_POST_VARS['catid'];
        	$result = $xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix("faqcategories")." WHERE catid = '$catid'");
        	list($name) = mysql_fetch_row($result);
				
			xoops_cp_header();
			echo"<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo "<h4>";
            echo ""._AM_DELETETHISCAT."</font></h4>$name<br /><br />";
            echo "<table><tr><td>";
            echo myTextForm("category.php?op=del&catid=".$HTTP_POST_VARS['catid']."&confirm=1&question=$name", _AM_YES);
            echo "</td><td>";
            echo myTextForm("category.php?op=default", _AM_NO);
            echo "</td></tr></table>";
            echo "</div><br /><br />";
            echo"</td></tr></table>";
			xoops_cp_footer();
		}
    exit();
	break;   

case "default":
      default:
	
	xoops_cp_header();
	
	$modify = '0';
	$name = '';
	$description = '';
	
	Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;
	echo "<div><h3>" . _AM_FADMINCATH . "</h3></div>";
	
	faqlinks();
	
	$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("faqcategories")."");
	$check = mysql_num_rows($result);	
	if ($check > 0) {
		$mytree = new XoopsTree($xoopsDB->prefix("faqcategories"),"catid","0");
		$sform = new XoopsThemeForm(_AM_MODIFYCAT, "storyform", xoops_getenv('PHP_SELF'));
		
		//Modify Category
		ob_start();
		$sform->addElement(new XoopsFormHidden('catid', ''));
		$mytree->makeMySelBox("name", "catid");
		$sform->addElement(new XoopsFormLabel(_AM_MODIFYTHISCAT, ob_get_contents()));
		ob_end_clean();
			
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('modify', 1);
		$hidden = new XoopsFormHidden('op', 'mod');
		$button_tray->addElement($hidden);
		$button_tray->addElement(new XoopsFormButton('', 'mod', _AM_MODIFY, 'submit'));
		$sform->addElement($button_tray);
		$sform->display();
		
		//Delete Category	
		$mytree2 = new XoopsTree($xoopsDB->prefix("faqcategories"),"catid","0");
		$dform = new XoopsThemeForm(_AM_DELCAT, "storyform", xoops_getenv('PHP_SELF'));

		ob_start();
		$dform->addElement(new XoopsFormHidden('catid', ''));
		$mytree2->makeMySelBox("name", "catid");
		$dform->addElement(new XoopsFormLabel(_AM_DELETETHISCAT, ob_get_contents()));
		ob_end_clean();
			
		$button_tray = new XoopsFormElementTray('','');
		$hidden = new XoopsFormHidden('modify', 1);
		$hidden = new XoopsFormHidden('op', 'del');
		$button_tray->addElement($hidden);
		$button_tray->addElement(new XoopsFormButton('', 'mod', _AM_DELETE, 'submit'));
		$dform->addElement($button_tray);
		$dform->display();
	}
	editcat(); 
	break;


}
wffaqfooter();
xoops_cp_footer();
?>