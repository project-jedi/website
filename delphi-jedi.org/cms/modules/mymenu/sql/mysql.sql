# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
# --------------------------------------------------------

#
# Table structure for table `mymenu`
#

CREATE TABLE mymenu (
  menuid int(4) unsigned NOT NULL auto_increment,
  position int(4) unsigned NOT NULL,
  indent int(2) unsigned NOT NULL default '0',
  itemname varchar(60) NOT NULL default '',
  margin varchar(12) NOT NULL default '0pt',
  itemurl varchar(100) NOT NULL default '',
  bold tinyint(1) NOT NULL default '0',
  membersonly tinyint(1) NOT NULL default '1',
  status tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (menuid),
  KEY idxmymenustatus (status)
) TYPE=MyISAM;
