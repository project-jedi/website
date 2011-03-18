<?
// directories and files
$spooldir="spool";
$imgdir="img";
$file_newsportal="newsportal.php";
$file_groups="index.php";
$file_thread="thread_frameset.php";
$file_framethread="thread.php";
$file_article="article.php";
$file_post="post.php";
$file_language="extras/lang/english.lang";
$file_footer="";

// newsserver setup
$server="news.gotodoc.de";
$port=119;
// $post_server="";
// $post_port=119;
$maxfetch=0;
$initialfetch=0;
$server_auth_user="";
$server_auth_pass="";

// thread-layout
$thread_treestyle=7;
$thread_fontPre="<small>";
$thread_fontPost="</small>";
$thread_showDate=true;
$thread_showSubject=true;
$thread_showAuthor=true;
$thread_maxSubject=80;
$maxarticles=600;
$maxarticles_extra=100;
$age_count=3;
$age_time[1]=86400;
$age_color[1]="red";
$age_time[2]=259200;
$age_color[2]="#999900";
$age_time[3]=604800;
$age_color[3]="#00bb00";
$thread_sorting=-1;
$articles_per_page=0;
$startpage="first";

// frames
$frame_article="article";
$frame_thread="thread";
$frame_groups="_top";
$frame_post="_top";
$frame_threadframeset="_top";
$frame_externallink="_top";


// article layout
$article_show["Subject"]=true;
$article_show["From"]=true;
$article_show["Newsgroups"]=true;
$article_show["Followup"]=true;
$article_show["Organization"]=true;
$article_show["Date"]=true;
$article_show["Message-ID"]=false;
$article_show["References"]=true;
$article_show["User-Agent"]=false;
$article_showthread=0;

// Attachments
$attachment_show=true;
$attachment_delete_alternative=true; // delete non-text mutipart/alternative
$attachment_uudecode=false;

// Security settings
$send_poster_host=true;
$readonly=false;
$testgroup=true;
$validate_email=1;

// Misc
$title="NewsPortal WebNewsReader";
$organization="http://florian-amrhein.de/newsportal/";
$setcookies=true;
$compress_spoolfiles=false;
$cache_articles=false;       // makes problems, because there can be too
                             // much files in the spool-directory then...

include("english.lang"); // load the english language-definitions first because
                         // some of the other definitions are incomplete
include($file_language);

?>
