<?php
$modversion['name'] = _CC_MOD_NAME;
$modversion['version'] = "2";
$modversion['description'] = _CC_MOD_DESC;
$modversion['credits'] = "C-Jay Content by forlon - check http://c-jay.net for updates";
$modversion['author'] = "Christoph forlon Brecht";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 2;
$modversion['image'] = "cc_slogo.gif";
$modversion['dirname'] = "cjaycontent";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "cjaycontent";
$modversion['blocks'][1]['file'] = "cjaycontent.php";
$modversion['blocks'][1]['name'] = _CC_MOD_NAME;
$modversion['blocks'][1]['description'] = "C-jaycontent - Link Display";
$modversion['blocks'][1]['show_func'] = "b_cjaycontent_show";
$modversion['hasSearch'] = 1;
$modversion['search']['file']="include/search.inc.php";
$modversion['search']['func']="cjaycontent_search";
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name']="Testfiles";
$modversion['sub'][1]['url']="index.php?id=8";
?>