# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `bb_categories`
#

CREATE TABLE bb_categories (
  cat_id smallint(3) unsigned NOT NULL auto_increment,
  cat_title varchar(100) NOT NULL default '',
  cat_order varchar(10) default NULL,
  PRIMARY KEY  (cat_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_forum_access`
#

CREATE TABLE bb_forum_access (
  forum_id int(4) unsigned NOT NULL default '0',
  user_id int(5) unsigned NOT NULL default '0',
  can_post tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (forum_id,user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_forum_mods`
#

CREATE TABLE bb_forum_mods (
  forum_id int(4) unsigned NOT NULL default '0',
  user_id int(5) unsigned NOT NULL default '0',
  KEY forum_user_id (forum_id,user_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_forums`
#

CREATE TABLE bb_forums (
  forum_id int(4) unsigned NOT NULL auto_increment,
  forum_name varchar(150) NOT NULL default '',
  forum_desc text,
  forum_access tinyint(2) NOT NULL default '1',
  forum_moderator int(2) default NULL,
  forum_topics int(8) NOT NULL default '0',
  forum_posts int(8) NOT NULL default '0',
  forum_last_post_id int(5) unsigned NOT NULL default '0',
  cat_id int(2) NOT NULL default '0',
  forum_type int(10) default '0',
  allow_html ENUM('0','1') DEFAULT '0' NOT NULL,
  allow_sig ENUM('0','1') DEFAULT '0' NOT NULL,
  posts_per_page TINYINT(3) UNSIGNED DEFAULT '20' NOT NULL,
  hot_threshold TINYINT(3) UNSIGNED DEFAULT '10' NOT NULL,
  topics_per_page TINYINT(3) UNSIGNED DEFAULT '20' NOT NULL,
  PRIMARY KEY  (forum_id),
  KEY forum_last_post_id (forum_last_post_id),
  KEY cat_id (cat_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_posts`
#

CREATE TABLE bb_posts (
  post_id int(8) unsigned NOT NULL auto_increment,
  pid int(8) NOT NULL default '0',
  topic_id int(8) NOT NULL default '0',
  forum_id int(4) NOT NULL default '0',
  post_time int(10) NOT NULL default '0',
  uid int(5) unsigned NOT NULL default '0',
  poster_ip varchar(15) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  nohtml tinyint(1) NOT NULL default '0',
  nosmiley tinyint(1) NOT NULL default '0',
  icon varchar(25) NOT NULL default '',
  attachsig tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (post_id),
  KEY uid (uid),
  KEY pid (pid),
  KEY subject (subject(40)),
  KEY forumid_uid (forum_id, uid),
  KEY topicid_uid (topic_id, uid),
  KEY topicid_postid_pid (topic_id, post_id, pid),
  FULLTEXT KEY search (subject)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_posts_text`
#

CREATE TABLE bb_posts_text (
  post_id int(8) unsigned NOT NULL auto_increment,
  post_text text,
  PRIMARY KEY  (post_id),
  FULLTEXT KEY search (post_text)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bb_topics`
#

CREATE TABLE bb_topics (
  topic_id int(8) unsigned NOT NULL auto_increment,
  topic_title varchar(255) default NULL,
  topic_poster int(5) NOT NULL default '0',
  topic_time int(10) NOT NULL default '0',
  topic_views int(5) NOT NULL default '0',
  topic_replies int(4) NOT NULL default '0',
  topic_last_post_id int(8) unsigned NOT NULL default '0',
  forum_id int(4) NOT NULL default '0',
  topic_status tinyint(1) NOT NULL default '0',
  topic_sticky tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (topic_id),
  KEY forum_id (forum_id),
  KEY topic_last_post_id (topic_last_post_id),
  KEY topic_poster (topic_poster),
  KEY topic_forum (topic_id,forum_id),
  KEY topic_sticky (topic_sticky)
) TYPE=MyISAM;
