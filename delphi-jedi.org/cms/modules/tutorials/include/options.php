<?php
//-------------------------------------------------------------------------- //
//  Tutorials Version 2.0 Options Functions  			                     //
//												                             //
//	Author: Thomas (Todi) Wolf					                             //
//	Mail:	todi@dark-side.de					                             //
//	Homepage: http://www.mytutorials.info		                             //
//												                             //
//	for Xoops RC3								                             //
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
include("../../../mainfile.php");
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include("../language/".$xoopsConfig['language']."/main.php");
} else {
	include("../language/english/main.php");
}
	xoops_header(false);
			echo "<script type=\"text/javascript\">\n"
				."<!--\n"
				."function doCode(addCode) {\n"
				."var currentMessage = window.opener.xoopsGetElementById(\"".$target."\").value;\n"
				."window.opener.xoopsGetElementById(\"".$target."\").value=currentMessage+addCode;\n"
				."return;\n"
				."}\n"
				."</script>\n\n";
			echo "<style>
			#Options {
				background-color:#cccccc;
				border:1px solid #000;
				font-family:Tahoma,Sans-serif;
				font-size:12px;
				padding-left:8pt;
				padding-top:4pt;
				adding-right:8pt;
				padding-bottom:4pt;
			}
			</style>\n\n";
			echo "</head><body bgcolor=#b0b0c0>\n";
			echo "<center><h4>"._MD_TAGOPTIONS."</h4><b>"._MD_TAGINSERT."</b><br><br>";
    		echo "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id='options'>";
    		echo "<tr bgcolor=\"#eeeeee\"><th align=\"left\">"._MD_TAG."</th><th align=\"left\">"._MD_DESCRIPTION."</th></tr>";
			if ($target == "xcategory_visualize") {
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[title]\");'>[title]</a></td><td>"._MD_CTITLETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[subcat]\");'>[subcat]</a></td><td>"._MD_CSUBCAT."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image]\");'>[image]</a></td><td>"._MD_CIMAGETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image left]\");'>[image left]</a></td><td>"._MD_CIMAGETAGL."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image right]\");'>[image right]</a></td><td>"._MD_CIMAGETAGR."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[description]\");'>[description]</a></td><td>"._MD_CDESCRIPTIONTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[count]\");'>[count]</a></td><td>"._MD_COUNTTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[link]\");'>[link]</a></td><td>"._MD_CLINKTAG."</td></tr>";
			} elseif ($target == "xtutorial_visualize") {
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[title]\");'>[title]</a></td><td>"._MD_TTITLETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image]\");'>[image]</a></td><td>"._MD_TIMAGETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image left]\");'>[image left]</a></td><td>"._MD_TIMAGETAGL."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image right]\");'>[image right]</a></td><td>"._MD_TIMAGETAGR."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[description]\");'>[description]</a></td><td>"._MD_TDESCRIPTIONTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[author]\");'>[author]</a></td><td>"._MD_AUTHORTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[date]\");'>[date]</a></td><td>"._MD_DATETAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[hits]\");'>[hits]</a></td><td>"._MD_HITSTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[rating]\");'>[rating]</a></td><td>"._MD_RATINGTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[votes]\");'>[votes]</a></td><td>"._MD_VOTESTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[ratethis]\");'>[ratethis]</a></td><td>"._MD_RATETAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[link]\");'>[link]</a></td><td>"._MD_TLINKTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[print]\");'>[print]</a></td><td>"._MD_TPRINTTAG."</td></tr>";
			} else {
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[title]\");'>[title]</a></td><td>"._MD_TTITLETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image]\");'>[image]</a></td><td>"._MD_TIMAGETAG."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image left]\");'>[image left]</a></td><td>"._MD_TIMAGETAGL."</td></tr>";
    			echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[image right]\");'>[image right]</a></td><td>"._MD_TIMAGETAGR."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[author]\");'>[author]</a></td><td>"._MD_AUTHORTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[content]\");'>[content]</a></td><td>"._MD_CONTENTTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[date]\");'>[date]</a></td><td>"._MD_DATETAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[hits]\");'>[hits]</a></td><td>"._MD_HITSTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[rating]\");'>[rating]</a></td><td>"._MD_RATINGTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[votes]\");'>[votes]</a></td><td>"._MD_VOTESTAG."</td></tr>";
				echo "<tr><td><a href='javascript:justReturn()' onclick='doCode(\"[ratethis]\");'>[ratethis]</a></td><td>"._MD_RATETAG."</td></tr>";
			}
    		echo "</table><br />";
			echo "<a href=\"javascript:self.close()\"><font color=\"blue\">Close Window</font></a></center>";
	xoops_footer();
?>