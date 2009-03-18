<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.1 viewexttutorials.php                                         //
//                                                                                                                             //
//        Author: Thomas (Todi) Wolf                                                                     //
//        Mail:        todi@dark-side.de                                                                     //
//        Homepage: http://www.mytutorials.info                                             //
//                                                                                                                             //
//        for Xoops                                                                                                //
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

include "header.php";

        $xoopsOption['show_rblock'] = 0;
        include XOOPS_ROOT_PATH."/header.php";
        $tid = $HTTP_GET_VARS['tid'];
           $db->queryF("update ".$db->prefix("tutorials")." set hits=hits+1 where tid=$tid ");
    OpenTable();
        $result = $db->query("select tlink from ".$db->prefix("tutorials")." where tid=$tid ");
    list($tlink) = $db->fetch_row($result);
    $tlinkstring = $tlink;
        if(strlen($tlinkstring)>50)$tlinkstring=substr($tlinkstring,0,50).'...';
    echo "<font size=\"1\"><b>To open the page in a new Window use this link: </b><a href=\"".$tlink."\" target=\"blank\">".$tlinkstring."</a></font><hr>";
        echo "<iframe SRC=\"".$tlink."\" WIDTH=\"100%\"  HEIGHT=\"1200\"  FRAMESPACING=0 FRAMEBORDER=no  BORDER=0 SCROLLING=auto></iframe>";
    CloseTable();
    include XOOPS_ROOT_PATH."/footer.php";

?>