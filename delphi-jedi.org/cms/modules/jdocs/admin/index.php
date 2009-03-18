<?php
/**
 * $Id: index.php v 1.5 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
 */

include( "admin_header.php" );

$op = '';

foreach ( $HTTP_POST_VARS as $k => $v )
{
    ${$k} = $v;
} 
foreach ( $HTTP_GET_VARS as $k => $v )
{
    ${$k} = $v;
} 

function edittopic( $CID = '' )
{
    $html = '';
    $smiley = '';
    $xcodes = '';
    $pagetitle = '';
    $pageheadline = '';
    $page = '';
    $breaks = 1;
    $defaultpage = 0;
    $indeximage = '';
    $weight = 1;
    $htmlfile = '';
    $mainpage = 0;
    $submenu = 0;
    $allowcomments = 0;

    Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $XOOPS_URL, $xoopsModuleConfig;

    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    if ( $CID )
    {
        $result = $xoopsDB -> query( "SELECT CID,pagetitle, pageheadline, page, weight, html, smiley, xcodes, breaks, defaultpage, indeximage, htmlfile, mainpage, submenu, created, comments, allowcomments  FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE CID = $CID " );
        list( $CID, $pagetitle, $pageheadline, $page, $weight, $html, $smiley, $xcodes, $breaks, $defaultpage, $indeximage, $htmlfile, $mainpage, $submenu, $created, $comments, $allowcomments ) = $xoopsDB -> fetchrow( $result );

        if ( $xoopsDB -> getRowsNum( $result ) == 0 )
        {
            redirect_header( "index.php", 1, _AM_NOTOPICTOEDIT );
            exit();
        } 
        $sform = new XoopsThemeForm( _AM_MODIFYEXSITCHAN, "op", xoops_getenv( 'PHP_SELF' ) );
    } 
    else
    {
        $sform = new XoopsThemeForm( _AM_ADDCHAN, "op", xoops_getenv( 'PHP_SELF' ) );
    } 

    if ( !$indeximage ) $indeximage = "blank.png";
    $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['uploaddir'] );
    $indeximage_select = new XoopsFormSelect( '', 'indeximage', $indeximage );
    $indeximage_select -> addOptionArray( $graph_array );
    $indeximage_select -> setExtra( "onchange='showImgSelected(\"image1\", \"indeximage\", \"" . $xoopsModuleConfig['uploaddir'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $indeximage_tray = new XoopsFormElementTray( _AM_CHAIMAGE, '&nbsp;' );
    $indeximage_tray -> addElement( $indeximage_select );
    $indeximage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['uploaddir'] . "/" . $indeximage . "' name='image1' id='image1' alt='' />" ) );
    $sform -> addElement( $indeximage_tray );

    $sform -> addElement( new XoopsFormText( _AM_CHANW, 'weight', 4, 4, $weight ) );
    $sform -> addElement( new XoopsFormText( _AM_CHANQ, 'pagetitle', 50, 255, $pagetitle ), true );
    $sform -> addElement( new XoopsFormText( _AM_CHANHDL, 'pageheadline', 50, 255, $pageheadline ), false );

    ob_start();
    htmlarray( $htmlfile, XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['htmluploaddir'] );
    $sform -> addElement( new XoopsFormLabel( _AM_CHANHTML, ob_get_contents() ) );
    ob_end_clean();


    $sform -> addElement( new XoopsFormDhtmlTextArea( _AM_CHANA, 'page', $page, 15, 60 ), false );

    $html_checkbox = new XoopsFormCheckBox( '', 'html', $html );
    $html_checkbox -> addOption( 1, _AM_DOHTML );
    $sform -> addElement( $html_checkbox );
    $smiley_checkbox = new XoopsFormCheckBox( '', 'smiley', $smiley );
    $smiley_checkbox -> addOption( 1, _AM_DOSMILEY );
    $sform -> addElement( $smiley_checkbox );
    $xcodes_checkbox = new XoopsFormCheckBox( '', 'xcodes', $xcodes );
    $xcodes_checkbox -> addOption( 1, _AM_DOXCODE );
    $sform -> addElement( $xcodes_checkbox );
    $breaks_checkbox = new XoopsFormCheckBox( '', 'breaks', $breaks );
    $breaks_checkbox -> addOption( 1, _AM_BREAKS );
    $sform -> addElement( $breaks_checkbox );

    $sform -> insertBreak( "<b>" . _AM_MENU . "</b>", 'bg3' );
    $defaultpage_radio = new XoopsFormRadioYN( _AM_DEFAULT, 'defaultpage', $defaultpage, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
    $sform -> addElement( $defaultpage_radio );
    if ( $defaultpage == 0 )
    {
        $submenuitem_radio = new XoopsFormRadioYN( _AM_SUBMENUITEM, 'submenu', $submenu, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $submenuitem_radio );
        $mainpage_radio = new XoopsFormRadioYN( _AM_MAINPAGEITEM, 'mainpage', $mainpage, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $mainpage_radio );
    } 
    if ( !isset( $allowcomments ) ) $allowcomments = 0;
    $allowcomments_radio = new XoopsFormRadioYN( _AM_ALLOWCOMMENTSCHANHTML, 'allowcomments', $allowcomments, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
    $sform -> addElement( $allowcomments_radio );

    $sform -> addElement( new XoopsFormHidden( 'CID', $CID ) );
    $create_tray = new XoopsFormElementTray( '', '' );
    $create_tray -> addElement( new XoopsFormHidden( 'op', 'save' ) );
    if ( !$CID )
    {
        $butt_save = new XoopsFormButton( '', '', _AM_CREATE, 'submit' );
        $butt_save -> setExtra( 'onclick="this.form.elements.op.value=\'save\'"' );
    } 
    else
    {
        $butt_save = new XoopsFormButton( '', '', _AM_MODIFY, 'submit' );
        $butt_save -> setExtra( 'onclick="this.form.elements.op.value=\'save\'"' );
    } 
    $create_tray -> addElement( $butt_save );
    $butt_cancel = new XoopsFormButton( '', '', _AM_CANCEL, 'submit' );
    $butt_cancel -> setExtra( 'onclick="this.form.elements.op.value=\'cancel\'"' );
    $create_tray -> addElement( $butt_cancel );
    $sform -> addElement( $create_tray );
    $sform -> display();
    unset( $hidden );
} 

switch ( $op )
{
    case "mod":
        xoops_cp_header();
        adminmenu( _AM_CHANADMIN, $extra = '' );
        $CID = ( isset( $HTTP_POST_VARS['CID'] ) ) ? $HTTP_POST_VARS['CID'] : $CID;
        edittopic( $CID );
        break;

    case "del":
        Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB;

        if ( $confirm )
        {
            $xoopsDB -> query( "DELETE FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE CID = $CID" );
            xoops_groupperm_deletebymoditem ( $xoopsModule -> getVar( 'mid' ), '', $CID );
            xoops_comment_delete( $xoopsModule -> getVar( 'mid' ), $CID );
            redirect_header( "index.php", 1, sprintf( _AM_CHANISDELETED, $pagetitle ) );
            exit();
        } 
        else
        {
            $CID = ( isset( $HTTP_POST_VARS['CID'] ) ) ? $HTTP_POST_VARS['CID'] : $CID;

            $result = $xoopsDB -> query( "SELECT CID, pagetitle FROM " . $xoopsDB -> prefix( "wfschannel" ) . " " );
            if ( $xoopsDB -> getRowsNum( $result ) == 1 )
            {
                redirect_header( "index.php", 3, _AM_CANNOTDELETELASTONE );
                exit();
            } 

            $result = $xoopsDB -> query( "SELECT CID, pagetitle FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE CID = $CID" );
            list( $CID, $pagetitle ) = $xoopsDB -> fetchrow( $result );

            xoops_cp_header();
            echo"<table width='100%' border='0' cellpadding = '2' cellspacing='1' class = 'confirmMsg'><tr><td class='confirmMsg'>";
            echo "<div class='confirmMsg'>";
            echo "<h4>" . _AM_DELTHISCHAN . "</h4>";
            echo "<h5>$pagetitle</h5>";
            echo "<table><tr><td>";
            echo myTextForm( "index.php?op=del&CID=" . $CID . "&confirm=1&pagetitle=$pagetitle", _AM_DELETE );
            echo "</td><td>";
            echo myTextForm( "index.php", _AM_CANCEL );
            echo "</td></tr></table>";
            echo "</div><br /><br />";
            echo"</td></tr></table>";
        } 
        xoops_cp_footer();
        exit();
        break;

    case "save":

        global $xoopsUser, $xoopsDB, $HTTP_POST_VARS;

        $result = $xoopsDB -> query( "SELECT CID FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE defaultpage = '1'" );
        list( $CIDOLD ) = $xoopsDB -> fetchrow( $result );

        if ( $xoopsDB -> getRowsNum( $result ) >= 1 )
        {
            if ( $CIDOLD != $HTTP_POST_VARS['CID'] && $HTTP_POST_VARS['defaultpage'] == 1 )
            {
                $xoopsDB -> query( "UPDATE " . $xoopsDB -> prefix( "wfschannel" ) . " SET defaultpage = '0'" );
            } 
        } 

        $html = ( isset( $HTTP_POST_VARS['html'] ) ) ? 1 : 0;
        $smiley = ( isset( $HTTP_POST_VARS['smiley'] ) ) ? 1 : 0;
        $xcodes = ( isset( $HTTP_POST_VARS['xcodes'] ) ) ? 1 : 0;
        $breaks = ( isset( $HTTP_POST_VARS['breaks'] ) ) ? 1 : 0;

        $weight = ( isset( $HTTP_POST_VARS['weight'] ) && is_numeric( $HTTP_POST_VARS['weight'] ) ) ? $myts -> addSlashes( $HTTP_POST_VARS['weight'] ) : 1;
        $pagetitle = $myts -> addSlashes( $HTTP_POST_VARS['pagetitle'], 0, 0, 0 );
        $pageheadline = $myts -> addSlashes( $HTTP_POST_VARS['pageheadline'], 0, 0, 0 );
        $page = $myts -> addSlashes( $HTTP_POST_VARS['page'] );
        $CID = $myts -> addSlashes( $HTTP_POST_VARS['CID'] );

        $defaultpage = $myts -> addSlashes( $HTTP_POST_VARS['defaultpage'] );
        $submenu = $myts -> addSlashes( $HTTP_POST_VARS['submenu'] );
        $mainpage = $myts -> addSlashes( $HTTP_POST_VARS['mainpage'] );
        $allowcomments = $myts -> addSlashes( $HTTP_POST_VARS['allowcomments'] );

        $indeximage = ( isset( $HTTP_POST_VARS["indeximage"] ) ) ? $myts -> addSlashes( $HTTP_POST_VARS["indeximage"] ) : '';

        $htmlfile = $HTTP_POST_VARS["htmlpage"];
        $created = time();

        if ( !$CID )
        {
            if ( $xoopsDB -> query( "INSERT INTO " . $xoopsDB -> prefix( "wfschannel" ) . " (pagetitle, pageheadline, page, weight, html, smiley, xcodes, breaks, defaultpage, indeximage, htmlfile, mainpage, submenu, created, allowcomments) VALUES ('$pagetitle', '$pageheadline', '$page', '$weight','$html', '$smiley', '$xcodes', $breaks, $defaultpage, '$indeximage', '$htmlfile', '$mainpage', '$submenu', '$created', '$allowcomments')" ) )
            {
                redirect_header( "index.php", '1' , _AM_CHANCREATED );
            } 
            else
            {
                redirect_header( "index.php", '1' , _AM_CHANNOTCREATED );
            } 
        } 
        else
        {
            if ( $xoopsDB -> query( "UPDATE " . $xoopsDB -> prefix( "wfschannel" ) . " SET pagetitle = '$pagetitle', pageheadline = '$pageheadline', page = '$page', weight = '$weight', html ='$html', smiley ='$smiley', xcodes ='$xcodes', breaks ='$breaks', defaultpage ='$defaultpage', indeximage = '$indeximage', htmlfile = '$htmlfile', mainpage = '$mainpage', submenu = '$submenu', allowcomments = '$allowcomments' WHERE CID = $CID" ) )
            {
                redirect_header( "index.php", '1' , _AM_CHANMODIFY );
            } 
            else
            {
                redirect_header( "index.php", '1' , _AM_CHANNOTMODIFY );
            } 
        } 
        exit();
        break;

    case "create":
        xoops_cp_header();
        adminmenu( _AM_CHANADMIN, $extra = '' );
        edittopic();
        break;

    case "savelink":

        global $xoopsDB, $myts;

        $titlelink = $myts -> addSlashes( $HTTP_POST_VARS['titlelink'] );
        $textlink = $myts -> addSlashes( $HTTP_POST_VARS['textlink'] );
        $linkpagelogo = $myts -> addSlashes( $HTTP_POST_VARS['linkpagelogo'] );
        $button = $myts -> addSlashes( $HTTP_POST_VARS['button'] );
        $logo = $myts -> addSlashes( $HTTP_POST_VARS['logo'] );
        $banner = $myts -> addSlashes( $HTTP_POST_VARS['banner'] );

        $newsfeedjs = $myts -> addSlashes( $HTTP_POST_VARS['newsfeedjs'] );
        $newstitle = $myts -> addSlashes( $HTTP_POST_VARS['newstitle'] );

        $submenuitem = $myts -> addSlashes( $HTTP_POST_VARS['submenuitem'] );
        $mainpage = $myts -> addSlashes( $HTTP_POST_VARS['mainpage'] );
        $newsfeed = $myts -> addSlashes( $HTTP_POST_VARS['newsfeed'] );

        if ( $xoopsDB -> query( "UPDATE " . $xoopsDB -> prefix( "wfslinktous" ) . " SET textlink = '$textlink', titlelink = '$titlelink', button = '$button', logo = '$logo', banner = '$banner', linkpagelogo = '$linkpagelogo', newsfeed = '$newsfeed', submenuitem = '$submenuitem', mainpage = '$mainpage', newsfeedjs = '$newsfeedjs', newstitle = '$newstitle'" ) )
        {
            redirect_header( "index.php?op=links", '1' , _AM_CHANMODIFY );
        } 
        else
        {
            redirect_header( "index.php?op=links", '1' , _AM_CHANNOTMODIFY );
        } 

        exit();
        break;

    case "links":

        xoops_cp_header();

        adminmenu( _AM_CHANADMIN, $extra = '' );

        global $xoopsModuleConfig, $xoopsDB, $xoopsConfig;

        $result = $xoopsDB -> query( "SELECT submenuitem, textlink, linkpagelogo, button, logo, banner, mainpage, newsfeed,titlelink, newsfeedjs, newstitle  FROM " . $xoopsDB -> prefix( "wfslinktous" ) . "" );
        list( $submenuitem, $textlink, $linkpagelogo, $button, $logo, $banner, $mainpage, $newsfeed, $titlelink, $newsfeedjs, $newstitle ) = $xoopsDB -> fetchrow( $result );

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $sform = new XoopsThemeForm( _AM_CMODIFYLINK, "op", xoops_getenv( 'PHP_SELF' ) );

        if ( !$linkpagelogo ) $linkpagelogo = "blank.png";
        $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['linkimages'] );
        $linkpage_select = new XoopsFormSelect( '', 'linkpagelogo', $linkpagelogo );
        $linkpage_select -> addOptionArray( $graph_array );
        $linkpage_select -> setExtra( "onchange='showImgSelected(\"image1\", \"linkpagelogo\", \"" . $xoopsModuleConfig['linkimages'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $linkpage_tray = new XoopsFormElementTray( _AM_LINKPAGELOGO, '&nbsp;' );
        $linkpage_tray -> addElement( $linkpage_select );
        $linkpage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['linkimages'] . "/" . $linkpagelogo . "' name='image1' id='image1' alt='' />" ) );
        $sform -> addElement( $linkpage_tray );

        $sform -> addElement( new XoopsFormText( _AM_CHANQ, 'titlelink', 50, 255, $titlelink ), true );
        $sform -> insertBreak( "<b>" . _AM_LOGONNEWSFEED . "</b>", 'bg3' );

        if ( !$textlink ) $textlink = XOOPS_URL;
        $sform -> addElement( new XoopsFormText( _AM_TEXTLINK, 'textlink', 50, 255, $textlink ), true );

        if ( !$button ) $button = "blank.png";
        $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['linkimages'] );
        $smallimage_select = new XoopsFormSelect( '', 'button', $button );
        $smallimage_select -> addOptionArray( $graph_array );
        $smallimage_select -> setExtra( "onchange='showImgSelected(\"image2\", \"button\", \"" . $xoopsModuleConfig['linkimages'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $smallimage_tray = new XoopsFormElementTray( _AM_BUTTON, '&nbsp;' );
        $smallimage_tray -> addElement( $smallimage_select );
        $smallimage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['linkimages'] . "/" . $button . "' name='image2' id='image2' alt='' />" ) );
        $sform -> addElement( $smallimage_tray );

        if ( !$logo ) $logo = "blank.png";
        $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['linkimages'] );
        $medimage_select = new XoopsFormSelect( '', 'logo', $logo );
        $medimage_select -> addOptionArray( $graph_array );
        $medimage_select -> setExtra( "onchange='showImgSelected(\"image3\", \"logo\", \"" . $xoopsModuleConfig['linkimages'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $medimage_tray = new XoopsFormElementTray( _AM_LOGO, '&nbsp;' );
        $medimage_tray -> addElement( $medimage_select );
        $medimage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['linkimages'] . "/" . $logo . "' name='image3' id='image3' alt='' />" ) );
        $sform -> addElement( $medimage_tray );

        if ( !$banner ) $banner = "blank.png";
        $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['linkimages'] );
        $largeimage_select = new XoopsFormSelect( '', 'banner', $banner );
        $largeimage_select -> addOptionArray( $graph_array );
        $largeimage_select -> setExtra( "onchange='showImgSelected(\"image4\", \"banner\", \"" . $xoopsModuleConfig['linkimages'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $largeimage_tray = new XoopsFormElementTray( _AM_BANNER, '&nbsp;' );
        $largeimage_tray -> addElement( $largeimage_select );
        $largeimage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['linkimages'] . "/" . $banner . "' name='image4' id='image4' alt='' />" ) );
        $sform -> addElement( $largeimage_tray );

        $sform -> addElement( new XoopsFormText( _AM_NEWSFEEDTITLE, 'newstitle', 50, 255, $newstitle ), false );
        $newsfeed_radio = new XoopsFormRadioYN( _AM_ADDNEWSFEED, 'newsfeed', $newsfeed, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $newsfeed_radio );
        $newsfeedjs_radio = new XoopsFormRadioYN( _AM_ADDNEWSFEEDJS, 'newsfeedjs', $newsfeedjs, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $newsfeedjs_radio );

        $sform -> insertBreak( "<b>" . _AM_MENU . "</b>", 'bg3' );
        $submenuitem_radio = new XoopsFormRadioYN( _AM_SUBMENUITEM, 'submenuitem', $submenuitem, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $submenuitem_radio );
        $mainpage_radio = new XoopsFormRadioYN( _AM_MAINPAGEITEM, 'mainpage', $mainpage, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $mainpage_radio );

        $create_tray = new XoopsFormElementTray( '', '' );
        $create_tray -> addElement( new XoopsFormHidden( 'op', 'savelink' ) );
        $butt_save = new XoopsFormButton( '', '', _AM_MODIFY, 'submit' );
        $butt_save -> setExtra( 'onclick="this.form.elements.op.value=\'savelink\'"' );
        $create_tray -> addElement( $butt_save );
        $butt_cancel = new XoopsFormButton( '', '', _AM_CANCEL, 'submit' );
        $butt_cancel -> setExtra( 'onclick="this.form.elements.op.value=\'cancel\'"' );
        $create_tray -> addElement( $butt_cancel );
        $sform -> addElement( $create_tray );
        $sform -> display();
        unset( $hidden );

        xoops_cp_footer();
        exit();

        break;

    case "saverefer":

        global $xoopsDB, $myts;

        $titlerefer = $myts -> addSlashes( $HTTP_POST_VARS['titlerefer'] );
        $chanrefheadline = $myts -> addSlashes( $HTTP_POST_VARS['chanrefheadline'] );
        $submenuitem = $myts -> addSlashes( $HTTP_POST_VARS['submenuitem'] );
        $mainpage = $myts -> addSlashes( $HTTP_POST_VARS['mainpage'] );
        $emailaddress = $myts -> addSlashes( $HTTP_POST_VARS['emailaddress'] );
        $usersblurb = $myts -> addSlashes( $HTTP_POST_VARS['usersblurb'] );
        $defblurb = $myts -> addSlashes( $HTTP_POST_VARS['defblurb'] );

        $breaks = ( isset( $HTTP_POST_VARS['breaks'] ) ) ? 1 : 0;
        $html = ( isset( $HTTP_POST_VARS['html'] ) ) ? 1 : 0;
        $smiley = ( isset( $HTTP_POST_VARS['smiley'] ) ) ? 1 : 0;
        $xcodes = ( isset( $HTTP_POST_VARS['xcodes'] ) ) ? 1 : 0;
        $referpagelogo = ( isset( $HTTP_POST_VARS["referpagelogo"] ) ) ? $myts -> addSlashes( $HTTP_POST_VARS["referpagelogo"] ) : '';

        if ( $xoopsDB -> query( "UPDATE " . $xoopsDB -> prefix( "wfsrefer" ) . " SET titlerefer = '$titlerefer', chanrefheadline = '$chanrefheadline', submenuitem = '$submenuitem', mainpage = '$mainpage', emailaddress = '$emailaddress', usersblurb = '$usersblurb', defblurb = '$defblurb', referpagelogo = '$referpagelogo', html ='$html', smiley ='$smiley', xcodes ='$xcodes', breaks ='$breaks' " ) )
        {
            redirect_header( "index.php?op=refer", '1' , _AM_CHANMODIFY );
        } 
        else
        {
            redirect_header( "index.php?op=refer", '1' , _AM_CHANNOTMODIFY );
        } 

        exit();
        break;

    case "refer":

        xoops_cp_header();

        adminmenu( _AM_CHANADMIN, $extra = '' );

        global $xoopsModuleConfig, $xoopsDB, $xoopsConfig;

        $titlerefer = '';
        $chanrefheadline = '';
        $submenuitem = 1;
        $mainpage = 1;
        $emailaddress = 1;
        $usersblurb = 0;
        $defblurb = '';
        $referpagelogo = '';
        $html = 0;
        $smiley = 0;
        $xcodes = 0;
        $breaks = 1;

        $result = $xoopsDB -> query( "SELECT * FROM " . $xoopsDB -> prefix( "wfsrefer" ) . "" );
        list( $titlerefer, $chanrefheadline, $submenuitem, $mainpage, $referpagelogo, $emailaddress, $usersblurb, $defblurb, $smiley, $xcodes, $breaks, $html ) = $xoopsDB -> fetchrow( $result );

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $sform = new XoopsThemeForm( _AM_CMODIFYLINK, "op", xoops_getenv( 'PHP_SELF' ) );
        $sform -> setExtra( 'enctype="multipart/form-data"' );

        if ( !$referpagelogo ) $referpagelogo = "blank.png";
        $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['uploaddir'] );
        $linkpage_select = new XoopsFormSelect( '', 'referpagelogo', $referpagelogo );
        $linkpage_select -> addOptionArray( $graph_array );
        $linkpage_select -> setExtra( "onchange='showImgSelected(\"image1\", \"referpagelogo\", \"" . $xoopsModuleConfig['uploaddir'] . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $linkpage_tray = new XoopsFormElementTray( _AM_LINKPAGELOGO, '&nbsp;' );
        $linkpage_tray -> addElement( $linkpage_select );
        $linkpage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['uploaddir'] . "/" . $referpagelogo . "' name='image1' id='image1' alt='' />" ) );
        $sform -> addElement( $linkpage_tray );

        $sform -> addElement( new XoopsFormText( _AM_CHANQ, 'titlerefer', 50, 255, $titlerefer ), true );
        $sform -> addElement( new XoopsFormDhtmlTextArea( _AM_CHANHDL, 'chanrefheadline', $chanrefheadline, 15, 60 ), false );

        $html_checkbox = new XoopsFormCheckBox( '', 'html', $html );
        $html_checkbox -> addOption( 1, _AM_DOHTML );
        $sform -> addElement( $html_checkbox );

        $smiley_checkbox = new XoopsFormCheckBox( '', 'smiley', $smiley );
        $smiley_checkbox -> addOption( 1, _AM_DOSMILEY );
        $sform -> addElement( $smiley_checkbox );

        $xcodes_checkbox = new XoopsFormCheckBox( '', 'xcodes', $xcodes );
        $xcodes_checkbox -> addOption( 1, _AM_DOXCODE );
        $sform -> addElement( $xcodes_checkbox );

        $breaks_checkbox = new XoopsFormCheckBox( '', 'breaks', $breaks );
        $breaks_checkbox -> addOption( 1, _AM_BREAKS );
        $sform -> addElement( $breaks_checkbox );

        $sform -> insertBreak( "<b>" . _AM_EMAILSETTINGS . "</b>", 'bg3' );
        $emailaddress_radio = new XoopsFormRadioYN( _AM_EMAILADDRESS, 'emailaddress', $emailaddress, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $emailaddress_radio );
        $usersblurb_radio = new XoopsFormRadioYN( _AM_USERSBLURB, 'usersblurb', $usersblurb, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $usersblurb_radio );
        $sform -> addElement( new XoopsFormTextArea( _AM_DEFBLURB, 'defblurb', $defblurb, 15, 60 ), false );

        $sform -> insertBreak( "<b>" . _AM_MENU . "</b>", 'bg3' );
        $submenuitem_radio = new XoopsFormRadioYN( _AM_SUBMENUITEM, 'submenuitem', $submenuitem, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $submenuitem_radio );
        $mainpage_radio = new XoopsFormRadioYN( _AM_MAINPAGEITEM, 'mainpage', $mainpage, ' ' . _AM_YES . '', ' ' . _AM_NO . '' );
        $sform -> addElement( $mainpage_radio );

        $create_tray = new XoopsFormElementTray( '', '' );
        $create_tray -> addElement( new XoopsFormHidden( 'op', 'saverefer' ) );
        $butt_save = new XoopsFormButton( '', '', _AM_MODIFY, 'submit' );
        $butt_save -> setExtra( 'onclick="this.form.elements.op.value=\'saverefer\'"' );
        $create_tray -> addElement( $butt_save );
        $butt_cancel = new XoopsFormButton( '', '', _AM_CANCEL, 'submit' );
        $butt_cancel -> setExtra( 'onclick="this.form.elements.op.value=\'cancel\'"' );
        $create_tray -> addElement( $butt_cancel );
        $sform -> addElement( $create_tray );
        $sform -> display();
        unset( $hidden );

        xoops_cp_footer();
        exit();

        break;
    case "default":
    default:

        xoops_cp_header();

        Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

        adminmenu( _AM_CHANADMIN, $extra = '' );

        $result = $xoopsDB -> query( "SELECT CID, pagetitle FROM " . $xoopsDB -> prefix( "wfschannel" ) . " WHERE defaultpage = 1 " );
        list( $CID, $pagetitle ) = $xoopsDB -> fetchrow( $result );

        $result2 = $xoopsDB -> query( "SELECT CID FROM " . $xoopsDB -> prefix( "wfschannel" ) . "" );
        $numrows = $xoopsDB -> getRowsNum( $result2 );

        $pagetitle = "<a href='../index.php?op=mod&CID=" . $CID . "'>" . $pagetitle . "</a>";

        if ( $xoopsDB -> getRowsNum( $result ) == 0 )
        {
            echo "<p><div>" . _AM_NODEFAULTPAGESET . "</div></p>";
        } 
        else
        {
            echo "<p><div>" . _AM_DEFAULTPAGESET . ": " . $pagetitle . "</div>";
        } 
        echo "<div>" . _AM_TOTALNUMCHANL . ": <b>" . $numrows . "</b></div></p>";

        if ( isset( $HTTP_GET_VARS['show'] ) && $HTTP_GET_VARS['show'] != "" )
        {
            $show = intval( $HTTP_GET_VARS['show'] );
        } 
        else
        {
            $show = $xoopsModuleConfig['perpage'];
        } 

        $min = isset( $HTTP_GET_VARS['min'] ) ? intval( $HTTP_GET_VARS['min'] ) : 0;
        if ( !isset( $max ) )
        {
            $max = $min + $show;
        } 

        if ( $numrows > 0 )
        {
            $sql = "SELECT CID, pagetitle, pageheadline, weight, defaultpage, mainpage, submenu FROM " . $xoopsDB -> prefix( "wfschannel" ) . " ORDER BY CID";
            $result = $xoopsDB -> query( $sql, $show, $min );

            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo "<tr>";
            echo "<td class='bg3' align='center' width = '5%'><b>" . _AM_ID . "</b></td>";
            echo "<td class='bg3' align='left'><b>" . _AM_PAGETITLE . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_WEIGHT . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_DEFAULTPAGE . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_ISMAINPAGELINK . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_ISSUBMENU . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_ACTION . "</b></td>";
            echo "</tr>";
            $x = 0;

            while ( list( $CID, $pagetitle, $pageheadline, $weight, $defaultpage, $mainpage, $submenu ) = $xoopsDB -> fetchrow( $result ) )
            {
                $pagetitle = $myts -> htmlSpecialChars( $pagetitle );
                $weight = $myts -> htmlSpecialChars( $weight );
                $defaultpage = ( $defaultpage == 1 ) ? _AM_YES : _AM_NO;
                $mainpage = ( $mainpage == 1 ) ? _AM_YES : _AM_NO;
                $submenu = ( $submenu == 1 ) ? _AM_YES : _AM_NO;
                $modify = "<a href='index.php?op=mod&CID=" . $CID . "'>" . _AM_MODIFY . "</a>";
                $delete = "<a href='index.php?op=del&CID=" . $CID . "'>" . _AM_DELETE . "</a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $CID . "</td>";
                echo "<td class='even' align='left'><a href='index.php?op=mod&CID=$CID'>" . $pagetitle . "</a></td>";
                echo "<td class='even' align='center'>" . $weight . "</td>";
                echo "<td class='even' align='center'>" . $defaultpage . "</td>";
                echo "<td class='even' align='center'>" . $mainpage . "</td>";
                echo "<td class='even' align='center'>" . $submenu . "</td>";
                echo "<td class='even' align='center'> $modify | $delete</td>";
                echo "</tr>";
                $x++;
            } 
            echo "</table>\n"; 
            // Calculates how many pages exist.  Which page one should be on, etc...
            $downloadpages = ceil( $numrows / $show );
            if ( $numrows % $show == 0 )
            {
                $downloadpages = $downloadpages - 1;
            } 
            // Page Numbering
            if ( $downloadpages != 1 && $downloadpages != 0 )
            {
                echo "<br />";
                $prev = $min - $show;
                if ( $prev >= 0 )
                {
                    echo "<a href='index.php?min=$prev&show=$show'>";
                    echo "<b> " . _AM_PREVIOUS . " </b></a>&nbsp;";
                } 

                $counter = 1;
                $currentpage = ( $max / $show );

                while ( $counter <= $downloadpages )
                {
                    $mintemp = ( $show * $counter ) - $show;
                    if ( $counter == $currentpage )
                    {
                        echo "<b>$counter</b>&nbsp;";
                    } 
                    else
                    {
                        echo "<a href='index.php?min=$mintemp&show=$show'>$counter</a>&nbsp;";
                    } 
                    $counter++;
                } 

                if ( $numrows > $max )
                {
                    echo "<a href='index.php?min=$max&show=$show'>";
                    echo "<b> " . _AM_NEXT . " </b></a>";
                } 
                // XoopsPageNav($downloadpages, $show, $current_start, $start_name="start", $extra_arg="");
            } 
        } 
        break;
} 
xoops_cp_footer();

?>