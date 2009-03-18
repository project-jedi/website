<?php
/**
 * $Id: perrmission.php v 1.1 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
 */


include( "admin_header.php" );
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

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
    case "channels":
    default:
        global $xoopsDB, $xoopsModule;

        $item_list = array();
        $block = array();
        $module_id = $xoopsModule -> getVar( 'mid' );
        xoops_cp_header();

        adminmenu( _AM_CHANADMIN, $extra = '' );

        $result = $xoopsDB -> query( "SELECT CID, pagetitle FROM " . $xoopsDB -> prefix( "wfschannel" ) . " " );
        $permissioncount = $xoopsDB -> getRowsNum( $result );

        if ( $permissioncount > 0 )
        {
            while ( $myrow = $xoopsDB -> fetcharray( $result ) )
            {
                $item_list = array();
                $item_list['cid'] = $myrow['CID'];
                $item_list['title'] = $myrow['pagetitle'];

                $title_of_form = 'Permission form for Channels';
                $perm_name = 'Channel Permissions';
                $perm_desc = 'Select Channels that each group is allowed to view';

                $form = new XoopsGroupPermForm( $title_of_form, $module_id, $perm_name, $perm_desc );
                $block[] = $item_list;

                foreach ( $block as $itemlists )
                {
                    $form -> addItem( $itemlists['cid'], $itemlists['title'] );
                } 
            } 
            echo $form -> render();
        } 
        else
        {

        } 

        xoops_cp_footer();
        exit();
        break;

    case "links":
        global $xoopsDB, $xoopsModule;

        xoops_cp_header();

        adminmenu( _AM_CHANADMIN, $extra = '' );

        $module_id = $xoopsModule -> getVar( 'mid' );
        $title_of_form = 'Permission for Link to us';
        $perm_name = 'LinkPermissions';
        $perm_desc = 'Select FAQ Category that each group is allowed to view';

        $sform = new XoopsGroupPermForm( $title_of_form, $module_id, $perm_name, $perm_desc );
        $sform -> addItem( 1, _AM_LINKTOUS );
        echo $sform -> render();
        break;

    case "default":

        xoops_cp_header();
        adminmenu( _AM_CHANADMIN, $extra = '' );

        echo "<p><div><b>" . _AM_SELECTPERMISSIONTYPE . "</b></div></p>";
        echo "<p><div><a href='permissions.php?op=links'>" . _AM_MODIFYCATPERMISSIONS . "</a></div>";
        echo "<div><a href='permissions.php?op=channels'>" . _AM_MODIFYFAQPERMISSIONS . "</a></div></p>";
} 
xoops_cp_footer();

?>