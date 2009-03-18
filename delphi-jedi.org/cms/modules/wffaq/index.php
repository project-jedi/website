<?php
/* 
* $Id: index.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");
include XOOPS_ROOT_PATH."/modules/wffaq/include/functions.php";

Global $xoopsUser, $xoopsDB, $xoopsConfig;

$op ='';

foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

foreach ($HTTP_GET_VARS as $k => $v) {
	${$k} = $v;
}

if (isset($HTTP_GET_VARS['op'])) $op=$HTTP_GET_VARS['op'];
if (isset($HTTP_POST_VARS['op'])) $op=$HTTP_POST_VARS['op'];

$PHP_SELF = $_SERVER["PHP_SELF"];

switch($op){

	case "cat":
		$xoopsOption['template_main'] = 'wffaq_category.html';
		$sql = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("faqcategories")." WHERE catID = $c ");
		$cat_info = $xoopsDB->fetcharray($sql);
		$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("faqtopics")." WHERE catID = '$c' and submit ='1' ORDER BY topicID");
        $totalcat = $xoopsDB->getRowsNum($sql);
		$totaltopics = $xoopsDB->getRowsNum($result);		
		
		$category = array();
		$topics = array();
					
		if ($totalcat == 0) {
            redirect_header("javascript:history.go(-1)",1, _MD_MAINNOSELECTCAT);
			exit();
		}
		if ($totaltopics == 0) {
            redirect_header("javascript:history.go(-1)",1, _MD_MAINNOTOPICS);
			exit();
		}
		$category['catid'] = $cat_info['catID'];
		$category['name'] = $cat_info['name'];
		$category['catlink'] = "<a href='javascript:history.go(-1)'>"._MD_RETURN."</a><b> | </b><a href='./index.php'>"._MD_RETURN2INDEX."</a>";
		$category['description'] = $cat_info['description'];
		//$category['cjump'] = generatecjump();
		
	     while ($cat_data = $xoopsDB->fetcharray($result)) {
	        $topics['summary'] = $cat_data['summary'];
			$topics['question'] = $cat_data['question'];
	        $topics['datesub']= formatTimestamp($cat_data['datesub'],"D, d-M-Y");
			$topics['topicID'] = $cat_data['topicID'];
			if ($cat_data['uid']) {
				$thisUser = new XoopsUser($cat_data['uid']);
				$thisUser->getVar("uname");
				$thisUser->getVar("uid");
				$topics['poster'] = "<a href='".XOOPS_URL."/userinfo.php?uid=".$thisUser->uid()."'>".$thisUser->uname()."</a>"; //$thisUser->getVar("uname");
			} else {
				$topics['poster'] = "Guest";
			}
			
	        $topics['counter'] = $cat_data['counter'];
			$xoopsTpl->append('topics', array("id" => $cat_data['topicID'], "question" => $topics['question'], "summary" => $topics['summary'], "poster" => $topics['poster'], "datesub" =>$topics['datesub'], "counter" => $topics['counter'] ));
        }
	    $xoopsTpl->assign(array('lang_topicsindex' => _MD_MAINPTOPICSINDEX, 'lang_question' => _MD_MAINPQUESTION, 'lang_summary' => _MD_MAINPSUMMARY, 'lang_author' => _MD_MAINPAUTHOR, 'lang_submitted' => _MD_MAINPSUBMITTED, 'lang_hits' => _MD_MAINPHITS, 'lang_categorytag' => _MD_CATEGORY));
		$xoopsTpl->assign('category', $category);
		$data = "";
       break;

	case "view":
    	$xoopsOption['template_main'] = 'wffaq_answer.html';
		Global $xoopsUser, $xoopsDB;
		$faqsa = array();
		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("faqtopics")." SET counter=counter+1 WHERE topicID = '$t' ");
		$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("faqtopics")." WHERE topicID = '$t' and submit = '1' order by datesub");
       	list($topicID, $catID, $question, $answer, $summary, $uid, $submit, $datesub, $counter) = $xoopsDB->fetchrow($result);

	    $result2 = $xoopsDB->query("SELECT name FROM ".$xoopsDB->prefix("faqcategories")." WHERE catID = '$catID'");
        list($cat) = $xoopsDB->fetchrow($result2);

        $answer = str_replace("\r\n", "<br>", $answer);
        $answer = str_replace("\n", "<br>", $answer);
		$faqsa['answer'] = $answer;
		$faqsa['datesub'] = formatTimestamp($datesub,"D, d-M-Y, H:i");
		$faqsa['counter'] = $counter;
		
		$faqsa['question'] = $question;
		//$faqsa['printer'] = "index.php?op=print&t=".$t;
		//$faqsa['cjump'] = generatecjump();
		$faqsa['catlink'] = "<a href='javascript:history.go(-1)'>"._MD_BACK2CAT."</a><b> | </b><a href='./index.php'>"._MD_RETURN2INDEX."</a>";
		
		if ($uid == 0) {
			$faqsa['poster'] = "Guest";
		}else{
			$thisUser= new XoopsUser($uid);
			$thisUser->getVar("uname");
			$thisUser->getVar("uid");
			$faqsa['poster'] = "<a href='".XOOPS_URL."/userinfo.php?uid=".$thisUser->uid()."'>".$thisUser->uname()."</a>"; //$thisUser->getVar("uname");
		}
        
		$xoopsTpl->assign('faqpage', $faqsa);
   	    $xoopsTpl->assign(array('lang_faq' => _MD_FAQ, 'lang_publish' => _MD_PUBLISH, 'lang_posted' => _MD_POSTED, 'lang_read' => _MD_READ, 'lang_times' => _MD_TIMES, 'lang_articleheading' => '<h4>'.$question.'</h4>'));
	break;

	case "default":
      default:	

		global $xoopsUser, $xoopsConfig, $xoopsDB;
		
		$index = array();
		
		$xoopsOption['template_main'] = 'wffaq_index.html';
	    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("faqcategories")." ORDER BY name");
        $total = $xoopsDB->getRowsNum($result);	
        if ($total == 0) {
            redirect_header("javascript:history.go(-1)",1, _MD_MAINNOCATADDED);
			exit(); 
        } 
        while ($query_data = $xoopsDB->fetcharray($result)) {
               $query_data['name'] = $query_data['name'];
               $page = array("ID" => $query_data['catID'], "DESCRIPTION" => $query_data['description'], "CATEGORY" => $query_data['name'], "TOTAL" => $query_data['total']);
               $xoopsTpl->append('indexpage', array("id" => $query_data['catID'], "description" => $query_data['description'], "category" => $query_data['name'], "total" => $query_data['total']));

		    }
   	    $xoopsTpl->assign(array('lang_category' => _MD_MAININDEXCAT, 'lang_description' => _MD_MAININDEXDESC, 'lang_total' => _MD_MAININDEXTOTAL, 'lang_indextext' => _MD_MAININDEX, 'lang_articleheading' => '<h4>'.sprintf(_MD_WELCOMETOSEC, $xoopsConfig['sitename'], _MD_CAPTION).'</h4>'));

}		

include(XOOPS_ROOT_PATH."/footer.php");

?>