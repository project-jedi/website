<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:                                                                 //
// myPHPNUKE Web Portal System - http://myphpnuke.com/                       //
// PHP-NUKE Web Portal System - http://phpnuke.org/                          //
// Thatware - http://thatware.org/                                           //
//                                                                           //
// modified for e-xoops                                                      //
// http://www.e-xoops.de                                                     //
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

include_once("admin_header.php");

/*********************************************************/
/* mymenu Grabber to put other sites news in our site */
/*********************************************************/

function MyMenuAdmin() {
        global $db, $xoopsConfig, $xoopsModule, $myts;
        xoops_cp_header();
        OpenTable();

        echo "<big><b>"._AM_TITLE."</big></b>";

        //*********** Menueintrag hinzufügen ******************************************************
        echo "<h4 style='text-align:left;'>"._AM_ADDMENUITEM."</h4>
        <form action='index.php' method='post'>
        <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
        <tr>
        <td class='bg2'>
                <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr>
                <td class='bg3'><b>"._AM_POS."</b></td>
                <td class='bg1'><input type='text' name='xposition' size='4' maxlength='4' />&nbsp&nbsp&nbsp(0000-9999)</td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_ITEMNAME."</b></td>
                <td class='bg1'><input type='text' name='itemname' size='50' maxlength='60' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_INDENT."</b></td>
                <td class='bg1'><input type='text' name='indent' size='2' maxlength='2' value='0' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_FONT."</b></td>
                <td class='bg1'>
                <input type='radio' checked name='bold' value='0'>"._AM_NORMAL."
                <input type='radio'         name='bold' value='1'>"._AM_BOLD."
                </td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_ITEMURL."</b></td>
                <td class='bg1'><input type='text' name='itemurl' size='50' maxlength='100' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_MARGIN."</b></td>
                <td class='bg1'><input type='text' name='margin' size='12' maxlength='12' value='0pt' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_MEMBERSONLY."</b></td>
                <td class='bg1'>
                <input type='radio' checked name='membersonly' value='1'>"._AM_MEMBERS."
                <input type='radio'         name='membersonly' value='0'>"._AM_ALL."
                </td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_STATUS."</b></td>
                <td class='bg1'>
                <input type='radio' checked name='status' value='1'>"._AM_ACTIVE."
                <input type='radio'         name='status' value='0'>"._AM_INACTIVE."
                </td>
                </tr>
                <tr>
                <td class='bg3'>&nbsp;</td>
                <td class='bg1'><input type='hidden' name='fct' value='mymenu' /><input type='hidden' name='op' value='MyMenuAdd' /><input type='submit' value='"._AM_ADD."' /></td>
                </tr>
                </table>
        </td>
        </tr>
        </table>
        </form>
        <br />";

        //*********** Menueintrag ändern/löschen ******************************************************
        echo "<h4 style='text-align:left;'>"._AM_CHANGEMENUITEM."</h4>
        <form action='index.php' method='post'>
        <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
        <tr>
        <td class='bg2'>
                <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr class='bg3'>
                <td><b>"._AM_POS_SHORT."</b></td>
                <td><b>"._AM_ITEMNAME."</b></td>
                <td><b>"._AM_INDENT_SHORT."</b></td>
                <td><b>"._AM_MARGIN_SHORT."</b></td>
                <td><b>"._AM_ITEMURL."</b></td>
                <td><b>"._AM_MEMBERSONLY_SHORT."</b></td>
                <td><b>"._AM_STATUS."</b></td>
                <td><b>"._AM_FUNCTION."</b></td>";
                $result = $db->query("SELECT menuid, position, itemname, indent, margin, itemurl, bold, membersonly, status FROM ".$db->prefix("mymenu")." ORDER BY position");
                //$myts =& MyTextSanitizer::getInstance();
                while ( list($menuid, $position, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status) = $db->fetch_row($result) ) {
                        $itemname = $myts->makeTboxData4Show($itemname);
                        $itemurl = $myts->makeTboxData4Show($itemurl);
                        echo "<tr class='bg1'><td align='right'>$position</td>";

                        if ($bold == 1) {
                                 echo "<td><b>$itemname</b></td>";
                        } else {
                                 echo "<td>$itemname</td>";
                        }
                        echo "<td>$indent</td>";
                        echo "<td>$margin</td>";
                        echo "<td>$itemurl</td>";
                        if ( $membersonly == 1 ) {
                                echo "<td>"._AM_YES."</td>";
                        } else {
                                echo "<td> </td>";
                        }
                        if ( $status == 1 ) {
                                echo "<td>"._AM_ACTIVE."</td>";
                        } else {
                                echo "<td>"._AM_INACTIVE."</td>";
                        }
                echo "<td><a href='index.php?op=MyMenuEdit&amp;menuid=$menuid'>"._AM_EDIT."</a> | <a href='index.php?op=MyMenuDel&amp;menuid=$menuid&amp;ok=0'>"._AM_DELETE."</a></td>
                </tr>";
                }
                echo "</table>
        </td>
        </tr>
        </table>
        </form>";

        CloseTable();
}

function MyMenuEdit($menuid) {
        global $db, $xoopsConfig, $xoopsModule, $myts;
        xoops_cp_header();
        $result = $db->query("SELECT position, itemname, indent, margin, itemurl, bold, membersonly, status FROM ".$db->prefix("mymenu")." WHERE menuid=$menuid");
        list($xposition, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status) = $db->fetch_row($result);
        //$myts =& MyTextSanitizer::getInstance();
        $itemname  = $myts->makeTboxData4Edit($itemname);
        $itemurl   = $myts->makeTboxData4Edit($itemurl);
        OpenTable();
        echo "<big><b>"._AM_TITLE."</big></b>
        <h4 style='text-align:left;'>"._AM_EDITMENUITEM."</h4>
        <form action='index.php' method='post'>
        <input type='hidden' name='menuid' value='$menuid' />
        <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
        <tr>
        <td class='bg2'>
                <table width='100%' border='0' cellpadding='4' cellspacing='1'>
                <tr>
                <td class='bg3'><b>"._AM_POS."</b></td>
                <td class='bg1'><input type='text' name='xposition' size='4' maxlength='4' value='$xposition' />&nbsp&nbsp&nbsp(0000-9999)</td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_ITEMNAME."</b></td>
                <td class='bg1'><input type='text' name='itemname' size='50' maxlength='60' value='$itemname' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_INDENT."</b></td>
                <td class='bg1'><input type='text' name='indent' size='2' maxlength='2' value='$indent' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_FONT."</b></td>
                <td class='bg1'>";
                if( $bold == 1 ) {
                       $checked_bold = "checked";  $checked_normal = "";
        } else {
                       $checked_normal = "checked";$checked_bold = "";
               }
                echo "
                <input type='radio' $checked_normal name='bold' value='0'>"._AM_NORMAL."
                <input type='radio' $checked_bold   name='bold' value='1'>"._AM_BOLD."
                </td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_ITEMURL."</b></td>
                <td class='bg1'><input type='text' name='itemurl' size='50' maxlength='100' value='$itemurl' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_MARGIN."</b></td>
                <td class='bg1'><input type='text' name='margin' size='12' maxlength='12' value='$margin' /></td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_MEMBERSONLY."</b></td>
                <td class='bg1'>";
                if( $membersonly == 1 ) {
                           $checked_members  = "checked";$checked_allusers = "";
               } else {
                           $checked_allusers = "checked";$checked_members   = "";
                      }
                echo "
                <input type='radio' $checked_members  name='membersonly' value='1'>"._AM_MEMBERS."
                <input type='radio' $checked_allusers name='membersonly' value='0'>"._AM_ALL."
                </td>
                </tr>
                <tr>
                <td class='bg3'><b>"._AM_STATUS."</b></td>
                <td class='bg1'>";
                if( $status == 1 ) {
                           $checked_active   = "checked";$checked_inactive = "";
               } else {
                           $checked_inactive = "checked";$checked_active   = "";
                      }
                echo "
                <input type='radio' $checked_active   name='status' value='1'>"._AM_ACTIVE."
                <input type='radio' $checked_inactive name='status' value='0'>"._AM_INACTIVE."
                </td>
                </tr>
                <tr>
                <td class='bg3'>&nbsp;</td>
                <td class='bg1'><input type='hidden' name='fct' value='mymenu' /><input type='hidden' name='op' value='MyMenuSave' /><input type='submit' value='"._AM_SAVECHANG."' /></td>
                </tr>
                </table>
        </td>
        </tr>
        </table>
        </form>";
        CloseTable();
}

function MyMenuSave($menuid, $xposition, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status) {
        global $db, $myts;
        //$myts =& MyTextSanitizer::getInstance();
    $itemname  = $myts->makeTboxData4Save(trim($itemname));
    $itemurl   = $myts->makeTboxData4Save(trim($itemurl));
        $db->query("UPDATE ".$db->prefix("mymenu")." SET position=$xposition, itemname='$itemname', indent=$indent, margin='$margin', itemurl='$itemurl', bold=$bold, membersonly=$membersonly, status=$status WHERE menuid=$menuid");
        redirect_header("index.php?op=MyMenuAdmin",1,_AM_DBUPDATED);
        exit();
}

function MyMenuAdd($xposition, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status) {
        global $db, $myts;
        //$myts =& MyTextSanitizer::getInstance();
    $itemname  = $myts->makeTboxData4Save(trim($itemname));
    $itemurl   = $myts->makeTboxData4Save(trim($itemurl));
        $newid = $db->genId($db->prefix("mymenu")."_menuid_seq");
    $db->query("INSERT INTO ".$db->prefix("mymenu")." (menuid, position, itemname, indent, margin, itemurl, bold, membersonly, status) VALUES ($newid, $xposition, '$itemname', $indent, '$margin', '$itemurl', $bold, $membersonly, $status)");
    redirect_header("index.php?op=MyMenuAdmin",1,_AM_DBUPDATED);
    exit();
}

function MyMenuDel($menuid, $ok=0) {
        global $db, $xoopsConfig, $xoopsModule, $myts;
        if ( $ok == 1 ) {
                $db->query("DELETE FROM ".$db->prefix(mymenu)." WHERE menuid=$menuid");
                redirect_header("index.php?op=MyMenuAdmin",1,_AM_DBUPDATED);
                exit();
        } else {
                xoops_cp_header();
                OpenTable();
                $result = $db->query("SELECT position, itemname, indent, margin, itemurl, bold, membersonly, status FROM ".$db->prefix("mymenu")." WHERE menuid=$menuid");
                list($position, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status) = $db->fetch_row($result);
                echo "<big><b>"._AM_TITLE."</big></b>";
                echo "<h4 style='text-align:left;'>"._AM_DELETEMENUITEM."</h4>
                <form action='index.php' method='post'>
                <input type='hidden' name='menuid' value='$menuid' />
                <table border='0' cellpadding='0' cellspacing='0' valign='top' width='100%'>
                        <tr>
                        <td class='bg2'>
                        <table width='100%' valign='top' border='0' cellpadding='4' cellspacing='1'>
                                <tr>
                                <td class='bg3' width='30%'><b>"._AM_POS."</b></td>
                                <td class='bg1'>".$position."</td>
                                </tr>
                                <tr>
                                <td class='bg3'><b>"._AM_ITEMNAME."</b></td>
                                <td class='bg1'>".$itemname."</td>
                                </tr>
                                <tr>
                                <td class='bg3'><b>"._AM_ITEMURL."</b></td>
                                <td class='bg1'>".$itemurl."</td>
                                </tr>
                        </table>
                        </td>
                        </tr>
                </table>
                </form>";
                echo "<table valign='top'><tr>";
                echo "<td width='30%'valign='top'><span style='color:#ff0000;'><b>"._AM_WANTDEL."</b></span></td>";
                echo "<td width='3%'>\n";
                echo myTextForm("index.php?op=MyMenuDel&menuid=$menuid&ok=1", _AM_YES);
                echo "</td><td>\n";
                echo myTextForm("index.php?op=MyMenuAdmin", _AM_NO);
                echo "</td></tr></table>\n";
                CloseTable();
        }
}

switch($op) {
        case "MyMenuDel":
                MyMenuDel($menuid, $ok);
                break;
        case "MyMenuAdd":
                MyMenuAdd($xposition, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status);
                break;
        case "MyMenuSave":
                MyMenuSave($menuid, $xposition, $itemname, $indent, $margin, $itemurl, $bold, $membersonly, $status);
                break;
        case "MyMenuAdmin":
                MyMenuAdmin();
                break;
        case "MyMenuEdit":
                MyMenuEdit($menuid);
                break;
        default:
                MyMenuAdmin();
                break;
}
xoops_cp_footer();
?>