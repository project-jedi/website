<?php 
/**
 * module files can include this file for admin authorization
 * the file that will include this file must be located under xoops_url/modules/module_directory_name/admin_directory_name/
 */
 
include_once '../../../mainfile.php';
include_once XOOPS_ROOT_PATH . "/include/cp_functions.php";
$url_arr = explode( '/', str_replace( str_replace( 'https://', 'http://', XOOPS_URL . '/modules/' ), '', 'http://' . $HTTP_SERVER_VARS['HTTP_HOST'] . $xoopsRequestUri ) );
$module_handler = & xoops_gethandler( 'module' );
$xoopsModule = & $module_handler->getByDirname( $url_arr[0] );
unset( $url_arr );
if ( !is_object( $xoopsModule ) || !$xoopsModule->getVar( 'isactive' ) ) {
    redirect_header( XOOPS_URL . '/', 1, _MODULENOEXIST );
    exit();
} 
$moduleperm_handler = & xoops_gethandler( 'groupperm' );
if ( $xoopsUser ) {
    if ( !$moduleperm_handler->checkRight( 'module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
        redirect_header( XOOPS_URL . "/user.php", 1, _NOPERM );
        exit();
    } 
} else {
    redirect_header( XOOPS_URL . "/user.php", 1, _NOPERM );
    exit();
} 
// set config values for this module
if ( $xoopsModule->getVar( 'hasconfig' ) == 1 || $xoopsModule->getVar( 'hascomments' ) == 1 ) {
    $config_handler = & xoops_gethandler( 'config' );
    $xoopsModuleConfig = & $config_handler->getConfigsByCat( 0, $xoopsModule->getVar( 'mid' ) );
} 
// include the default language file for the admin interface
if ( file_exists( "../language/" . $xoopsConfig['language'] . "/admin.php" ) ) {
    include "../language/" . $xoopsConfig['language'] . "/admin.php";
} 

?>