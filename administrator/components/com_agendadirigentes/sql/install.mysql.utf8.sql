#compromissos anteriores
DROP TABLE IF EXISTS `#__agendadirigentes_compromissos_anteriores`;
CREATE TABLE `#__agendadirigentes_compromissos_anteriores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dirigente_id` int(10) unsigned NOT NULL,
  `src` varchar(255) NOT NULL,
  `data_inicial` date NOT NULL,
  `data_final` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `published` int(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_dirigente_id` (`dirigente_id`),
  KEY `idx_inicio_validade` (`data_inicial`),
  KEY `idx_fim_validade` (`data_final`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#tabela de dirigentes
DROP TABLE IF EXISTS `#__agendadirigentes_dirigentes`;
CREATE TABLE `#__agendadirigentes_dirigentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `catid` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `state` TINYINT(3) NOT NULL DEFAULT '0',
  `block` int(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(3) unsigned NOT NULL DEFAULT '0',
  `interino` int(1) unsigned NOT NULL DEFAULT '0',
  `em_atividade` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_catid` (`catid`),
  KEY `idx_cargo_id` (`cargo_id`),
  KEY `idx_block` (`block`),
  KEY `idx_em_atividade` (`em_atividade`),
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#relacionamento dirigentes e eventos
DROP TABLE IF EXISTS `#__agendadirigentes_dirigentes_compromissos`;
CREATE TABLE `#__agendadirigentes_dirigentes_compromissos` (
  `dirigente_id` int(11) NOT NULL,
  `compromisso_id` int(11) NOT NULL,
  `owner` int(11) DEFAULT '0',
  `sobreposto` int(11) NOT NULL DEFAULT '0' COMMENT 'Compromissos sobrepostos não são exibidos porque uma autoridade convocou outra para reunião.',
  PRIMARY KEY (`dirigente_id`,`compromisso_id`),
  KEY `idx_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#autorizacoes de cadastro
DROP TABLE IF EXISTS `#__agendadirigentes_dirigentes_permissoes`;
CREATE TABLE `#__agendadirigentes_dirigentes_permissoes` (
  `user_id` int(11) NOT NULL,
  `dirigente_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`dirigente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#cargos de dirigentes
DROP TABLE IF EXISTS `#__agendadirigentes_cargos`;
CREATE TABLE `#__agendadirigentes_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_catid` (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#insercoes temporarias, pacote padrao
 INSERT INTO `portal_modelo_3xdev`.`x3dts_assets` VALUES 
 (227,1,409,418,1,'com_agendadirigentes','com_agendadirigentes','{}'),
 (228,227,410,411,2,'com_agendadirigentes.category.49','Gabinete do Ministro',''),
 (229,227,412,413,2,'com_agendadirigentes.category.50','Secretaria Executiva',''),
 (230,227,414,417,2,'com_agendadirigentes.category.51','Diretoria A',''),
 (231,230,415,416,3,'com_agendadirigentes.category.52','Coordenação B','');

 INSERT INTO `portal_modelo_3xdev`.`x3dts_categories` VALUES  
 (49,228,1,79,80,1,'gabinete-do-ministro','com_agendadirigentes','Gabinete do Ministro',0x676162696E6574652D646F2D6D696E697374726F,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',430,'2014-08-20 03:04:04',0,'0000-00-00 00:00:00',0,'*',1),
 (50,229,1,81,82,1,'secretaria-executiva','com_agendadirigentes','Secretaria Executiva',0x736563726574617269612D657865637574697661,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',430,'2014-08-20 03:04:11',0,'0000-00-00 00:00:00',0,'*',1),
 (51,230,1,83,86,1,'diretoria-a','com_agendadirigentes','Diretoria A',0x64697265746F7269612D61,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',430,'2014-08-20 03:04:18',0,'0000-00-00 00:00:00',0,'*',1),
 (52,231,51,84,85,2,'diretoria-a/coordenacao-b','com_agendadirigentes','Coordenação B',0x636F6F7264656E6163616F2D62,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',430,'2014-08-20 03:04:26',0,'0000-00-00 00:00:00',0,'*',1);

INSERT INTO `portal_modelo_3xdev`.`x3dts_agendadirigentes_cargos` VALUES 
 (1,'Ministro',49),
 (2,'Chefe de Gabinete',49),
 (3,'Secretário Executivo',50),
 (4,'Diretor',51),
 (5,'Coordenador',52);
#fim insercoes temporarias, pacote padrao

#tabela principal de eventos, sera transformada em compromissos
DROP TABLE IF EXISTS `#__agendadirigentes_compromissos`;
CREATE TABLE `#__agendadirigentes_compromissos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `catid` int(11) DEFAULT 0,
  `state` int(11) DEFAULT 0,
  `data_inicial` date DEFAULT '0000-00-00',
  `horario_inicio` time DEFAULT '00:00:00',
  `data_final` date DEFAULT '0000-00-00',
  `horario_fim` time DEFAULT '00:00:00',
  `dia_todo` int(1) unsigned DEFAULT 0,
  `local` varchar(255) DEFAULT NULL,
  `compromisso_alterado` int(1) DEFAULT 0,
  `description` text,
  `ordering` int(5) DEFAULT NULL,
  `params` tinytext,
  `created` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) DEFAULT 0,
  `modified` datetime DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) DEFAULT 0,
  `checked_out` int(11) DEFAULT '0',
  `checked_out_time` datetime DEFAULT '0000-00-00 00:00:00',
  `featured` int(1) DEFAULT 0,
  `language` char(7) DEFAULT NULL,
  `version` INTEGER DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_data_inicial` (`data_inicial`),
  KEY `idx_horario_inicio` (`horario_inicio`),
  KEY `idx_data_final` (`data_final`),
  KEY `idx_horario_fim` (`horario_fim`),
  KEY `idx_published` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_outros` (`dia_todo`,`compromisso_alterado`,`featured`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#servers cadastro, tabela também será eliminada na versão mais recente
DROP TABLE IF EXISTS `#__agendadirigentes_servers_cadastro`;
CREATE TABLE `#__agendadirigentes_servers_cadastro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_ip` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;