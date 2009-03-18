<?php
/****************************************************************************
 * Function: b_waiting_show                                                                                                        *
 *                                                                                                                                                         *
 * Output  : Returns all tutorials, they are not activated                                        *
 ***************************************************************************/

function b_waiting_show() {
        global $db, $myts;
        $block = array();
        $block['title'] = _MB_BLOCK_TITLE3;
        $block['content'] = "<div style=\"text-align:left;\">";
        $result = $db->query("select tid, tname from ".$db->prefix("tutorials")." WHERE status=0 or status=2 order by date");
        $num = $db->num_rows($result);
        $block['content'] .= "<strong><big>&middot;</big></strong>&nbsp;Waiting Tutorials:&nbsp;$num<br />";
        while (list($tid, $tname) = $db->fetch_row($result) ) {
                $tname = $myts->makeTboxData4Show($tname);
                if ( !XOOPS_USE_MULTIBYTES ){
                        if (strlen($tname) >= 19) {
                                $tname = substr($tname,0,18)."...";
                        }
                }
                $block['content'] .= "<strong><big>-</big></strong>&nbsp;<a href=\"".XOOPS_URL."/modules/tutorials/admin/index.php?op=waitTutorial&tid=$tid\">$tname</a><br /></div> ";
        }
        return $block;
}

?>