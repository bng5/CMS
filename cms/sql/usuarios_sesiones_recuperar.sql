
CREATE TABLE `usuarios_sesiones_recuperar` (
  `usuario_id` int(10) unsigned NOT NULL,
  `pase` char(32) NOT NULL,
  `tiempo` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `recuperar` tinyint(1) NOT NULL default '0',
  KEY `usuario` (`usuario_id`),
  KEY `pase` (`pase`),
  KEY `creado` (`tiempo`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii;
