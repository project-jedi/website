CREATE TABLE xoopsheadline (
  headline_id smallint(3) unsigned NOT NULL auto_increment,
  headline_name varchar(255) NOT NULL default '',
  headline_url varchar(255) NOT NULL default '',
  headline_rssurl varchar(255) NOT NULL default '',
  headline_encoding varchar(15) NOT NULL default '',
  headline_cachetime mediumint(8) unsigned NOT NULL default '3600',
  headline_asblock tinyint(1) unsigned NOT NULL default '0',
  headline_display tinyint(1) unsigned NOT NULL default '0',
  headline_weight smallint(3) unsigned NOT NULL default '0',
  headline_mainfull tinyint(1) unsigned NOT NULL default '1',
  headline_mainimg tinyint(1) unsigned NOT NULL default '1',
  headline_mainmax tinyint(2) unsigned NOT NULL default '10',
  headline_blockimg tinyint(1) unsigned NOT NULL default '0',
  headline_blockmax tinyint(2) unsigned NOT NULL default '10',
  headline_xml text NOT NULL default '',
  headline_updated int(10) NOT NULL default'0',
  PRIMARY KEY  (headline_id)
) TYPE=MyISAM;



INSERT INTO xoopsheadline VALUES (1, 'XOOPS Official Website', 'http://www.xoops.org/', 'http://www.xoops.org/backend.php', 'ISO-8859-1', 86400, 0, 1, 0, 1, 1, 10, 0, 10, '', 0);
