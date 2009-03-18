<?php
/* 
* $Id: functions.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/


function generatecjump()
{
    global $PHP_SELF, $tbprefix, $xoopsDB;

    $result = $xoopsDB->query("SELECT catID, name FROM ".$xoopsDB->prefix("faqcategories")."");

    if ($xoopsDB->fetchRow($result) == 1)
    {
        return "&nbsp;";
    }

    $html = "<form method=\"post\">";
    $html .= "<select name=\"cjump\" onchange=\"jumpMenu(this)\">";
    $html .= "<option value=\"index.php\">Category Jump:</option>";
    $html .= "<option value=\"index.php\">--------</option>";

    while($query_data = mysql_fetch_array($result))
	{
        $html .= "<option value=\"index.php?op=cat&c=" . $query_data["catID"] . "\">" . $query_data["name"] . "</option>";
    }

    $html .= "</select>";
    $html .= "</form>";

    return $html;
}


function faqlinks() {
	echo "<table width='100%' border='0' cellspacing='1' cellpadding='2' class = outer>";
	echo "<tr><th class = 'bg3' colspan = '3'>"._AM_FADMINHEAD."</th></tr>";
	echo "<tr>";
	echo " <td class = 'even'><a href='index.php?op=default'>"._AM_FNEWFAQ."</a></td>";
	echo " <td class = 'odd'>"._AM_FNEWFAQTXT."</td>";
	echo "</tr>";
	echo "<tr>";
	echo " <td width='24%' class = 'even'><a href='category.php?op=default'>"._AM_FNEWCAT."</a></td>";
	echo " <td class = 'odd'>"._AM_FNEWCATTXT."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class = 'even'><a href='./submissions.php?op=cat'>"._AM_FVAL."</a></td>";
	echo "<td class = 'odd'>"._AM_FVALTXT."</td>";
	echo "</tr>";
	echo "</table>";
}
function wffaqfooter() {

echo "<br /><div style='text-align:center'>"._AM_VISITSUPPORT."</div>";

}

?>