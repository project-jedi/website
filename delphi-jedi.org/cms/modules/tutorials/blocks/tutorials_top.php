<?php
/******************************************************************************
 * Function: b_tutorials_top_show
 * Input   : $options[0] = date for the most recent links
 *                    hits for the most popular tutorials
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayes
 * Output  : Returns the desired most recent or most popular tutorials
 ******************************************************************************/
function b_tutorials_top_show($options) {
        global $db, $framebrowse, $myts;
        include_once XOOPS_ROOT_PATH."/modules/tutorials/cache/config.php";
        $block = array();
        $block['content'] = "<div style=\"text-align:left;\"><small>";

        $result = $db->query("select tid, tname, tlink, codes, status, hits, date from ".$db->prefix("tutorials")." WHERE status=1 or status=3 ORDER BY ".$options[0]." DESC",$options[1],0);
        while (list($tid, $tname, $tlink, $codes, $status, $hits, $date) = $db->fetch_row($result) ) {
                $tname = $myts->makeTboxData4Show($tname);
                if ( !XOOPS_USE_MULTIBYTES ){
                        if (strlen($tname) >= 19) {
                                $tname = substr($tname,0,18)."...";
                        }
                }
                if ($tlink != "") {
                        if ($framebrowse == 1 || $codes >= 10) {
                                $link_url = "".XOOPS_URL."/modules/tutorials/viewexttutorial.php?tid=$tid";
                                $link_target = "";
                        } else {
                                $link_url = $tlink;
                                $link_target = " target=\"_blank\"";
                        }
                        $block['content'] .= "&nbsp;<strong><big>&middot;</big></strong>&nbsp;<a href=\"".$link_url."\" ".$link_target.">".$tname."</a>";
                } else {
                        $block['content'] .= "&nbsp;<strong><big>&middot;</big></strong>&nbsp;<a href=\"".XOOPS_URL."/modules/tutorials/viewtutorial.php?tid=$tid\">$tname</a>";
                }
            $count = 7;        //7 days
                $startdate = (time()-(86400 * $count));
            if ($startdate < $time) {
                        if($status==1){
                                $block['content'] .= "&nbsp;<img src=\"".XOOPS_URL."/modules/tutorials/images/newred.gif\" />";
                        } elseif ($status==3){
                                $block['content'] .= "&nbsp;<img src=\"".XOOPS_URL."/modules/tutorials/images/update.gif\" />";
                       }
            }
                if($options[0] == "date"){
                        $block['content'] .= "&nbsp;<small>(".formatTimestamp($date,"s").")</small><br />";
                        $block['title'] = _MB_BLOCK_TITLE1;
                }elseif($options[0] == "hits"){
                        $block['content'] .= "&nbsp;<small>(".$hits.")</small><br />";
                        $block['title'] = _MB_BLOCK_TITLE2;
                }
        }
    $block['content'] .= "</small></div>";
        return $block;
}


function b_tutorials_top_edit($options) {

        $form = ""._MB_TUTORIALS_DISP."&nbsp;";
        $form .= "<input type='hidden' name='options[]' value='";
        if($options[0] == "date"){
                $form .= "date'";
        }else {
                $form .= "hits'";
        }
        $form .= " />";
        $form .= "<input type='text' name='options[]' value='".$options[1]."' />&nbsp;"._MB_TUTORIALS_TUTS."";
        return $form;
}
?>