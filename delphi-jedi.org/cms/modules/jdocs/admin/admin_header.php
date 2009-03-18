<?php
/**
 * $Id: admin header.php v 1.5 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
 */

include( "../../../mainfile.php" );
include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . "/class/xoopsmodule.php";
include_once XOOPS_ROOT_PATH . "/modules/jdocs/include/functions.php";
include XOOPS_ROOT_PATH . "/class/xoopstree.php";
include XOOPS_ROOT_PATH . "/class/xoopslists.php";
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

if ( is_object( $xoopsUser ) )
{
    $xoopsModule = XoopsModule :: getByDirname( "jdocs" );
    if ( !$xoopsUser -> isAdmin( $xoopsModule -> mid() ) )
    {
        redirect_header( XOOPS_URL . "/", 3, _NOPERM );
        exit();
    } 
} 
else
{
    redirect_header( XOOPS_URL . "/", 1, _NOPERM );
    exit();
} 

$myts = & MyTextSanitizer :: getInstance();

?>
