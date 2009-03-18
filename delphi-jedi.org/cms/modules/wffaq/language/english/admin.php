<?php
/* 
* $Id: admin.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

//Main ADmin Section

define('_AM_FAQMANINTRO','Welcome to the WF-FAQ Control Panel');


/*
* Uni Lang defines
*/
define('_AM_SUBMIT','Create');
define('_AM_CREATE','Create');
define('_AM_YES','Yes');
define('_AM_NO','No');
define('_AM_DELETE','Delete');
define('_AM_MODIFY','Modify');
define('_AM_UPDATED','Database has been updated');
define('_AM_NOTUPDATED','There was an error updating the database!');
define('_AM_CATCREATED','New Category was created and saved!');
define('_AM_CATMODIFY','Category was modified and saved!');
/*
* Lang defines for functions.php
*/
define('_AM_FADMINHEAD','WF-FAQ Admin');
define('_AM_FADMINCATH','WF-FAQ Category Admin');
define('_AM_FNEWCAT','FAQ Category Index');
define('_AM_FNEWCATTXT','Create, Modify or Delete a FAQ Category. Or Return to main FAQ Category Index.');
define('_AM_FNEWFAQ','FAQ Topic Index');
define('_AM_FNEWFAQTXT','Create, Modify or Delete a FAQ Topic. Or Return to main FAQ Topic Index.');
define('_AM_FVAL','Validate new submissions');
define('_AM_FVALTXT',"Allows you to delete or validate new FAQ's submitted.");
/*
* Lang defines for Category.php
*/
define('_AM_FRECOUNT','Recount FAQ Topics');
define('_AM_FRECOUNTTXT','Allows you to recount the number of FAQ in each category.');
define('_AM_CREATIN','Create in');
define('_AM_CATNAME','Category Name');
define('_AM_CATDESCRIPT','Category Description');
define('_AM_NOCATTOEDIT','There is no category to edit.');
define('_AM_MODIFYCAT','Modify Category');
define('_AM_DELCAT','Delete Category');
define('_AM_ADDCAT','ADD Category');
define('_AM_MODIFYTHISCAT','Modify this Category?');
define('_AM_DELETETHISCAT','Delete this Category?');
define('_AM_CATISDELETED','Category %s has been deleted');

/*
* Lang defines for topics.php
*/
define('_AM_TOPICSADMIN','FAQ Topics Admin');
define('_AM_NOTCTREATEDACAT','You cannot add a FAQ until you create a FAQ Category!');
define('_AM_ADDFAQ','Create new FAQ');
define('_AM_CREATEIN','Create in');
define('_AM_TOPICQ','Question');
define('_AM_TOPICA','Answer');
define('_AM_SUMMARY','Summary');
define('_AM_MODIFYFAQ','Modify FAQ');
define('_AM_MODIFYEXSITFAQ','Modify FAQ');
define('_AM_MODIFYTHISFAQ','Modify this FAQ question');
define('_AM_DELFAQ','Delete FAQ');
define('_AM_DELTHISFAQ','Delete this FAQ');
define('_AM_NOTOPICTOEDIT','No FAQ in database to modify');
define('_AM_DELETETHISTOPIC','Delete this FAQ Topic?');
define('_AM_TOPICISDELETED','FAQ %s has been deleted');
define('_AM_FAQCREATED','FAQ was created and saved');
define('_AM_FAQNOTCREATED','ERROR: FAQ was NOT created and saved');
define('_AM_FAQMODIFY','FAQ was modified and saved');
define('_AM_FAQNOTMODIFY','ERROR: FAQ was NOT modified and saved');

define('_AM_SUBALLOW','Allow');
define('_AM_SUBDELETE','Delete');
define('_AM_SUBRETURN','Return to Admin');
define('_AM_SUBRETURNTO','Return To New Submissions');
define('_AM_AUTHOR','Author');
define('_AM_PUBLISHED','Published');
define('_AM_SUBPREVIEW','The WF-FAQ Admin view');
define('_AM_SUBADMINPREV','This is the admin preview of this FAQ topic.');
define('_AM_DBUPDATED','FAQ Database has been updated');
/*
*  Copyright and Support.  Please do not remove this line as this is part of the only copyright agreement for using WF-FAQ 
*/
define('_AM_VISITSUPPORT', 'Visit the WF-Section website for information, updates and help on its usage.<br /> WF-FAQ v1 Catzwolf &copy; 2003 <a href="http://wfsections.xoops2.com/" target="_blank">WF-FAQ</a>');

?>