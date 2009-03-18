<?php
// $Id: banners.php,v 1.11 2003/10/04 08:38:37 okazu Exp $
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

include "mainfile.php";
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);

/********************************************/
/* Function to let your client login to see */
/* the stats                                */
/********************************************/
function clientlogin()
{
	global $xoopsDB, $xoopsLogger, $xoopsConfig;
	include("header.php");
//	OpenTable();
	echo "<style type='text/css'>
				body {background-color : #fcfcfc;color: #000000;font-weight: normal;font-size: 12px;font-family: Trebuchet MS,Verdana, Arial, Helvetica, sans-serif;margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;}
				.redirect {width: 70%;margin: 110px;text-align: center;padding: 15px;text-align:center;border: #e0e0e0 1px solid;color: #666666;background-color: #f6f6f6;text-align: center;}
				.redirect a:link {color: #666666;text-decoration: none;font-weight: bold;}
				.redirect a:visited {color: #666666;text-decoration: none;font-weight: bold;}
				.redirect a:hover {color: #999999;text-decoration: underline;font-weight: bold;}
				hr {height: 3px;border: 3px #E18A00 solid;filter : Alpha(Opacity=100,FinishOpacity=10,Style=2);width: 95%;}
				font.bigtext { font-size: 16px; font-weight: bold; }
		</style>

    <form action='banners.php' method='post'>
	<table width='100%' class='redirect'>
    <tr><td colspan='2' align='center'>
	<b>Advertising Statistics</b><hr /></td></tr>
	<tr><td align='right'><b>Login: </b></td>
		<td><input class='textbox' type='text' name='login' size='12' maxlength='10' /></td></tr>
	<tr><td align='right'><b>Password: </b></td>
		<td><input class='textbox' type='password' name='pass' size='12' maxlength='10' /></td></tr>
	<tr><td align='center' colspan='2'><input class='textbox' type='hidden' name='op' value='Ok' />
		<input type='submit' value='Login'></td></tr>
	<tr><td colspan='2' align='center'><hr />Please type your client information</td></tr>
	</table></form>";
//	CloseTable();
	include "footer.php";
}

/*********************************************/
/* Function to display the banners stats for */
/* each client                               */
/*********************************************/
function bannerstats($login, $pass)
{
	global $xoopsDB, $xoopsConfig, $xoopsLogger;
	$myts =& MyTextSanitizer::getInstance();
	$result = $xoopsDB->query("SELECT cid, name, passwd FROM ".$xoopsDB->prefix("bannerclient")." WHERE login='$login'");
    list($cid, $name, $passwd) = $xoopsDB->fetchRow($result);

    if ( $login=="" AND $pass=="" OR $pass=="" ) {
		redirect_header("banners.php",2,"Login Incorrect!!!");
		//include("footer.php");
		exit();
    } else {
		if ( $pass==$passwd ) {
			include "header.php";
//			OpenTable();
    		echo "<style type='text/css'>
    		             .b_td {color: #ffffff; background-color: #2F5376; padding: 3px; text-align: center;}
                  </style>
            <h4 style='text-align:center;'><b>Current Active Banners for $name</b><br /></h4>
			<table width='100%' border='0'><tr>
                <td class='b_td'><b>ID</b></td>
				<td class='b_td'><b>Imp. Made</b></td>
				<td class='b_td'><b>Imp. Total</b></td>
				<td class='b_td'><b>Imp. Left</b></td>
				<td class='b_td'><b>Clicks</b></td>
				<td class='b_td'><b>% Clicks</b></td>
				<td class='b_td'><b>Functions</b></td><tr>";
			$result = $xoopsDB->query("select bid, imptotal, impmade, clicks, date from ".$xoopsDB->prefix("banner")." where cid=$cid");
    		while ( list($bid, $imptotal, $impmade, $clicks, $date) = $xoopsDB->fetchRow($result) ) {
				if ( $impmade == 0 ) {
					$percent = 0;
        		} else {
					$percent = substr(100 * $clicks / $impmade, 0, 5);
				}
				if ( $imptotal == 0 ) {
					$left = "Unlimited";
				} else {
					$left = $imptotal-$impmade;
				}
				echo "<td align='center'>$bid</td>
                <td align='center'>$impmade</td>
				<td align='center'>$imptotal</td>
				<td align='center'>$left</td>
				<td align='center'>$clicks</td>
				<td align='center'>$percent%</td>
        		<td align='center'><a href='banners.php?op=EmailStats&amp;login=$login&amp;pass=$pass&amp;cid=$cid&amp;bid=$bid'>E-mail Stats</a></td><tr>";
			}
			echo "</table><br /><br /><div>Following are your running Banners in ".$xoopsConfig['sitename']." </div><br /><br />";
			$result = $xoopsDB->query("select bid, imageurl, clickurl, htmlbanner, htmlcode from ".$xoopsDB->prefix("banner")." where cid=$cid");
    		while ( list($bid, $imageurl, $clickurl,$htmlbanner, $htmlcode) = $xoopsDB->fetchRow($result) ) {
				$numrows = $xoopsDB->getRowsNum($result);
				if ($numrows>1) {
					echo "<hr /><br />";
				}
				if ($htmlbanner){
					echo $myts->displayTarea($htmlcode,1);
				}else{
					if(strtolower(substr($imageurl,strrpos($imageurl,".")))==".swf") {
						echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/ swflash.cab#version=6,0,40,0\"; width=\"468\" height=\"60\">";
						echo "<param name=movie value=\"$imageurl\">";
						echo "<param name=quality value=high>";
						echo "<embed src=\"$imageurl\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"; type=\"application/x-shockwave-flash\" width=\"468\" height=\"60\">";
						echo "</embed>";
						echo "</object>";
					} else {
						echo "<img src='$imageurl' border='1' alt='' />";
					}
				}
				echo"Banner ID: $bid<br />
				Send <a href='banners.php?op=EmailStats&amp;login=$login&amp;cid=$cid&amp;bid=$bid&amp;pass=$pass'>E-Mail Stats</a> for this Banner<br />";
				if (!$htmlbanner){
					echo "This Banners points to <a href='$clickurl'>this URL</a><br />
					<form action='banners.php' method='submit'>
					Change URL: <input class='textbox' type='text' name='url' size='50' maxlength='200' value='$clickurl' />
					<input class='textbox' type='hidden' name='login' value='$login' />
					<input class='textbox' type='hidden' name='bid' value='$bid' />
					<input class='textbox' type='hidden' name='pass' value='$pass' />
					<input class='textbox' type='hidden' name='cid' value='$cid' />
					<input type='submit' name='op' value='Change' /></form>";
				}
			}
//    		CloseTable();

			/* Finnished Banners */
			echo "<br />";
//			OpenTable();
    		if(!$result = $xoopsDB->query("select bid, impressions, clicks, datestart, dateend from ".$xoopsDB->prefix("bannerfinish")." where cid=$cid")){
			echo "<h4 style='text-align:center;'>Banners Finished for $name</h4><br />
			<table width='100%' border='0'><tr>
    		<td class='b_td'><b>ID</b></td>
    		<td class='b_td'><b>Impressions</b></td>
    		<td class='b_td'><b>Clicks</b></td>
    		<td class='b_td'><b>% Clicks</b></td>
    		<td class='b_td'><b>Start Date</b></td>
    		<td class='b_td'><b>End Date</b></td></tr>";
    		while ( list($bid, $impressions, $clicks, $datestart, $dateend) = $xoopsDB->fetchRow($result) ) {
				$percent = substr(100 * $clicks / $impressions, 0, 5);
				echo "<tr><td align='center'>$bid</td>
				<td align='center'>$impressions</td>
        		<td align='center'>$clicks</td>
				<td align='center'>$percent%</td>
        		<td align='center'>".formatTimestamp($datestart)."</td>
        		<td align='center'>".formatTimestamp($dateend)."</td></tr>";
			}
//			CloseTable();
			echo "</table>";
			}
			include "footer.php";
		} else {
			redirect_header("banners.php",2,"Login Incorrect!!!");
			//include("footer.php");
			exit();
		}
	}
}

/*********************************************/
/* Function to let the client E-mail his     */
/* banner Stats                              */
/*********************************************/
function EmailStats($login, $cid, $bid, $pass)
{
	global $xoopsDB, $xoopsConfig;
	$result2 = $xoopsDB->query("select name, email from ".$xoopsDB->prefix("bannerclient")." where cid=$cid");
    list($name, $email) = $xoopsDB->fetchRow($result2);
    if ( $email == "" ) {
		redirect_header("banners.php",3,"There isn't an email associated with client ".$name.".<br />Please contact the Administrator");
		//include "footer.php";
		exit();
	} else {
		$result = $xoopsDB->query("select bid, imptotal, impmade, clicks, imageurl, clickurl, date from ".$xoopsDB->prefix("banner")." where bid=$bid and cid=$cid");
		list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = $xoopsDB->fetchRow($result);
        if ( $impmade == 0 ) {
			$percent = 0;
        } else {
			$percent = substr(100 * $clicks / $impmade, 0, 5);
        }

        if ( $imptotal == 0 ) {
			$left = "Unlimited";
	    	$imptotal = "Unlimited";
        } else {
			$left = $imptotal-$impmade;
        }
		$fecha = date("F jS Y, h:iA.");
		$subject = "Your Banner Statistics at ".$xoopsConfig[sitename]."";
		$message = "Following are the complete stats for your advertising investment at ". $xoopsConfig['sitename']." :\n\n\nClient Name: $name\nBanner ID: $bid\nBanner Image: $imageurl\nBanner URL: $clickurl\n\nImpressions Purchased: $imptotal\nImpressions Made: $impmade\nImpressions Left: $left\nClicks Received: $clicks\nClicks Percent: $percent%\n\n\nReport Generated on: $fecha";
		$xoopsMailer =& getMailer();
		$xoopsMailer->useMail();
		$xoopsMailer->setToEmails($email);
		$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
		$xoopsMailer->setFromName($xoopsConfig['sitename']);
		$xoopsMailer->setSubject($subject);
		$xoopsMailer->setBody($message);
		$xoopsMailer->send();
		redirect_header("banners.php?op=Ok&amp;login=$login&amp;pass=$pass",3,"Statistics for your banner has been sent to your email address.");
		//include "footer.php";
		exit();
	}
}

/*********************************************/
/* Function to let the client to change the  */
/* url for his banner                        */
/*********************************************/
function change_banner_url_by_client($login, $pass, $cid, $bid, $url)
{
	global $xoopsDB;
    $result = $xoopsDB->query("select passwd from ".$xoopsDB->prefix("bannerclient")." where cid=".$cid."");
    list($passwd) = $xoopsDB->fetchRow($result);
    if ( $pass == $passwd ) {
		$xoopsDB->queryF("update ".$xoopsDB->prefix("banner")." set clickurl='".$url."' where bid=".$bid."");
    }
	redirect_header("banners.php?op=Ok&amp;login=$login&amp;pass=$pass",3,"URL has been changed.");
	//include "footer.php";
	exit();
}

function clickbanner($bid)
{
	global $xoopsDB;
	$bresult = $xoopsDB->query("select clickurl from ".$xoopsDB->prefix("banner")." where bid=$bid");
	list($clickurl) = $xoopsDB->fetchRow($bresult);
    $xoopsDB->queryF("update ".$xoopsDB->prefix("banner")." set clicks=clicks+1 where bid=$bid");
	echo "<html><head><meta http-equiv='Refresh' content='0; URL=".$clickurl."'></head><body></body></html>";
	exit();
}

switch ( $op ) {
case "click":
	clickbanner($bid);
	break;
case "login":
	clientlogin();
	break;
case "Ok":
	bannerstats($login, $pass);
	break;
case "Change":
	change_banner_url_by_client($login, $pass, $cid, $bid, $url);
	break;
case "EmailStats":
	EmailStats($login, $cid, $bid, $pass);
	break;
default:
	clientlogin();
	break;
}

?>
