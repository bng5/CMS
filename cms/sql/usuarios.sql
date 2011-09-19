

CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(70) collate utf8_unicode_ci NOT NULL,
  `estado_id` tinyint(3) unsigned NOT NULL default '0',
  `nombre_mostrar` varchar(30) character set utf8 NOT NULL,
  `clave` char(34) character set ascii NOT NULL,
  `email` varchar(70) collate utf8_unicode_ci NOT NULL,
  `aut` char(32) character set ascii default NULL,
  `created_date` int(11) NOT NULL,
  `creado_por` int(10) unsigned default NULL,
  `su` tinyint(1) NOT NULL default '0',
  `leng_id` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `usuario` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
