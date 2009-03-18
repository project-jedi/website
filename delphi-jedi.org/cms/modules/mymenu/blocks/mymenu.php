<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
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
function block_MYMENU_show() {
        global $db, $xoopsUser;
        $block = array();
        $block['title'] = _MB_MYMENU_TITLE;
        $block['content'] = "<p style='margin-top: 3pt; margin-bottom: 0pt; line-height:100%'>";
        $result = $db->query("SELECT position, indent, itemname, margin, itemurl, bold, membersonly, status FROM ".$db->prefix("mymenu")." ORDER BY position");
        while (list($position, $indent, $itemname, $margin, $itemurl, $bold, $membersonly, $status) = $db->fetch_row($result)) {
                if ( $status == 1 ) {
                        if ($xoopsUser or $membersonly == 0) {
                                if ($bold == 1) {
                                         $block['content'] .= "<b><p style='margin-left: $indent; margin-top: 0; margin-bottom: $margin; line-height:100%'><a href='$itemurl'>$itemname</a></b><br>";
                                } else {
                                        $block['content'] .= "<p style='margin-left: $indent; margin-top: 0; margin-bottom: $margin; line-height:100%'><a href='$itemurl'>$itemname</a><br>";
                                }
                        }
                }
        }
        return $block;
}
?>