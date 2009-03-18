<?php
/**
 * $Id: upload.php v 1.0 20 November 2003 Catwolf Exp $
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

if ( isset( $HTTP_GET_VARS['rootpath'] ) )
{
    $rootpath = intval( $HTTP_GET_VARS['rootpath'] );
} 
else
{
    $rootpath = 0;
} 

switch ( $op )
{
    case "upload":

        global $HTTP_POST_VARS;

        if ( $HTTP_POST_FILES['uploadfile']['name'] != "" )
        {
            if ( file_exists( XOOPS_ROOT_PATH . "/" . $HTTP_POST_VARS['uploadpath'] . "/" . $HTTP_POST_FILES['uploadfile']['name'] ) )
            {
                redirect_header( "upload.php", 1, _AM_CHANIMAGEEXIST );
            } 

            if ( $HTTP_POST_VARS['rootnumber'] != 3 )
            {
                $allowed_mimetypes = array( 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png' );
            } 
            else
            {
                $allowed_mimetypes = array( 'text/html' );
            } 
            uploading( $allowed_mimetypes, $HTTP_POST_FILES['uploadfile']['name'], "upload.php", 0, $HTTP_POST_VARS['uploadpath'], 1 );
        } 
        else
        {
            redirect_header( "upload.php", '2' , _AM_CHANNOIMAGEEXIST );
        } 
        exit();
        break;

    case "delfile":

        if ( $confirm )
        {
            $filetodelete = XOOPS_ROOT_PATH . "/" . $HTTP_POST_VARS['uploadpath'] . "/" . $HTTP_POST_VARS['channelfile'];
            if ( file_exists( $filetodelete ) )
            {
                chmod( $filetodelete, 0666 );
                if ( @unlink( $filetodelete ) )
                {
                    redirect_header( "upload.php", 3, _AM_FILEDELETED );
                } 
                else
                {
                    redirect_header( "upload.php", 3, _AM_ERRORDELETEFILE );
                } 
            } 
            exit();
        } 
        else
        {
            xoops_cp_header();
            xoops_confirm( array( 'op' => 'delfile', 'uploadpath' => $HTTP_POST_VARS['uploadpath'], 'channelfile' => $HTTP_POST_VARS['channelfile'], 'confirm' => 1 ), 'upload.php', _AM_DELETEFILE . "<br/><br>" . $HTTP_POST_VARS['channelfile'], "Delete" );
        } 
        break;

    case "default":
    default:

        $displayimage = '';

        xoops_cp_header();

        Global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig;

        $dirarray = array( 1 => $xoopsModuleConfig['uploaddir'], 2 => $xoopsModuleConfig['linkimages'], 3 => $xoopsModuleConfig['htmluploaddir'] );
        $namearray = array( 1 => _AM_CHAN_UPLOADDIR , 2 => _AM_CHAN_LINKIMAGES, 3 => _AM_CHAN_HTMLUPLOADDIR );
        $listarray = array( 1 => _AM_UPLOADCHANLOGO , 2 => _AM_UPLOADLINKIMAGE, 3 => _AM_UPLOADCHANHTML );

        adminmenu( _AM_CHANADMIN, $extra = 1 );

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        echo "<div><b>" . _AM_SERVERSTATUS . ":</b></div>";

        $safemode = ( ini_get( 'safe_mode' ) ) ? _AM_SAFEMODEISON : _AM_SAFEMODEISOFF;
        $downloads = ( ini_get( 'enable_dl' ) ) ? _AM_UPLOADSON : _AM_UPLOADSOFF;
        echo "<div>" . $safemode . "</div>";
        echo "<div>" . $downloads . "";
        if ( ini_get( 'enable_dl' ) )
        {
            echo " " . _AM_ANDTHEMAX . " " . ini_get( 'upload_max_filesize' ) . "</div>";
        } 

        if ( $rootpath > 0 )
        {
            echo "<p><div><b>" . _AM_UPLOADPATH . "</b> " . XOOPS_ROOT_PATH . "/" . $dirarray[$rootpath] . "</div></p>";
        } 
        $iform = new XoopsThemeForm( _AM_UPLOADIMAGE . $listarray[$rootpath], "op", xoops_getenv( 'PHP_SELF' ) );
        $iform -> setExtra( 'enctype="multipart/form-data"' );

        ob_start();
        $iform -> addElement( new XoopsFormHidden( 'dir', $rootpath ) );
        getDirSelectOption( $namearray[$rootpath], $dirarray, $namearray );
        $iform -> addElement( new XoopsFormLabel( _AM_DIRSELECT, ob_get_contents() ) );
        ob_end_clean();

        if ( $rootpath > 0 )
        {
            if ( !$channelfile ) $channelfile = "blank.png";
            $graph_array = & XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . "/" . $dirarray[$rootpath] );

            if ( $rootpath != 3 )
            {
                $smallimage_select = new XoopsFormSelect( '', 'channelfile', $channelfile );
                $smallimage_select -> addOptionArray( $graph_array );
                $smallimage_select -> setExtra( "onchange='showImgSelected(\"image\", \"channelfile\", \"" . $dirarray[$rootpath] . "\", \"\", \"" . XOOPS_URL . "\")'" );

                $smallimage_tray = new XoopsFormElementTray( _AM_BUTTON, '&nbsp;' );
                $smallimage_tray -> addElement( $smallimage_select );
                $smallimage_tray -> addElement( new XoopsFormLabel( '', "<br /><br /><img src='" . XOOPS_URL . "/" . $dirarray[$rootpath] . "/" . $channelfile . "' name='image' id='image' alt='' />" ) );
                $iform -> addElement( $smallimage_tray );
            } 
            else
            {
                ob_start();
                htmlarray( $htmlfile, XOOPS_ROOT_PATH . "/" . $dirarray[$rootpath] );
                $iform -> addElement( new XoopsFormLabel( _AM_CHANHTML, ob_get_contents() ) );
                ob_end_clean();
            } 
            $iform -> addElement( new XoopsFormFile( _AM_UPLOADLINKIMAGE, 'uploadfile', $xoopsModuleConfig['maxfilesize'] ) );
            $iform -> addElement( new XoopsFormHidden( 'uploadpath', $dirarray[$rootpath] ) );
            $iform -> addElement( new XoopsFormHidden( 'rootnumber', $rootpath ) );

            $dup_tray = new XoopsFormElementTray( '', '' );
            $dup_tray -> addElement( new XoopsFormHidden( 'op', 'upload' ) );
            $butt_dup = new XoopsFormButton( '', '', _SUBMIT, 'submit' );
            $butt_dup -> setExtra( 'onclick="this.form.elements.op.value=\'upload\'"' );
            $dup_tray -> addElement( $butt_dup );

            $butt_dupct = new XoopsFormButton( '', '', _AM_DELETE, 'submit' );
            $butt_dupct -> setExtra( 'onclick="this.form.elements.op.value=\'delfile\'"' );
            $dup_tray -> addElement( $butt_dupct );
            $iform -> addElement( $dup_tray );
        } 
        $iform -> display();
} 
xoops_cp_footer();

?>