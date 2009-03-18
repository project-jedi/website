<?php
// $Id: modinfo.php,v 1.12 2003/04/11 15:20:05 okazu Exp $
// Module Info

// The name of this module
define("_MI_MYLINKS_NAME","Web Links");

// A brief description of this module
define("_MI_MYLINKS_DESC","Creates a web links section where users can search/submit/rate various web sites.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_MYLINKS_BNAME1","Recent Links");
define("_MI_MYLINKS_BNAME2","Top Links");

// Sub menu titles
define("_MI_MYLINKS_SMNAME1","Submit");
define("_MI_MYLINKS_SMNAME2","Popular");
define("_MI_MYLINKS_SMNAME3","Top Rated");

// Names of admin menu items
define("_MI_MYLINKS_ADMENU2","Add/Edit Links");
define("_MI_MYLINKS_ADMENU3","Submitted Links");
define("_MI_MYLINKS_ADMENU4","Broken Links");
define("_MI_MYLINKS_ADMENU5","Modified Links");
define("_MI_MYLINKS_ADMENU6","Link Checker");

// Title of config items
define('_MI_MYLINKS_POPULAR', 'Select the number of hits for links to be marked as popular');
define('_MI_MYLINKS_NEWLINKS', 'Select the maximum number of new links displayed on top page');
define('_MI_MYLINKS_PERPAGE', 'Select the maximum number of links displayed in each page');
define('_MI_MYLINKS_USESHOTS', 'Select yes to display screenshot images for each link');
define('_MI_MYLINKS_USEFRAMES', 'Would you like to display the linked page withing a frame?');
define('_MI_MYLINKS_SHOTWIDTH', 'Maximum allowed width of each screenshot image');
define('_MI_MYLINKS_ANONPOST','Allow anonymous users to post links?');
define('_MI_MYLINKS_AUTOAPPROVE','Auto approve new links without admin intervention?');

// Description of each config items
define('_MI_MYLINKS_POPULARDSC', '');
define('_MI_MYLINKS_NEWLINKSDSC', '');
define('_MI_MYLINKS_PERPAGEDSC', '');
define('_MI_MYLINKS_USEFRAMEDSC', '');
define('_MI_MYLINKS_USESHOTSDSC', '');
define('_MI_MYLINKS_SHOTWIDTHDSC', '');
define('_MI_MYLINKS_AUTOAPPROVEDSC','');

// Text for notifications

define('_MI_MYLINKS_GLOBAL_NOTIFY', 'Global');
define('_MI_MYLINKS_GLOBAL_NOTIFYDSC', 'Global links notification options.');

define('_MI_MYLINKS_CATEGORY_NOTIFY', 'Category');
define('_MI_MYLINKS_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current link category.');

define('_MI_MYLINKS_LINK_NOTIFY', 'Link');
define('_MI_MYLINKS_LINK_NOTIFYDSC', 'Notification options that aply to the current link.');

define('_MI_MYLINKS_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_MYLINKS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new link category is created.');
define('_MI_MYLINKS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new link category is created.');
define('_MI_MYLINKS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link category');

define('_MI_MYLINKS_GLOBAL_LINKMODIFY_NOTIFY', 'Modify Link Requested');
define('_MI_MYLINKS_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notify me of any link modification request.');
define('_MI_MYLINKS_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Receive notification when any link modification request is submitted.');
define('_MI_MYLINKS_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Link Modification Requested');

define('_MI_MYLINKS_GLOBAL_LINKBROKEN_NOTIFY', 'Broken Link Submitted');
define('_MI_MYLINKS_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notify me of any broken link report.');
define('_MI_MYLINKS_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Receive notification when any broken link report is submitted.');
define('_MI_MYLINKS_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Link Reported');

define('_MI_MYLINKS_GLOBAL_LINKSUBMIT_NOTIFY', 'New Link Submitted');
define('_MI_MYLINKS_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notify me when any new link is submitted (awaiting approval).');
define('_MI_MYLINKS_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Receive notification when any new link is submitted (awaiting approval).');
define('_MI_MYLINKS_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link submitted');

define('_MI_MYLINKS_GLOBAL_NEWLINK_NOTIFY', 'New Link');
define('_MI_MYLINKS_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new link is posted.');
define('_MI_MYLINKS_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new link is posted.');
define('_MI_MYLINKS_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link');

define('_MI_MYLINKS_CATEGORY_LINKSUBMIT_NOTIFY', 'New Link Submitted');
define('_MI_MYLINKS_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notify me when a new link is submitted (awaiting approval) to the current category.');
define('_MI_MYLINKS_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Receive notification when a new link is submitted (awaiting approval) to the current category.');
define('_MI_MYLINKS_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link submitted in category');

define('_MI_MYLINKS_CATEGORY_NEWLINK_NOTIFY', 'New Link');
define('_MI_MYLINKS_CATEGORY_NEWLINK_NOTIFYCAP', 'Notify me when a new link is posted to the current category.');
define('_MI_MYLINKS_CATEGORY_NEWLINK_NOTIFYDSC', 'Receive notification when a new link is posted to the current category.');
define('_MI_MYLINKS_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link in category');

define('_MI_MYLINKS_LINK_APPROVE_NOTIFY', 'Link Approved');
define('_MI_MYLINKS_LINK_APPROVE_NOTIFYCAP', 'Notify me when this link is approved.');
define('_MI_MYLINKS_LINK_APPROVE_NOTIFYDSC', 'Receive notification when this link is approved.');
define('_MI_MYLINKS_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Link approved');

?>
