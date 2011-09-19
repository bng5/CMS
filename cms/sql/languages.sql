
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(6) NOT NULL,
  `superior` int(10) unsigned default NULL,
  `dir` enum('ltr','rtl') NOT NULL default 'ltr',
  `leng_poromision` tinyint(1) default NULL,
  `estado` tinyint(1) unsigned NOT NULL default '0',
  `nombre_nativo` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

