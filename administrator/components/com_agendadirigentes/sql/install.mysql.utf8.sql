#compromissos anteriores
DROP TABLE IF EXISTS `#__agendadedirigentes_compromissos_anteriores`;
CREATE TABLE `#__agendadedirigentes_compromissos_anteriores` (
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
DROP TABLE IF EXISTS `#__agendadedirigentes_dirigentes`;
CREATE TABLE `#__agendadedirigentes_dirigentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `catid` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `block` int(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_catid` (`catid`),
  KEY `idx_cargo_id` (`cargo_id`),
  KEY `idx_block` (`block`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#relacionamento dirigentes e eventos
DROP TABLE IF EXISTS `#__agendadedirigentes_dirigentes_compromissos`;
CREATE TABLE `#__agendadedirigentes_dirigentes_compromissos` (
  `dirigente_id` int(11) NOT NULL,
  `compromisso_id` int(11) NOT NULL,
  `owner` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Status 1: comum; Status 2: sobreposto',
  PRIMARY KEY (`dirigente_id`,`compromisso_id`),
  KEY `idx_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#autorizacoes de cadastro
DROP TABLE IF EXISTS `#__agendadedirigentes_dirigentes_permissoes`;
CREATE TABLE `#__agendadedirigentes_dirigentes_permissoes` (
  `user_id` int(11) NOT NULL,
  `dirigente_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`dirigente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#cargos de dirigentes
DROP TABLE IF EXISTS `#__agendadedirigentes_cargos`;
CREATE TABLE `#__agendadedirigentes_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_catid` (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#tabela principal de eventos, sera transformada em compromissos
DROP TABLE IF EXISTS `#__agendadedirigentes_compromissos`;
CREATE TABLE `#__agendadedirigentes_compromissos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `catid` int(11) DEFAULT 0,
  `published` int(11) DEFAULT 0,
  `data_inicial` date DEFAULT '0000-00-00',
  `horario_inicio` time DEFAULT '00:00:00',
  `exibir_horario_inicio` int(1) DEFAULT 1,
  `data_final` date DEFAULT '0000-00-00',
  `horario_fim` time DEFAULT '00:00:00',
  `exibir_horario_fim` int(1) DEFAULT 1,
  `dia_todo` int(1) unsigned DEFAULT 0,
  `local` varchar(255) DEFAULT NULL,
  `exibir_local` int(1) DEFAULT 1,
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
DROP TABLE IF EXISTS `#__agendadedirigentes_servers_cadastro`;
CREATE TABLE `#__agendadedirigentes_servers_cadastro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_ip` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;