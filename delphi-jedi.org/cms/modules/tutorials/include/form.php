<?php

        if(isset($gid)){
                $xgid=$gid;
        }
        if ($cimg != '') {
                if (ereg("http://",$cimg)) {
                        $imgpath = $cimg;
                } else {
                        $imgpath = "".IMAGE_URL."/$cimg";
                }
        }
        $imagesize = GetImageSize($imgpath);
        $imagewidth = $imagesize[0];
        $imageheight = $imagesize[1];

        if ($timg != '' && $timgwidth == 0) $timgwidth = $imagewidth;
        if ($timg != '' && $timgheight == 0) $timgheight = $imageheight;

?>
<!--xoopsCode start-->
<script type="text/javascript">
<!--
var backup_timgwidth = <?php echo $timgwidth; ?>;
var backup_timgheight = <?php echo $timgheight; ?>;
var timgwidthmax = <?php echo $imgwidth; ?>;
var timgheightmax = <?php echo $imgheight; ?>;

function calcPicsize(nr){
        var faktor = 0;
        var buffer = 0;
        if (nr == 1) {
                for(i=0;i<document.tutorialform.timgwidth.value.length;++i)
                   if (document.tutorialform.timgwidth.value.charAt(i) < "0" || document.tutorialform.timgwidth.value.charAt(i) > "9")
                document.tutorialform.timgwidth.value = backup_timgwidth;

                if (document.tutorialform.timgwidth.value < 0){
                        document.tutorialform.timgwidth.value = backup_timgwidth;
                }
                if (document.tutorialform.timgwidth.value > timgwidthmax){
                        document.tutorialform.timgwidth.value = timgwidthmax;
                }
                faktor = (document.tutorialform.timgwidth.value * 1) / backup_timgwidth;
                buffer =  Math.round(backup_timgheight * faktor);
                if (buffer > timgheightmax) {
                        document.tutorialform.timgheight.value = timgheightmax;
                        faktor = (document.tutorialform.timgheight.value * 1) / backup_timgheight;
                        document.tutorialform.timgwidth.value = Math.round(backup_timgwidth * faktor);
                } else {
                        document.tutorialform.timgheight.value = buffer;
                }
        }
        if (nr == 2) {
                for(i=0;i<document.tutorialform.timgheight.value.length;++i)
                   if (document.tutorialform.timgheight.value.charAt(i) < "0" || document.tutorialform.timgheight.value.charAt(i) > "9")
                document.tutorialform.timgheight.value = backup_timgheight;

                if (document.tutorialform.timgheight.value < 0){
                        document.tutorialform.timgheight.value = backup_timgheight;
                }
                if (document.tutorialform.timgheight.value > timgheightmax){
                        document.tutorialform.timgheight.value = timgheightmax;
                }
                faktor = (document.tutorialform.timgheight.value * 1) / backup_timgheight;
                buffer =  Math.round(backup_timgwidth * faktor);
                if (buffer > timgwidthmax) {
                        document.tutorialform.timgwidth.value = timgwidthmax;
                        faktor = (document.tutorialform.timgwidth.value * 1) / backup_timgwidth;
                        document.tutorialform.timgheight.value = Math.round(backup_timgheight * faktor);
                } else {
                        document.tutorialform.timgwidth.value = buffer;
                }
        }
        backup_timgwidth = (document.tutorialform.timgwidth.value * 1);
        backup_timgheight = (document.tutorialform.timgheight.value * 1);
        document.timage.width = backup_timgwidth;
        document.timage.height = backup_timgheight;
}

function setorgsize() {
        document.tutorialform.timgwidth.value = <?php echo $imagewidth; ?>;
        document.tutorialform.timgheight.value = <?php echo $imageheight; ?>;
        backup_timgwidth = (document.tutorialform.timgwidth.value * 1);
        backup_timgheight = (document.tutorialform.timgheight.value * 1);
        document.timage.width = backup_timgwidth;
        document.timage.height = backup_timgheight;
}
//-->
</script>
<?PHP
        echo "<form name=\"tutorialform\" action=\"$scriptname\" method=\"post\">";
        echo "<table width=600 border=0 cellspacing=8 align=center>";


        echo "<tr><td><fieldset style=\"padding:5px;\"><legend><b>$cname</b></legend>";
        if ($imgpath != "") {
                echo "<img src=\"".$imgpath."\" align=right>";
        }
        echo "$cdesc<br></fieldset></td></tr>";

        if (($imgsubdirexists == 0 && $scriptname == "index.php") || ($imgsubdirexists == 0 && $useruploads == 1 && $scriptname == "submit.php")) {
                echo "<tr><td>";
                echo _MD_QUESTDIR."<input type=\"checkbox\" name=\"maketdir\" value=\"1\" onclick=\"submit();\"><br>";
                echo "</td></tr>";
        }

        echo "<tr><td>";
        if(isset($tid)){
                echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\">";
            echo _MD_CATEGORYC;
            $mytree->makeMySelBox("cname", "cname", "$cid", 0, "cid");
                echo "</td></tr>";
        } else {
                echo "<input type=\"hidden\" name=\"tid\" value=\"0\">";
                echo "<input type=\"hidden\" name=\"cid\" value=\"$cid\">";
        }
        echo "<input type=\"hidden\" name=\"dir\" value=\"$dir\">";
        echo "<input type=\"hidden\" name=\"time\" value=\"$time\">";
        echo "<input type=\"hidden\" name=\"hits\" value=\"$hits\">";
        echo "<input type=\"hidden\" name=\"rating\" value=\"$rating\">";
        echo "<input type=\"hidden\" name=\"votes\" value=\"$votes\">";

    $result2=$db->query("select count(*) from ".$db->prefix("tutorials_groups")." where cid=$cid");
        list($numrows)=$db->fetch_row($result2);
    if ($numrows>0) {
                echo "<tr><td>"._MD_TGROUP."<select name='gid'>";
                if ($xgid == 0) {
                        echo "<option value='$xgid' selected>"._MD_NOGROUPSELELECT."</option>";
                } else {
                        echo "<option value='$xgid'>"._MD_NOGROUPSELELECT."</option>";
                }
                $result3 = $db->query("select gid, gname from ".$db->prefix(tutorials_groups)." where cid=$cid");
                while(list($gid, $gname) = $db->fetch_row($result3)) {
                        $gname=$myts->makeTboxData4Show($gname);
                        if($xgid==$gid) {
                                echo "<option value='$gid' selected>$gname</option>";
                        } else {
                                echo "<option value='$gid'>$gname</option>";
                        }
                }
                echo "</select></td></tr>";
        }
?>
<!--xoopsCode start-->
<script type="text/javascript">
<!--
function xoopsCodePagebreak(id){
        var dom = xoopsGetElementById(id);
        dom.value += "[pagebreak]"
        dom.focus();
}
//-->
</script>
<?PHP
// Tutorials Form -----------------------//
        echo "<tr><td>"._MD_TNAME."<br><input type=\"text\" name=\"tname\" size=\"60\" value=\"";
        if(isset($tname)){
                echo $tname;
        }
        echo "\"></td></tr>";
        echo "<tr><td>"._MD_TAUTHOR."<br><input type=\"text\" name=\"tauthor\" size=\"60\" value=\"";
        if(isset($tauthor)){
                echo $tauthor;
        }
        echo "\"></td></tr>";
        echo "<tr><td>"._MD_TIMAGE."<br><input type=\"text\" id=\"timg\" name=\"timg\" size=\"60\" maxlength=\"150\" value=\"";
        if(isset($timg)){
                echo $timg;
        }
        echo "\">\n";
        if ($scriptname == "index.php"){
                if ($imgdirexists == 1) {
                        echo "<input type='button' value='Upload' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/modules/tutorials/upload.php?img_path=$img_path&target=timg&logo=1&target2=timgwidth&target3=timgheight\",\"upload\",450,450);' />\n";
                        echo "<br>"._MD_EG.": ".IMAGE_URL."/";
                } else {
                        echo "<br /><font color=\"#ff0000\">"._MD_DIRNOTEXISTS."</font>";
                }
        } else {
                if ($imgdirexists == 1 && $useruploads == 1) {
                        echo "<input type='button' value='Upload' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/modules/tutorials/upload.php?img_path=$img_path&target=timg&logo=1&target2=timgwidth&target3=timgheight\",\"upload\",450,450);' />\n";
                        echo "<br>"._MD_EG.": ".IMAGE_URL."/";
                } elseif ($imgdirexists == 0 && $useruploads == 1) {
                        echo "<br /><font color=\"#ff0000\">"._MD_DIRNOTEXISTS."</font>";
                } else {
                        echo "<br />http://www.domain.de/images/pic.gif";
                }
        }
        echo "</td></tr>";
        echo "<tr><td>";

        if (isset($timgwidth) && isset($timgheight) && $timgwidth > 0 && $timgheight > 0) {
                echo _MD_IMGWIDTH."&nbsp;<input type=\"text\" id=\"timgwidth\" name=\"timgwidth\" size=\"6\" value=\"".$timgwidth."\" onchange='calcPicsize(1);'>\n";
                echo "&nbsp;&nbsp;"._MD_IMGHEIGHT."&nbsp;<input type=\"text\" id=\"timgheight\" name=\"timgheight\" size=\"6\" value=\"".$timgheight."\" onchange='calcPicsize(2);'>\n";
                echo "&nbsp;&nbsp;<input type=button value='Reset' onclick='setorgsize();'>\n";
        } else {
                echo "<input type=\"hidden\" id=\"timgwidth\" name=\"timgwidth\" value=\"\">\n";
                echo "<input type=\"hidden\" id=\"timgheight\" name=\"timgheight\" value=\"\">\n";
        }
        echo "</td></tr>";
        echo "<tr><td>"._MD_DESCRIPTION."<br><textarea name=tdesc rows=5 cols=50>";
        if(isset($tdesc)){
                echo $tdesc;
        }
        echo "</textarea></td></tr>";
        echo "<tr><td><hr><br>"._MD_TLINK." ("._MD_LINKINFO.")<br><input type=\"text\" name=\"tlink\" size=\"90\" value=\"";
        if(isset($tlink)){
                echo $tlink;
        }
        echo "\"><br><input type=\"checkbox\" name=\"framebrowse\" value=\"1\" ";
        if ($framebrowse == 1) {
                echo "checked=\"checked\"";
        }
        echo "> "._MD_FRAMEBROWSE."<br>";
        echo "<b><font color=red>"._MD_LINKWARNING."</font></b></td></tr>";
        echo "<tr><td><hr><br><b>"._MD_CONTENT."</b><br><br>\n";

        echo "<input type='button' value='URL' onclick='xoopsCodeUrl(\"tcont\");'/>\n";
        echo "<input type='button' value='EMAIL' onclick='xoopsCodeEmail(\"tcont\");'/>\n";
        echo "<input type='button' value='Insert Image' onclick='xoopsCodeImg(\"tcont\");'/>\n";
        if ($createdir == 1 || $imgsubdirexists == 1) {
#                echo "<input type='button' value='Insert Image' onclick='xoopsCodeImg(\"tcont\");'/>\n";
                echo "<input type='button' value='Upload & Insert Image' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/modules/tutorials/upload.php?img_path=$img_path2&target=tcont&logo=0\",\"upload\",450,450);'/>\n";
#        } else {
#                echo "<input type='button' value='Insert Image' onclick='xoopsCodeImg(\"tcont\");'/>\n";
        }
        echo "<input type='button' value='"._MD_NEXTPAGE."' onclick='xoopsCodePagebreak(\"tcont\");'/>\n";
        echo "<br />";
        $sizearray = array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
        echo "<select id='tcontSize' onchange='setVisible(\"tconthiddenText\");setElementSize(\"tconthiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='SIZE'>"._SIZE."</option>\n";
        foreach ( $sizearray as $size ) {
                echo "<option value='$size'>$size</option>\n";
        }
        echo "</select>\n";

        $fontarray = array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Tahoma", "Verdana");
        echo "<select id='tcontFont' onchange='setVisible(\"tconthiddenText\");setElementFont(\"tconthiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='FONT'>"._FONT."</option>\n";
        foreach ( $fontarray as $font ) {
                echo "<option value='$font'>$font</option>\n";
        }
        echo "</select>\n";

        $colorarray = array("00", "33", "66", "99", "CC", "FF");
        echo "<select id='tcontColor' onchange='setVisible(\"tconthiddenText\");setElementColor(\"tconthiddenText\",this.options[this.selectedIndex].value);'>\n";
        echo "<option value='COLOR'>"._COLOR."</option>\n";
        foreach ( $colorarray as $color1 ) {
                foreach ( $colorarray as $color2 ) {
                        foreach ( $colorarray as $color3 ) {
                                echo "<option value='".$color1.$color2.$color3."' style='background-color:#".$color1.$color2.$color3.";color:#".$color1.$color2.$color3.";'>#".$color1.$color2.$color3."</option>\n";
                        }
                }
        }
        echo "</select><span id='tconthiddenText'>"._EXAMPLE."</span>\n";

        echo "<br /><input type='checkbox' id='tcontBold' onclick='setVisible(\"tconthiddenText\");makeBold(\"tconthiddenText\");' /><b>B</b>&nbsp;<input type='checkbox' id='tcontItalic' onclick='setVisible(\"tconthiddenText\");makeItalic(\"tconthiddenText\");' /><i>I</i>&nbsp;<input type='checkbox' id='tcontUnderline' onclick='setVisible(\"tconthiddenText\");makeUnderline(\"tconthiddenText\");' /><u>U</u>&nbsp;&nbsp;<input type='textbox' id='tcontAddtext' size='20' />&nbsp;<input type='button' onclick='xoopsCodeText(\"tcont\")' value='"._ADD."'><br /><br /><textarea id='tcont' name='tcont' wrap='virtual' cols='80' rows='20'>";
        if(isset($tcont)){
                echo $tcont;
        }
        echo "</textarea><br />\n";


$desc = new XoopsFormDhtmlTextArea('', 'tcont');
echo $desc->renderSmileys();
	//xoopsSmilies("tcont");
	

        if ( !empty($xoopsConfig['allow_html']) ) {
                echo "<tr><td><p>"._MD_ALLOWEDHTML."<br />";
                echo get_allowed_html();
                echo "</p></td></tr>";
        }

        echo "<tr><td><input type=\"checkbox\" name=\"xsmiley\" value=\"1\"";
        if (!empty($xsmiley)) {
                 echo " checked=\"checked\"";
        }
        echo "> "._MD_DISSMILEY."</td></tr>";
        echo "<tr><td><input type=\"checkbox\" name=\"xhtml\" value=\"1\"";
        if (!empty($xhtml)) {
                 echo " checked=\"checked\"";
        }
        echo "> "._MD_DISHTML."</td></tr>";
        echo "<tr><td>";

        if ($scriptname == "index.php") {
                echo "<br><b>"._MD_ACTIVATED."</b><br>";
                if (isset($status)) {
                        if ($status == 1) {
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"1\" CHECKED>&nbsp;" ._MD_YES."&nbsp;</INPUT>";
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"0\">&nbsp;" ._MD_NO."&nbsp;</INPUT>";
                        } elseif ($status == 0) {
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"1\">&nbsp;" ._MD_YES."&nbsp;</INPUT>";
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"0\" CHECKED>&nbsp;" ._MD_NO."&nbsp;</INPUT>";
                        }
                        if ($status == 3) {
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"3\" CHECKED>&nbsp;" ._MD_YES."&nbsp;</INPUT>";
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"2\">&nbsp;" ._MD_NO."&nbsp;</INPUT>";
                        } elseif ($status == 2) {
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"3\">&nbsp;" ._MD_YES."&nbsp;</INPUT>";
                                echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"2\" CHECKED>&nbsp;" ._MD_NO."&nbsp;</INPUT>";
                        }
                } else {
                        echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"1\">&nbsp;" ._MD_YES."&nbsp;</INPUT>";
                        echo "<INPUT TYPE=\"RADIO\" NAME=\"status\" VALUE=\"0\" CHECKED>&nbsp;" ._MD_NO."&nbsp;</INPUT>";
                }
        }else {
                echo "<input type=\"hidden\" name=\"status\" value=\"$status\">";
        }
        echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
        echo "</td></tr>";
        echo "<tr><td><select name='op'>\n";
        echo "<option value='PreviewTutorial' selected='selected'>"._MD_PREVIEW."</option>\n";
        echo "<option value='SaveTutorial'>"._MD_SAVE."</option>\n";
        echo "</select>";
        echo "&nbsp;<input type='submit' value='"._MD_GO."' />\n";
        if (isset($timg) || isset($tlink) || isset($tcont) || isset($tname) || isset($tdesc)) {
                if (isset($tid)) {
                        echo "<input type='button' value='"._MD_BREAKOFF."' onclick=\"location='index.php'\">\n";
                        if ($scriptname == "index.php") {
                                echo "<input type='button' value='"._MD_CLEAR."' onclick=\"location='index.php?op=delTutorial&tid=$tid&ok=1'\">\n";
                        }
                } else {
                        echo "<input type='button' value='"._MD_BREAKOFF."' onclick=\"location='index.php'\">\n";
                }
        } else {
                echo "<input type='reset' value='"._MD_CLEAR."' />\n";
        }
        echo "</td></tr></table>";
        echo "</form>";

?>