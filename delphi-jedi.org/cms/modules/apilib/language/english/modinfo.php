<?php
// $Id: modinfo.php,v 1.13 2003/03/27 12:11:05 w4z004 Exp $
// Module Info

// The name of this module
define("_MI_MYDOWNLOADS_NAME","Downloads");

// A brief description of this module
define("_MI_MYDOWNLOADS_DESC","Creates a downloads section where users can download/submit/rate various files.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_MYDOWNLOADS_BNAME1","Recent Downloads");
define("_MI_MYDOWNLOADS_BNAME2","Top Downloads");

// Sub menu titles
define("_MI_MYDOWNLOADS_SMNAME1","Submit");
define("_MI_MYDOWNLOADS_SMNAME2","Popular");
define("_MI_MYDOWNLOADS_SMNAME3","Top Rated");

// Names of admin menu items
define("_MI_MYDOWNLOADS_ADMENU2","Add/Edit Downloads");
define("_MI_MYDOWNLOADS_ADMENU3","Submitted Downloads");
define("_MI_MYDOWNLOADS_ADMENU4","Broken Downloads");
define("_MI_MYDOWNLOADS_ADMENU5","Modified Downloads");

// Title of config items
define('_MI_MYDOWNLOADS_POPULAR', 'Number of hits for downloadable items to be marked as popular');
define('_MI_MYDOWNLOADS_NEWDLS', 'Maximum number of new download items displayed on top page');
define('_MI_MYDOWNLOADS_PERPAGE', 'Maximum number of download items displayed on each page');
define('_MI_MYDOWNLOADS_USESHOTS', 'Select yes to display screenshot images for each download item');
define('_MI_MYDOWNLOADS_SHOTWIDTH', 'Type in the maximum width of screenshot images');
define('_MI_MYDOWNLOADS_CHECKHOST', 'Disallow direct download linking? (leeching)');
define('_MI_MYDOWNLOADS_REFERERS', 'This Sites can directly link you files <br />Separate each one with | ');
define("_MI_MYDOWNLOADS_ANONPOST","Allow anonymous users to post download items?");
define('_MI_MYDOWNLOADS_AUTOAPPROVE','Auto approve new downloads without admin intervention?');

// Description of each config items
define('_MI_MYDOWNLOADS_POPULARDSC', '');
define('_MI_MYDOWNLOADS_NEWDLSDSC', '');
define('_MI_MYDOWNLOADS_PERPAGEDSC', '');
define('_MI_MYDOWNLOADS_USESHOTSDSC', '');
define('_MI_MYDOWNLOADS_SHOTWIDTHDSC', '');
define('_MI_MYDOWNLOADS_REFERERSDSC', '');
define('_MI_MYDOWNLOADS_AUTOAPPROVEDSC', '');

// Text for notifications

define('_MI_MYDOWNLOADS_GLOBAL_NOTIFY', 'Global');
define('_MI_MYDOWNLOADS_GLOBAL_NOTIFYDSC', 'Global downloads notification options.');

define('_MI_MYDOWNLOADS_CATEGORY_NOTIFY', 'Category');
define('_MI_MYDOWNLOADS_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current file category.');

define('_MI_MYDOWNLOADS_FILE_NOTIFY', 'File');
define('_MI_MYDOWNLOADS_FILE_NOTIFYDSC', 'Notification options that apply to the current file.');

define('_MI_MYDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_MYDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new file category is created.');
define('_MI_MYDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new file category is created.');
define('_MI_MYDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file category');                              

define('_MI_MYDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY', 'Modify File Requested');
define('_MI_MYDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP', 'Notify me of any file modification request.');
define('_MI_MYDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC', 'Receive notification when any file modification request is submitted.');
define('_MI_MYDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : File Modification Requested');

define('_MI_MYDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY', 'Broken File Submitted');
define('_MI_MYDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP', 'Notify me of any broken file report.');
define('_MI_MYDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC', 'Receive notification when any broken file report is submitted.');
define('_MI_MYDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken File Reported');

define('_MI_MYDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY', 'File Submitted');
define('_MI_MYDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP', 'Notify me when any new file is submitted (awaiting approval).');
define('_MI_MYDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC', 'Receive notification when any new file is submitted (awaiting approval).');
define('_MI_MYDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file submitted');

define('_MI_MYDOWNLOADS_GLOBAL_NEWFILE_NOTIFY', 'New File');
define('_MI_MYDOWNLOADS_GLOBAL_NEWFILE_NOTIFYCAP', 'Notify me when any new file is posted.');
define('_MI_MYDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC', 'Receive notification when any new file is posted.');
define('_MI_MYDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file');

define('_MI_MYDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY', 'File Submitted');
define('_MI_MYDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP', 'Notify me when a new file is submitted (awaiting approval) to the current category.');   
define('_MI_MYDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC', 'Receive notification when a new file is submitted (awaiting approval) to the current category.');      
define('_MI_MYDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file submitted in category'); 

define('_MI_MYDOWNLOADS_CATEGORY_NEWFILE_NOTIFY', 'New File');
define('_MI_MYDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP', 'Notify me when a new file is posted to the current category.');   
define('_MI_MYDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC', 'Receive notification when a new file is posted to the current category.');      
define('_MI_MYDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file in category'); 

define('_MI_MYDOWNLOADS_FILE_APPROVE_NOTIFY', 'File Approved');
define('_MI_MYDOWNLOADS_FILE_APPROVE_NOTIFYCAP', 'Notify me when this file is approved.');
define('_MI_MYDOWNLOADS_FILE_APPROVE_NOTIFYDSC', 'Receive notification when this file is approved.');
define('_MI_MYDOWNLOADS_FILE_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : File Approved');

?>
