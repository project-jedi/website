# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `mydownloads_broken`
#

CREATE TABLE mydownloads_broken (
  reportid int(5) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  sender int(11) NOT NULL default '0',
  ip varchar(20) NOT NULL default '',
  PRIMARY KEY  (reportid),
  KEY lid (lid),
  KEY sender (sender),
  KEY ip (ip)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mydownloads_cat`
#

CREATE TABLE mydownloads_cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  PRIMARY KEY  (cid),
  KEY pid (pid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mydownloads_downloads`
#

CREATE TABLE mydownloads_downloads (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  homepage varchar(100) NOT NULL default '',
  version varchar(10) NOT NULL default '',
  size int(8) NOT NULL default '0',
  platform varchar(50) NOT NULL default '',
  logourl varchar(60) NOT NULL default '',
  submitter int(11) NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mydownloads_mod`
#

CREATE TABLE mydownloads_mod (
  requestid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  homepage varchar(100) NOT NULL default '',
  version varchar(10) NOT NULL default '',
  size int(8) NOT NULL default '0',
  platform varchar(50) NOT NULL default '',
  logourl varchar(60) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter int(11) NOT NULL default '0',
  PRIMARY KEY  (requestid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mydownloads_text`
#

CREATE TABLE mydownloads_text (
  lid int(11) unsigned NOT NULL default '0',
  description text NOT NULL,
  KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `mydownloads_votedata`
#

CREATE TABLE mydownloads_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
) TYPE=MyISAM;
