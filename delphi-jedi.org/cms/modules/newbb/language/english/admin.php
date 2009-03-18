<?php
// $Id: admin.php,v 1.7 2003/03/05 05:56:22 w4z004 Exp $
//%%%%%%	File Name  index.php   	%%%%%
define("_MD_A_FORUMCONF","Forum Configuration");
define("_MD_A_ADDAFORUM","Add a Forum");
define("_MD_A_LINK2ADDFORUM","This Link will take you to a page where you can add a forum to the database.");
define("_MD_A_EDITAFORUM","Edit a Forum");
define("_MD_A_LINK2EDITFORUM","This link will allow you to edit an existing forum.");
define("_MD_A_SETPRIVFORUM","Set Private Forum Permissions");
define("_MD_A_LINK2SETPRIV","This link will allow you to set the access to an existing private forum.");
define("_MD_A_SYNCFORUM","Sync forum/topic index");
define("_MD_A_LINK2SYNC","This link will allow you to sync up the forum and topic indexes to fix any discrepancies that might arise");
define("_MD_A_ADDACAT","Add a Category");
define("_MD_A_LINK2ADDCAT","This link will allow you to add a new category to put forums into.");
define("_MD_A_EDITCATTTL","Edit a Category Title");
define("_MD_A_LINK2EDITCAT","This link will allow you edit the title of a category.");
define("_MD_A_RMVACAT","Remove a Category");
define("_MD_A_LINK2RMVCAT","This link allows you to remove any categories from the database");
define("_MD_A_REORDERCAT","Re-Order Categories");
define("_MD_A_LINK2ORDERCAT","This link will allow you to change the order in which your categories display on the index page");

//%%%%%%	File Name  admin_forums.php   	%%%%%
define("_MD_A_FORUMUPDATED","Forum Updated");
define("_MD_A_HTSMHNBRBITHBTWNLBAMOTF","However the selected moderator(s) have not be removed because if they had been there would no longer be any moderators on this forum.");
define("_MD_A_FORUMREMOVED","Forum Removed.");
define("_MD_A_FRFDAWAIP","Forum removed from database along with all its posts.");
define("_MD_A_NOSUCHFORUM","No such forum");
define("_MD_A_EDITTHISFORUM","Edit This Forum");
define("_MD_A_DTFTWARAPITF","Delete this forum (This will also remove all posts in this forum!)");
define("_MD_A_FORUMNAME","Forum Name:");
define("_MD_A_FORUMDESCRIPTION","Forum Description:");
define("_MD_A_MODERATOR","Moderator(s):");
define("_MD_A_REMOVE","Remove");
define("_MD_A_NOMODERATORASSIGNED","No Moderators Assigned");
define("_MD_A_NONE","None");
define("_MD_A_CATEGORY","Category:");
define("_MD_A_ANONYMOUSPOST","Anonymous Posting");
define("_MD_A_REGISTERUSERONLY","Registered users only");
define("_MD_A_MODERATORANDADMINONLY","Moderators/Administrators only");
define("_MD_A_TYPE","Type:");
define("_MD_A_PUBLIC","Public");
define("_MD_A_PRIVATE","Private");
define("_MD_A_SELECTFORUMEDIT","Select a Forum to Edit");
define("_MD_A_NOFORUMINDATABASE","No Forums in Database");
define("_MD_A_DATABASEERROR","Database Error");
define("_MD_A_EDIT","Edit");
define("_MD_A_CATEGORYUPDATED","Category Updated.");
define("_MD_A_EDITCATEGORY","Editing Category:");
define("_MD_A_CATEGORYTITLE","Category Title:");
define("_MD_A_SELECTACATEGORYEDIT","Select a Category to Edit");
define("_MD_A_CATEGORYCREATED","Category Created.");
define("_MD_A_NTWNRTFUTCYMDTVTEFS","Note: This will NOT remove the forums under the category, you must do that via the Edit Forum section.");
define("_MD_A_REMOVECATEGORY","Remove Category");
define("_MD_A_CREATENEWCATEGORY","Create a New Category");
define("_MD_A_YDNFOATPOTFDYAA","You did not fill out all the parts of the form.<br>Did you assign at least one moderator? Please go back and correct the form.");
define("_MD_A_FORUMCREATED","Forum Created.");
define("_MD_A_VTFYJC","View the forum  you just created.");
define("_MD_A_EYMAACBYAF","Error, you must add a category before you add forums");
define("_MD_A_CREATENEWFORUM","Create a New Forum");
define("_MD_A_ACCESSLEVEL","Access Level:");
define("_MD_A_CATEGORYMOVEUP","Category Moved Up");
define("_MD_A_TCIATHU","This is already the highest category.");
define("_MD_A_CATEGORYMOVEDOWN","Category Moved Down");
define("_MD_A_TCIATLD","This is already the lowest category.");
define("_MD_A_SETCATEGORYORDER","Set Category Ordering");
define("_MD_A_TODHITOTCWDOTIP","The order displayed here is the order the categories will display on the index page. To move a category up in the ordering click Move Up to move it down click Move Down.");
define("_MD_A_ECWMTCPUODITO","Each click will move the category 1 place up or down in the ordering.");
define("_MD_A_CATEGORY1","Category");
define("_MD_A_MOVEUP","Move Up");
define("_MD_A_MOVEDOWN","Move Down");


define("_MD_A_FORUMUPDATE","Forum Settings Updated");
define("_MD_A_RETURNTOADMINPANEL","Return to the Administration Panel.");
define("_MD_A_RETURNTOFORUMINDEX","Return to the forum index");
define("_MD_A_ALLOWHTML","Allow HTML:");
define("_MD_A_YES","Yes");
define("_MD_A_NO","No");
define("_MD_A_ALLOWSIGNATURES","Allow Signatures:");
define("_MD_A_HOTTOPICTHRESHOLD","Hot Topic Threshold:");
define("_MD_A_POSTPERPAGE","Posts per Page:");
define("_MD_A_TITNOPPTTWBDPPOT","(This is the number of posts per topic that will be displayed per page of a topic)");
define("_MD_A_TOPICPERFORUM","Topics per Forum:");
define("_MD_A_TITNOTPFTWBDPPOAF","(This is the number of topics per forum that will be displayed per page of a forum)");
define("_MD_A_SAVECHANGES","Save Changes");
define("_MD_A_CLEAR","Clear");
define("_MD_A_CLICKBELOWSYNC","Clicking the button below will sync up your forums and topics pages with the correct data from the database. Use this section whenever you notice flaws in the topics and forums lists.");
define("_MD_A_SYNCHING","Synchronizing forum index and topics (This may take a while)");
define("_MD_A_DONESYNC","Done!");
define("_MD_A_CATEGORYDELETED","Category deleted.");

//%%%%%%	File Name  admin_priv_forums.php   	%%%%%

define("_MD_A_SAFTE","Select a Forum to Edit");
define("_MD_A_NFID","No Forums in Database");
define("_MD_A_EFPF","Editing Forum Permissions for: <b>%s</b>");
define("_MD_A_UWA","Users With Access:");
define("_MD_A_UWOA","Users Without Access:");
define("_MD_A_ADDUSERS","Add Users -->");
define("_MD_A_CLEARALLUSERS","Clear all users");
define("_MD_A_REVOKEPOSTING","revoke posting");
define("_MD_A_GRANTPOSTING","grant posting");
?>