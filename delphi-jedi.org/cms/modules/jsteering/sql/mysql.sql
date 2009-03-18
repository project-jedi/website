#
# Table structure for table `jsteering`
#

CREATE TABLE jsteering (
  storyid int(8) NOT NULL auto_increment,
  blockid int(8) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  text text default NULL,
  visible tinyint(1) NOT NULL default '0',
  homepage tinyint(1) NOT NULL default '0',
  nohtml tinyint(1) NOT NULL default '0',
  nosmiley tinyint(1) NOT NULL default '0',
  nobreaks tinyint(1) NOT NULL default '0',
  nocomments tinyint(1) NOT NULL default '0',
  link tinyint(1) NOT NULL default '0',
  address varchar(255) default NULL,
  submenu tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (storyid),
  KEY title (title(40))
) TYPE=MyISAM;