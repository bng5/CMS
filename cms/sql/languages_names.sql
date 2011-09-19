
CREATE TABLE `lenguajes_nombres` (
  `id` int(10) unsigned NOT NULL,
  `leng_id` int(10) unsigned NOT NULL,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`,`leng_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
