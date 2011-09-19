
CREATE TABLE `secciones_nombres` (
  `id` int(10) unsigned NOT NULL,
  `leng_id` tinyint(3) unsigned NOT NULL,
  `titulo` varchar(50) collate utf8_unicode_ci NOT NULL,
  `url` varchar(100) character set ascii default NULL,
  PRIMARY KEY  (`id`,`leng_id`),
  UNIQUE KEY `new_index` (`leng_id`,`url`),
  KEY `seccion_titulo` (`titulo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
