<?php
// $Id: main.php,v 1.10 2003/05/02 18:19:43 okazu Exp $
//%%%%%%		Module Name phpBB  		%%%%%
//functions.php
define("_MD_ERROR","Error");
define("_MD_NOPOSTS","No Posts");
define("_MD_GO","Go");

//index.php
define("_MD_FORUM","Forum");
define("_MD_WELCOME","Welcome to %s Forum.");
define("_MD_TOPICS","Topics");
define("_MD_POSTS","Posts");
define("_MD_LASTPOST","Last Post");
define("_MD_MODERATOR","Moderator");
define("_MD_NEWPOSTS","New posts");
define("_MD_NONEWPOSTS","No new posts");
define("_MD_PRIVATEFORUM","Private forum");
define("_MD_BY","by"); // Posted by
define("_MD_TOSTART","To start viewing messages, select the forum that you want to visit from the selection below.");
define("_MD_TOTALTOPICSC","Total Topics: ");
define("_MD_TOTALPOSTSC","Total Posts: ");
define("_MD_TIMENOW","The time now is %s");
define("_MD_LASTVISIT","You last visited: %s");
define("_MD_ADVSEARCH","Advanced Search");
define("_MD_POSTEDON","Posted on: ");
define("_MD_SUBJECT","Subject");

//page_header.php
define("_MD_MODERATEDBY","Moderated by");
define("_MD_SEARCH","Search");
define("_MD_SEARCHRESULTS","Search Results");
define("_MD_FORUMINDEX","%s Forum Index");
define("_MD_POSTNEW","Post New Message");
define("_MD_REGTOPOST","Register To Post");

//search.php
define("_MD_KEYWORDS","Keywords:");
define("_MD_SEARCHANY","Search for ANY of the terms (Default)");
define("_MD_SEARCHALL","Search for ALL of the terms");
define("_MD_SEARCHALLFORUMS","Search All Forums");
define("_MD_FORUMC","Forum");
define("_MD_SORTBY","Sort by");
define("_MD_DATE","Date");
define("_MD_TOPIC","Topic");
define("_MD_USERNAME","Username");
define("_MD_SEARCHIN","Search in");
define("_MD_BODY","Body");
define("_MD_NOMATCH","No records match that query. Please broaden your search.");
define("_MD_POSTTIME","Post Time");

//viewforum.php
define("_MD_REPLIES","Replies");
define("_MD_POSTER","Poster");
define("_MD_VIEWS","Views");
define("_MD_MORETHAN","New posts [ Popular ]");
define("_MD_MORETHAN2","No New posts [ Popular ]");
define("_MD_TOPICSTICKY","Topic is Sticky");
define("_MD_TOPICLOCKED","Topic is Locked");
define("_MD_LEGEND","Legend");
define("_MD_NEXTPAGE","Next Page");
define("_MD_SORTEDBY","Sorted by");
define("_MD_TOPICTITLE","topic title");
define("_MD_NUMBERREPLIES","number of replies");
define("_MD_TOPICPOSTER","topic poster");
define("_MD_LASTPOSTTIME","last post time");
define("_MD_ASCENDING","Ascending order");
define("_MD_DESCENDING","Descending order");
define("_MD_FROMLASTDAYS","From last %s days");
define("_MD_THELASTYEAR","From the last year");
define("_MD_BEGINNING","From the beginning");

//viewtopic.php
define("_MD_AUTHOR","Author");
define("_MD_LOCKTOPIC","Lock this topic");
define("_MD_UNLOCKTOPIC","Unlock this topic");
define("_MD_STICKYTOPIC","Make this topic Sticky");
define("_MD_UNSTICKYTOPIC","Make this topic UnSticky");
define("_MD_MOVETOPIC","Move this topic");
define("_MD_DELETETOPIC","Delete this topic");
define("_MD_TOP","Top");
define("_MD_PARENT","Parent");
define("_MD_PREVTOPIC","Previous Topic");
define("_MD_NEXTTOPIC","Next Topic");

//forumform.inc
define("_MD_ABOUTPOST","About Posting");
define("_MD_ANONCANPOST","<b>Anonymous</b> users can post new topics and replies to this forum");
define("_MD_PRIVATE","This is a <b>Private</b> forum.<br />Only users with special access can post new topics and replies to this forum");define("_MD_REGCANPOST","All <b>Registered</b> users can post new topics and replies to this forum");
define("_MD_MODSCANPOST","Only <B>Moderators and Administrators</b> can post new topics and replies to this forum");
define("_MD_PREVPAGE","Previous Page");
define("_MD_QUOTE","Quote");

// ERROR messages
define("_MD_ERRORFORUM","ERROR: Forum not selected!");
define("_MD_ERRORPOST","ERROR: Post not selected!");
define("_MD_NORIGHTTOPOST","You don't have the right to post in this forum.");
define("_MD_NORIGHTTOACCESS","You don't have the right to access this forum.");
define("_MD_ERRORTOPIC","ERROR: Topic not selected!");
define("_MD_ERRORCONNECT","ERROR: Could not connect to the forums database.");
define("_MD_ERROREXIST","ERROR: The forum you selected does not exist. Please go back and try again.");
define("_MD_ERROROCCURED","An Error Occured");
define("_MD_COULDNOTQUERY","Could not query the forums database.");
define("_MD_FORUMNOEXIST","Error - The forum/topic you selected does not exist. Please go back and try again.");
define("_MD_USERNOEXIST","That user does not exist.  Please go back and search again.");
define("_MD_COULDNOTREMOVE","Error - Could not remove posts from the database!");
define("_MD_COULDNOTREMOVETXT","Error - Could not remove post texts!");

//reply.php
define("_MD_ON","on"); //Posted on
define("_MD_USERWROTE","%s wrote:"); // %s is username

//post.php
define("_MD_EDITNOTALLOWED","You're not allowed to edit this post!");
define("_MD_EDITEDBY","Edited by");
define("_MD_ANONNOTALLOWED","Anonymous user not allowed to post.<br>Please register.");
define("_MD_THANKSSUBMIT","Thanks for your submission!");
define("_MD_REPLYPOSTED","A reply to your topic has been posted.");
define("_MD_HELLO","Hello %s,");
define("_MD_URRECEIVING","You are receiving this email because a message you posted on %s forums has been replied to."); // %s is your site name
define("_MD_CLICKBELOW","Click on the link below to view the thread:");

//forumform.inc
define("_MD_YOURNAME","Your Name:");
define("_MD_LOGOUT","Logout");
define("_MD_REGISTER","Register");
define("_MD_SUBJECTC","Subject:");
define("_MD_MESSAGEICON","Message Icon:");
define("_MD_MESSAGEC","Message:");
define("_MD_ALLOWEDHTML","Allowed HTML:");
define("_MD_OPTIONS","Options:");
define("_MD_POSTANONLY","Post Anonymously");
define("_MD_DISABLESMILEY","Disable Smiley");
define("_MD_DISABLEHTML","Disable html");
define("_MD_NEWPOSTNOTIFY", "Notify me of new posts in this thread");
define("_MD_ATTACHSIG","Attach Signature");
define("_MD_POST","Post");
define("_MD_SUBMIT","Submit");
define("_MD_CANCELPOST","Cancel Post");

// forumuserpost.php
define("_MD_ADD","Add");
define("_MD_REPLY","Reply");

// topicmanager.php
define("_MD_YANTMOTFTYCPTF","You are not the moderator of this forum therefore you cannot perform this function.");
define("_MD_TTHBRFTD","The topic has been removed from the database.");
define("_MD_RETURNTOTHEFORUM","Return to the forum");
define("_MD_RTTFI","Return to the forum index");
define("_MD_EPGBATA","Error - Please go back and try again.");
define("_MD_TTHBM","The topic has been moved.");
define("_MD_VTUT","View the updated topic");
define("_MD_TTHBL","The topic has been locked.");
define("_MD_TTHBS","The topic has been Stickyed.");
define("_MD_TTHBUS","The topic has been unStickyed.");
define("_MD_VIEWTHETOPIC","View the topic");
define("_MD_TTHBU","The topic has been unlocked.");
define("_MD_OYPTDBATBOTFTTY","Once you press the delete button at the bottom of this form the topic you have selected, and all its related posts, will be <b>permanently</b> removed.");
define("_MD_OYPTMBATBOTFTTY","Once you press the move button at the bottom of this form the topic you have selected, and its related posts, will be moved to the forum you have selected.");
define("_MD_OYPTLBATBOTFTTY","Once you press the lock button at the bottom of this form the topic you have selected will be locked. You may unlock it at a later time if you like.");
define("_MD_OYPTUBATBOTFTTY","Once you press the unlock button at the bottom of this form the topic you have selected will be unlocked. You may lock it again at a later time if you like.");
define("_MD_OYPTSBATBOTFTTY","Once you press the Sticky button at the bottom of this form the topic you have selected will be Stickyed. You may unSticky it again at a later time if you like.");
define("_MD_OYPTTBATBOTFTTY","Once you press the unSticky button at the bottom of this form the topic you have selected will be unStickyed. You may Sticky it again at a later time if you like.");
define("_MD_MOVETOPICTO","Move Topic To:");
define("_MD_NOFORUMINDB","No Forums in DB");
define("_MD_DATABASEERROR","Database Error");
define("_MD_DELTOPIC","Delete Topic");

// delete.php
define("_MD_DELNOTALLOWED","Sorry, but you're not allowed to delete this post.");
define("_MD_AREUSUREDEL","Are you sure you want to delete this post and all its child posts?");
define("_MD_POSTSDELETED","Selected post and all its child posts deleted.");

// definitions moved from global.
define("_MD_THREAD","Thread");
define("_MD_FROM","From");
define("_MD_JOINED","Joined");
define("_MD_ONLINE","Online");
define("_MD_BOTTOM","Bottom");
?>
