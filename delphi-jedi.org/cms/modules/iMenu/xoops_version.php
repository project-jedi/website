<?php


$modversion['name'] = _IM_IMENU_NAME;
$modversion['version'] = "2.00";
$modversion['description'] = _IM_IMENU_DESC;
$modversion['credits'] = "";
$modversion['author'] = "luinithil" ;
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "icon_imenu.gif";
$modversion['dirname'] = "iMenu";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "imenu";
$modversion['hasMain'] = 0;
$modversion['blocks'][1]['file'] = "imenu.php";
$modversion['blocks'][1]['name'] = _IM_IMENU_NAME;
$modversion['blocks'][1]['description'] = "link menu";
$modversion['blocks'][1]['show_func'] = "b_imenu_show";
$modversion['blocks'][1]['template'] = 'imenu_block.html';
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

?>
