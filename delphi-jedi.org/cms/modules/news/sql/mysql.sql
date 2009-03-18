#
# Table structure for table `stories`
#

CREATE TABLE stories (
  storyid int(8) unsigned NOT NULL auto_increment,
  uid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  created int(10) unsigned NOT NULL default '0',
  published int(10) unsigned NOT NULL default '0',
  expired int(10) UNSIGNED NOT NULL default '0',
  hostname varchar(20) NOT NULL default '',
  nohtml tinyint(1) NOT NULL default '0',
  nosmiley tinyint(1) NOT NULL default '0',
  hometext text NOT NULL,
  bodytext text NOT NULL,
  counter int(8) unsigned NOT NULL default '0',
  topicid smallint(4) unsigned NOT NULL default '1',
  ihome tinyint(1) NOT NULL default '0',
  notifypub tinyint(1) NOT NULL default '0',
  story_type varchar(5) NOT NULL default '',
  topicdisplay tinyint(1) NOT NULL default '0',
  topicalign char(1) NOT NULL default 'R',
  comments smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (storyid),
  KEY idxstoriestopic (topicid),
  KEY ihome (ihome),
  KEY uid (uid),
  KEY published_ihome (published,ihome),
  KEY title (title(40)),
  KEY created (created),
  FULLTEXT KEY search (title,hometext,bodytext)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `topics`
#

CREATE TABLE topics (
  topic_id smallint(4) unsigned NOT NULL auto_increment,
  topic_pid smallint(4) unsigned NOT NULL default '0',
  topic_imgurl varchar(20) NOT NULL default '',
  topic_title varchar(50) NOT NULL default '',
  PRIMARY KEY  (topic_id),
  KEY pid (topic_pid)
) TYPE=MyISAM;

INSERT INTO topics VALUES (1,0,'xoops.gif','XOOPS');
