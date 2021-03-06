<?php
/* 
* $Id: xoops_version.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

$modversion['name'] = _MI_FAQM_NAME;
$modversion['version'] = 1.02;
$modversion['description'] = _MI_FAQMD_DESC;
$modversion['author'] = "Catzwolf";
$modversion['credits'] = "X-Mode, the Xoops Core team and 'Tom' for bugging me to do this module";
//$modversion['help'] = "wffaq.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/wffaq_slogo.png";
$modversion['dirname'] = "wffaq";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "faqcategories";
$modversion['tables'][1] = "faqtopics";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "wffaq_search";

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_FAQSUB_SMNAME1;
$modversion['sub'][1]['url'] = "submit.php?op=add";

// Templates
$modversion['templates'][1]['file'] = 'wffaq_category.html';
$modversion['templates'][1]['description'] = 'Display category';
$modversion['templates'][2]['file'] = 'wffaq_index.html';
$modversion['templates'][2]['description'] = 'Display index';
$modversion['templates'][3]['file'] = 'wffaq_answer.html';
$modversion['templates'][3]['description'] = 'Display answer';
?>
