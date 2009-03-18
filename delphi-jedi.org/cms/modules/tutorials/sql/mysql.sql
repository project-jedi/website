#
# Tabellenstruktur für Tabelle `tutorials`
#

CREATE TABLE tutorials (
  tid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  gid int(11) NOT NULL default '0',
  tname text NOT NULL,
  tdesc text NOT NULL,
  timg varchar(255) NOT NULL default '',
  tcont text NOT NULL,
  tlink varchar(255) NOT NULL default '',
  tauthor text NOT NULL,
  status tinyint(1) NOT NULL default '0',
  codes tinyint(1) NOT NULL default '0',
  hits int(11) NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(5) unsigned NOT NULL default '0',
  date int(10) NOT NULL default '0',
  submitter int(11) unsigned NOT NULL default '0',
  dir int(10) NOT NULL default '0',
  timgwidth int(6) NOT NULL default '0',
  timgheight int(6) NOT NULL default '0',
  PRIMARY KEY  (tid),
  KEY cid (cid),
  KEY rating (rating),
  KEY status (status),
  KEY gid (gid),
  KEY tlink (tlink)
) TYPE=MyISAM;




#
# Tabellenstruktur für Tabelle `tutorials_categorys`
#

CREATE TABLE tutorials_categorys (
  cid int(11) NOT NULL auto_increment,
  scid int(11) NOT NULL default '0',
  cname varchar(40) NOT NULL default '',
  cdesc text NOT NULL,
  cimg varchar(255) NOT NULL default '',
  cimgwidth int(6) NOT NULL default '0',
  cimgheight int(6) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY scid (scid),
  KEY cname (cname)
) TYPE=MyISAM;



#
# Tabellenstruktur für Tabelle `tutorials_groups`
#

CREATE TABLE tutorials_groups (
  gid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  pos int(11) NOT NULL default '0',
  gname varchar(40) NOT NULL default '',
  PRIMARY KEY  (gid),
  KEY cid (cid),
  KEY pos (pos)
) TYPE=MyISAM;


#
# Tabellenstruktur für Tabelle `tutorials_votedata`
#

CREATE TABLE tutorials_votedata (
  ratingid int(15) unsigned NOT NULL auto_increment,
  tid int(11) unsigned NOT NULL default '0',
  ratinguser int(5) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY ratingtimestamp (ratingtimestamp),
  KEY tid (tid)
) TYPE=MyISAM;