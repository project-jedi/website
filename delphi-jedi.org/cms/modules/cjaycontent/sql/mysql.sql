CREATE TABLE cjaycontent (
  id int(5) unsigned NOT NULL auto_increment,
  title varchar(150) NOT NULL default '',
  type tinyint(10) unsigned NOT NULL default '0',
  design tinyint(4) unsigned NOT NULL default '0',
  hide tinyint(4) unsigned NOT NULL default '0',
  adress varchar(255) default NULL,
  comment varchar(255) default NULL,
  content text NOT NULL,
  submitter varchar(255) default NULL,
  date int(10) unsigned NOT NULL default '0',
  image varchar(255) default NULL,
  hits int(6) unsigned NOT NULL default '0',
  weight int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY  `index`(`id`)
  ) TYPE=MyISAM;

INSERT INTO cjaycontent VALUES (1, 'C-JAY Contet Start', 0, 0, 1, 'DO_NOT_DELETE.php', 'DO NOT DELETE THIS FILE!!', '', '1', 1050232144, NULL, 37, 0);

