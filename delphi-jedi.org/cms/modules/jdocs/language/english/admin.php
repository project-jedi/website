<?php
/**
 * $Id: admin.php v 1.3 20 November 2003 Catwolf Exp $
 * Module: WF-Channel
 * Version: v1.0.2
 * Release Date: 20 November 2003
 * Author: Catzwolf
 * Licence: GNU
 */

/**
 * Uni Lang defines
 */
define('_AM_SUBMIT', 'Submit');
define('_AM_CREATE', 'Create');
define('_AM_YES', 'Yes');
define('_AM_NO', 'No');
define('_AM_CANCEL', 'Cancel');
define('_AM_DELETE', 'Delete');
define('_AM_MODIFY', 'Modify');

define('_AM_UPDATED', 'Database has been updated');
define('_AM_NOTUPDATED', 'There was an error updating the database!');
define('_AM_CANNOTDELETELASTONE', 'You cannot delete this item, WF-Channel need at least 1 item to work correctly');
define('_AM_NODEFAULTPAGESET', 'WARNING: No Default page set, please select one');
define('_AM_DEFAULTPAGESET', 'Default page set to Channel');
define('_AM_TOTALNUMCHANL', 'Total Number Channels');

/**
 * Lang defines for topics.php
 */
define('_AM_CHANADMIN', 'WF-Channel Admin');
define('_AM_ADDCHAN', 'Create new Channel');
define('_AM_CHANQ', 'Channel Title:');
define('_AM_CHANA', 'Page Content:');
define('_AM_CHANW', 'Channel Title Weight:');
define('_AM_MODIFYCHAN', 'Modify Channel');
define('_AM_MODIFYEXSITCHAN', 'Modify Channel');
define('_AM_MODIFYTHISCHAN', 'Modify this Channel');
define('_AM_DELCHAN', 'Delete Channel');
define('_AM_DELTHISCHAN', 'Delete this Channel');
define('_AM_NOCHANTOEDIT', 'No Channel in database to modify');

define('_AM_CHANISDELETED', 'Channel \'%s\' has been deleted');

define('_AM_CHANCREATED', 'Channel was created and saved');
define('_AM_CHANNOTCREATED', 'ERROR: Channel was NOT created nor saved');
define('_AM_CHANMODIFY', 'Channel was modified and saved');
define('_AM_CHANNOTMODIFY', 'ERROR: Channel was NOT modified nor saved');
define('_AM_CHANIMAGEEXIST', 'file exists on server, please choose another one!');
define('_AM_CHANNOIMAGEEXIST', 'No Image Selected');

define('_AM_SUBALLOW', 'Allow');
define('_AM_SUBDELETE', 'Delete');
define('_AM_SUBRETURN', 'Return to Admin');
define('_AM_AUTHOR', 'Author');
define('_AM_PUBLISHED', 'Published');
define('_AM_SUBPREVIEW', 'The WF-Channel Admin view');
define('_AM_SUBADMINPREV', 'This is the admin preview of this Channel topic.');
define('_AM_DBUPDATED', 'Channel Database has been updated');

define('_AM_TITLE', 'Title:');
define('_AM_CHAIMAGE', 'Channel Logo:');
define('_AM_CHANHTML', 'Static HTML:');
define('_AM_ACTION', 'Action');
define('_AM_DOHTML', ' Disable HTML Tags');
define('_AM_DOSMILEY', ' Disable Smiley Icons');
define('_AM_DOXCODE', ' Disable XOOPS Codes');
define('_AM_BREAKS', ' Use Linebreak Conversion? (Disable when using HTML)');
define('_AM_DEFAULT', ' Set as Default Channel?');
define('_AM_ISSUBMENU', 'Submenu Item');
define('_AM_ALLOWCOMMENTSCHANHTML', 'Allow Comments for this channel?');

define('_AM_TEXTLINKHTML', 'Text Link HTML Code');
define('_AM_BUTTONHTML', 'Button Link HTML Code');
define('_AM_LOGOHTML', 'Logo Link HTML Code');
define('_AM_BANNERHTML', 'Banner Link HTML Code');
define('_AM_ADDNEWSFEEDJS', 'Add JS newsfeed option to link page?');

define("_AM_CHANHDL", "Channel headline:");
define("_AM_ID", "ID");
define("_AM_PAGETITLE", "Channel Title");
define("_AM_WEIGHT", "Weight");
define("_AM_DEFAULTPAGE", "Default Channel");
define("_AM_GENERALSET", "General Settings");
define("_AM_MAINADMIN", "Main Admin Page");
define("_AM_CREATENEWPAGE", "Create New Channel");
define("_AM_GROUPPERMISSIONS", "Group Permissions");
define('_AM_ISMAINPAGELINK', 'Main Page Link');
define('_AM_UPLOAD', 'Upload File');
define('_AM_REORDER', 'Reorder Channels');
define('_AM_UPLOADPATH', 'Upload Path:');
define('_AM_REORDERADMIN', 'Reorder Channels');

define("_AM_UPLOADCHANLOGO", "Channel Logo");
define("_AM_UPLOADCHANHTML", "Static HTML File");

define('_AM_CHAN_UPLOADDIR','Image Upload Directory');
define('_AM_CHAN_LINKIMAGES','Link Image Upload Directory');
define('_AM_CHAN_HTMLUPLOADDIR','HTML Upload Directory');

define("_AM_CLINKTOUS", "Link Page Admin");
define("_AM_CMODIFYLINK", "Modify Links");
define("_AM_SUBMENUITEM", "Add as a submenu item?");
define("_AM_MAINPAGEITEM", "Add link to the main page?");
define("_AM_TEXTLINK", "Title of the Text Link:");
define("_AM_LINKPAGELOGO", "Channel Logo:");
define("_AM_BUTTON", "Image for Button link:");
define("_AM_LOGO", "Image for Logo link:");
define("_AM_BANNER", "Image for Banner link:");
define("_AM_ADDNEWSFEED", "Add newsfeed option to link page?");
define('_AM_NEWSFEEDTITLE', 'Newsfeed Title:');
define("_AM_UPLOADIMAGE", "Upload ");
define("_AM_UPLOADLINKIMAGE", "Link Images");
define("_AM_DIRSELECT", "Choose Upload Directory");
define("_AM_PREVIOUS", "Previous");
define("_AM_NEXT", "Next");
define('_AM_LINKTOUS', 'Link to Us');

define("_AM_REFER", "Refer Page Admin");
define("_AM_EMAILSETTINGS", "Email Settings");
define("_AM_EMAILADDRESS", "Use Senders Stored Email address?");
define("_AM_USERSBLURB", "Allow User to enter own Message?");
define("_AM_DEFBLURB", "Enter default message:");

define("_AM_MENU", "Menu Settings");
define("_AM_LOGONNEWSFEED", "Logo and newsfeed options");

define('_AM_REORDERID', 'ID');
define('_AM_REORDERTITLE', 'Title');
define('_AM_REORDERWEIGHT', 'Weight');
define('_AM_REORDERCHANNEL', 'Channel Reordered');

define('_AM_SERVERSTATUS', 'Server status');
define('_AM_SAFEMODEISON', 'Safe_mode is ON (This may cause problems)');
define('_AM_SAFEMODEISOFF', 'Safe_mode is OFF');
define('_AM_UPLOADSON', 'Uploads is ON');
define('_AM_UPLOADSOFF', 'Uploads is OFF');
define('_AM_ANDTHEMAX', ' and Max Upload size = ');
define('_AM_DELETEFILE', 'WARNING<br/>Delete This File?');
?>