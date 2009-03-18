<?php
/**
 * $Id: article.php v 1.5 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
 */

include( "header.php" );
include_once( XOOPS_ROOT_PATH . "/header.php" );

$op = '';

foreach ( $HTTP_POST_VARS as $k => $v )
{
    ${$k} = $v;
} 

foreach ( $HTTP_GET_VARS as $k => $v )
{
    ${$k} = $v;
} 

switch ( $op )
{
    case "refersend":

        include XOOPS_ROOT_PATH . "/class/xoopsmailer.php";

        Global $HTTP_POST_VARS, $myts, $xoopsUser;

        $result = $xoopsDB -> query( "SELECT * FROM " . $xoopsDB -> prefix( "wfsrefer" ) . "" );
        list( $titlerefer, $chanrefheadline, $submenuitem, $mainpage, $referpagelogo, $emailaddress, $usersblurb, $defblurb ) = $xoopsDB -> fetchrow( $result );

        $sname = ( !empty( $HTTP_POST_VARS['uname'] ) ) ? $myts -> htmlSpecialChars( $HTTP_POST_VARS['uname'] ) : $xoopsUser -> getVar( "email" );
        $semail = ( !empty( $HTTP_POST_VARS['email'] ) ) ? $myts -> htmlSpecialChars( $HTTP_POST_VARS['email'] ) : $xoopsUser -> getVar( "email" );
        $rname = $myts -> htmlSpecialChars( $HTTP_POST_VARS['runame'] );
        $remail = $myts -> htmlSpecialChars( $HTTP_POST_VARS['remail'] );

        $message = ( !empty( $defblurb ) ) ? $myts -> htmlSpecialChars( $defblurb ) : $myts -> htmlSpecialChars( $HTTP_POST_VARS['message'] );

        $subject = $sname . " " . _MD_MESSAGESUBECT;

        $xoopsMailer = & getMailer();
        $xoopsMailer -> useMail();

        $xoopsMailer -> setTemplateDir( XOOPS_ROOT_PATH . '/modules/wfchannel/language/' . $xoopsConfig['language'] . '/mail_template' );
        $xoopsMailer -> setTemplate( "refer.tpl" );

        $xoopsMailer -> assign( "SITENAME", $xoopsConfig['sitename'] );
        $xoopsMailer -> assign( "SITEURL", XOOPS_URL . "/" );
        $xoopsMailer -> assign( "TITLE", _MD_MESSAGETITLE );
        $xoopsMailer -> assign( "SUSER", $sname );
        $xoopsMailer -> assign( "RUSER", $rname );
        $xoopsMailer -> assign( "MESSAGE", $message );

        $xoopsMailer -> setToEmails( $remail );
        $xoopsMailer -> setFromEmail( $semail );
        $xoopsMailer -> setFromName( $sname );
        $xoopsMailer -> setSubject( $subject );
        $xoopsMailer -> send();

        redirect_header( "index.php", 1, _MD_EMAILSENT );
        exit();
        break;

    case "refer":

        $xoopsOption['template_main'] = 'wfchannel_refer.html';

        Global $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsUser;

        $usersblurb = '';

        $referfriend = array();
        $result = $xoopsDB -> query( "SELECT * FROM " . $xoopsDB -> prefix( "wfsrefer" ) . "" );
        list( $titlerefer, $chanrefheadline, $submenuitem, $mainpage, $referpagelogo, $emailaddress, $usersblurb, $defblurb, $smiley, $xcodes, $breaks, $html ) = $xoopsDB -> fetchrow( $result );

        $html = ( $html == 1 ) ? 0 : 1;
        $smiley = ( $smiley == 1 ) ? 0 : 1;
        $xcodes = ( $xcodes == 1 ) ? 0 : 1;
        $breaks = ( $breaks == 0 ) ? 1 : 0;

        $referfriend['textlink'] = $myts -> htmlSpecialChars( $titlerefer );
        $referfriend['chanrefheadline'] = $myts -> displayTarea( $chanrefheadline, $html, $smiley, $smiley, 1, $breaks );
        $referfriend['path'] = $xoopsModuleConfig['uploaddir'];
        $referfriend['linkpagelogo'] = ( $referpagelogo == "blank.png" || !$referpagelogo ) ? '' : $myts -> htmlSpecialChars( $referpagelogo );

        if ( $emailaddress && is_object( $xoopsUser ) )
        {
            $referfriend['uname'] = $xoopsUser -> getVar( 'uname' );
            $referfriend['emailaddy'] = $xoopsUser -> getVar( 'email' );
        } 

        if ( $usersblurb == 1 )
        {
            $referfriend['usersblurb'] = $myts -> htmlSpecialChars( $usersblurb );
            $referfriend['defblurb'] = $myts -> htmlSpecialChars( $defblurb );
        } 

        $xoopsTpl -> assign( 'referfriend', $referfriend );
        $xoopsTpl -> assign( 'lang_sendername' , _MD_SENDERNAME );
        $xoopsTpl -> assign( 'lang_senderemail' , _MD_SENDEREMAIL );
        $xoopsTpl -> assign( 'lang_recipname' , _MD_RECPINAME );
        $xoopsTpl -> assign( 'lang_reciptremail' , _MD_RECPIEMAIL );
        $xoopsTpl -> assign( 'lang_writeblurb' , _MD_WRITEBLURB );
        $xoopsTpl -> assign( 'lang_linktous' , $titlerefer );
        $xoopsTpl -> assign( 'lang_linkintro' , _MD_LINKINTRO );

        break;

    case "link":

        $xoopsOption['template_main'] = 'wfchannel_linktous.html';

        Global $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

        $linktous = array();

        $result = $xoopsDB -> query( "SELECT submenuitem, textlink, linkpagelogo, button, logo, banner, mainpage, newsfeed, newsfeedjs, newstitle FROM " . $xoopsDB -> prefix( "wfslinktous" ) . "" );
        list( $submenuitem, $textlink, $linkpagelogo, $button, $logo, $banner, $mainpage, $newsfeed, $newsfeedjs, $newstitle ) = $xoopsDB -> fetchrow( $result );

        $linktous['textlink'] = $myts -> htmlSpecialChars( $textlink );
        $linktous['path'] = $xoopsModuleConfig['linkimages'];
        $linktous['linkpagelogo'] = ( $linkpagelogo == "blank.png" || !$linkpagelogo ) ? '' : $myts -> htmlSpecialChars( $linkpagelogo );
        $linktous['button'] = ( $button == "blank.png" || !$button ) ? '' : $myts -> htmlSpecialChars( $button );
        $linktous['logo'] = ( $logo == "blank.png" || !$logo ) ? '' : $myts -> htmlSpecialChars( $logo );
        $linktous['logohtml'] = $myts -> makeTboxData4Show( $logohtml );
        $linktous['banner'] = ( $banner == "blank.png" || !$banner ) ? '' : $myts -> htmlSpecialChars( $banner );
        $linktous['sitename'] = $xoopsConfig['sitename'];
        $linktous['newsfeed'] = $myts -> htmlSpecialChars( $newsfeed );
        $linktous['newsfeedjs'] = $myts -> htmlSpecialChars( $newsfeedjs );
        $linktous['newstitle'] = $myts -> htmlSpecialChars( $newstitle );
        $linktous['xoops_url'] = XOOPS_URL;

        $xoopsTpl -> assign( 'linktous', $linktous );
        $xoopsTpl -> assign( 'lang_linktous' , $titlelink );
        $xoopsTpl -> assign( 'lang_linkintro' , _MD_LINKINTRO );
        $xoopsTpl -> assign( 'lang_textexample' , _MD_TEXTLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_buttonexample' , _MD_BUTTONLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_logoexample' , _MD_LOGOLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_bannerexample' , _MD_BANNERLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_newsfeedexample' , _MD_NEWSFEEDLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_newsfeedjsexample' , _MD_NEWSFEEDJSLINKEXAMPLE );
        $xoopsTpl -> assign( 'lang_displaytext' , _MD_DISPLAYTEXTLINK );
        $xoopsTpl -> assign( 'lang_displaybutton' , _MD_DISPLAYBUTTONLINK );
        $xoopsTpl -> assign( 'lang_displaylogo' , _MD_DISPLAYLOGOLINK );
        $xoopsTpl -> assign( 'lang_displaybanner' , _MD_DISPLAYBANNERLINK );
        $xoopsTpl -> assign( 'lang_displaynews' , _MD_DISPLAYNEWSLINK );
        $xoopsTpl -> assign( 'lang_displaynewsrss' , _MD_DISPLAYNEWSRSSLINK );
        $xoopsTpl -> assign( 'lang_displayjsnewsrss' , _MD_DISPLAYJSNEWSRSSLINK );
        $xoopsTpl -> assign( 'lang_displayscript' , _MD_DISPLAYSCRIPT );
        $xoopsTpl -> assign( 'lang_copyrightnotice' , _MD_COPYRIGHTNOTICE );
        break;

    case "default":
    default:

        Global $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $myts;

        $xoopsOption['template_main'] = 'wfchannel_index.html';

        $articletag = array();
        $chanlink = array();
        $total = array();
        $mainp = array();

        $result = $xoopsDB -> query( "SELECT CID FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE defaultpage = '1'" );
        list( $CID ) = $xoopsDB -> fetchrow( $result );

        $pagenum = isset( $HTTP_GET_VARS['pagenum'] ) ? intval( $HTTP_GET_VARS['pagenum'] ) : $CID;

        $result5 = $xoopsDB -> query( "SELECT * FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE CID = '$pagenum'" );
        list( $CID, $pagetitle, $pageheadline, $maintext, $weight, $html, $smiley, $xcodes, $breaks, $defaultpage, $indeximage, $htmlfile, $mainpage, $submenu, $created, $comments, $allowcomments ) = $xoopsDB -> fetchrow( $result5 );

        if ( !$result )
        {
            redirect_header( "index.php", 2, _MD_NOSTORY );
            exit();
        } 

        $html = ( $html ) ? 0 : 1;
        $smiley = ( $smiley ) ? 0 : 1;
        $xcodes = ( $xcodes ) ? 0 : 1;
        $breaks = ( $breaks ) ? 1 : 0;

        $articletag['isdefaultpage'] = $defaultpage;
        $articletag['headline'] = $myts -> htmlSpecialChars( $pageheadline );

        if ( !$htmlfile )
        {
            $articletag['maintext'] = $myts -> displayTarea( $maintext, $html, $smiley, $xcodes, 1, $breaks );
        } 
        else
        {
            $maintextfile = XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['htmluploaddir'] . "/" . $htmlfile;
            if ( file_exists( $maintextfile ) && false !== $fp = fopen( $maintextfile, 'r' ) )
            {
                $articletag['maintext'] = fread( $fp, filesize( $maintextfile ) );
                $articletag['maintext'] = $myts -> displayTarea( $articletag['maintext'], $html, $smiley, $xcodes, 1, $breaks );
                fclose( $fp );
            } 
        } 

        if ( $indeximage )
        {
            if ( file_exists( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['uploaddir'] . "/" . $myts -> htmlSpecialChars( $indeximage ) ) )
            {
                $articletag['indeximage'] = "<img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['uploaddir'] . "/" . $myts -> makeTboxData4Show( $indeximage ) . "' name='image' id='image' alt='' />";
            } 
        } 
        else
        {
            $articletag['indeximage'] = '';
        } 
        $xoopsTpl -> assign( 'article', $articletag );

        $result2 = $xoopsDB -> query( "SELECT CID, pagetitle, pageheadline FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE defaultpage = '0' AND mainpage = '1' ORDER BY weight" );
        $total['total'] = $xoopsDB -> getRowsNum( $result2 );
        
        $result3 = $xoopsDB -> query( "SELECT mainpage, titlelink FROM " . $xoopsDB -> prefix( "wfslinktous" ) . "" );
        list( $mainpage, $titlelink ) = $xoopsDB -> fetchrow( $result3 );

        $result4 = $xoopsDB -> query( "SELECT mainpage, titlerefer FROM " . $xoopsDB -> prefix( "wfsrefer" ) . "" );
        list( $refmainpage, $refertitle ) = $xoopsDB -> fetchrow( $result4 );

        if ( $mainpage )
        {
            if ( is_object( $xoopsUser ) )
            {
                $mainp['num'] = $mainpage;
                $xoopsTpl -> assign( 'lang_referfriend' , $refertitle );
            } 
            else
            {
                if ( $xoopsModuleConfig['anonlink'] )
                {
                    $mainp['num'] = $mainpage;
                    $xoopsTpl -> assign( 'lang_linktous' , $titlelink );
                } 
            } 
        } 

        if ( $refmainpage )
        {
            if ( is_object( $xoopsUser ) )
            {
                $mainp['ref'] = $refmainpage;
                $xoopsTpl -> assign( 'lang_linktous' , $titlelink );
            } 
            else
            {
                if ( $xoopsModuleConfig['anonrefer'] )
                {
                    $mainp['ref'] = $refmainpage;
                    $xoopsTpl -> assign( 'lang_referfriend' , $refertitle );
                } 
            } 
        } 
        $xoopsTpl -> assign( 'totalcount', $total );
        $xoopsTpl -> assign( 'mainp', $mainp ); 
        // get comments count
        $mod_id = $xoopsModule -> getVar( 'mid' );
        $count = xoops_comment_count( $mod_id, '' ); 
        // get module permissions
        $groups = $xoopsUser ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gperm_handler = & xoops_gethandler( 'groupperm' );

        while ( $query_data = $xoopsDB -> fetcharray( $result2 ) )
        {
            if ( $gperm_handler -> checkRight( 'Channel Permissions' , $query_data['CID'], $groups, $xoopsModule -> getVar( 'mid' ) ) )
            {
                $chanlink['id'] = $query_data['CID'];
                if ( !empty( $query_data['pageheadline'] ) )
                {
                    $chanlink['pagetitle'] = $myts -> displayTarea( $query_data['pageheadline'] );
                } 
                else
                {
                    $chanlink['pagetitle'] = $myts -> displayTarea( $query_data['pagetitle'] );
                } 
                $xoopsTpl -> append( 'chanlink', $chanlink );
            } 
        } 
        $xoopsTpl -> assign( 'lang_more', _MD_ALSOSEE );
} 

if ( $allowcomments == 1 )
{
    include( XOOPS_ROOT_PATH . "/include/comment_view.php" );
} 
include( XOOPS_ROOT_PATH . "/footer.php" );

?>