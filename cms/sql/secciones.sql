
CREATE TABLE `secciones` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `superior_id` int(10) unsigned NOT NULL default '0',
  `orden` int(10) unsigned default NULL,
  `identificador` varchar(32) collate ascii_bin NOT NULL,
  `info` tinyint(1) NOT NULL default '0',
  `items` tinyint(1) NOT NULL default '0',
  `items_anidados` tinyint(1) NOT NULL default '0',
  `categorias` tinyint(1) NOT NULL default '0',
  `categorias_prof` tinyint(1) NOT NULL default '0',
  `salida_sitio` tinyint(3) unsigned NOT NULL default '0' COMMENT '0 - no, 1 - si, 2 - sólo con login',
  `menu` tinyint(1) NOT NULL default '1',
  `rev` int(10) unsigned NOT NULL default '1',
  `propietario` int(10) unsigned NOT NULL,
  `grupo` int(10) unsigned NOT NULL,
  `permiso_grupo` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `identificador` (`identificador`),
  KEY `rev` (`rev`),
  KEY `menu` (`menu`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=ascii COLLATE=ascii_bin;
