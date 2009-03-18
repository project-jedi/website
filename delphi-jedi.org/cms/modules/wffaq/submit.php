<?php
/* 
* $Id: submit.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");

Global $xoopsUser, $xoopsUser, $xoopsConfig;

if (!is_object($xoopsUser)) {
   redirect_header("index.php", 1, _NOPERM);
   exit();
}

global $wfsConfig;
foreach ($HTTP_POST_VARS as $k => $v) {
	${$k} = $v;
}

foreach ($HTTP_GET_VARS as $k => $v) {
	${$k} = $v;
}

$op = 'form';

if ( isset($HTTP_POST_VARS['post']) ) {
	$op = 'post';
} elseif ( isset($HTTP_POST_VARS['edit']) ) {
    $op = 'edit';
}

switch($op){

case 'post':
	
	$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
	Global $xoopsUser, $xoopsConfig;
	
	if (is_object($xoopsUser)) {
   		$uid = $xoopsUser->uid();
	} else {
		$uid = 0;
	}
	if(intval($HTTP_POST_VARS['catid'])) {
	} else {
	echo intval($HTTP_POST_VARS['catid']);
	}
	
	$cat = $myts->makeTboxData4Save($HTTP_POST_VARS['catid']);
	$question = $myts->makeTboxData4Save($HTTP_POST_VARS['question']);
	$answer = $myts->makeTareaData4Save($HTTP_POST_VARS['answer']);
	$summary = $myts->makeTareaData4Save($HTTP_POST_VARS['summary']);
	$uid = $xoopsUser->uid();
	$datesub = time();
	$submit = 0;
	
    $result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix("faqtopics")." (catID, question, answer, summary, uid, datesub, submit) VALUES ('$cat', '$question', '$answer', '$summary', '$uid', '$datesub', '$submit')");
    	
	if ($result) {
 	   	$xoopsMailer =& getMailer();
       	$xoopsMailer->useMail();
       	$xoopsMailer->setToEmails($xoopsConfig['adminmail']);
       	$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
       	$xoopsMailer->setFromName($xoopsConfig['sitename']);
       	$xoopsMailer->setSubject(_MD_NOTIFYSBJCT);
       	$body = _MD_NOTIFYMSG;
       	$body .= "\n\n"._MD_TITLE.": ".$question;
       	$body .= "\n"._MD_POSTEDBY.": ".XoopsUser::getUnameFromId($uid);
        $body .= "\n"._MD_DATE.": ".formatTimestamp(time(), 'm', $xoopsConfig['default_TZ']);
        $body .= "\n\n".XOOPS_URL.'/modules/wffaq/admin/submissions.php?op=allow&t=$topicID&c=$catID';
        $xoopsMailer->setBody($body);
        $xoopsMailer->send();
     } else {
        redirect_header("submit.php",2,_MD_ERRORSAVINGDB);
     }
	
	redirect_header("index.php",2,_MD_SUBMITUSER);
    exit();
	break;


case 'form':
default:
   
        include XOOPS_ROOT_PATH.'/header.php';
        $result = $xoopsDB->query("SELECT catID, name FROM ".$xoopsDB->prefix("faqcategories")." ORDER BY name");
		$options = "";
            while ($query_data = mysql_fetch_array($result, MYSQL_ASSOC))
            {
                $options .= "<option value=\"" . $query_data["catID"] . "\">" . $query_data["name"] . "</option>";
            }
		
		$question = '';
        $answer = '';
		$summary = '';
        $catid = 1;
		//$uid = 0;
		$noname = 0;
        $nohtml = 0;
        $nosmiley = 0;
        $notifypub = 1;
		include 'include/storyform.inc.php';
  		include XOOPS_ROOT_PATH.'/footer.php';
        break;
}
?>