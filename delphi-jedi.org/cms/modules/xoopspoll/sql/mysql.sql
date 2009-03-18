# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `poll_data`
#

CREATE TABLE xoopspoll_option (
  option_id int(10) unsigned NOT NULL auto_increment,
  poll_id mediumint(8) unsigned NOT NULL default '0',
  option_text varchar(255) NOT NULL default '',
  option_count smallint(5) unsigned NOT NULL default '0',
  option_color varchar(25) NOT NULL default '',
  PRIMARY KEY  (option_id),
  KEY poll_id (poll_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mpn_poll_desc`
#

CREATE TABLE xoopspoll_desc (
  poll_id mediumint(8) unsigned NOT NULL auto_increment,
  question varchar(255) NOT NULL default '',
  description tinytext NOT NULL default '',
  user_id int(5) unsigned NOT NULL default '0',
  start_time int(10) unsigned NOT NULL default '0',
  end_time int(10) unsigned NOT NULL default '0',
  votes smallint(5) unsigned NOT NULL default '0',
  voters smallint(5) unsigned NOT NULL default '0',
  multiple tinyint(1) unsigned NOT NULL default '0',
  display tinyint(1) unsigned NOT NULL default '0',
  weight smallint(5) unsigned NOT NULL default '0',
  mail_status tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (poll_id),
  KEY end_time (end_time),
  KEY display (display)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `poll_log`
#

CREATE TABLE xoopspoll_log (
  log_id int(10) unsigned NOT NULL auto_increment,
  poll_id mediumint(8) unsigned NOT NULL default '0',
  option_id int(10) unsigned NOT NULL default '0',
  ip char(15) NOT NULL default '',
  user_id int(5) unsigned NOT NULL default '0',
  time int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (log_id),
  KEY poll_id_user_id (poll_id, user_id),
  KEY poll_id_ip (poll_id, ip)
) TYPE=MyISAM;
# --------------------------------------------------------


INSERT INTO xoopspoll_desc VALUES (1, 'What do you think about XOOPS?', 'A simple survey about the content management script used on this site.', 1, 1020447898, 1051983686, 0, 0, 0, 1, 0, 0);
INSERT INTO xoopspoll_option VALUES (1, 1, 'Excellent!', 0, 'aqua.gif');
INSERT INTO xoopspoll_option VALUES (2, 1, 'Cool', 0, 'blue.gif');
INSERT INTO xoopspoll_option VALUES (3, 1, 'Hmm..not bad', 0, 'brown.gif');
INSERT INTO xoopspoll_option VALUES (4, 1, 'What the hell is this?', 0, 'darkgreen.gif');
