

# DROP TABLE IF EXISTS `imenu`;

CREATE TABLE `imenu` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `title` varchar(150) NOT NULL default '',
  `hide` tinyint(4) unsigned NOT NULL default '0',
  `link` varchar(255) default NULL,
  `target` varchar(255) default NULL,
  `groups` varchar(255) default NULL,
  `users` varchar(255) default NULL,
  `weight` tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `index` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

INSERT INTO `imenu` (`id`, `title`, `hide`, `link`, `target`, `groups`, `users`, `weight`) VALUES ('', 'maxon.net | target example _self, _blank, _parent, etc.', 0, 'http://www.maxon.net', '_blank', '1|2', '', 1);
INSERT INTO `imenu` (`id`, `title`, `hide`, `link`, `target`, `groups`, `users`, `weight`) VALUES ('', 'realBasic | 2 user only example, separeted by a |', 0, 'http://www.realbasic.com', '_blank', '', 'luinithil|d_bass', 3);
INSERT INTO `imenu` (`id`, `title`, `hide`, `link`, `target`, `groups`, `users`, `weight`) VALUES ('', 'News | example of a module link , this is the mod dir name', 0, '[news]', '_self', '1|2|3', '', 2);
INSERT INTO `imenu` (`id`, `title`, `hide`, `link`, `target`, `groups`, `users`, `weight`) VALUES ('', 'Main | header example. NOTE : no link !', 0, '', '', '1|2|3', '', 0);

    

