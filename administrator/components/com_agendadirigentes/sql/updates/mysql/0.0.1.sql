#agendas anteriores
DROP TABLE IF EXISTS `#__agendadedirigentes_agendasantigas`;
CREATE TABLE `#__agendadedirigentes_agendasantigas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `autoridade_id` int(10) unsigned NOT NULL,
  `src` varchar(255) NOT NULL,
  `ano` int(10) unsigned NOT NULL,
  `inicio_validade` date NOT NULL,
  `fim_validade` date NOT NULL,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_autoridade_id` (`autoridade_id`),
  KEY `idx_inicio_validade` (`inicio_validade`),
  KEY `idx_fim_validade` (`fim_validade`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#tabela de autoridades
DROP TABLE IF EXISTS `#__agendadedirigentes_autoridades`;
CREATE TABLE `#__agendadedirigentes_autoridades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cat_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL,
  `owner_id` int(5) DEFAULT '0',
  `block` int(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_cat_id` (`cat_id`),
  KEY `idx_cargo_id` (`cargo_id`),
  KEY `idx_owner_id` (`owner_id`),
  KEY `idx_block` (`block`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#relacionamento autoridades e eventos
DROP TABLE IF EXISTS `#__agendadedirigentes_autoridades_has_eventos`;
CREATE TABLE `#__agendadedirigentes_autoridades_has_eventos` (
  `autoridade_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `owner` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Status 1: comum; Status 2: sobreposto',
  PRIMARY KEY (`autoridade_id`,`evento_id`),
  KEY `idx_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#autorizacoes de cadastro
DROP TABLE IF EXISTS `#__agendadedirigentes_autorizacoes_cadastro`;
CREATE TABLE `#__agendadedirigentes_autorizacoes_cadastro` (
  `user_id` int(11) NOT NULL,
  `autoridade_id` int(11) NOT NULL,
  `owner` int(11) DEFAULT '0',
  PRIMARY KEY (`user_id`,`autoridade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#cargos de autoridades
DROP TABLE IF EXISTS `#__agendadedirigentes_cargos`;
CREATE TABLE `#__agendadedirigentes_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cat_id` int(5) NOT NULL DEFAULT '0',
  `group_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_cat_id` (`cat_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#emails para avisos administrativos: tabela será eliminada
DROP TABLE IF EXISTS `#__agendadedirigentes_emails`;
CREATE TABLE `#__agendadedirigentes_emails` (
  `emails` varchar(255) DEFAULT NULL,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#tabela principal de eventos, sera transformada em compromissos
DROP TABLE IF EXISTS `#__agendadedirigentes_eventos`;
CREATE TABLE `#__agendadedirigentes_eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `pauta` text,
  `local` varchar(255) DEFAULT NULL,
  `params` tinytext,
  `status` int(1) unsigned DEFAULT '0',
  `exibir_local` int(11) DEFAULT NULL,
  `dia_todo` int(1) unsigned DEFAULT '0',
  `exibir_horario_fim` int(11) DEFAULT NULL,
  `checked_out` int(11) DEFAULT '0',
  `catid` int(11) DEFAULT '0',
  `horario_fim` time DEFAULT NULL,
  `exibir_horario_inicio` int(11) DEFAULT NULL,
  `order` int(5) DEFAULT NULL,
  `alterado` int(11) DEFAULT '0',
  `data_alteracao` datetime DEFAULT NULL,
  `id_alterado` int(11) DEFAULT '0',
  `published` int(11) DEFAULT '0',
  `data_final` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_data` (`data`),
  KEY `idx_horario_inicio` (`horario_inicio`),
  KEY `idx_data_final` (`data_final`),
  KEY `idx_published` (`published`),
  KEY `idx_horario_fim` (`horario_fim`),
  KEY `idx_catid` (`catid`),
  KEY `idx_status` (`status`),
  KEY `idx_outros` (`dia_todo`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

#servers cadastro, tabela também será eliminada na versão mais recente
DROP TABLE IF EXISTS `#__agendadedirigentes_servers_cadastro`;
CREATE TABLE `#__agendadedirigentes_servers_cadastro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_ip` varchar(65) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;