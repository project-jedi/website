
                                News portal
                                      
   Version 0.24pre4
   
Introduction

   News portal is a PHP based newsreader. It is licensed under the GNU
   Public License (see enclosed LICENSE).
   
Overview

   This script collection enables the access to a newsserver (by NNTP)
   from a webpage. It allows you to combine web-forums and newsgroups.
   The script is also suitable for presentation of announce newsgroups on
   web pages, without having the user notice that he is in fact accessing
   a newsserver.
   
   The main functionality of the script is located in the file
   newsportal.php, which contains the major part of the implemented
   php3-functions. In addition to that there are four more php3-files,
   which are directly accessed by the browser:
     * index.php shows the available newsgroups of the newsserver (if you
       have added the names to groups.txt)
     * thread.php displays the article-overview of a newsgroup. The
       articles are displayed in a thread.
     * article.php displays an individual article.
     * post.php posts a message into a newsgroup.
     * attachment.php shows possible attachments of articles.
       
   There are some more files that control the behavior of newsportal or
   contain information:
     * config.inc contains the configuration.
     * head.inc contains the header and the body-tag of the pages. This
       way the layout of the pages (i.e. the background) can easily be
       adjusted.
     * tail.inc contains the end of every page.
     * german.lang : The German language definitions
     * english.lang : The English language definitions
       
   Since fetching the article overview of the newsserver takes quite some
   time, newsportal caches this data in the directory spool/. Any file
   can be put in this directory, they will automatically be regenerated.
   
Installation:

    1. download the zip or tar.gz archive
    2. unzip it to a directory
    3. The fileconfig.inc must be edited with your settings (the most
       important variables are: $server, $port, $title and $readonly).
    4. Write the names of all groups newsportal should show into the file
       groups.txt. Behind the groupname, seperated by a blank, a
       description of the group can be added. If the description is
       missing, newsportal will try to request the description from the
       newsserver.
    5. The spool directory has to be created and configured to grant read
       an write access to the newsserver ("chmod 777 spool" ).
    6. Adjust the Codepage in head.inc. If you are in western europe or
       the USA the predefined iso-8859-1 is correct and you don't have to
       change it.
       
Configuration

   The following adjustments can be made in config.inc
   
   Directories and files:
     * $file_newsportal="newsportal.php":Name of the file containing the
       newsportal-functions.
     * $file_groups="index.php": The file which shows the list of
       available newsgroups.
     * $file_thread="thread.php": The file which shows the article-thread
       of a selected newsgroup
     * $file_article="article.php": Displays an article
     * $file_post="post.php": The file which allows you to post an
       article to a newsgroup. This file can be removed, if the system is
       set on readonly (see below).
     * $file_language="english.lang": Reference to the language
       definition file.
     * $file_footer: Optionally, the name of a file can be indicated,
       which will be attached to every article posted to a newsgroup.
       
   Newsserver setip
     * $server : Hostname or IP of the newsserver
     * $port : Port of the newsserver, normally 119
     * $post_server: Optionally an extra newsserver can be indicated here
       which is used by post.php3 for writing articles. This is useful if
       two newsservers need to be accessed, a fast read-only server and a
       slow server to post articles. Be aware that it might take some
       time until the posted article will show up on your main newsserver
       ($server), which you use to read articles.
     * $post_port : Port of your post-newsserver
     * $server_auth_user: If the newsserver requires authentication by
       name and password put your username here. Otherwise just set the
       variable to "".
     * $server_auth_pass: Put your password here.
       
   Thread Layout
     * $treestyle :The appearance of the message tree:
          + 0: Simple listing of the articles
          + 1: Easy listing of the articles, with some more HTML tags
          + 2: Simple listing in a table
          + 3: Threaded with HTML-tags (UL, li)
          + 4: Threaded with text characters
          + 5: Threaded with graphical images
          + 6: Threaded with text characters and table
          + 7: Threaded with graphical images table
     * $thread_fontPre: The code given here is put in front of every text
       fragment in thread.php3, i.e. font face or size can be set.
     * $thread_fontPost: The same as $thread_fontPre, only code is
       appended at the end of the text.
     * $thread_showDate,
       $thread_showSubject,
       $thread_showAuthor:
          + true: the date / the subject / the author are displayed in
            the thread
          + false: output is suppressed.
     * $thread_maxSubject : Maximum number of characters of the subject
       displayed
     * $maxarticles: This number indicates the maximum amount of overview
       data of a newsgroup newsportal tries to get from the newsserver.
       "0" means no limitation. $maxarticles also indicates the amount of
       articles to be stored in the spoolfiles. A lower value means less
       work for newsportal
     * $maxarticles_extra: The problem with $maxarticles is that all
       article data must be completely requested again by the new server,
       if the indicated value is exceeded. $maxarticles_extra can be set
       to prevent this. The article-spool will only be restructured if
       $maxarticles + $maxarticles_extra articles are present, whereby
       $maxarticles many article data are requested. Only if an exact
       given number of articles should be displayed on the web page, the
       value of this variable schould be set "0".
     * $age_count : Number of different age levels for the coloured
       marking of articles
     * $$age_time[n] : maximal age of an article in seconds, so that the
       article gets marked with the colour $age_color[n]. n is a natural
       number > = 1 and all numbers from 1 to n must be assigned, gaps
       are not permitted.
     * $age_color[n]: The colour in which the article is marked
     * $thread_sorting : The sort sequence for the articles:
          + 0: No assortment, articles are displayed in the order in
            which they are polled from the server. This is nearly like
            ascending assortment.
          + 1: ascending assortment, the oldest articles to the top.
          + -1: descending assortment, the newest articles to the top.
     * $articles_per_page: If this value is not 0, the maximum amount of
       articles is given, which are to be displayed on one page at the
       same time. The thread will be split into individual pages.
     * $startpage: In connection with $$articles_per_page the variable
       indicates, which page is to be displayed first:
          + "first": The page with the newest articles
          + "last": the page with the oldest articles
       The specification should be co-ordinated with $thread_sorting.
       "first" for 0 and 1, and "last" for -1.
       
   Article layout
     * $article_show["Subject"],
       $article_show["From"],
       $article_show["Newsgroups"],
       $article_show["Organization"],
       $article_show["Date"],
       $article_show["Message-ID"],
       $article_show["User-Agent"],
       $article_show["References"]: "true" displays the respective header
       line in article.php3, by "false" it is suppressed.
       
   Frame support
   Example files for the frame support are located in extras/frames/. In
   this section the names of the frames can be defined. If you want to
   use frames you have to set the variable $frame_thread to
   "thread_frameset.php3".
     * $frame_articles: Name of the article frame. Must be the same as
       defined in thread_frameset.php3.
     * $frame_thread: Name of the thread frame.
     * $frame_groups: Name of the frame for the grouplist, normally set
       to "_top" to open a new frameset.
     * $frame_post : Name of the frame for post.php3
     * $frame_threadframeset : Name of the frame, in which the frameset
       is to appear, which takes up the article and thread Frames.
       Normally set to "_top".
     * $frame_externallink: Target frame for external links within
       articles.
       
   Safety settings
     * $send_poster_host: "true" means that a header-line named
       "X-HTTP-Posting-Host:" will be attached to every posted article,
       set to the hostname of the user who wrote the article.
     * $readonly : if set to "true", the newsportal is read-only. The
       file post.php3 can be safely removed.
     * $testgroup : if set to "true" newsportal checks if a group is
       listed in groups.txt when accessed through thread.php3. Otherwise
       a group could be seen simply entering the right URL, although the
       group is not displayed in the group list.
     * $validate_email : Sets how newsportal checks an email address in
       post.php3 for syntax:
          + 0: no examination. Not recommended, since the newsserver will
            give an error message, if the address is not syntactically
            correct.
          + 1: Checks the address on syntactic correctness.
          + 2: Additionally a MX or A record is checked for the
            domain-name of the e-mail address. Newsportal performs a
            hostname lookup.
       
   General setting
     * $title: The value of this variable is put in the title-header of
       the generated webpages.
     * $organization : Name of your organization. Put after the
       "Organization:"-header when posting articles.
     * $setcookies : Permits the user to save his name and his
       email-address as cookies in his browser.
     * $compress_spoolfiles: Sets whether the spool files should be
       compressed or not. This is recommended under normal conditions,
       since the size of the spoolfiles shrinks approximately to about
       15% of the original size. Be aware that some PHP-Versions do not
       support compressing
       
Safety notes

   A few things must be kept in mind to not allow newsportal to open
   safety-holes:
     * config.inc can be requested by every user that knows the filename,
       if you do not move it to a protected area of your webserver.
       
   This script was originally (and actually still) only meant for access
   to local newsgroups. If you use it with UseNet newsgroups, following
   problems could show up:
     * Articles could be posted anonymously (i.e. spamming), see
       $send_poster_host
     * Newsportal produces 8-bit header lines (i.e. the Subject), which
       is not permitted. However there do not seem to be any problems.
     * There are lists with so-called "open" newsservers in the internet.
       Mostly "open" doesn't mean for this server that everyone is
       allowed to use this server. Normally it means that the operator of
       the server forgot to protect his server adequatly. So before using
       an "open" newsserver, you should make sure that the operator
       permits the use of his server for newsportal.
     * Posting articles anonymously is not accepted in most UseNet
       groups. Before you give writing access to a newsgroup, you should
       ask the users in the newsgroup if they have no objections. Do not
       give public write access on UseNet newsgroups, if you do not know
       exactly, what you are doing!
       
   The author reserves the right not to be responsible for the
   topicality, correctness, completeness or quality of the program
   provided. Liability claims regarding damage caused by the program
   provided, will therefore be rejected.
   In other words: Use this program at your own risk !
   
Compatibility

   Newsportal should work with every phpserver with php3 support and
   every newsserver. Webserver and newsserver do not need to run on the
   same machine.
   
Contact

   Florian Amrhein
   email: florian.amrhein@gmx.de
   WWW: http://florian-amrhein.de
