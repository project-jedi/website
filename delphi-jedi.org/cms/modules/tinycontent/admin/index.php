<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include_once "admin_header.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

if (isset($HTTP_GET_VARS)) {
    foreach ($HTTP_GET_VARS as $k => $v) {
      $$k = $v;
    }
  }

  if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
      $$k = $v;
    }
  }

// ------------------------------------------------------------------------- //
// Switch Statement for the different operations                             //
// ------------------------------------------------------------------------- //
$xoopsDB =& Database::getInstance();
global $op;
switch ($op) {

// ------------------------------------------------------------------------- //
// Show Content Page -> Overview                                             //
// ------------------------------------------------------------------------- //
case "show":
        global $xoopsDB;
        $myts =& MyTextSanitizer::getInstance();
    xoops_cp_header();

        echo "<form action='index.php' method='post'>";
        echo "<h4>"._TC_ADMINTITLE."</h4><table border='0' cellpadding='0' cellspacing='1' width='100%' class='outer'>";
        echo "<tr class='even'><td><b>"._TC_STORYID."</b></td><td><b>"._TC_HOMEPAGE."</b></td><td><b>"._TC_LINKNAME."</b></td>";
        echo "<td><b>"._TC_LINKID."</b></td><td><b>"._TC_VISIBLE."</b></td><td><b>"._COMMENTS."</b></td>";
        echo "<td><b>"._TC_ACTION."</b></td></tr>";

    $result = $xoopsDB->query("SELECT storyid, blockid, title, visible, homepage, nocomments, link FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." ORDER BY blockid");

    while ($tcontent = $xoopsDB->fetcharray($result)) {
      $title=$myts->makeTboxData4Show($tcontent['title'], 0, 0, 0);

          if ($tcontent['visible'] == '1') {
           $check1 = "";
           $check2 = "selected='selected'";
          } else {
           $check1 = "selected='selected'";
           $check2 = "";
          }

          if ($tcontent['homepage'] == '1') {
           $check3 = "checked='checked'";
          } else {
           $check3 = "";
          }

          if ($tcontent['nocomments'] == '1') {
           $check4 = "selected='selected'";
           $check5 = "";
          } else {
           $check4 = "";
           $check5 = "selected='selected'";
          }

          echo "<tr class='odd'><td>".$tcontent['storyid']."</td>";
          echo "<td><input type='radio' name='homepage[]' value='".$tcontent['storyid']."' ".$check3."></td>";
          echo "<td><a href='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/index.php?storyid=".$tcontent['storyid']."'>".$title."</a></td>";
          echo "<td><input type='hidden' name='id[]' value='".$tcontent['storyid']."' /><input type='text' name='blockid[".$tcontent['storyid']."]' size='2' maxlength='2' value='".$tcontent['blockid']."'/></td>";
          echo "<td><select name='visible[".$tcontent['storyid']."]'><option value='0' ".$check1." />"._TC_NO."</option><option value='1' ".$check2." />"._TC_YES."</option></select></td>";
          echo "<td><select name='nocomments[".$tcontent['storyid']."]'><option value='1' ".$check4." />"._TC_NO."</option><option value='0' ".$check5." />"._TC_YES."</option></select></td>";
            if ($tcontent['link'] == '1') {
              echo "<td><a href='index.php?op=elink&id=".$tcontent['storyid']."'>"._TC_EDIT."</a>";
            } else {
              echo "<td><a href='index.php?op=edit&id=".$tcontent['storyid']."'>"._TC_EDIT."</a>";
            }
          echo " | <a href='index.php?op=delete&id=".$tcontent['storyid']."'>"._TC_DELETE."</a></td></tr>";

      }
          echo "</table>";

          echo "<br /><div align='center'>
               <input type='hidden' name='op' value='update' />
                  <input type='submit' name='submit' value="._SUBMIT." /></div>";
      echo "</form>";

          xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Show add content Page                                                     //
// ------------------------------------------------------------------------- //
case "submit":
        xoops_cp_header();

        echo "<h4>"._TC_ADMINTITLE."</h4>";

        $form = new XoopsThemeForm(""._TC_ADDCONTENT."", "form_name", "index.php");
        $text_box = new XoopsFormText(""._TC_LINKNAME."", "title", 50, 30);
        $form->addElement($text_box);

        $visible_checkbox = new XoopsFormCheckBox(""._TC_VISIBLE."", 'visible');
          $visible_checkbox->addOption(1, ""._TC_YES."");
        $form->addElement($visible_checkbox);

        $t_area = new XoopsFormDhtmlTextArea(""._TC_CONTENT."", 'message', '', 37, 35);
        $form->addElement($t_area);

        $option_tray = new XoopsFormElementTray(_OPTIONS,'<br />');
          $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', 0);
          $nohtml_checkbox->addOption(1, _DISABLEHTML);
            $option_tray->addElement($nohtml_checkbox);
          $smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', 0);
          $smiley_checkbox->addOption(1, _DISABLESMILEY);
            $option_tray->addElement($smiley_checkbox);
          $comments_checkbox = new XoopsFormCheckBox('', 'nocomments', 0);
          $comments_checkbox->addOption(1, _TC_DISABLECOM);
            $option_tray->addElement($comments_checkbox);
        $form->addElement($option_tray);
        $add = "add";
        $form->addElement(new XoopsFormHidden('op', $add));
        $submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
        $form->addElement($submit);
        $form->display();

        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Add a content to database                                                 //
// ------------------------------------------------------------------------- //
case "add":
        $myts =& MyTextSanitizer::getInstance();

        $title=$myts->makeTboxData4Save($title);
        $message=$myts->makeTareaData4Save($message);

        $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)."");
        $rows = mysql_num_rows($result);

        if ($rows == 0) {
          $hp = 1;
        } else {
          $hp = 0;
        }

        $sqlinsert="INSERT INTO ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." (title,text,visible,homepage,nohtml,nosmiley,nocomments,link) VALUES ('".$title."','".$message."','".intval($visible)."','".$hp."','".intval($nohtml)."','".intval($nosmiley)."','".intval($nocomments)."','0')";
        if ( !$result = $xoopsDB->query($sqlinsert) )
                        {
                                echo _TC_ERRORINSERT;
                        }
        redirect_header("index.php",2,_TC_DBUPDATED);
        break;

// ------------------------------------------------------------------------- //
// Update Content -> Show Content Page                                       //
// ------------------------------------------------------------------------- //
case "update":

        foreach ($id as $storyid) {

        if ($storyid == intval($homepage[0])) {
            $hp = 1;
          } else {
            $hp = 0;
          }

          $sqlinsert="UPDATE ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." SET blockid='".intval($blockid[$storyid])."',visible='".intval($visible[$storyid])."',homepage='".$hp."',nocomments='".intval($nocomments[$storyid])."' WHERE storyid='".intval($storyid)."'";
        if ( !$result = $xoopsDB->query($sqlinsert) )
                        {
                                echo _TC_ERRORINSERT;
                        }

        }
        redirect_header("index.php?op=show",2,_TC_DBUPDATED);

        break;

// ------------------------------------------------------------------------- //
// Show Edit Content Page                                                    //
// ------------------------------------------------------------------------- //
case "edit":
        global $xoopsDB;
        $myts =& MyTextSanitizer::getInstance();
        xoops_cp_header();

    $result = $xoopsDB->query("SELECT storyid,title,text,visible,nohtml,nosmiley,nocomments FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." WHERE storyid=".intval($id));

    list($storyid,$title,$text,$visible,$nohtml,$nosmiley,$nocomments) = $xoopsDB->fetchRow($result);

        $title=$myts->makeTboxData4Edit($title);
    $message=$myts->makeTareaData4Edit($text);

        echo "<h4>"._TC_ADMINTITLE."</h4>";

        $form = new XoopsThemeForm(""._TC_EDITCONTENT."", "form_name", "index.php");
        $text_box = new XoopsFormText(""._TC_LINKNAME."", "title", 50, 30, $title);
        $form->addElement($text_box);

        $visible_checkbox = new XoopsFormCheckBox(""._TC_VISIBLE."", 'visible', $visible);
          $visible_checkbox->addOption(1, ""._TC_YES."");
        $form->addElement($visible_checkbox);

        $t_area = new XoopsFormDhtmlTextArea(""._TC_CONTENT."", 'message', $message, 37, 35);
        $form->addElement($t_area);

        $option_tray = new XoopsFormElementTray(_OPTIONS,'<br />');
          $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);
          $nohtml_checkbox->addOption(1, _DISABLEHTML);
            $option_tray->addElement($nohtml_checkbox);
          $smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);
          $smiley_checkbox->addOption(1, _DISABLESMILEY);
            $option_tray->addElement($smiley_checkbox);
          $comments_checkbox = new XoopsFormCheckBox('', 'nocomments', $nocomments);
          $comments_checkbox->addOption(1, _TC_DISABLECOM);
            $option_tray->addElement($comments_checkbox);
        $form->addElement($option_tray);
        $editit = "editit";
        $form->addElement(new XoopsFormHidden('op', $editit));
        $form->addElement(new XoopsFormHidden('id', $storyid));
        $submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
        $form->addElement($submit);
        $form->display();

        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Do the edit of the Content                                                //
// ------------------------------------------------------------------------- //
case "editit":
        $myts =& MyTextSanitizer::getInstance();

        $title=$myts->makeTboxData4Save($title);
        $message=$myts->makeTareaData4Save($message);

        $sqlinsert="UPDATE ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." SET title='".$title."',text='".$message."',visible='".intval($visible)."',nohtml='".intval($nohtml)."',nosmiley='".intval($nosmiley)."',nocomments='".intval($nocomments)."' WHERE storyid='".intval($id)."'";
        if ( !$result = $xoopsDB->query($sqlinsert) )
                        {
                                echo _TC_ERRORINSERT;
                        }
        redirect_header("index.php?op=show",2,_TC_DBUPDATED);

        break;

// ------------------------------------------------------------------------- //
// Show new link Page                                                        //
// ------------------------------------------------------------------------- //
case "nlink":
        xoops_cp_header();

        echo "<h4>"._TC_ADMINTITLE."</h4>";

        $dir=XOOPS_ROOT_PATH."/modules/"._MI_DIR_NAME."/content/";
        if(!eregi("777",decoct(fileperms($dir)))) {
          echo"<font color='FF0000'><h4>"._TC_PERMERROR."</h4></font>";
        }

// Upload File
        echo "<form name='form_name2' id='form_name2' action='index.php' method='post' enctype='multipart/form-data'>";
        echo "<table cellspacing='1' width='100%' class='outer'>";
        echo "<tr><th colspan='2'>"._TC_ULFILE."</th></tr>";
        echo "<tr valign='top' align='left'><td class='head'>"._TC_SFILE."</td><td class='even'><input type='file' name='fileupload' id='fileupload' size='30' /></td></tr>";
        echo "<tr valign='top' align='left'><td class='head'><input type='hidden' name='MAX_FILE_SIZE' id='op' value='500000' /><input type='hidden' name='op' id='op' value='upload' /></td><td class='even'><input type='submit' name='submit' value='"._TC_UPLOAD."' /></td></tr>";
        echo "</table>";
    echo "</form>";

// Delete File
        $form = new XoopsThemeForm(""._TC_DELFILE."", "form_name", "index.php");

        $address_select = new XoopsFormSelect(""._TC_URL."", "address");
    $folder = dir("../content/");
        while($file = $folder->read()) {
      if ($file != "." && $file != "..") {
             $address_select->addOption($file, "".$file."");
          }
        }
    $folder->close();
        $form->addElement($address_select);

        $delfile = "delfile";
        $form->addElement(new XoopsFormHidden('op', $delfile));
        $submit = new XoopsFormButton("", "submit", _TC_DELETE, "submit");
        $form->addElement($submit);
        $form->display();

// Add PageWrap
        $form = new XoopsThemeForm(""._TC_ADDLINK."", "form_name", "index.php");
        $text_box = new XoopsFormText(""._TC_LINKNAME."", "title", 50, 30);
        $form->addElement($text_box);

        $visible_checkbox = new XoopsFormCheckBox(""._TC_VISIBLE."", 'visible', 1);
          $visible_checkbox->addOption(1, ""._TC_YES."");
        $form->addElement($visible_checkbox);

        $address_select = new XoopsFormSelect(""._TC_URL."", "address");
    $folder = dir("../content/");
        while($file = $folder->read()) {
      if ($file != "." && $file != "..") {
             $address_select->addOption($file, "".$file."");
          }
        }
    $folder->close();
        $form->addElement($address_select);

        $comments_checkbox = new XoopsFormCheckBox(_TC_DISABLECOM, 'nocomments', 0);
          $comments_checkbox->addOption(1, ""._TC_YES."");
        $form->addElement($comments_checkbox);

        $addlink = "addlink";
        $form->addElement(new XoopsFormHidden('op', $addlink));
        $submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
        $form->addElement($submit);
        $form->display();

        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Upload File                                                //
// ------------------------------------------------------------------------- //
case "upload":

        $uploadpath=XOOPS_ROOT_PATH."/modules/"._MI_DIR_NAME."/content/";
        $source=$_FILES[fileupload][tmp_name];
        $fileupload_name=$_FILES[fileupload][name];
        if ( ($source != 'none') && ($source != '' )) {
          $dest=$uploadpath.$fileupload_name;
          if(file_exists($uploadpath.$fileupload_name)) {
            redirect_header("index.php",2,_TC_ERRORUPL);
          } else {
            if (copy($source, $dest)) {
              redirect_header("index.php",2,_TC_UPLOADED);
            } else {
                  redirect_header("index.php",2,_TC_ERRORUPL);
            }
          unlink ($source);
          }
        }

        break;

// ------------------------------------------------------------------------- //
// Delete File - Confirmation Question                                    //
// ------------------------------------------------------------------------- //
case "delfile":
        xoops_cp_header();
        xoops_confirm(array('address' => $address, 'op' => 'delfileok'), 'index.php', _TC_RUSUREDELF, _YES);
        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Delete it definitely                                                      //
// ------------------------------------------------------------------------- //
case "delfileok":
        $dir=XOOPS_ROOT_PATH."/modules/"._MI_DIR_NAME."/content/";
        @unlink($dir."/".$address);
    redirect_header("index.php",2,_TC_FDELETED);
        break;

// ------------------------------------------------------------------------- //
// Add a PageWrap to database                                                //
// ------------------------------------------------------------------------- //
case "addlink":
        $myts =& MyTextSanitizer::getInstance();

        $title=$myts->makeTboxData4Save($title);
        $address=$myts->makeTboxData4Save($address);

        $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)."");
        $rows = mysql_num_rows($result);

        if ($rows == 0) {
          $hp = 1;
        } else {
          $hp = 0;
        }

        $sqlinsert="INSERT INTO ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." (title,text,visible,homepage,nohtml,nosmiley,nocomments,link,address) VALUES ('".$title."','0','".intval($visible)."','".$hp."','0','0','".intval($nocomments)."','1','".$address."')";
        if ( !$result = $xoopsDB->query($sqlinsert) )
                        {
                                echo _TC_ERRORINSERT;
                        }
        redirect_header("index.php",2,_TC_DBUPDATED);

        break;

// ------------------------------------------------------------------------- //
// Show Edit Link Page                                                       //
// ------------------------------------------------------------------------- //
case "elink":
        global $xoopsDB;
        $myts =& MyTextSanitizer::getInstance();
        xoops_cp_header();

    $result = $xoopsDB->query("SELECT storyid,title,visible,nocomments,address FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." WHERE storyid=".intval($id));

    list($storyid,$title,$visible,$nocomments,$address) = $xoopsDB->fetchRow($result);

        $title=$myts->makeTboxData4Edit($title);
        $address=$myts->makeTboxData4Edit($address);

        if ($visible == 1) {
           $check1 = "checked";
          } else {
           $check1 = "";
          }

        if ($nocomments == 1) {
           $check2 = "checked";
          } else {
           $check2 = "";
          }

        echo "<h4>"._TC_ADMINTITLE."</h4>";
        echo "<form name='form_name' id='form_name' action='index.php' method='post'>";
        echo "<table cellspacing='1' width='100%' class='outer'>";
        echo "<tr><th colspan='2'>"._TC_EDITLINK."</th></tr>";
        echo "<tr valign='top' align='left'><td class='head'>"._TC_LINKNAME."</td><td class='even'><input type='text' name='title' id='title' size='50' maxlength='30' value='".$title."' /></td></tr>";
        echo "<tr valign='top' align='left'><td class='head'>"._TC_VISIBLE."</td><td class='even'><input type='checkbox' name='visible' value='1' ".$check1." /></td></tr>";
        echo "<tr valign='top' align='left'><td class='head'>"._TC_URL."</td><td class='even'><select name='address' size='1' id='address'>";

   $folder = dir("../content/");
        while($file = $folder->read()) {
      if ($file != "." && $file != "..") {
             if ($file == $address) {
                   echo "<option value='".$file."' selected>".$file."</option>";
                 } else {
                   echo "<option value='".$file."'>".$file."</option>";
                 }
          }
        }

        echo "</select></td></tr>";
        echo "<tr valign='top' align='left'><td class='head'>"._TC_DISABLECOM."</td><td class='even'><input type='checkbox' name='nocomments' value='1' ".$check2." /></td></tr>";
        echo "<tr valign='top' align='left'><td class='head'><input type='hidden' name='id' value='".$storyid."' /><input type='hidden' name='op' id='op' value='linkeditit' /></td><td class='even'><input type='submit' name='submit' value='"._SUBMIT."' /></td></tr>";
        echo "</table>";
    echo "</form>";

/*
        $form = new XoopsThemeForm(""._TC_EDITLINK."", "form_name", "index.php");
        $text_box = new XoopsFormText(""._TC_LINKNAME."", "title", 50, 30, $title);
        $form->addElement($text_box);

        $visible_checkbox = new XoopsFormCheckBox(""._TC_VISIBLE."", 'visible', $visible);
          $visible_checkbox->addOption(1, ""._TC_YES."");
        $form->addElement($visible_checkbox);

//        $address_box = new XoopsFormText(""._TC_URL."", "address", 50, 255, $address);
//        $form->addElement($address_box);

        $address_select = new XoopsFormSelect(""._TC_URL."", "address");
    $folder = dir("../content/");
        while($file = $folder->read()) {
      if ($file != "." && $file != "..") {
             if ($file == $address) {
                   $address_select->addOption($file, "".$file."", $file);
                 } else {
                   $address_select->addOption($file, "".$file."");
                 }
          }
        }
    $folder->close();
        $form->addElement($address_select);


        $linkeditit = "linkeditit";
        $form->addElement(new XoopsFormHidden('op', $linkeditit));
        $form->addElement(new XoopsFormHidden('id', $storyid));
        $submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
        $form->addElement($submit);
        $form->display();
*/

        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Do the edit of the PageWrap                                               //
// ------------------------------------------------------------------------- //
case "linkeditit":
        $myts =& MyTextSanitizer::getInstance();

        $title=$myts->makeTboxData4Save($title);
        $address=$myts->makeTboxData4Save($address);

        $sqlinsert="UPDATE ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." SET title='".$title."',visible='".intval($visible)."',nocomments='".intval($nocomments)."',address='".$address."' WHERE storyid='".intval($id)."'";
        if ( !$result = $xoopsDB->query($sqlinsert) )
                        {
                                echo _TC_ERRORINSERT;
                        }
        redirect_header("index.php?op=show",2,_TC_DBUPDATED);

        break;

// ------------------------------------------------------------------------- //
// Delete Content - Confirmation Question                                    //
// ------------------------------------------------------------------------- //
case "delete":
        xoops_cp_header();
        xoops_confirm(array('id' => intval($id), 'op' => 'deleteit'), 'index.php', _TC_RUSUREDEL, _YES);
        xoops_cp_footer();
        break;

// ------------------------------------------------------------------------- //
// Delete it definitely                                                      //
// ------------------------------------------------------------------------- //
case "deleteit":
        global $xoopsDB;
    $result=$xoopsDB->query("DELETE FROM ".$xoopsDB->prefix(_MI_TINYCONTENT_PREFIX)." WHERE storyid=".intval($id));
        xoops_comment_delete($xoopsModule->getVar('mid'), $id);
        redirect_header("index.php?op=show",1,_TC_DBUPDATED);
        break;

// ------------------------------------------------------------------------- //
// Admin menu: displayed after click on module logo                          //
// ------------------------------------------------------------------------- //
default:
        xoops_cp_header();
    echo "<h4>"._TC_ADMINTITLE."</h4><table width='100%' border='0' cellspacing='1' class='outer'>";
        echo "<tr><td class='odd'> - <b><a href='index.php?op=submit'>"._TC_MD_ADMENU1."</a></b>";
        echo "<br /><br />";
        echo " - <b><a href='index.php?op=nlink'>"._TC_MD_ADMENU2."</a></b>";
        echo "<br /><br />";
        echo " - <b><a href='index.php?op=show'>"._TC_MD_ADMENU3."</a></b></td></tr></table>";
        xoops_cp_footer();
        break;
}
?>