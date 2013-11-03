-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.9


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema portal_modelo_3x
--

CREATE DATABASE IF NOT EXISTS portal_modelo_3x;
USE portal_modelo_3x;

--
-- Definition of table `portal_modelo_3x`.`pmgov2013_assets`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_assets`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set parent.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `level` int(10) unsigned NOT NULL COMMENT 'The cached level in the nested tree.',
  `name` varchar(50) NOT NULL COMMENT 'The unique name for the asset.\n',
  `title` varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_asset_name` (`name`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_assets`
--

/*!40000 ALTER TABLE `pmgov2013_assets` DISABLE KEYS */;
LOCK TABLES `pmgov2013_assets` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_assets` VALUES  (1,0,1,217,0,'root.1','Root Asset','{\"core.login.site\":{\"6\":1,\"2\":1},\"core.login.admin\":{\"6\":1},\"core.login.offline\":{\"6\":1},\"core.admin\":{\"8\":1},\"core.manage\":{\"7\":1},\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (2,1,1,2,1,'com_admin','com_admin','{}'),
 (3,1,3,6,1,'com_banners','com_banners','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (4,1,7,8,1,'com_cache','com_cache','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
 (5,1,9,10,1,'com_checkin','com_checkin','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
 (6,1,11,12,1,'com_config','com_config','{}'),
 (7,1,13,16,1,'com_contact','com_contact','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (8,1,17,166,1,'com_content','com_content','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1},\"core.edit.own\":[]}'),
 (9,1,167,168,1,'com_cpanel','com_cpanel','{}'),
 (10,1,169,170,1,'com_installer','com_installer','{\"core.admin\":[],\"core.manage\":{\"7\":0},\"core.delete\":{\"7\":0},\"core.edit.state\":{\"7\":0}}'),
 (11,1,171,172,1,'com_languages','com_languages','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (12,1,173,174,1,'com_login','com_login','{}'),
 (13,1,175,176,1,'com_mailto','com_mailto','{}'),
 (14,1,177,178,1,'com_massmail','com_massmail','{}'),
 (15,1,179,180,1,'com_media','com_media','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":{\"5\":1}}'),
 (16,1,181,182,1,'com_menus','com_menus','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (17,1,183,184,1,'com_messages','com_messages','{\"core.admin\":{\"7\":1},\"core.manage\":{\"7\":1}}'),
 (18,1,185,186,1,'com_modules','com_modules','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (19,1,187,190,1,'com_newsfeeds','com_newsfeeds','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (20,1,191,192,1,'com_plugins','com_plugins','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (21,1,193,194,1,'com_redirect','com_redirect','{\"core.admin\":{\"7\":1},\"core.manage\":[]}'),
 (22,1,195,196,1,'com_search','com_search','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1}}'),
 (23,1,197,198,1,'com_templates','com_templates','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (24,1,199,202,1,'com_users','com_users','{\"core.admin\":{\"7\":1},\"core.manage\":[],\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (25,1,203,206,1,'com_weblinks','com_weblinks','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1},\"core.create\":{\"3\":1},\"core.delete\":[],\"core.edit\":{\"4\":1},\"core.edit.state\":{\"5\":1},\"core.edit.own\":[]}'),
 (26,1,207,208,1,'com_wrapper','com_wrapper','{}'),
 (27,8,18,19,2,'com_content.category.2','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (28,3,4,5,2,'com_banners.category.3','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (29,7,14,15,2,'com_contact.category.4','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (30,19,188,189,2,'com_newsfeeds.category.5','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (31,25,204,205,2,'com_weblinks.category.6','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'),
 (32,24,200,201,1,'com_users.category.7','Uncategorised','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (33,1,209,210,1,'com_finder','com_finder','{\"core.admin\":{\"7\":1},\"core.manage\":{\"6\":1}}'),
 (34,1,211,212,1,'com_joomlaupdate','com_joomlaupdate','{\"core.admin\":[],\"core.manage\":[],\"core.delete\":[],\"core.edit.state\":[]}'),
 (35,37,21,22,3,'com_content.article.1','Editoria A','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (36,1,213,214,1,'com_jce','jce','{}'),
 (37,8,20,47,2,'com_content.category.8','Assuntos','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (38,37,23,24,3,'com_content.article.2','Pagina 1: titulo do texto institucional','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (39,43,32,33,6,'com_content.article.3','Página 2: título do texto institucional','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (40,43,34,35,6,'com_content.article.4','Página 3: título do texto institucional','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (41,37,25,38,3,'com_content.category.9','Editoria A','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (42,41,26,37,4,'com_content.category.10','Menu de 2. Nivel','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (43,42,27,36,5,'com_content.category.11','Menu de 3. Nivel','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (44,43,28,29,6,'com_content.category.12','Página 2: título do texto institucional','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (45,43,30,31,6,'com_content.category.13','Página 3: título do texto institucional','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (46,37,39,42,3,'com_content.category.14','Editoria B','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (47,37,43,46,3,'com_content.category.15','Editoria C','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (48,46,40,41,4,'com_content.article.5','Editoria B','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (49,47,44,45,4,'com_content.article.6','Editoria C','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (50,8,48,67,2,'com_content.category.16','Sobre','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (51,50,49,50,3,'com_content.article.7','Institucional','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (52,50,51,52,3,'com_content.article.8','Ações e Programas','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (53,50,53,54,3,'com_content.article.9','Auditoria','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (54,50,55,56,3,'com_content.article.10','Convênios','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (55,50,57,58,3,'com_content.article.11','Despesas','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (56,50,59,60,3,'com_content.article.12','Licitações e contratos','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (57,50,61,62,3,'com_content.article.13','Servidores','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (58,50,63,64,3,'com_content.article.14','Informações classificadas','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (59,50,65,66,3,'com_content.article.15','Serviço de Informação ao Cidadão (SIC)','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (60,8,68,107,2,'com_content.category.17','Últimas notícias','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (61,60,69,70,3,'com_content.category.18','Texto 1 - Título da notícia entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (62,60,75,76,3,'com_content.category.19','Texto 3 - Título da notícia entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (63,60,71,72,3,'com_content.category.20','Texto 4 - Título da notícia entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (64,60,73,74,3,'com_content.category.21','Release 1: Título do release entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (65,60,77,78,3,'com_content.category.22','Release 2: Título do release entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (66,60,79,80,3,'com_content.category.23','Release 3: Título do release entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (67,60,81,82,3,'com_content.category.24','Release 4: Título do release entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (68,60,83,84,3,'com_content.category.25','Release 5: Título do release entre 35 e 90 caracteres','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (69,60,85,86,3,'com_content.article.16','Texto 1 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (70,60,87,88,3,'com_content.article.17','Release 5: Título do release entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (71,60,89,90,3,'com_content.article.18','Release 4: Título do release entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (72,60,91,92,3,'com_content.article.19','Release 1: Título do release entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (73,60,93,94,3,'com_content.article.20','Release 2: Título do release entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (74,60,95,96,3,'com_content.article.21','Release 3: Título do release entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (75,60,97,98,3,'com_content.article.22','Texto 3 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (76,60,99,100,3,'com_content.article.23','Texto 4 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (77,60,101,102,3,'com_content.article.24','Texto 5 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (78,60,103,104,3,'com_content.article.25','Texto 2 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (79,82,139,140,3,'com_content.article.26','Conheça o novo modelo de plataforma digital do governo federal','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (80,81,109,110,3,'com_content.article.27','Manuais','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (81,8,108,111,2,'com_content.category.26','manuais','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (82,8,138,141,2,'com_content.category.27','Sobre a nova identidade visual do Governo Federal','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (83,93,129,132,3,'com_content.category.28','Perguntas frequentes','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (84,93,125,128,3,'com_content.category.29','Contato','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (85,93,121,124,3,'com_content.category.30','Servicos da Denominação','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (86,93,117,120,3,'com_content.category.31','Dados abertos','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (87,93,113,116,3,'com_content.category.32','Area de imprensa','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (88,83,130,131,4,'com_content.article.28','Perguntas frequentes','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (89,84,126,127,4,'com_content.article.29','Contato','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (90,85,122,123,4,'com_content.article.30','Servicos da Denominação','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (91,86,118,119,4,'com_content.article.31','Dados abertos','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (92,87,114,115,4,'com_content.article.32','Area de imprensa','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (93,8,112,137,2,'com_content.category.33','Menu superior','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (94,93,133,136,3,'com_content.category.34','Acessibilidade','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (95,94,134,135,4,'com_content.article.33','Acessibilidade','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (97,60,105,106,3,'com_content.article.34','Saiba como montar o menu da Lei de Acesso à Informação','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (98,8,142,151,2,'com_content.category.35','Programas','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (99,98,143,144,3,'com_content.article.35','Texto 3 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (100,98,145,146,3,'com_content.article.36','Texto 4 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (101,98,147,148,3,'com_content.article.37','Texto 5 - Título da notícia entre 35 e 90 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (102,98,149,150,3,'com_content.article.38','Texto 5 - Título da notícia entre 35 e 90 caracteres (2)',''),
 (103,8,152,165,2,'com_content.category.36','Galeria de imagens','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (104,103,153,164,3,'com_content.category.37','Galeria 1','{\"core.create\":{\"6\":1,\"3\":1},\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1},\"core.edit.own\":{\"6\":1,\"3\":1}}'),
 (105,104,154,155,4,'com_content.article.39','Imagem 1: título com até 45 caracteres','{\"core.delete\":{\"6\":1},\"core.edit\":{\"6\":1,\"4\":1},\"core.edit.state\":{\"6\":1,\"5\":1}}'),
 (106,104,156,157,4,'com_content.article.40','Imagem 2: título com até 45 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (107,104,158,159,4,'com_content.article.41','Imagem 3: título com até 45 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (108,104,160,161,4,'com_content.article.42','Imagem 4: título com até 45 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (109,104,162,163,4,'com_content.article.43','SEM Imagem: título com até 45 caracteres','{\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[]}'),
 (110,1,215,216,1,'com_blankcomponent','blankcomponent','{}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_assets` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_associations`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_associations`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_associations` (
  `id` int(11) NOT NULL COMMENT 'A reference to the associated item.',
  `context` varchar(50) NOT NULL COMMENT 'The context of the associated item.',
  `key` char(32) NOT NULL COMMENT 'The key for the association computed from an md5 on associated ids.',
  PRIMARY KEY (`context`,`id`),
  KEY `idx_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_associations`
--

/*!40000 ALTER TABLE `pmgov2013_associations` DISABLE KEYS */;
LOCK TABLES `pmgov2013_associations` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_associations` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_banner_clients`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_banner_clients`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_banner_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey` text NOT NULL,
  `own_prefix` tinyint(4) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_banner_clients`
--

/*!40000 ALTER TABLE `pmgov2013_banner_clients` DISABLE KEYS */;
LOCK TABLES `pmgov2013_banner_clients` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_banner_clients` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_banner_tracks`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_banner_tracks`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_banner_tracks` (
  `track_date` datetime NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`track_date`,`track_type`,`banner_id`),
  KEY `idx_track_date` (`track_date`),
  KEY `idx_track_type` (`track_type`),
  KEY `idx_banner_id` (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_banner_tracks`
--

/*!40000 ALTER TABLE `pmgov2013_banner_tracks` DISABLE KEYS */;
LOCK TABLES `pmgov2013_banner_tracks` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_banner_tracks` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_banners`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_banners`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `custombannercode` varchar(2048) NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `params` text NOT NULL,
  `own_prefix` tinyint(1) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`),
  KEY `idx_banner_catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_banners`
--

/*!40000 ALTER TABLE `pmgov2013_banners` DISABLE KEYS */;
LOCK TABLES `pmgov2013_banners` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_banners` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_categories`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_categories`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`extension`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_categories`
--

/*!40000 ALTER TABLE `pmgov2013_categories` DISABLE KEYS */;
LOCK TABLES `pmgov2013_categories` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_categories` VALUES  (1,0,0,0,73,0,'','system','ROOT',0x726F6F74,'','',1,0,'0000-00-00 00:00:00',1,'{}','','','',0,'2009-10-18 16:07:09',0,'0000-00-00 00:00:00',0,'*',1),
 (2,27,1,1,2,1,'uncategorised','com_content','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:26:37',0,'0000-00-00 00:00:00',4,'*',1),
 (3,28,1,3,4,1,'uncategorised','com_banners','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\",\"foobar\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:27:35',0,'0000-00-00 00:00:00',0,'*',1),
 (4,29,1,5,6,1,'uncategorised','com_contact','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:27:57',0,'0000-00-00 00:00:00',0,'*',1),
 (5,30,1,7,8,1,'uncategorised','com_newsfeeds','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:28:15',0,'0000-00-00 00:00:00',0,'*',1),
 (6,31,1,9,10,1,'uncategorised','com_weblinks','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:28:33',0,'0000-00-00 00:00:00',0,'*',1),
 (7,32,1,11,12,1,'uncategorised','com_users','Uncategorised',0x756E63617465676F7269736564,'','',1,0,'0000-00-00 00:00:00',1,'{\"target\":\"\",\"image\":\"\"}','','','{\"page_title\":\"\",\"author\":\"\",\"robots\":\"\"}',42,'2010-06-28 13:28:33',0,'0000-00-00 00:00:00',0,'*',1),
 (8,37,1,13,28,1,'assuntos','com_content','Assuntos',0x617373756E746F73,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:09:55',0,'0000-00-00 00:00:00',0,'*',1),
 (9,41,8,14,23,2,'assuntos/editoria-a','com_content','Editoria A',0x656469746F7269612D61,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:15:48',0,'0000-00-00 00:00:00',0,'*',1),
 (10,42,9,15,22,3,'assuntos/editoria-a/menu-de-2-nivel','com_content','Menu de 2. Nivel',0x6D656E752D64652D322D6E6976656C,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:17:17',0,'0000-00-00 00:00:00',0,'*',1),
 (11,43,10,16,21,4,'assuntos/editoria-a/menu-de-2-nivel/menu-de-3-nivel','com_content','Menu de 3. Nivel',0x6D656E752D64652D332D6E6976656C,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:17:49',0,'0000-00-00 00:00:00',0,'*',1),
 (12,44,11,17,18,5,'assuntos/editoria-a/menu-de-2-nivel/menu-de-3-nivel/pagina-2-titulo-do-texto-institucional','com_content','Página 2: título do texto institucional',0x706167696E612D322D746974756C6F2D646F2D746578746F2D696E737469747563696F6E616C,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:18:50',576,'2013-10-21 17:18:53',0,'*',1),
 (13,45,11,19,20,5,'assuntos/editoria-a/menu-de-2-nivel/menu-de-3-nivel/pagina-3-titulo-do-texto-institucional','com_content','Página 3: título do texto institucional',0x706167696E612D332D746974756C6F2D646F2D746578746F2D696E737469747563696F6E616C,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:19:21',0,'0000-00-00 00:00:00',0,'*',1),
 (14,46,8,24,25,2,'assuntos/editoria-b','com_content','Editoria B',0x656469746F7269612D62,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:31:38',576,'2013-10-21 17:32:39',0,'*',1),
 (15,47,8,26,27,2,'assuntos/editoria-c','com_content','Editoria C',0x656469746F7269612D63,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:32:25',0,'0000-00-00 00:00:00',0,'*',1),
 (16,50,1,29,30,1,'sobre','com_content','Sobre',0x736F627265,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 17:58:32',0,'0000-00-00 00:00:00',0,'*',1),
 (17,60,1,31,48,1,'ultimas-noticias','com_content','Últimas notícias',0x756C74696D61732D6E6F746963696173,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:17:00',0,'0000-00-00 00:00:00',2,'*',1),
 (18,61,17,32,33,2,'ultimas-noticias/texto-1-titulo-da-noticia-entre-35-e-90-caracteres','com_content','Texto 1 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D312D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'','<div>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:20:18',576,'2013-10-21 18:23:28',0,'*',1),
 (19,62,17,38,39,2,'ultimas-noticias/texto-3-titulo-da-noticia-entre-35-e-90-caracteres','com_content','Texto 3 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D332D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'','<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:23:56',576,'2013-10-21 18:25:58',0,'*',1),
 (20,63,17,34,35,2,'ultimas-noticias/texto-4-titulo-da-noticia-entre-35-e-90-caracteres','com_content','Texto 4 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D342D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'','<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:24:44',0,'0000-00-00 00:00:00',0,'*',1),
 (21,64,17,36,37,2,'ultimas-noticias/release-1-titulo-do-release-entre-35-e-90-caracteres','com_content','Release 1: Título do release entre 35 e 90 caracteres',0x72656C656173652D312D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'','<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chapéu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtítulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:25:41',0,'0000-00-00 00:00:00',0,'*',1),
 (22,65,17,40,41,2,'ultimas-noticias/release-2-titulo-do-release-entre-35-e-90-caracteres','com_content','Release 2: Título do release entre 35 e 90 caracteres',0x72656C656173652D322D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'','<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:26:37',0,'0000-00-00 00:00:00',0,'*',1),
 (23,66,17,42,43,2,'ultimas-noticias/release-3-titulo-do-release-entre-35-e-90-caracteres','com_content','Release 3: Título do release entre 35 e 90 caracteres',0x72656C656173652D332D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'','<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chapéu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtítulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:27:18',0,'0000-00-00 00:00:00',0,'*',1),
 (24,67,17,44,45,2,'ultimas-noticias/release-4-titulo-do-release-entre-35-e-90-caracteres','com_content','Release 4: Título do release entre 35 e 90 caracteres',0x72656C656173652D342D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'','<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chapéu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtítulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:28:08',0,'0000-00-00 00:00:00',0,'*',1),
 (25,68,17,46,47,2,'ultimas-noticias/release-5-titulo-do-release-entre-35-e-90-caracteres','com_content','Release 5: Título do release entre 35 e 90 caracteres',0x72656C656173652D352D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'','<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',-2,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-21 18:28:42',0,'0000-00-00 00:00:00',0,'*',1),
 (26,81,1,49,50,1,'manuais','com_content','manuais',0x6D616E75616973,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 20:53:19',0,'0000-00-00 00:00:00',0,'*',1),
 (27,82,1,65,66,1,'sobre-a-nova-identidade-visual-do-governo-federal','com_content','Sobre a nova identidade visual do Governo Federal',0x736F6272652D612D6E6F76612D6964656E7469646164652D76697375616C2D646F2D676F7665726E6F2D6665646572616C,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:30:58',576,'2013-11-02 22:01:28',0,'*',1),
 (28,83,33,60,61,2,'menu-superior/perguntas-frequentes','com_content','Perguntas frequentes',0x70657267756E7461732D6672657175656E746573,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:32:46',576,'2013-10-23 21:44:35',0,'*',1),
 (29,84,33,58,59,2,'menu-superior/contato','com_content','Contato',0x636F6E7461746F,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:33:00',576,'2013-10-23 21:44:24',0,'*',1),
 (30,85,33,56,57,2,'menu-superior/servicos-da-denominacao','com_content','Servicos da Denominação',0x7365727669636F732D64612D64656E6F6D696E6163616F,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:33:28',576,'2013-10-23 21:44:14',0,'*',1),
 (31,86,33,54,55,2,'menu-superior/dados-abertos','com_content','Dados abertos',0x6461646F732D61626572746F73,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:33:42',576,'2013-10-23 21:44:06',0,'*',1),
 (32,87,33,52,53,2,'menu-superior/area-de-imprensa','com_content','Area de imprensa',0x617265612D64652D696D7072656E7361,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:34:19',576,'2013-10-23 21:43:57',0,'*',1),
 (33,93,1,51,64,1,'menu-superior','com_content','Menu superior',0x6D656E752D7375706572696F72,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:43:10',0,'0000-00-00 00:00:00',0,'*',1),
 (34,94,33,62,63,2,'menu-superior/acessibilidade','com_content','Acessibilidade',0x61636573736962696C6964616465,'','<p><br style=\"text-align: left;\" /></p>',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-10-23 21:48:21',576,'2013-10-23 21:51:57',0,'*',1),
 (35,98,1,67,68,1,'programas','com_content','Programas',0x70726F6772616D6173,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-11-03 00:35:39',0,'0000-00-00 00:00:00',0,'*',1),
 (36,103,1,69,72,1,'galeria-de-imagens','com_content','Galeria de imagens',0x67616C657269612D64652D696D6167656E73,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-11-03 13:04:06',0,'0000-00-00 00:00:00',3,'*',1),
 (37,104,36,70,71,2,'galeria-de-imagens/galeria-1','com_content','Galeria 1',0x67616C657269612D31,'','',1,0,'0000-00-00 00:00:00',1,'{\"category_layout\":\"\",\"image\":\"\"}','','','{\"author\":\"\",\"robots\":\"\"}',576,'2013-11-03 13:04:20',576,'2013-11-03 13:04:35',0,'*',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_categories` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_contact_details`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_contact_details`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_contact_details`
--

/*!40000 ALTER TABLE `pmgov2013_contact_details` DISABLE KEYS */;
LOCK TABLES `pmgov2013_contact_details` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_contact_details` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_content`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_content`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` char(7) NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_content`
--

/*!40000 ALTER TABLE `pmgov2013_content` DISABLE KEYS */;
LOCK TABLES `pmgov2013_content` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_content` VALUES  (1,35,'Editoria A',0x656469746F7269612D61,'<div>\r\n<div id=\"c1e7e8f45dc4438296670befba1af889\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/c1e7e8f45dc4438296670befba1af889\">\r\n<div>\r\n<p class=\"tile-subtitle\" style=\"text-align: left;\"><span style=\"font-size: 10pt; color: #3366ff;\"><img style=\"margin-left: 5px; float: right;\" src=\"images/imagens_menu/video.png\" alt=\"video\" />Chapéu</span></p>\r\n<a class=\"imag\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\" href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/texto-2-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" /></a><span class=\"imag\"> </span>\r\n<h2 style=\"text-align: left;\">&nbsp;<span style=\"font-size: 10pt; color: #000000;\"><span style=\"color: #000000;\">Título da notícia em 3 linhas - até 50 caracteres</span><a title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\" href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\"><span style=\"color: #000000;\"></span> </a> </span></h2>\r\n<p class=\"tile-description\" style=\"text-align: left;\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"db631c61295a409e8fe5408dee369c07\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/db631c61295a409e8fe5408dee369c07\">\r\n<div>\r\n<p class=\"tile-subtitle\" style=\"text-align: left;\">&nbsp;</p>\r\n<p class=\"tile-subtitle\" style=\"text-align: left;\"><span style=\"font-size: 10pt; color: #3366ff;\">Chapéu</span></p>\r\n<a class=\"imag\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\" href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/texto-1-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" /> </a>\r\n<h2 style=\"text-align: left;\">&nbsp;<span style=\"font-size: 10pt; color: #000000;\"><span style=\"color: #000000;\">Título da notícia em 3 linhas - até 50 caracteres</span><a title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\" href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\"><span style=\"color: #000000;\"></span> </a> </span></h2>\r\n<p class=\"tile-description\" style=\"text-align: left;\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"b7d1e844f3ae4cd2a7c01a8397c0cdf7\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/b7d1e844f3ae4cd2a7c01a8397c0cdf7\">\r\n<div>\r\n<p class=\"tile-subtitle\">&nbsp;</p>\r\n<p class=\"tile-subtitle\" style=\"text-align: left;\"><span style=\"font-size: 10pt; color: #3366ff;\">Chapéu</span></p>\r\n<h2 style=\"text-align: left;\"><span style=\"font-size: 10pt; color: #000000;\"><span style=\"color: #000000;\">Título da notícia em duas linhas cheias com até 90 caracteres. Título da notícia em 2 linhas</span><a title=\"Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas\" href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-3-titulo-da-noticia-entre-35-e-90-caracteres\"> </a> </span></h2>\r\n<p class=\"tile-description\" style=\"text-align: left;\">Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas</p>\r\n<div class=\"visualClear\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"eba7851c6a8b42b9955c31ea87657979\" class=\"tile tile-default\" data-tile=\"@@mediacarousel/eba7851c6a8b42b9955c31ea87657979\">\r\n<div id=\"mediacarousel-eba7851c6a8b42b9955c31ea87657979\" class=\"mediacarousel\">\r\n<h2 class=\"mediacarousel-tile\">&nbsp;</h2>\r\n<div id=\"mediacarousel-gallerie-eba7851c6a8b42b9955c31ea87657979\" class=\"ready image\" style=\"height: 419.667px;\">\r\n<div class=\"galleria-container notouch iframe\" style=\"width: 360px; height: 270px;\">\r\n<div class=\"galleria-stage\">\r\n<div class=\"galleria-images\" style=\"position: relative; top: 0px; left: 0px; width: 100%; height: 100%;\">\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: absolute; top: 0px; left: 0px; transition: none 0s ease 0s; opacity: 0; z-index: 0;\">&nbsp;</div>\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: absolute; top: 0px; left: 0px; opacity: 1; width: 360px; height: 270px; transition: none 0s ease 0s; z-index: 1;\">\r\n<div class=\"galleria-layer\" style=\"position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; z-index: 2; display: block; width: 0px; height: 0px;\">&nbsp;</div>\r\n</div>\r\n</div>\r\n<div class=\"galleria-loader\" style=\"opacity: 0.4; display: none;\">&nbsp;</div>\r\n<div class=\"galleria-counter\" style=\"opacity: 0.4; display: block;\"><span class=\"galleria-current\">1</span> / <span class=\"galleria-total\">5</span></div>\r\n<div class=\"galleria-image-nav\">&nbsp;</div>\r\n</div>\r\n<div class=\"galleria-info\" style=\"opacity: 1;\">\r\n<div class=\"galleria-info-text\">\r\n<div class=\"galleria-info-title\"><a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-5-titulo-com-ate-45-caracteres/view\">Vídeo 5: título com até 45 caracteres</a></div>\r\n<div class=\"galleria-info-description\">Legenda do vídeo 5. Para ficar em uma linha use 77 caracteres. Para ficar em duas, 157 caracteres</div>\r\n<div class=\"rights\" style=\"display: block;\" data-index=\"0\">Autor/Criador do vídeo</div>\r\n</div>\r\n<div class=\"galleria-info-link\" style=\"display: none;\">&nbsp;</div>\r\n<div class=\"galleria-info-close\" style=\"display: none;\">&nbsp;</div>\r\n</div>\r\n<div class=\"galleria-thumbnails-container\" style=\"opacity: 1;\">\r\n<div class=\"galleria-thumb-nav-left disabled\">&nbsp;</div>\r\n<div class=\"galleria-thumbnails-list\" style=\"overflow: hidden; position: relative;\">\r\n<div class=\"galleria-thumbnails\" style=\"overflow: hidden; position: relative; width: 285px; height: 40px; left: 0px;\">\r\n<div class=\"galleria-image active\" style=\"overflow: hidden; position: relative; visibility: visible; width: 51px; height: 38px;\"><img style=\"display: block; opacity: 1; min-width: 0px; min-height: 0px; max-width: none; max-height: none; width: 51px; height: 38px; position: absolute; top: 0px; left: 0px;\" src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-5-titulo-com-ate-45-caracteres/@@images/0d7d327d-f33a-410d-a1fd-2da52011820b.jpeg\" alt=\"\" width=\"51\" height=\"38\" /></div>\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: relative; visibility: visible; width: 51px; height: 38px;\"><img style=\"display: block; opacity: 0.6; min-width: 0px; min-height: 0px; max-width: none; max-height: none; width: 51px; height: 38px; position: absolute; top: 0px; left: 0px;\" src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-4-titulo-com-ate-45-caracteres/@@images/b4e9775e-8b9c-457b-9d05-c4d39dce34dd.jpeg\" alt=\"\" width=\"51\" height=\"38\" /></div>\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: relative; visibility: visible; width: 51px; height: 38px;\"><img style=\"display: block; opacity: 0.6; min-width: 0px; min-height: 0px; max-width: none; max-height: none; width: 51px; height: 38px; position: absolute; top: 0px; left: 0px;\" src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-3-titulo-com-ate-45-caracteres/@@images/8b4f5b24-4678-4591-894c-feb8b212611c.jpeg\" alt=\"\" width=\"51\" height=\"38\" /></div>\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: relative; visibility: visible; width: 51px; height: 38px;\"><img style=\"display: block; opacity: 0.6; min-width: 0px; min-height: 0px; max-width: none; max-height: none; width: 51px; height: 38px; position: absolute; top: 0px; left: 0px;\" src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-2-titulo-com-ate-45-caracteres/@@images/6e18d60e-4d16-4921-a0cc-54a2d2eb98af.jpeg\" alt=\"\" width=\"51\" height=\"38\" /></div>\r\n<div class=\"galleria-image\" style=\"overflow: hidden; position: relative; visibility: visible; width: 51px; height: 38px;\"><img style=\"display: block; opacity: 0.6; min-width: 0px; min-height: 0px; max-width: none; max-height: none; width: 51px; height: 38px; position: absolute; top: 0px; left: 0px;\" src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/video-1-titulo-com-ate-45-caracteres/@@images/f8dfd87e-7961-4a21-a9ee-f0adfb857ba4.jpeg\" alt=\"\" width=\"51\" height=\"38\" /></div>\r\n</div>\r\n</div>\r\n<div class=\"galleria-thumb-nav-right disabled\">&nbsp;</div>\r\n</div>\r\n<div class=\"galleria-tooltip\" style=\"opacity: 0;\">&nbsp;</div>\r\n</div>\r\n</div>\r\n<div class=\"mediacarousel-footer-container\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"c92bf1f0074d4f7ca662ba73168c31d6\" class=\"tile verde\" data-tile=\"@@standaloneheader/c92bf1f0074d4f7ca662ba73168c31d6\">\r\n<div class=\"outstanding-header\"><span class=\"outstanding-link\">Mais vídeos</span></div>\r\n<div class=\"outstanding-header\">&nbsp;</div>\r\n<div class=\"outstanding-header\">&nbsp;</div>\r\n<div class=\"outstanding-header\" style=\"text-align: left;\">&nbsp;</div>\r\n</div>\r\n</div>\r\n<div class=\"row\" data-layout-type=\"row\">\r\n<div class=\"cell width-5 position-0 \" data-panel=\"\">\r\n<div>\r\n<div id=\"a5649a69a3d340b7b764863116c961d1\" class=\"tile verde\" data-tile=\"@@standaloneheader/a5649a69a3d340b7b764863116c961d1\">\r\n<div class=\"outstanding-header\">\r\n<h2 class=\"outstanding-title\">Assunto 1</h2>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"b0730eabce904e26af69e73b7199228f\" class=\"tile verde\" data-tile=\"@@nitf/b0730eabce904e26af69e73b7199228f\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h3>Título da notícia entre 40 e 50 caracteres com espaço</h3>\r\n<p class=\"tile-description\">Subtítulo em uma linha com até 90 caracteres. Subtítulo em duas linhas com até 90 caracteres</p>\r\n<div class=\"visualClear\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"cell width-5 position-5 \" data-panel=\"\">\r\n<div>\r\n<div id=\"da748697ff30417ba173411a4119fd8c\" class=\"tile laranja\" data-tile=\"@@standaloneheader/da748697ff30417ba173411a4119fd8c\">\r\n<div class=\"outstanding-header\">\r\n<h2 class=\"outstanding-title\">Assunto 2</h2>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"fe7eb78520c54808ad2c93dc84a0235a\" class=\"tile laranja\" data-tile=\"@@nitf/fe7eb78520c54808ad2c93dc84a0235a\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h3>Título da notícia entre 40 e 50 caracteres com espaço</h3>\r\n<p class=\"tile-description\">Subtítulo em uma linha com até 90 caracteres. Subtítulo em duas linhas com até 90 caracteres</p>\r\n<div class=\"visualClear\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"cell width-5 position-10 \" data-panel=\"\">\r\n<div>\r\n<div id=\"bbe7cb043bcd4da0910677b7294cfec4\" class=\"tile azul-claro\" data-tile=\"@@standaloneheader/bbe7cb043bcd4da0910677b7294cfec4\">\r\n<div class=\"outstanding-header\">\r\n<h2 class=\"outstanding-title\">Assunto 3</h2>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"f3a41236612f465ca7fef22d4c6b4c4f\" class=\"tile azul-claro\" data-tile=\"@@nitf/f3a41236612f465ca7fef22d4c6b4c4f\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h3>Título da notícia entre 40 e 50 caracteres com espaço</h3>\r\n<p class=\"tile-description\">Subtítulo em uma linha com até 90 caracteres. Subtítulo em duas linhas com até 90 caracteres</p>\r\n<br class=\"tile-description\" /></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>','',1,8,'2013-10-21 14:40:14',576,'','2013-10-22 12:50:36',576,0,'0000-00-00 00:00:00','2013-10-21 14:40:14','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"0\",\"link_titles\":\"0\",\"show_intro\":\"0\",\"show_category\":\"0\",\"link_category\":\"0\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"0\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"0\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',31,2,'','',1,76,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (2,38,'Pagina 1: titulo do texto institucional',0x706167696E612D312D746974756C6F2D646F2D746578746F2D696E737469747563696F6E616C,'<p style=\"text-align: left;\">Espaço para inserir o subtítulo do texto institucional. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Segundo subtítulo em negrito</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Terceiro subtítulo em negrito</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Link externo 1<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Link externo 2<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Link externo 3<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Link externo 4<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Link externo 5<br />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','',1,8,'2013-10-21 17:11:14',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-21 17:11:14','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,1,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (3,39,'Página 2: título do texto institucional',0x706167696E612D322D746974756C6F2D646F2D746578746F2D696E737469747563696F6E616C,'<p style=\"text-align: left;\">Espaço para inserir o subtítulo do texto institucional. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">•&nbsp;&nbsp;&nbsp; Lorem ipsum dolor sit amet;</p>\r\n<p style=\"text-align: left;\">•&nbsp;&nbsp;&nbsp; Lorem ipsum dolor sit amet;</p>\r\n<p style=\"text-align: left;\">•&nbsp;&nbsp;&nbsp; Lorem ipsum dolor sit amet</p>\r\n<p style=\"text-align: left;\">•&nbsp;&nbsp;&nbsp; Lorem ipsum dolor sit amet</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','',1,11,'2013-10-21 17:13:25',576,'','2013-10-21 17:29:25',576,0,'0000-00-00 00:00:00','2013-10-21 17:13:25','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (4,40,'Página 3: título do texto institucional',0x706167696E612D332D746974756C6F2D646F2D746578746F2D696E737469747563696F6E616C,'<p style=\"text-align: left;\"><span style=\"color: #c0c0c0;\"><strong>Espaço para inserir o subtítulo do texto institucional. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</strong></span></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','',1,11,'2013-10-21 17:14:11',576,'','2013-10-21 17:29:34',576,0,'0000-00-00 00:00:00','2013-10-21 17:14:11','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',4,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (5,48,'Editoria B',0x656469746F7269612D62,'<div class=\"row\" style=\"text-align: left;\" data-layout-type=\"row\">\r\n<div class=\"cell width-5 position-0 \" data-panel=\"\">\r\n<div>\r\n<div id=\"d851ae9071794d20a821de0c2495f2e2\" class=\"tile azul-claro\" data-tile=\"@@nitf/d851ae9071794d20a821de0c2495f2e2\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<a class=\"imag\" title=\"Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres\" href=\"http://tv1-lnx-04.grupotv1.com/portalmodelo/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/texto-1-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" /> </a>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres</p>\r\n</div>\r\n</div>\r\n</div>\r\n<div>&nbsp;</div>\r\n<div>\r\n<div id=\"bbc6404fc50f419fada71ec28a369083\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/bbc6404fc50f419fada71ec28a369083\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h2>Título da notícia em duas linhas em até 50 caracteres</h2>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"cell width-5 position-5 \" data-panel=\"\">\r\n<div>\r\n<div id=\"8b7832e2df6b4137b4f8f059ad5c4925\" class=\"tile laranja\" data-tile=\"@@nitf/8b7832e2df6b4137b4f8f059ad5c4925\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<a class=\"imag\" title=\"Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres\" href=\"http://tv1-lnx-04.grupotv1.com/portalmodelo/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/texto-2-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" /> </a>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres</p>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"53b54dfd8bf64e268b611fc16c057faa\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/53b54dfd8bf64e268b611fc16c057faa\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h2>Título da notícia em duas linhas em até 50 caracteres</h2>\r\n<div>&nbsp;</div>\r\n<div>Chapéu</div>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"cell width-5 position-10 \" data-panel=\"\">\r\n<div>\r\n<div id=\"028b23f042944cae9c91e248dc192eab\" class=\"tile verde\" data-tile=\"@@nitf/028b23f042944cae9c91e248dc192eab\">\r\n<div><a class=\"imag\" title=\"Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres\" href=\"http://tv1-lnx-04.grupotv1.com/portalmodelo/conteudos-de-marcacao/texto-3-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/texto-3-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-3-titulo-da-noticia-entre-35-e-90-caracteres\" /><br style=\"clear: right;\" /> </a>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">&nbsp;</p>\r\n<p class=\"tile-description\">Subtítulo em três linhas com até 110 caracteres. Subtítulo em três linhas com até 110 caracteres</p>\r\n<div>&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"5e62039de6674899974139b7a04327f6\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/5e62039de6674899974139b7a04327f6\">\r\n<div>\r\n<p class=\"tile-subtitle\">Chapéu</p>\r\n<h2>Título da notícia em duas linhas em até 50 caracteres</h2>\r\n<p>&nbsp;</p>\r\n<p><img style=\"float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/220x220_cor-1.jpg\" alt=\"220x220 cor-1\" /> <img src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/220x220_cor-2.jpg\" alt=\"220x220 cor-2\" /> <img style=\"float: right;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_b/220x220_cor-3.jpg\" alt=\"220x220 cor-3\" /></p>\r\n<div class=\"visualClear\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"error\">&nbsp;.</div>\r\n</div>\r\n</div>','',1,14,'2013-10-21 17:37:58',576,'','2013-10-22 13:17:14',576,0,'0000-00-00 00:00:00','2013-10-21 17:37:58','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',20,0,'','',1,31,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (6,49,'Editoria C',0x656469746F7269612D63,'<div class=\"cell width-5 position-0 \" style=\"text-align: left;\" data-panel=\"\">\r\n<div>\r\n<div id=\"c77b4042cfef45839eab8aefecae128b\" class=\"tile azul-claro\" data-tile=\"@@standaloneheader/c77b4042cfef45839eab8aefecae128b\">\r\n<div class=\"outstanding-header\">\r\n<h2 class=\"outstanding-title\">Assunto 1</h2>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"b0730eabce904e26af69e73b7199228f\" class=\"tile --NOVALUE--\" data-tile=\"@@nitf/b0730eabce904e26af69e73b7199228f\">\r\n<div><a class=\"imag\" title=\"Subtítulo em três linhas com até 100 caracteres. Subtítulo em três linhas com até 100 caracteres\" href=\"http://tv1-lnx-04.grupotv1.com/portalmodelo/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\"> <img class=\"left\" style=\"float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/editoria_c/texto-1-titulo-da-noticia-entre-35-e-90-caracteres.jpeg\" alt=\"texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" /> </a>\r\n<h3>&nbsp;</h3>\r\n<h3>&nbsp;</h3>\r\n<h3>&nbsp;</h3>\r\n<h3>&nbsp;Título em duas linhas escrito com até 50 caracteres</h3>\r\n<p class=\"tile-description\">Subtítulo em três linhas com até 100 caracteres. Subtítulo em três linhas com até 100 caracteres</p>\r\n<div class=\"visualClear\">&nbsp;</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p>&nbsp;</p>\r\n</div>','',1,15,'2013-10-21 17:50:17',576,'','2013-10-22 12:58:53',576,0,'0000-00-00 00:00:00','2013-10-21 17:50:17','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',7,0,'','',1,16,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (7,51,'Institucional',0x696E737469747563696F6E616C,'<p style=\"text-align: left;\">Nesta seção são divulgadas informações institucionais e organizacionais do(a) [nome do órgão ou entidade], compreendendo suas funções, competências, estrutura organizacional, relação de autoridades (quem é quem), agenda de autoridades, horários de atendimento e legislação do órgão/entidade</p>\r\n<p style=\"text-align: left;\">Esse item deve apresentar as seguintes informações em relação ao órgão/entidade:</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; I. Estrutura organizacional (organograma);<br />&nbsp;&nbsp;&nbsp; II. Competências;<br />&nbsp;&nbsp;&nbsp; III. Base jurídica da estrutura organizacional e das competências do órgãos/entidade, inclusive regimentos<br />&nbsp;&nbsp;&nbsp; internos, quando existirem;<br />&nbsp;&nbsp;&nbsp; IV. Lista dos principais cargos e seus respectivos ocupantes (denominado “Quem é quem”);<br />&nbsp;&nbsp;&nbsp; V. Telefones, emails e endereços de contato dos ocupantes dos principais cargos; agenda de autoridades;<br />&nbsp;&nbsp;&nbsp; VI.Horários de atendimento do órgãos/entidade.</p>\r\n<p style=\"text-align: left;\">As informações relativas aos subitens I a V devem ser apresentadas, ao menos, para as unidades do órgão/entidade em nível hierárquico equivalente ou superior às coordenações-gerais. Os órgãos/entidades que disponibilizam as informações relativas a esses itens em seus respectivos portais eletrônicos poderão disponibilizar links remetendo para a referida área já existente em seu portal. O órgão/entidade que não disponibiliza as informações referentes a esse item deverá produzir e disponibilizar a informação.</p>\r\n<p style=\"text-align: left;\">O subitem V (agenda de autoridades) sugere-se que seja publicado para cargos de 1º e 2º escalão, autarquias, inclusive as especiais, fundações mantidas pelo Poder Público, empresas públicas e sociedades de economia mista. Esse item deverá apresentar:</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; a) a agenda de reuniões com pessoas físicas e jurídicas com as quais se relacione funcionalmente, com registro sumário das matérias tratadas;<br />&nbsp;&nbsp;&nbsp; b) audiências concedidas, com informações sobre seus objetivos, participantes e resultados;<br />&nbsp;&nbsp;&nbsp; c) eventos político-eleitorais de que a autoridade participe, informando as condições de logística e financeira da participação.</p>\r\n<p style=\"text-align: left;\">O subitem VII (horários de atendimento do órgão/entidade) refere-se às informações de horário de funcionamento e atendimento ao público do órgão/entidade e de suas respectivas unidades, em caso da existência de horários diferenciados entre as unidades ou da existência de unidades descentralizadas.</p>\r\n<p style=\"text-align: left;\">O órgão/entidade que divulga o referido conjunto de informações em seu portal eletrônico poderá disponibilizar link remetendo para a área onde as informações já estão disponíveis.</p>\r\n<p style=\"text-align: left;\">A <a class=\"external-link\" title=\"\" href=\"http://epwg.governoeletronico.gov.br/cartilha-redacao\" target=\"_self\">Cartilha e-PWG</a> – Redação para Web possui orientações para a escrita de áreas comuns de portais.</p>\r\n<p style=\"text-align: left;\"><strong>Guias</strong></p>\r\n<p style=\"text-align: left;\">Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<ul style=\"text-align: left;\">\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a><span>&nbsp;(arquivo pdf, tamanho: 1,14 MB)</span></li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a><span>&nbsp;(arquivo pdf, tamanho:1,27 MB)</span></li>\r\n<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a><span>&nbsp;(arquivo pdf, tamanho: 682 KB)</span></li>\r\n</ul>','',1,16,'2013-10-21 18:02:08',576,'','2013-10-23 20:30:53',576,0,'0000-00-00 00:00:00','2013-10-21 18:02:08','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',4,8,'','',1,18,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (8,52,'Ações e Programas',0x61636F65732D652D70726F6772616D6173,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas as informações pertinentes aos programas, ações, projetos e atividades implementadas pelo (a) [nome do órgão ou entidade]</div>\r\n<p style=\"text-align: left;\">Programas são o principal instrumento que os governos utilizam para promover a integração entre os entes e os setores para concretizar políticas públicas e otimizar seus recursos, sejam eles financeiros, humanos, logísticos ou materiais. Por outro lado, uma ação é um conjunto de operações, cujos produtos contribuem para os objetivos do programa governamental. A ação pode ser um projeto, atividade ou operação especial.</p>\r\n<p style=\"text-align: left;\">O órgão/entidade deverá disponibilizar o seguinte conjunto mínimo de informações em relação a seus respectivos programas, projetos e ações:<br />I - lista dos programas e ações executados pelo órgão/entidade;<br />II - indicação da unidade responsável pelo desenvolvimento e implementação;<br />III - principais metas;<br />IV - indicadores de resultado e impacto, quando existentes;<br />V - principais resultados.</p>\r\n<p style=\"text-align: left;\">Em relação aos programas e ações que se constituírem serviços diretamente prestados ao público pelo órgão/entidade, deverão ser apresentadas as seguintes informações adicionais:<br />I - o serviço oferecido;<br />II - os requisitos, documentos e informações necessários para acessar o serviço;<br />III - as principais etapas para processamento do serviço;<br />IV - o prazo máximo para a prestação do serviço;<br />V - a forma de prestação do serviço;<br />VI - a forma de comunicação com o solicitante do serviço;<br />VII - os locais e formas de acessar o serviço;<br />VIII - prioridades de atendimento;<br />IX - tempo de espera para atendimento;<br />X - prazos para a realização dos serviços;<br />XI - mecanismos de comunicação com os usuários;<br />XII - procedimentos para receber, atender, gerir e responder às sugestões e reclamações;<br />XIII - fornecimento de informações acerca das etapas, presentes e futuras, esperadas para a realização dos serviços, inclusive estimativas de prazos;<br />XIV - mecanismos de consulta, por parte dos usuários, acerca das etapas, cumpridas e pendentes, para a realização do serviço solicitado;<br />XV - tratamento a ser dispensado aos usuários quando do atendimento;<br />XVI - requisitos básicos para o sistema de sinalização visual das unidades de atendimento;<br />XVII - condições mínimas a serem observadas pelas unidades de atendimento, em especial no que se refere a acessibilidade, limpeza e conforto;<br />XVIII - procedimentos alternativos para atendimento quando o sistema informatizado se encontrar indisponível;<br />XIX - outras informações julgadas de interesse dos usuários.</p>\r\n<p style=\"text-align: left;\">O órgão ou entidade que já divulga o referido conjunto de informações em seu portal eletrônico ou que já publica sua respectiva Carta de Serviços ao Cidadão poderá disponibilizar link remetendo para a área onde as informações já estão disponíveis.</p>\r\n<p style=\"text-align: left;\"><span>Se o órgão/entidade ainda não divulga tais informações em seu portal eletrônico, ele deverá produzir e disponibilizar o conteúdo. Duas opções que podem contribuir para o atendimento do órgão/entidade a esse item são:</span>\r\n</p>\r\n<p style=\"text-align: left;\"><span>A publicação dos dados institucionais dos órgão/entidade disponíveis no Sistema Integrado de Planejamento e Orçamento do Brasil (SIOP), disponível em: <a class=\"external-link\" title=\"\" href=\"https://www.siop.planejamento.gov.br/siop/\" target=\"_self\">https://www.siop.planejamento.gov.br/siop/</a></span>\r\n</p>\r\n<p style=\"text-align: left;\">O direcionamento para o link do relatório de gestão do órgão/entidade, desde que esteja atualizado e as informações sejam de fácil localização.</p>\r\n<p style=\"text-align: left;\"><span>Caso o órgão/entidade possua informação extra no próprio site, ele poderá indicar o link. Exemplo seria o relatório de avaliação do PPA, no site da CGU: <a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/AvaliacaoPPA/index.asp\" target=\"_self\">http://www.cgu.gov.br/Publicacoes/AvaliacaoPPA/index.asp</a></span>\r\n</p>\r\n<p style=\"text-align: left;\"><strong>Guias</strong>\r\n</p>\r\n<p style=\"text-align: left;\">Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a>&nbsp;(arquivo pdf)</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a>&nbsp;(arquivo pdf)</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a>&nbsp;(arquivo pdf)</p>\r\n','',1,16,'2013-10-21 18:02:49',576,'','2013-10-23 20:23:12',576,0,'0000-00-00 00:00:00','2013-10-21 18:02:49','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,7,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (9,53,'Auditoria',0x61756469746F726961,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas informações referentes ao resultado de inspeções, auditorias, prestações e tomada de contas realizadas no (a) [nome do órgão ou entidade]</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n	<div id=\"parent-fieldname-text\">\r\n		<p>Os órgãos/entidades deverão disponibilizar os relatórios de gestão, os relatórios e certificados de auditoria, com pareceres do órgão de controle interno, e dos pronunciamentos dos ministros de Estado supervisores das áreas das autoridades de nível hierárquico equivalente, contidos nos processos de contas anuais, contendo a íntegra das peças e informações complementares contendo, minimamente, os seguintes dados:</p>\r\n		<ul>\r\n			<li><span>I - exercício ao qual se referem as contas;</span>\r\n			</li>\r\n			<li><span>II - código e descrição da respectiva unidade;</span>\r\n			</li>\r\n			<li><span>III - número do processo no órgão ou entidade de origem;</span>\r\n			</li>\r\n			<li><span>IV - número do processo no Tribunal de Contas da União;</span>\r\n			</li>\r\n			<li class=\"last-item\"><span>V - situação junto ao Tribunal de Contas da União, de modo que se informe se o processo foi entregue, sobrestado ou julgado.</span>\r\n			</li>\r\n		</ul>\r\n		<p><br />Caso o órgão/entidade já disponibilize as informações em seu portal eletrônico, poderá ser disponibilizado link para a área em que os relatórios já são divulgados.</p>\r\n		<p>Se o órgão/entidade ainda não divulga a informação em seu portal eletrônico, deverá providenciá-lo.</p>\r\n		<p><strong>Guias</strong>\r\n		</p>\r\n		<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n		<ul>\r\n			<li><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a><span>&nbsp;<span>(arquivo pdf, tamanho: 1,14 MB)</span></span>\r\n			</li>\r\n			<li><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a><span>&nbsp;<span>(arquivo pdf, tamanho:1,27 MB)</span></span>\r\n			</li>\r\n			<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a><span>&nbsp;<span>&nbsp;(arquivo pdf, tamanho: 682 KB)</span></span>\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n','',1,16,'2013-10-21 18:03:23',576,'','2013-10-23 20:24:13',576,0,'0000-00-00 00:00:00','2013-10-21 18:03:23','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,6,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (10,54,'Convênios',0x636F6E76656E696F73,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas informações sobre os repasses e transferências de recursos financeiros efetuados pelo (a) [nome do órgão ou entidade]</div>\r\n<p style=\"text-align: left;\">As informações a serem divulgadas nesse item referem-se às transferências de recursos da União realizadas pelo órgão/entidade mediante convênios, contratos de repasse e termos de cooperação, ou instrumentos congêneres com órgãos ou entidades públicas ou privadas sem fins lucrativos. Incluem-se nesta seção as transferências constitucionais e legais, e as transferências de renda direta ao cidadão, realizadas pelo órgão ou entidade.</p>\r\n<p style=\"text-align: left;\"><span>Serão divulgadas as seguintes informações relativas aos convênios ou instrumentos congêneres celebrados pelo órgão/entidade:</span></p>\r\n<p style=\"text-align: left;\">I - órgão superior;<br />II - órgão subordinado ou entidade vinculada;<br />III - unidade gestora;<br />IV - nome do conveniado;<br />V - número do convênio;<br />VI - número do processo;<br />VII - objeto;<br />VIII - valor de repasse;<br />IX - valor da contrapartida do conveniado;<br />X - valor total dos recursos;<br />XI - período de vigência.</p>\r\n<p style=\"text-align: left;\">O órgão/entidade que divulga o referido conjunto de informações em seu portal eletrônico ou possui Página de Transparência poderá disponibilizar link remetendo para a área do portal onde as informações já estão disponíveis ou para sua respectiva Página de Transparência.</p>\r\n<p style=\"text-align: left;\">Se o órgão/entidade ainda não divulgar tais informações em seu portal eletrônico, ele poderá disponibilizar link para as consultas do Portal da Transparência que apresentam os respectivos dados/informações ou para o Sistema de Gestão de Convênios e Contratos de Repasse do Governo Federal (SICONV). Os links a serem indicados são:</p>\r\n<p style=\"text-align: left;\"><strong><span>Para o Portal da Transparência do Governo Federal<br /></span></strong><a class=\"external-link\" title=\"\" href=\"http://www.portaldatransparencia.gov.br/convenios/\" target=\"_self\">Seção Convênios</a><span>&nbsp;<br /></span><a class=\"external-link\" title=\"\" href=\"http://www.portaldatransparencia.gov.br/PortalTransparenciaPrincipal2.asp\" target=\"_self\">Seção Despesas - Transferências de Recursos</a></p>\r\n<p style=\"text-align: left;\"><span><strong>Para o Portal de Convênios (SICONV)</strong><br /></span><span>O link indicado é a opção de consulta&nbsp;</span><a class=\"external-link\" title=\"\" href=\"https://www.convenios.gov.br/portal/acessoLivre.html\" target=\"_self\">Lista convênios por Órgão</a></p>\r\n<p style=\"text-align: left;\"><strong>Guias</strong></p>\r\n<p style=\"text-align: left;\">Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a>&nbsp;(arquivo pdf)</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a>&nbsp;(arquivo pdf)</p>\r\n<p style=\"text-align: left;\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a>&nbsp;(arquivo pdf)</p>','',1,16,'2013-10-21 18:03:58',576,'','2013-10-23 20:24:33',576,0,'0000-00-00 00:00:00','2013-10-21 18:03:58','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,5,'','',1,3,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (11,55,'Despesas',0x6465737065736173,'<p style=\"text-align: left;\">Nesta seção são divulgadas informações sobre a execução orçamentária e financeira detalhada do (a) [nome do Órgão ou entidade]</p>\r\n<p style=\"text-align: left;\">São consideradas despesas quaisquer gastos com aquisição e contratação de obras e compras governamentais. A execução financeira é a utilização dos recursos financeiros visando atender à realização dos programas, ações e projetos e/ou subatividades atribuídos às unidades orçamentárias.</p>\r\n<p style=\"text-align: left;\">Deverão ser divulgados os seguintes dados e informações em relação à execução orçamentária e financeira dos órgãos/entidades:</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; I - Quadro de Detalhamento de Programas, por unidade orçamentária do órgão/entidade, contendo:<br />&nbsp;&nbsp;&nbsp; a) código e especificação dos programas orçamentários;<br />&nbsp;&nbsp;&nbsp; b) orçamento atualizado, levando em consideração os recursos consignados por programa na Lei Orçamentária Anual e em seus créditos adicionais;<br />&nbsp;&nbsp;&nbsp; c) valor liquidado no ano considerado, para exercícios encerrados, e valor liquidado até o mês considerado, para o exercício corrente;<br />&nbsp;&nbsp;&nbsp; d) valor pago no ano considerado, para exercícios encerrados, e valor pago até o mês considerado, para o exercício corrente;<br />&nbsp;&nbsp;&nbsp; e) percentual dos recursos liquidados comparados aos autorizados;<br />&nbsp;&nbsp;&nbsp; f) percentual dos recursos pagos comparados aos autorizados.</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; II - Quadro de Execução de Despesas, por unidade orçamentária dos órgãos e entidades, contendo:<br />&nbsp;&nbsp;&nbsp; a) descrição da natureza das despesas;<br />&nbsp;&nbsp;&nbsp; b) valor liquidado no ano considerado, para exercícios encerrados e valor liquidado até o mês considerado, para o exercício corrente;<br />&nbsp;&nbsp;&nbsp; c) valor pago no ano considerado, para exercícios encerrados e valor pago até o mês considerado, para o exercício corrente.</p>\r\n<p style=\"text-align: left;\">O órgão/entidade deverá detalhar suas despesas com diárias e passagens pagas a servidores públicos em viagens a trabalho ou a colaboradores eventuais em viagens no interesse da Administração, no seguinte nível de detalhe para cada trecho da viagem:</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; I - órgão superior;<br />&nbsp;&nbsp;&nbsp; II - órgão subordinado ou entidade vinculada;<br />&nbsp;&nbsp;&nbsp; III - unidade gestora;<br />&nbsp;&nbsp;&nbsp; IV - nome do servidor;<br />&nbsp;&nbsp;&nbsp; V - cargo;<br />&nbsp;&nbsp;&nbsp; VI - origem de todos os trechos da viagem;<br />&nbsp;&nbsp;&nbsp; VII - destino de todos os trechos da viagem;<br />&nbsp;&nbsp;&nbsp; VIII - período da viagem;<br />&nbsp;&nbsp;&nbsp; IX - motivo da viagem;<br />&nbsp;&nbsp;&nbsp; X - meio de transporte;<br />&nbsp;&nbsp;&nbsp; XI - categoria da passagem;<br />&nbsp;&nbsp;&nbsp; XII - valor da passagem;<br />&nbsp;&nbsp;&nbsp; XIII - número de diárias;<br />&nbsp;&nbsp;&nbsp; XIV - valor total das diárias;<br />&nbsp;&nbsp;&nbsp; XV - valor total da viagem.</p>\r\n<p style=\"text-align: left;\">O órgão/entidade que divulga o referido conjunto de informações em seu portal eletrônico ou possui Página de Transparência atualizada poderá disponibilizar link remetendo para a área do portal onde as informações já estão disponíveis ou para sua respectiva Página de Transparência.</p>\r\n<p style=\"text-align: left;\">&nbsp;</p>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>O órgão/entidade que não disponibiliza dados de despesa em seu portal eletrônico e não possui Página de Transparência poderá disponibilizar o link do <a class=\"external-link\" title=\"\" href=\"http://www.portaltransparencia.gov.br/despesasdiarias/\" target=\"_self\">Portal da Transparência</a>&nbsp;para cumprir este item, exceto detalhamento de diárias e passagens. O Portal da Transparência do Governo Federal disponibiliza informações de execução orçamentária e financeira dos órgãos/entidades da Administração Federal. As informações são extraídas do <span>Sistema Integrado de Administração Financeira do Governo Federal (</span>SIAFI), portanto, são publicados no Portal da Transparência apenas os dados dos órgãos/entidades que utilizam o SIAFI. Nesses casos, o órgão/entidade poderá disponibilizar link de acesso para o Portal da Transparência. Os órgãos/entidades que não utilizam o SIAFI e cujas informações, portanto, não se encontram no Portal da Transparência, deverão divulgar por meio próprio suas informações de execução orçamentária e financeira.</p>\r\n<p><strong>Guias</strong></p>\r\n<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<ul>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a><span>&nbsp;<span>(arquivo pdf, tamanho: 1,14 MB)</span></span></li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a><span>&nbsp;<span>(arquivo pdf, tamanho:1,27 MB)</span></span></li>\r\n<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a><span>&nbsp;<span>(arquivo pdf, tamanho: 682 KB)</span></span></li>\r\n</ul>\r\n</div>\r\n</div>','',1,16,'2013-10-21 18:04:35',576,'','2013-10-23 20:24:47',576,0,'0000-00-00 00:00:00','2013-10-21 18:04:35','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,4,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (12,56,'Licitações e contratos',0x6C6963697461636F65732D652D636F6E747261746F73,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas as licitações e contratos realizados pelo (a) [nome do órgão ou entidade]</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>As informações a serem divulgadas nesse tópico referem-se aos procedimentos licitatórios, às contratações e aos gastos diretos realizados pelo órgão/entidade.</p>\r\n<p>As seguintes informações, referentes às licitações realizadas e em andamento pelos órgãos/entidades deverão ser publicadas:</p>\r\n<p>I - órgão superior;<br />II - órgão subordinado ou entidade vinculada;<br />III - unidade administrativa dos serviços gerais (UASG);<br />IV - número da licitação;<br />V - número do processo;<br />VI - modalidade da licitação;<br />VII - objeto;<br />VIII - número de itens;<br />IX - data e hora da abertura;<br />X - local da abertura;<br />XI - cidade da abertura;<br />XII - Unidade da Federação da abertura;<br />XIII - situação da licitação (aberta ou homologada);<br />XIV - contato no órgão ou entidade responsável;<br />XV - atalho para solicitação, por meio de correio eletrônico, da íntegra de editais, atas, anexos, projetos básicos e informações adicionais, diretamente à área responsável do órgão ou entidade.</p>\r\n<p>As seguintes informações, relativas aos contratos firmados e notas de empenho expedidas pelos órgãos/entidades deverão ser disponibilizados:</p>\r\n<p>I - órgão superior;<br />II - órgão subordinado ou entidade vinculada;<br />III - unidade administrativa dos serviços gerais (UASG);<br />IV - número do contrato;<br />V - data de publicação no Diário Oficial da União;<br />VI - número do processo;<br />VII - modalidade da licitação;<br />VIII - nome do contratado;<br />IX - número de inscrição do contratado no Cadastro Nacional de Pessoas Jurídicas (CNPJ) ou no Cadastro de Pessoas Físicas (CPF);<br />X - objeto;<br />XI - fundamento legal;<br />XII - período de vigência;<br />XIII - valor do contrato;<br />XIV - situação do contrato (ativo, concluído, rescindido ou cancelado);<br />XV - atalho para solicitar ao órgão ou entidade responsável, via correio eletrônico, a íntegra do instrumento de contrato e respectivos aditivos;<br />XVI - relação de aditivos ao contrato com as seguintes informações:<br />a) número do aditivo;<br />b) data da publicação no Diário Oficial da União;<br />c) número do processo;<br />d) objeto do aditivo.</p>\r\n<p>O órgão/entidade que divulga o referido conjunto de informações em seu portal eletrônico ou possui Página de Transparência atualizada poderá disponibilizar link remetendo para a área do portal onde as informações já estão disponíveis ou para sua respectiva Página de Transparência.</p>\r\n<p>Para aqueles órgãos/entidades que não possuem Página de Transparência própria, as informações detalhadas sobre licitações e contratos poderão ser extraídas do Sistema Integrado de Administração de Serviços Gerais (SIASG) ou do próprio sistema do órgão.</p>\r\n<p><strong>Guias</strong></p>\r\n<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a>&nbsp;(arquivo pdf)</p>\r\n</div>\r\n</div>','',1,16,'2013-10-21 18:06:12',576,'','2013-10-23 20:25:11',576,0,'0000-00-00 00:00:00','2013-10-21 18:06:12','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,3,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','');
INSERT INTO `portal_modelo_3x`.`pmgov2013_content` VALUES  (13,57,'Servidores',0x7365727669646F726573,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas informações sobre concursos públicos de provimento de cargos e relação dos servidores públicos lotados ou em exercício no (a) [nome do órgão ou entidade]</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Nesta seção, deverão ser publicadas as íntegras dos editais de concursos públicos para provimento de cargos realizados pelo órgão/entidade e a relação dos agentes públicos, efetivos ou não, lotados ou em exercício no órgão/entidade, apresentando as seguintes informações mínimas:</p>\r\n<ul>\r\n<li><span>I - número de identificação funcional;</span></li>\r\n<li><span>II - nome completo;</span></li>\r\n<li><span>III - CPF (ocultando os três primeiros dígitos e os dois dígitos verificadores do CPF);</span></li>\r\n<li><span>IV - cargo e função;</span></li>\r\n<li><span>V - lotação;</span></li>\r\n<li><span>VI - Regime Jurídico;</span></li>\r\n<li><span>VII - jornada de trabalho;</span></li>\r\n<li><span>VIII - ato de nomeação ou contratação;</span></li>\r\n<li><span>IX - respectiva data de publicação do ato;</span></li>\r\n<li><span>X - cargo efetivo ou permanente ou emprego permanente;</span></li>\r\n<li class=\"last-item\"><span>XI - órgão ou entidade de origem, no caso de servidor requisitado ou cedido.</span></li>\r\n</ul>\r\n<p>Membros de conselhos de administração ou fiscal da administração indireta também devem figurar nesta relação, assim como militares das Forças Armadas. Agentes públicos cujo exercício profissional é protegido por sigilo, em atendimento à legislação vigente, não devem figurar nesta relação.</p>\r\n<p>O órgão/entidade que utiliza o Sistema Integrado de Administração de Recursos Humanos (SIAPE) poderá, para cumprir este item, disponibilizar link para consulta “Servidores” do Portal da Transparência, disponível no <a class=\"external-link\" title=\"\" href=\"http://www.portaldatransparencia.gov.br/servidores\" target=\"_self\">Portal da Transparência</a>.</p>\r\n<p>A entidade da Administração Indireta, incluindo agências reguladoras e conselhos de administração e fiscal, cujos registros de servidores não estão no SIAPE, deverá disponibilizar a relação de servidores e agentes públicos, ou, caso já tenha a informação em seu portal eletrônico, remeter para área onde estão disponíveis essas informações.</p>\r\n<p><strong>Guias</strong></p>\r\n<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<ul>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a><span>&nbsp;<span>(arquivo pdf, tamanho: 1,14 MB)</span></span></li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a><span>&nbsp;<span>(arquivo pdf, tamanho:1,27 MB)</span></span></li>\r\n<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a><span>&nbsp;<span>(arquivo pdf, tamanho: 682 KB)</span></span></li>\r\n</ul>\r\n</div>\r\n</div>','',1,16,'2013-10-21 18:06:44',576,'','2013-10-23 20:25:46',576,0,'0000-00-00 00:00:00','2013-10-21 18:06:44','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,2,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (14,58,'Informações classificadas',0x696E666F726D61636F65732D636C6173736966696361646173,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgados o rol das informações classificadas em cada grau de sigilo e o rol das informações desclassificadas nos últimos doze meses no âmbito do [nome do órgão ou entidade]</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>O novo item de navegação “Informações classificadas” deverá trazer um texto explicativo sobre o seu&nbsp;objetivo de atender aos incisos I e II, do Art. 45, do <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2012/Decreto/D7724.htm\" target=\"_self\">Decreto nº 7.724/2012</a>.</p>\r\n<p>A página deverá conter duas áreas específicas para a apresentação das listagens requeridas, com as seguintes&nbsp;nomenclaturas:</p>\r\n<p>• Rol de informações classificadas<br />• Rol de informações desclassificadas</p>\r\n<p><strong>Conteúdo da área “rol de informações desclassificadas”</strong></p>\r\n<p>Essa área deverá conter os Números Únicos de Protocolo (NUP) de todos os documentos desclassificados&nbsp;desde a entrada em vigor da Lei de Acesso à Informação.</p>\r\n<p><strong>Conteúdo da área “rol de informações classificadas”</strong></p>\r\n<p>O conteúdo dessa área deverá apresentar as seguintes informações:</p>\r\n<p>• Código de Indexação de Documento que contém Informação Classificada – CIDIC;<br />• Categoria na qual se enquadra a informação;<br />• Indicação do dispositivo legal que fundamenta a classificação;<br />• Data da produção da informação;<br />• Data da classificação;<br />• Prazo da classificação.</p>\r\n<p>Somente devem ser incluídas no “Rol de informações classificadas” as informações classificadas nos termos&nbsp;do §1º do art. 24 da <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm\" target=\"_self\">Lei nº 12.527/2011</a>, ou seja, como reservadas, secretas ou ultrassecretas. Por isso,&nbsp;informações cujo sigilo seja devido a outras legislações (como fiscal e tributária), documentos preparatórios&nbsp;e informações pessoais não estão sujeitos aos termos de divulgação apresentados neste guia.</p>\r\n<p><strong>a) Formato de apresentação do CIDIC<br /></strong><br />O formato de apresentação do Código de Indexação de Documento que contém Informação Classificada<br />(CIDIC) obedece às seguintes regras, de acordo com os artigos 50 a 54 do <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2012/Decreto/D7845.htm\" target=\"_self\">Decreto nº 7.845/2012</a>:</p>\r\n<p>1. A 1ª parte do CIDIC corresponde ao Número Único de Protocolo – NUP do documento que&nbsp;contém a informação. Este é um código exclusivamente numérico;<br />2. A 2ª parte do CIDIC, separada da 1ª parte por um “.”, iniciará sempre por um caractere alfabético&nbsp;(“U”, “S” ou “R”), de acordo com o grau de sigilo. Além disso, deve prever até o máximo de 39 posições,&nbsp;com caracteres alfanuméricos e separadores;<br />3. Os separadores utilizados serão: “.” e “/” (este último, para as datas);<br />4. Para as informações classificadas no grau reservado e secreto, a 2ª parte do CIDIC terá sempre 28&nbsp;posições com caracteres alfanuméricos e separadores;<br />5. Para as informações classificadas no grau ultrassecreto, a 2ª parte do CIDIC terá 28 posições com&nbsp;<span>caracteres alfanuméricos e separadores, enquanto não ocorrer prorrogação do prazo do sigilo;<br /></span><span>6. Quando ocorrer a prorrogação do prazo de sigilo da informação classificada no grau ultrassecreto,&nbsp;</span><span>a nova data deverá constar no final da 2ª parte do CIDIC, totalizando 39 posições com caracteres&nbsp;</span><span>alfanuméricos e separadores;</span></p>\r\n<p><strong>b) Categoria na qual se enquadra a informação</strong></p>\r\n<p>A divulgação da informação “categoria na qual se enquadra a informação” deve obedecer os padrões estabelecidos&nbsp;no Vocabulário Controlado de Governo Eletrônico – VCGE (Anexo II do Decreto 7.845/2012).&nbsp;Trata-se de um padrão criado para facilitar e uniformizar a classificação dos tipos de informações tratadas&nbsp;em todo o Governo Federal.</p>\r\n<p>Para consultar o VCGE, acesse: <a href=\"http://vocab.e.gov.br/2011/03/vcge#esquema\">http://vocab.e.gov.br/2011/03/vcge#esquema</a>&nbsp;Deve-se utilizar apenas o primeiro nível do Vocabulário.</p>\r\n<p><strong>c) Fundamentação legal da classificação</strong></p>\r\n<p>Para indicar a fundamentação legal da classificação, deve-se fazer referência ao inciso do artigo 23 da <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm\" target=\"_self\">Lei&nbsp;nº 12.527/2011</a> utilizado como justificativa para a classificação da informação.</p>\r\n<p><strong>d) Formato para publicação das listagens</strong></p>\r\n<p>A disponibilização das listagens deve observar o disposto no artigo 8º, parágrafo 3, inciso II, da <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_Ato2011-2014/2011/Lei/L12527.htm\" target=\"_self\">Lei de Acesso&nbsp;à Informação</a>, ou seja, as informações devem ser publicadas em formatos “abertos e não proprietários,&nbsp;tais como planilhas e texto, de modo a facilitar a análise das informações”. Também devem ser observados&nbsp;os padrões estabelecidos pela e-PING - Padrões de Interoperabilidade de Governo Eletrônico.&nbsp;O formato (como, por exemplo, html, csv, ods, etc.) a ser utilizado pode ser definido pelo próprio órgão&nbsp;ou entidade, considerando as normas e procedimentos internos de segurança da informação.</p>\r\n<p><strong>Guias</strong></p>\r\n<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a>&nbsp;(arquivo pdf)</p>\r\n</div>\r\n</div>','',1,16,'2013-10-21 18:07:21',576,'','2013-10-23 20:25:01',576,0,'0000-00-00 00:00:00','2013-10-21 18:07:21','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,1,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (15,59,'Serviço de Informação ao Cidadão (SIC)',0x7365727669636F2D64652D696E666F726D6163616F2D616F2D6369646164616F2D736963,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas as informações sobre o Sistema de Informações ao Cidadão (SIC), pertinentes ao seu funcionamento, localização e dados de contato no âmbito do (a) [nome do órgão ou entidade]</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Nesse tópico o órgão/entidade disponibilizará as seguintes informações sobre o(s) Serviço(s) de Informação ao Cidadão (SICs), de que trata o artigo 9º da Lei de Acesso à Informação:</p>\r\n<p><span>I - localização;</span><br />II - horário de funcionamento;<br />III - nome dos servidores responsáveis pelo SIC;<br />IV - telefone e e-mails específicos para orientação e esclarecimentos de dúvidas, tais como sobre a protocolização de requerimentos de acesso à informação; a tramitação de solicitação de informação;<br />V - nome da autoridade do órgão responsável pelo monitoramento da implementação da Lei de Acesso à Informação no âmbito do órgão/entidade (autoridade prevista no artigo 40º da Lei 12.527/11).</p>\r\n<p>Também será disponibilizado neste item modelo de formulário de solicitação de informação para aqueles que queiram apresentam o pedido em meio físico (papel) junto ao SIC.</p>\r\n<p><span>Adicionalmente, o órgão ou entidade deverá disponibilizar eventuais informações sobre os procedimentos que os cidadãos deverão adotar para solicitar acesso à informação perante o respectivo órgão/entidade.</span></p>\r\n<p>Neste item, o órgão/entidade deverá disponibilizar link para o Sistema de Solicitação de Acesso à Informação do Poder Executivo Federal, a ser disponibilizado pela Controladoria-Geral da União (CGU), o qual permitirá que o requerente selecione o órgão ou entidade para o qual deseja endereçar pedido de acesso à informação. A CGU entrará em contato com todas as autoridades responsáveis pela implementação da Lei de Acesso à Informação dos órgãos/entidades a fim de indicar o exato link onde estará disponível a referida informação, bem como receber indicação do nome do servidor que deverá ser cadastrado para ter acesso ao sistema.</p>\r\n<p><strong>Guias</strong></p>\r\n<p>Para mais informações sobre a seção de Acesso à Informação, confira os guias elaborados pela Controladoria-Geral da União.</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_SecaoSitios.pdf\" target=\"_self\">Guia para criação da seção de acesso à informação nos portais eletrônicos dos órgãos e entidades federais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.cgu.gov.br/Publicacoes/BrasilTransparente/Guia_TransparenciaAtiva_EstadosMunicipios.pdf\" target=\"_self\">Guia para criação da&nbsp;Seção de Acesso à Informação&nbsp;nos portais eletrônicos&nbsp;dos Órgãos e Entidades&nbsp;Estaduais e Municipais</a>&nbsp;(arquivo pdf)</p>\r\n<p><a class=\"external-link\" title=\"\" href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\" target=\"_self\">Publicação do rol de informações&nbsp;classificadas e desclassificadas e&nbsp;de relatórios estatísticos sobre a&nbsp;Lei de Acesso à Informação</a>&nbsp;(arquivo pdf)</p>\r\n</div>\r\n</div>','',1,16,'2013-10-21 18:07:56',576,'','2013-10-23 20:25:24',576,0,'0000-00-00 00:00:00','2013-10-21 18:07:56','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (16,69,'Texto 1 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D312D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 1 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"nitf custom galleria\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-22 20:44:43',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',6,10,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (17,70,'Release 5: Título do release entre 35 e 90 caracteres',0x72656C656173652D352D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'<p style=\"text-align: left;\">Chapéu da editoria<br />Subtítulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n<p style=\"text-align: left;\"><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; Use o estilo Citação, localizado no campo Corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp; \r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>','',1,17,'2013-10-22 19:42:21',576,'','2013-10-23 20:41:04',576,0,'0000-00-00 00:00:00','2013-10-22 19:42:21','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',10,9,'','',1,10,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (18,71,'Release 4: Título do release entre 35 e 90 caracteres',0x72656C656173652D342D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Release 4: Título do release entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"nitf custom galleria\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-23 20:37:52',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,8,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (19,72,'Release 1: Título do release entre 35 e 90 caracteres',0x72656C656173652D312D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'<p>Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n','\r\n<p><img src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" border=\"0\" alt=\"nitf custom galleria\" style=\"margin-right: 10px; float: left;\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p> </p>\r\n<p><object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object></p>',1,17,'2013-10-21 18:35:16',576,'','2013-11-03 02:17:55',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,7,'','',1,2,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','Chapéu da editoria'),
 (20,73,'Release 2: Título do release entre 35 e 90 caracteres',0x72656C656173652D322D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 1 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"nitf custom galleria\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>','',1,17,'2013-10-21 18:35:16',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,6,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (21,74,'Release 3: Título do release entre 35 e 90 caracteres',0x72656C656173652D332D746974756C6F2D646F2D72656C656173652D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 1 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"nitf custom galleria\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>','',1,17,'2013-10-21 18:35:16',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,5,'','',1,2,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (22,75,'Texto 3 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D332D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Subtítulo do texto 3. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtítulo do texto 3. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\"><span class=\"copyright\">Crédito da imagem</span></div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;<img id=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" class=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" style=\"margin-right: 10px; float: left;\" title=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" lang=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" src=\"images/imagens_menu/conteudos_de_marcacao/nitf_custom_galleria.jpeg\" alt=\"nitf custom galleria\" longdesc=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" usemap=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>\r\n<p style=\"text-align: left;\">&nbsp;</p>\r\n<p style=\"text-align: left;\">&nbsp;</p>\r\n<p style=\"text-align: left;\"><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p style=\"text-align: left;\"><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p style=\"text-align: left;\"><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;&nbsp;&nbsp; Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-23 20:44:29',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',16,4,'','',1,12,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (23,76,'Texto 4 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D342D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 4 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Texto 4 - Título da notícia entre 35 e 90 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">\r\n<p><a title=\"Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente\" href=\"images/imagens_menu/imagem-noticia-vertical-tamanho-550.jpeg\"><img style=\"margin-right: 10px; float: left;\" src=\"images/imagens_menu/imagem-noticia-vertical-tamanho-550.jpeg\" alt=\"imagem-noticia-vertical-tamanho-550\" /></a>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 8pt;\">Legenda da foto (arquivo .JPG) deve</span></p>\r\n<p><span style=\"font-size: 8pt;\"> ter até 60 caracteres,</span></p>\r\n<p><span style=\"font-size: 8pt;\"> preferencialmente</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-23 20:46:00',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',12,3,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','');
INSERT INTO `portal_modelo_3x`.`pmgov2013_content` VALUES  (24,77,'Texto 5 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D352D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 5 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtítulo do texto 5. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div style=\"text-align: left;\">\r\n<p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n</div>\r\n<p style=\"text-align: left;\">&nbsp;</p>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-22 21:30:03',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,2,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (25,78,'Texto 2 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D322D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<h1 class=\"documentFirstHeading\" style=\"text-align: left;\">Texto 2 - Título da notícia entre 35 e 90 caracteres</h1>\r\n<h2 class=\"nitfSubtitle\" style=\"text-align: left;\">Chaéeu da editoria</h2>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">Subtitulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</div>\r\n<div class=\"documentDescription\" style=\"text-align: left;\">&nbsp;\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>&nbsp;</p>\r\n</div>\r\n<p>\r\n<object data=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" type=\"application/x-shockwave-flash\" width=\"640\" height=\"480\"><param name=\"movie\" value=\"//www.youtube-nocookie.com/v/Vj1MpR3iER4?hl=pt_BR&amp;version=3&amp;rel=0\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /></object>\r\n</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\"><strong>Subtítulo em negrito</strong></p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p style=\"text-align: left;\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>','',1,17,'2013-10-21 18:35:16',576,'','2013-10-23 20:43:54',576,0,'0000-00-00 00:00:00','2013-10-21 18:35:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',3,1,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (26,79,'Conheça o novo modelo de plataforma digital do governo federal',0x636F6E686563612D6F2D6E6F766F2D6D6F64656C6F2D64652D706C617461666F726D612D6469676974616C2D646F2D676F7665726E6F2D6665646572616C,'<p style=\"text-align: left;\">Estrutura reúne o que há de mais adequado em soluções digitais de acessibilidade e de divulgação de informações nos mais variados formatos; conheça todos os detalhes deste novo modelo</p>\r\n','\r\n<p style=\"text-align: left;\"><span>Para otimizar a comunicação com o cidadão, foi criada a Identidade Digital de Governo. Esse projeto busca padronizar os portais dos órgãos públicos federais e alinhar as informações com foco no cidadão.</span></p>\r\n<p style=\"text-align: left;\"><span><span>Os conteúdos, módulos e funcionalidades foram criados para facilitar o acesso aos serviços oferecidos pelo Governo Federal, assim como possibilitam, por meio de vídeos, infográficos, textos, aplicativos, vídeos, que as notícias sejam facilmente compreendidas.</span></span></p>\r\n<p style=\"text-align: left;\"><a href=\"index.php/manuais\">Acesse os manuais que irão auxiliar na montagem de sites dos órgãos do governo federal</a></p>\r\n<p style=\"text-align: left;\"><span><span><span>A nova Identidade também garante uma navegação acessível, para pessoas com deficiência, e adota conceito de web responsiva, ou seja, a páginas se adaptam automaticamente e podem ser visualizadas tanto em um computador quanto em smartphones e tablets, garantindo uma visualização mais uniforme.</span></span></span></p>\r\n<p style=\"text-align: left;\"><span>Navegue pelo portal e conheça todas as aplicações possíveis para os mais variados conteúdos, sejam vídeos, imagens, áudios e textos.</span></p>\r\n<p style=\"text-align: left;\">Bom trabalho!</p>',1,27,'2013-10-23 20:51:50',576,'','2013-11-02 20:17:56',576,0,'0000-00-00 00:00:00','2013-10-23 20:51:50','0000-00-00 00:00:00','{\"image_intro\":\"www.youtube.com\\/v\\/BGzfIhIUF68?version=3&hl=pt_BR&rel=0\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"Saiba mais\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',11,1,'Internet, comunicação','',1,45,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','identidade digital de governo'),
 (27,80,'Manuais',0x6D616E75616973,'<p>&nbsp;</p>\r\n<table class=\"plain\">\r\n<tbody>\r\n<tr>\r\n<td><a class=\"internal-link\" title=\"\" href=\"resolveuid/104ed19893a1441bb4cb47cfe6f59611\" target=\"_self\"> <img class=\"image-inline\" style=\"margin: 10px;\" title=\"Imagem da capa do Manual de diretrizes de comunicação da identidade digital\" src=\"images/imagens_menu/manuais/diretrizes.jpeg\" alt=\"diretrizes\" /> </a></td>\r\n<td>\r\n<p><span> <strong><a href=\"images/manuais/diretrizes-de-comunicacao_v2_final.pdf\">Manual de Diretrizes de Comunicação da Identidade Digital de Governo</a><a class=\"internal-link\" title=\"Manual de diretrizes de comunicação da identidade digital do governo\" href=\"resolveuid/104ed19893a1441bb4cb47cfe6f59611\" target=\"_self\"></a> </strong><br /> Material foi desenvolvido com objetivo de apresentar as funções, vantagens e características do Portal Padrão, parte integrante do Projeto de Identidade Digital do Governo Federal. <br /> <br /> versão 2.0 / outubro de 2013 </span></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><img class=\"image-inline\" style=\"margin: 10px;\" title=\"Imagem da capa do Guia de Estilo do Portal Padrão para Identidade Digital de Governo\" src=\"images/imagens_menu/manuais/guia_de_estilo.jpeg\" alt=\"guia de estilo\" /></td>\r\n<td>\r\n<p><span> <strong> Guia de Estilo do Portal Padrão para Identidade Digital de Governo (em breve) <br /> </strong> </span><span style=\"line-height: 1.5em;\">Guia Visual lista as funções, características e relações estruturais entre os diversos elementos do projeto gráfico, possibilitando a compreensão do conjunto e ao mesmo tempo oferecendo as informações necessárias para sua correta replicação.</span></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td><a class=\"external-link\" title=\"\" href=\"http://identidade-digital-de-governo-plone.readthedocs.org/en/latest/\" target=\"_self\"> <img class=\"image-inline\" style=\"margin: 10px;\" title=\"Imagem capa Manual Técnico de Instalação do Portal Padrão\" src=\"images/imagens_menu/manuais/manual_tecnico.jpeg\" alt=\"manual tecnico\" /> </a></td>\r\n<td><span> <strong> <a class=\"external-link\" title=\"Manual técnico de instalação do Portal Padrão\" href=\"http://identidade-digital-de-governo-plone.readthedocs.org/en/latest/\" target=\"_self\">Manual técnico de instalação do Portal Padrão para Identidade Digital de Governo</a> </strong> <br /> Este documento explica como instalar a implementação modelo do Portal Padrão utilizando o Plone, em um computador com sistema operacional Linux, empregando a distribuição Debian ou Ubuntu. </span></td>\r\n</tr>\r\n<tr>\r\n<td><a class=\"internal-link\" title=\"\" href=\"resolveuid/7022edde8ccf445f97f66f97758a310a\" target=\"_self\"> <img class=\"image-inline\" title=\"Imagem da capa do Manual de Gestão de Conteúdo em Plone do Portal Padrão\" src=\"images/imagens_menu/manuais/guia_de_conteudo_plone.jpeg\" alt=\"guia de conteudo plone\" /> </a></td>\r\n<td>\r\n<div>\r\n<p><strong><a href=\"images/manuais/manual-gestao-de-conteudo-em-plone-do-portal-padrao.pdf\">Manual de Gestão de Conteúdo em Plone do Portal Padrão para Identidade Digital de Governo</a> (arquivo em pdf) </strong><br /> Objetivo desse manual é apresentar a versão 4.3 do Plone e seus principais recursos, habilitando o leitor a utilizar suas ferramentas para a gestão de conteúdo e consequente aplicação nos demais sites do governo federal desenvolvidos em Plone.</p>\r\n</div>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<div class=\"row\" data-layout-type=\"row\">\r\n<div class=\"cell width-16 position-0 \" data-panel=\"\">\r\n<div>\r\n<div id=\"f96ec405e1814727beff88b52307485b\" class=\"tile azul-escuro\" data-tile=\"@@standaloneheader/f96ec405e1814727beff88b52307485b\">\r\n<div class=\"outstanding-header\">\r\n<h3 class=\"outstanding-title\" style=\"text-align: left;\">Outros manuais</h3>\r\n</div>\r\n</div>\r\n</div>\r\n<div>\r\n<div id=\"dbe92e9afafe46cd8001b4f3690d1004\" class=\"tile tile-default\" data-tile=\"@@collective.cover.richtext/dbe92e9afafe46cd8001b4f3690d1004\">\r\n<div class=\"tile-content\">\r\n<p style=\"text-align: left;\">Acesse a documentação abaixo para auxílio no desenvolvimento do portal padrão:</p>\r\n<p style=\"text-align: left;\"><strong> <a class=\"external-link\" title=\"Administração de sites e portais\" href=\"https://www.governoeletronico.gov.br/acoes-e-projetos/padroes-brasil-e-gov/guia-de-administracao\" target=\"_self\"><span style=\"color: #000000;\"> Administração de Sites e Portais</span> <br /> </a> </strong> <span>Objetivo do Guia de Administração é oferecer subsídios para a concepção, desenvolvimento, manutenção e administração de sítios de governo eletrônico na esfera federal.</span></p>\r\n<p style=\"text-align: left;\"><strong> <a class=\"external-link\" title=\"Cartilha de Codificação\" href=\"https://www.governoeletronico.gov.br/acoes-e-projetos/padroes-brasil-e-gov/cartilha-de-codificacao\" target=\"_self\"> Cartilha de Codificação <br /> </a> </strong> Manual detalha as recomendações de boas práticas em codificação, que orientem as equipes no desenvolvimento de sítios, portais e serviços de governo eletrônico com o propósito de torná-los identificáveis, portáveis, relevantes, acessíveis e efetivos.</p>\r\n<p style=\"text-align: left;\"><strong> <a class=\"external-link\" title=\"Cartilha de usabilidade\" href=\"https://www.governoeletronico.gov.br/acoes-e-projetos/padroes-brasil-e-gov/cartilha-de-usabilidade\" target=\"_self\"> Cartilha de Usabilidade <br /> </a> </strong> <span>Cartilha propõe ser um guia na aplicação da usabilidade em sítios da administração pública de forma clara e descomplicada. São apresentadas recomendações descritas de forma prática e aplicável, assim como orientações sobre como realizar testes.</span></p>\r\n<p style=\"text-align: left;\"><strong> <a class=\"external-link\" title=\"Acessibilidade\" href=\"https://www.governoeletronico.gov.br/acoes-e-projetos/e-MAG\" target=\"_self\"> Acessibilidade <br /> </a> </strong> <span style=\"line-height: 1.5em;\">Modelo de Acessibilidade de Governo Eletrônico (e-MAG) consiste em um conjunto de recomendações para que o processo de acessibilidade dos sítios e portais do governo brasileiro seja conduzido de forma padronizada e de fácil implementação.</span></p>\r\n<p style=\"text-align: left;\"><span style=\"line-height: 1.5em;\"> <strong> <a class=\"external-link\" title=\"Redes Sociais\" href=\"http://www.secom.gov.br/sobre-a-secom/acoes-e-programas/comunicacao-digital/redes-sociais/publicacoes/manual-de-redes-sociais-/at_download/file\" target=\"_self\">Redes Sociais</a> </strong> <br /> Este documento tem como objetivo estipular melhores práticas e guiar os agentes da comunidade Sicom no uso de redes sociais, incluindo a geração de conteúdo, interação com o usuário e atuação em casos de crise. </span></p>\r\n<p style=\"text-align: left;\"><span style=\"line-height: 1.5em;\"> <strong> <a class=\"external-link\" title=\"Cartilha de Redação Web\" href=\"https://www.governoeletronico.gov.br/acoes-e-projetos/padroes-brasil-e-gov/cartilha-de-redacao-web\" target=\"_self\">Cartilha de Redação Web</a> </strong> <br /> Desenvolvida por Bruno Rodrigues e com direitos cedidos ao Departamento de Governo Eletrônico, a cartilha pretende ser um guia e um norte na tarefa de elaborar informação clara, estruturada e eficaz para o meio digital</span></p>\r\n<p style=\"text-align: left;\">&nbsp;<a href=\"images/manuais/icones-portal-brasil-png.zip\">Acesse as opções de cores para os ícones do Portal Padrão em extensão .png (arquivo zip)</a></p>\r\n<br class=\"row\" data-layout-type=\"row\" />\r\n<p style=\"text-align: left;\">&nbsp;</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>','',1,26,'2013-10-23 20:52:45',576,'','2013-10-23 21:29:46',576,0,'0000-00-00 00:00:00','2013-10-23 20:52:45','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',13,0,'','',1,30,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (28,88,'Perguntas frequentes',0x70657267756E7461732D6672657175656E746573,'<div class=\"documentDescription description\" style=\"text-align: left;\">Nesta seção são divulgadas as perguntas frequentes sobre o (a) [nome do órgão ou entidade] e ações no âmbito de sua competência</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Caso o órgão ou entidade disponibilize uma seção de “Perguntas frequentes” em seu portal eletrônico, deverá remeter para o link que dá acesso a tal seção. O órgão/entidade que não divulga “Perguntas frequentes” deverá fazê-la e mantê-la constantemente atualizada, disponibilizando proativamente as respostas às perguntas usualmente formuladas pelos cidadãos.</p>\r\n<p>A <a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivIl_03/Resolu%C3%A7%C3%A3o/2002/RES07-02web.htm\" target=\"_self\">Resolução nº 7, de 29 de julho de 2002</a>, do Comitê Eletrônico de Governo Eletrônico e os Padrões Web em Governo Eletrônico (Guia de administração do MPOG 8) orientam acerca de criação de seção dedicada às respostas às perguntas mais frequentes da sociedade.</p>\r\n</div>\r\n</div>','',1,28,'2013-10-23 21:35:08',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-23 21:35:08','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (29,89,'Contato',0x636F6E7461746F,'<div class=\"documentDescription description\" style=\"text-align: left;\">Área reúne as formas de contato entre o visitante do portal e o órgão</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Esta seção do portal deverá fornecer ao internauta todas as formas de contato disponíveis para ele interagir com o órgão.&nbsp;</p>\r\n<p>Aqui devem ser publicados os telefones de contato, a ouvidoria, o&nbsp;endereço físico e eletrônico do órgão, além do formulário de contato (<a class=\"internal-link\" title=\"\" href=\"http://portalpadrao.plone.org.br/contato/contato\" target=\"_self\">Página com exemplo de formulário para contato</a>).</p>\r\n</div>\r\n</div>','',1,29,'2013-10-23 21:36:17',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-23 21:36:17','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (30,90,'Servicos da Denominação',0x7365727669636F732D64612D64656E6F6D696E6163616F,'<div class=\"documentDescription description\" style=\"text-align: left;\">Página agrega todos os links de acessos a sistemas do órgão</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Esta área deverá reunir os links de acesso a sistemas que um órgão ou entidade possua e que disponibilize para os seus visitantes.&nbsp;<span>A página agregadora de conteúdo tem como objetivo facilitar a navegação para o internauta e por isso deverá listar os acessos a sistema com&nbsp;</span><span>imagens, um breve descritivo sobre o que será encontrado na página em questão e o respectivo link para acesso.&nbsp;</span></p>\r\n<p><span>No exemplo abaixo, estão listados alguns sistemas do Ministério da Educação espalhados por todo o site da insituição. Nesta proposta, todos eles seriam publicados em uma única área:</span></p>\r\n</div>\r\n</div>','',1,30,'2013-10-23 21:37:10',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-23 21:37:10','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (31,91,'Dados abertos',0x6461646F732D61626572746F73,'<div class=\"documentDescription description\" style=\"text-align: left;\">Área em que o portal deve disponibilizar informações sobre os trabalhos realizados pelo órgão</div>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<p>Cada órgão deverá manter em seu portal um link para o site <a class=\"external-link\" title=\"\" href=\"http://dados.gov.br/dados-abertos/\" target=\"_self\">Página Dados Abertos</a>&nbsp;(link externo)<span>, que reúne informações de todas as principais áreas do governo federal.&nbsp;</span></p>\r\n</div>\r\n</div>','',1,31,'2013-10-23 21:37:38',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-10-23 21:37:38','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (32,92,'Area de imprensa',0x617265612D64652D696D7072656E7361,'<p class=\"callout\" style=\"text-align: left;\">Assessor 1<strong>&nbsp;<br /></strong><span>Esplanada dos Ministérios, Bl. “L” - 9º Andar - Gabinete<br /></span><span>70047-900 - Brasília - DF<br /></span><span>Fone: XX - XXXX - XXXX<br /></span><span>E-mail:&nbsp;</span><a href=\"mailto:acsgabinete@mec.gov.br\">e</a>mail@orgao.gov.br</p>\r\n<p class=\"callout\" style=\"text-align: left;\"><span>Assessor 2</span><strong><br /></strong><span>Esplanada dos Ministérios, Bl. “L” - 9º Andar - Gabinete<br /></span><span>70047-900 - Brasília - DF<br /></span><span>Fone: XX - XXXX - XXXX<br /></span><span>E-mail:&nbsp;</span><a href=\"mailto:acsgabinete@mec.gov.br\">e</a><span>mail@orgao.gov.br</span></p>\r\n<p class=\"callout\" style=\"text-align: left;\">Assessor 3<strong><br /></strong><span>Esplanada dos Ministérios, Bl. “L” - 9º Andar - Sala 903<br /></span><span>70047-900 - Brasília - DF<br /></span>Fone: XX - XXXX - XXXX<br /><span>E-mail:&nbsp;</span><a href=\"mailto:acsgabinete@mec.gov.br\">e</a><span>mail@orgao.gov.br</span></p>','',1,32,'2013-10-23 21:40:16',576,'','2013-10-23 21:40:38',576,0,'0000-00-00 00:00:00','2013-10-23 21:40:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (33,95,'Acessibilidade',0x61636573736962696C6964616465,'<p style=\"text-align: left;\">Este portal segue as diretrizes do e-MAG (Modelo de Acessibilidade em Governo Eletrônico), conforme as normas do Governo Federal, em obediência ao Decreto 5.296, de 2.12.2004</p>\r\n<p style=\"text-align: left;\">O termo acessibilidade significa incluir a pessoa com deficiência na participação de atividades como o uso de produtos, serviços e informações. Alguns exemplos são os prédios com rampas de acesso para cadeira de rodas e banheiros adaptados para deficientes.</p>\r\n<p style=\"text-align: left;\">Na internet, acessibilidade refere-se principalmente às recomendações do WCAG (World Content Accessibility Guide) do W3C e no caso do Governo Brasileiro ao e-MAG (Modelo de Acessibilidade em Governo Eletrônico). O e-MAG está alinhado as recomendações internacionais, mas estabelece padrões de comportamento acessível para sites governamentais.</p>\r\n<p style=\"text-align: left;\">Na parte superior do portal existe uma barra de acessibilidade onde se encontra atalhos de navegação padronizados e a opção para alterar o contraste. Essas ferramentas estão disponíveis em todas as páginas do portal.</p>\r\n<p style=\"text-align: left;\">Os atalhos padrões do governo federal são:</p>\r\n<p style=\"text-align: left;\">&nbsp;&nbsp;&nbsp; Teclando-se Alt + 1 em qualquer página do portal, chega-se diretamente ao começo do conteúdo principal da página.<br />&nbsp;&nbsp;&nbsp; Teclando-se Alt + 2 em qualquer página do portal, chega-se diretamente ao início do menu principal.<br />&nbsp;&nbsp;&nbsp; Teclando-se Alt + 3 em qualquer página do portal, chega-se diretamente em sua busca interna.<br />&nbsp;&nbsp;&nbsp; Teclando-se Alt + 4 em qualquer página do portal, chega-se diretamente ao rodapé do site.</p>\r\n<p style=\"text-align: left;\">Esses atalhos valem para o navegador Chrome, mas existem algumas variações para outros navegadores.</p>\r\n<p style=\"text-align: left;\">Quem prefere utilizar o Internet Explorer é preciso apertar o botão Enter do seu teclado após uma das combinações acima. Portanto, para chegar ao campo de busca de interna é preciso pressionar Alt+3 e depois Enter.</p>\r\n<p style=\"text-align: left;\">No caso do Firefox, em vez de Alt + número, tecle simultaneamente Alt + Shift + número.</p>\r\n<p style=\"text-align: left;\">Sendo Firefox no Mac OS, em vez de Alt + Shift + número, tecle simultaneamente Ctrl + Alt + número.</p>\r\n<p style=\"text-align: left;\">No Opera, as teclas são Shift + Escape + número. Ao teclar apenas Shift + Escape, o usuário encontrará uma janela com todas as alternativas de ACCESSKEY da página.</p>\r\n<p style=\"text-align: left;\">Ao final desse texto, você poderá baixar alguns arquivos que explicam melhor o termo acessibilidade e como deve ser implementado nos sites da Internet.</p>\r\n<div id=\"content-core\" style=\"text-align: left;\">\r\n<div id=\"parent-fieldname-text\">\r\n<h2>Leis e decretos sobre acessibilidade:</h2>\r\n<ul>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_Ato2004-2006/2004/Decreto/D5296.htm\" target=\"_self\">Decreto nº 5.296 de 02 de dezembro de 2004&nbsp;</a>(link externo)</li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2007-2010/2009/decreto/d6949.htm\" target=\"_self\">Decreto nº 6.949, de 25 de agosto de 2009</a> (link externo)&nbsp;- Promulga a Convenção Internacional sobre os Direitos das Pessoas com Deficiência e seu Protocolo Facultativo, assinados em Nova York, em 30 de março de 2007&nbsp;</li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2012/Decreto/D7724.htm\" target=\"_self\">Decreto nº 7.724, de 16 de Maio de 2012</a>&nbsp;(link externo) - Regulamenta a Lei No 12.527, que dispõe sobre o acesso a informações.</li>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://www.governoeletronico.gov.br/acoes-e-projetos/e-MAG\" target=\"_self\">Modelo de Acessibilidade de Governo Eletrônico</a>&nbsp;(link externo)&nbsp;</li>\r\n<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://www.governoeletronico.gov.br/biblioteca/arquivos/portaria-no-03-de-07-05-2007\" target=\"_self\">Portaria nº 03, de 07 de Maio de 2007 - formato .pdf (35,5Kb)</a>&nbsp;(link externo) - Institucionaliza o Modelo de Acessibilidade em Governo Eletrônico – e-MAG&nbsp;</li>\r\n</ul>\r\n<h2>Dúvidas, sugestões e críticas:</h2>\r\n<p>No caso de problemas com a acessibilidade do portal, favor acessar a&nbsp;<a class=\"external-link\" title=\"\" href=\"http://portalpadrao.plone.org.br/fale-conosco\" target=\"_self\">Página de contato</a><span>.</span></p>\r\n<h2>Dicas, links e recursos úteis:&nbsp;</h2>\r\n<ul>\r\n<li><a class=\"external-link\" title=\"\" href=\"http://acessibilidadelegal.com/\" target=\"_self\">Acessibilidade Legal</a>&nbsp;(link externo)</li>\r\n<li class=\"last-item\"><a class=\"external-link\" title=\"\" href=\"http://acessodigital.net/links.html\" target=\"_self\">Acesso Digital</a>&nbsp;(link externo)</li>\r\n</ul>\r\n</div>\r\n</div>','',1,34,'2013-10-23 21:52:16',576,'','2013-10-23 21:53:15',576,0,'0000-00-00 00:00:00','2013-10-23 21:52:16','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,6,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (34,97,'Saiba como montar o menu da Lei de Acesso à Informação',0x73616962612D636F6D6F2D6D6F6E7461722D6F2D6D656E752D64612D6C65692D64652D61636573736F2D612D696E666F726D6163616F,'<p>Órgãos do governo federal devem disponibilizar em seu site um menu especificado pela LAI</p>\r\n','\r\n<p>Desde 1º de junho de 2013, todos os órgãos do Poder Executivo devem publicar em seus sites um rol de informações classificadas e desclassificadas, além de relatórios estatísticos sobre a Lei de Acesso à Informação (LAI).  Para orientar o cumprimento da exigência, o governo federal elaborou uma <a href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/espaco-gestor/arquivos/Guia_InformacoesClassificadas.pdf\">cartilha</a>, que visa nortear a publicação e a disposição dos dados nos endereços eletrônicos das entidades governamentais.<span> </span></p>\r\n<p>No menu ao lado, abaixo do chapéu <strong>Sobre</strong>, estão todas as seções da legislação que que um órgão do governo federal deve publicar. Ao clicar em cada um deles o gestor de conteúdo terá uma explicação sobre qual informação deverá ser disponibilizada para o cidadão.</p>\r\n<p>A LAI tem por objetivo regulamentar o direito constitucional de acesso dos brasileiros às informações públicas. <span>De acordo com a Controladoria Geral da União (CGU), o comando central da lei é “O acesso à informação é regra. O sigilo é a exceção”. Segundo o Coordenador-Geral de Promoção da Ética, Transparência e Integridade da entidade, Renato Capanema, o cidadão não precisa justificar a solicitação da informação. “O principio básico de uma cultura de acesso é justamente que a informação é pertencente à sociedade e não ao Estado. Com a LAI, o governo federal agora entrega a informação ao seu legítimo dono”, afirma.</span></p>\r\n<p>Por meio do e-SIC, sistema desenvolvido pela CGU, qualquer pessoa (física ou jurídica) pode encaminhar pedidos de acesso à informação para órgãos e entidades dos três Poderes da União, Estados, Distrito Federal e Municípios pela internet. Caso não possua acesso à web, o cidadão tem a opção de fazer seu cadastro na unidade física do Sistema de Informações ao Cidadão (SIC), ou também por telefone.</p>\r\n<p>A LAI prevê dois tipos de restrição à regra de cessão das informações: dados pessoais e informações classificadas por autoridades como sigilosas.</p>\r\n<p>As informações consideradas sigilosas são aquelas que podem colocar em risco a segurança da sociedade ou do Estado. Elas são classificadas em três níveis, a contar da data de sua produção:</p>\r\n<p>• Ultrassecreta: prazo de segredo de 25 anos (renovável uma única vez)</p>\r\n<p>• Secreta: prazo de segredo de 15 anos</p>\r\n<p>• Reservada: prazo de segredo de 5 anos</p>\r\n<p><span>As informações pessoais são aquelas relacionadas à pessoa natural identificada ou identificável e por isso têm seu acesso restrito, independentemente de classificação de sigilo, pelo prazo máximo de 100 anos a partir da sua data de produção. A intenção é respeitar a intimidade, vida privada, honra e imagem das pessoas.</span></p>\r\n<p>Caso a informação solicitada seja negada e o cidadão não concorde com a decisão, é possível entrar com recurso e pedir uma nova avaliação. A LAI prevê até quatro instâncias de recurso, podendo ser solicitado pessoalmente ou via internet. No <a href=\"http://www.acessoainformacao.gov.br/acessoainformacaogov/sic/como-entrar-recurso.asp\">site Acesso à Informação</a> é possível ver o passo a passo do processo. </p>\r\n<p>Dos pedidos recebidos em seu primeiro ano de vida (maio de 2012 a maio de 2013), 95,8% (83.483) foram respondidos, sendo 79,2% (66.185) de forma positiva, com a informação solicitada entregue ao cidadão. Outros 6,9% (5.764) tiveram acesso parcialmente concedido, eram perguntas duplicadas ou repetidas, tratavam de informação inexistente ou não eram de competência do órgão demandando. Apenas 9,8% (8.205) foram respondidos negativamente por se tratarem de pedidos de dados pessoais ou sigilosos.</p>\r\n<p>Fonte<br /><a class=\"external-link\" href=\"http://www.brasil.gov.br\" target=\"_self\" title=\"\">Portal Brasil</a></p>',1,17,'2013-11-03 00:15:36',576,'','2013-11-03 12:16:56',576,0,'0000-00-00 00:00:00','2013-11-03 00:15:36','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',5,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','Legislação'),
 (35,99,'Texto 3 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D332D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<p>Subtítulo do texto 3. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n','\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',1,35,'2013-11-03 00:47:29',576,'','2013-11-03 00:51:33',576,0,'0000-00-00 00:00:00','2013-11-03 00:47:29','0000-00-00 00:00:00','{\"image_intro\":\"images\\/imagens_menu\\/foto-230-por-136-01.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"Foto no tamanho 230 pixels de largura por 136 pixels de altura\",\"image_intro_caption\":\"\",\"image_fulltext\":\"images\\/conteudo\\/thumb-imagem-conteudo.jpg\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"Legenda da foto (arquivo .JPG) deve ter at\\u00e9 60 caracteres, preferencialmente\",\"image_fulltext_caption\":\"Cr\\u00e9dito da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,8,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (36,100,'Texto 4 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D342D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<p>Subtítulo do texto 4. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n','\r\n<p> </p>\r\n<dl class=\"image-left captioned\" style=\"width: 143px;\"><dt><a href=\"../../conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres/imagem-noticia-vertical-tamanho-550\" rel=\"lightbox\"><img src=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres/imagem-noticia-vertical-tamanho-550/@@images/2be17981-e80a-4348-a4bd-3db6c0994e6a.jpeg\" border=\"0\" alt=\"Imagem noticia vertical tamanho 550\" title=\"Imagem noticia vertical tamanho 550\" width=\"143\" height=\"200\" /></a></dt><dd class=\"image-caption\" style=\"width: 143px;\">Legenda da foto (arquivo .JPG) deve ter até 60 caracteres, preferencialmente</dd></dl>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p> </p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',1,35,'2013-11-03 00:47:29',576,'','2013-11-03 00:57:55',576,0,'0000-00-00 00:00:00','2013-11-03 00:47:29','0000-00-00 00:00:00','{\"image_intro\":\"images\\/imagens_menu\\/foto-230-por-136-02.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"Foto no tamanho 230 pixels de largura por 136 pixels de altura\",\"image_intro_caption\":\"\",\"image_fulltext\":\"images\\/conteudo\\/thumb-imagem-conteudo.jpg\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"Legenda da foto (arquivo .JPG) deve ter at\\u00e9 60 caracteres, preferencialmente\",\"image_fulltext_caption\":\"Cr\\u00e9dito da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',5,0,'','',1,3,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (37,101,'Texto 5 - Título da notícia entre 35 e 90 caracteres',0x746578746F2D352D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D63617261637465726573,'<p>Subtítulo do texto 5. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n','\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',1,35,'2013-11-03 00:47:29',576,'','2013-11-03 01:33:50',576,0,'0000-00-00 00:00:00','2013-11-03 00:47:29','0000-00-00 00:00:00','{\"image_intro\":\"images\\/imagens_menu\\/foto-230-por-136-03.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"Foto no tamanho 230 pixels de largura por 136 pixels de altura\",\"image_intro_caption\":\"\",\"image_fulltext\":\"images\\/conteudo\\/thumb-imagem-conteudo.jpg\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"Legenda da foto (arquivo .JPG) deve ter at\\u00e9 60 caracteres, preferencialmente\",\"image_fulltext_caption\":\"Cr\\u00e9dito da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',7,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (38,102,'Texto 5 - Título da notícia entre 35 e 90 caracteres (2)',0x746578746F2D352D746974756C6F2D64612D6E6F74696369612D656E7472652D33352D652D39302D636172616374657265732D32,'<p>Subtítulo do texto 5. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres</p>\r\n','\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<blockquote class=\"pullquote\">Use o estilo citação, localizado no campo corpo do texto, para criar um olho na sua matéria. Não há um limite de caracteres</blockquote>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p><strong>Subtítulo em negrito</strong></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',-2,35,'2013-11-03 00:47:29',576,'','2013-11-03 01:33:50',576,0,'0000-00-00 00:00:00','2013-11-03 00:47:29','0000-00-00 00:00:00','{\"image_intro\":\"images\\/imagens_menu\\/foto-230-por-136-03.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"Foto no tamanho 230 pixels de largura por 136 pixels de altura\",\"image_intro_caption\":\"\",\"image_fulltext\":\"images\\/conteudo\\/thumb-imagem-conteudo.jpg\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"Legenda da foto (arquivo .JPG) deve ter at\\u00e9 60 caracteres, preferencialmente\",\"image_fulltext_caption\":\"Cr\\u00e9dito da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',7,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','');
INSERT INTO `portal_modelo_3x`.`pmgov2013_content` VALUES  (39,105,'Imagem 1: título com até 45 caracteres',0x696D6167656D2D312D746974756C6F2D636F6D2D6174652D34352D63617261637465726573,'<p>Espaço para incluir a legenda/descrição da imagem</p>','',1,37,'2013-11-03 13:14:39',576,'','0000-00-00 00:00:00',0,0,'0000-00-00 00:00:00','2013-11-03 13:14:39','0000-00-00 00:00:00','{\"image_intro\":\"images\\/galeria_em_artigos\\/image01_peq.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"Nome do autor da imagem\",\"image_fulltext\":\"images\\/galeria_em_artigos\\/image01_grd.png\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"Nome do autor da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',1,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (40,106,'Imagem 2: título com até 45 caracteres',0x696D6167656D2D322D746974756C6F2D636F6D2D6174652D34352D63617261637465726573,'<p>Espaço para incluir a legenda/descrição da imagem</p>','',1,37,'2013-11-03 13:14:39',576,'','2013-11-03 13:15:31',576,0,'0000-00-00 00:00:00','2013-11-03 13:14:39','0000-00-00 00:00:00','{\"image_intro\":\"images\\/galeria_em_artigos\\/image02_peq.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"Nome do autor da imagem\",\"image_fulltext\":\"images\\/galeria_em_artigos\\/image02_grd.png\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"Nome do autor da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (41,107,'Imagem 3: título com até 45 caracteres',0x696D6167656D2D332D746974756C6F2D636F6D2D6174652D34352D63617261637465726573,'<p>Espaço para incluir a legenda/descrição da imagem</p>','',1,37,'2013-11-03 13:14:39',576,'','2013-11-03 13:16:27',576,0,'0000-00-00 00:00:00','2013-11-03 13:14:39','0000-00-00 00:00:00','{\"image_intro\":\"images\\/galeria_em_artigos\\/image03_peq.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"Nome do autor da imagem\",\"image_fulltext\":\"images\\/galeria_em_artigos\\/image03_grd.png\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"Nome do autor da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (42,108,'Imagem 4: título com até 45 caracteres',0x696D6167656D2D342D746974756C6F2D636F6D2D6174652D34352D63617261637465726573,'<p>Espaço para incluir a legenda/descrição da imagem</p>','',1,37,'2013-11-03 13:14:39',576,'','2013-11-03 13:16:54',576,0,'0000-00-00 00:00:00','2013-11-03 13:14:39','0000-00-00 00:00:00','{\"image_intro\":\"images\\/galeria_em_artigos\\/image04_peq.jpg\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"Nome do autor da imagem\",\"image_fulltext\":\"images\\/galeria_em_artigos\\/image04_grd.png\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"Nome do autor da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',2,0,'','',1,1,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*',''),
 (43,109,'SEM Imagem: título com até 45 caracteres',0x73656D2D696D6167656D2D746974756C6F2D636F6D2D6174652D34352D63617261637465726573,'<p>Teste de item sem imagem. Espaço para incluir a legenda/descrição da imagem</p>','',1,37,'2013-11-03 13:14:39',576,'','2013-11-03 13:19:07',576,0,'0000-00-00 00:00:00','2013-11-03 13:14:39','0000-00-00 00:00:00','{\"image_intro\":\"\",\"float_intro\":\"\",\"image_intro_alt\":\"\",\"image_intro_caption\":\"Nome do autor da imagem\",\"image_fulltext\":\"\",\"float_fulltext\":\"\",\"image_fulltext_alt\":\"\",\"image_fulltext_caption\":\"Nome do autor da imagem\"}','{\"urla\":false,\"urlatext\":\"\",\"targeta\":\"\",\"urlb\":false,\"urlbtext\":\"\",\"targetb\":\"\",\"urlc\":false,\"urlctext\":\"\",\"targetc\":\"\"}','{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\",\"show_publishing_options\":\"\",\"show_article_options\":\"\",\"show_urls_images_backend\":\"\",\"show_urls_images_frontend\":\"\"}',4,0,'','',1,0,'{\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}',0,'*','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_content` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_content_frontpage`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_content_frontpage`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_content_frontpage`
--

/*!40000 ALTER TABLE `pmgov2013_content_frontpage` DISABLE KEYS */;
LOCK TABLES `pmgov2013_content_frontpage` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_content_frontpage` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_content_rating`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_content_rating`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_content_rating`
--

/*!40000 ALTER TABLE `pmgov2013_content_rating` DISABLE KEYS */;
LOCK TABLES `pmgov2013_content_rating` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_content_rating` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_content_types`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_content_types`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_content_types` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_title` varchar(255) NOT NULL DEFAULT '',
  `type_alias` varchar(255) NOT NULL DEFAULT '',
  `table` varchar(255) NOT NULL DEFAULT '',
  `rules` text NOT NULL,
  `field_mappings` text NOT NULL,
  `router` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`),
  KEY `idx_alias` (`type_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_content_types`
--

/*!40000 ALTER TABLE `pmgov2013_content_types` DISABLE KEYS */;
LOCK TABLES `pmgov2013_content_types` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_content_types` VALUES  (1,'Article','com_content.article','{\"special\":{\"dbtable\":\"#__content\",\"key\":\"id\",\"type\":\"Content\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"state\",\"core_alias\":\"alias\",\"core_created_time\":\"created\",\"core_modified_time\":\"modified\",\"core_body\":\"introtext\", \"core_hits\":\"hits\",\"core_publish_up\":\"publish_up\",\"core_publish_down\":\"publish_down\",\"core_access\":\"access\", \"core_params\":\"attribs\", \"core_featured\":\"featured\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"images\", \"core_urls\":\"urls\", \"core_version\":\"version\", \"core_ordering\":\"ordering\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"catid\", \"core_xreference\":\"xreference\", \"asset_id\":\"asset_id\"}, \"special\": {\"fulltext\":\"fulltext\"}}','ContentHelperRoute::getArticleRoute'),
 (2,'Weblink','com_weblinks.weblink','{\"special\":{\"dbtable\":\"#__weblinks\",\"key\":\"id\",\"type\":\"Weblink\",\"prefix\":\"WeblinksTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"state\",\"core_alias\":\"alias\",\"core_created_time\":\"created\",\"core_modified_time\":\"modified\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"publish_up\",\"core_publish_down\":\"publish_down\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"featured\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"images\", \"core_urls\":\"url\", \"core_version\":\"version\", \"core_ordering\":\"ordering\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"catid\", \"core_xreference\":\"xreference\", \"asset_id\":\"null\"}, \"special\": {}}','WeblinksHelperRoute::getWeblinkRoute'),
 (3,'Contact','com_contact.contact','{\"special\":{\"dbtable\":\"#__contact_details\",\"key\":\"id\",\"type\":\"Contact\",\"prefix\":\"ContactTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"name\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created\",\"core_modified_time\":\"modified\",\"core_body\":\"address\", \"core_hits\":\"hits\",\"core_publish_up\":\"publish_up\",\"core_publish_down\":\"publish_down\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"featured\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"image\", \"core_urls\":\"webpage\", \"core_version\":\"version\", \"core_ordering\":\"ordering\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"catid\", \"core_xreference\":\"xreference\", \"asset_id\":\"null\"}, \"special\": {\"con_position\":\"con_position\",\"suburb\":\"suburb\",\"state\":\"state\",\"country\":\"country\",\"postcode\":\"postcode\",\"telephone\":\"telephone\",\"fax\":\"fax\",\"misc\":\"misc\",\"email_to\":\"email_to\",\"default_con\":\"default_con\",\"user_id\":\"user_id\",\"mobile\":\"mobile\",\"sortname1\":\"sortname1\",\"sortname2\":\"sortname2\",\"sortname3\":\"sortname3\"}}','ContactHelperRoute::getContactRoute'),
 (4,'Newsfeed','com_newsfeeds.newsfeed','{\"special\":{\"dbtable\":\"#__newsfeeds\",\"key\":\"id\",\"type\":\"Newsfeed\",\"prefix\":\"NewsfeedsTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"name\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created\",\"core_modified_time\":\"modified\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"publish_up\",\"core_publish_down\":\"publish_down\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"featured\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"images\", \"core_urls\":\"link\", \"core_version\":\"version\", \"core_ordering\":\"ordering\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"catid\", \"core_xreference\":\"xreference\", \"asset_id\":\"null\"}, \"special\": {\"numarticles\":\"numarticles\",\"cache_time\":\"cache_time\",\"rtl\":\"rtl\"}}','NewsfeedsHelperRoute::getNewsfeedRoute'),
 (5,'User','com_users.user','{\"special\":{\"dbtable\":\"#__users\",\"key\":\"id\",\"type\":\"User\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"name\",\"core_state\":\"null\",\"core_alias\":\"username\",\"core_created_time\":\"registerdate\",\"core_modified_time\":\"lastvisitDate\",\"core_body\":\"null\", \"core_hits\":\"null\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"access\":\"null\", \"core_params\":\"params\", \"core_featured\":\"null\", \"core_metadata\":\"null\", \"core_language\":\"null\", \"core_images\":\"null\", \"core_urls\":\"null\", \"core_version\":\"null\", \"core_ordering\":\"null\", \"core_metakey\":\"null\", \"core_metadesc\":\"null\", \"core_catid\":\"null\", \"core_xreference\":\"null\", \"asset_id\":\"null\"}, \"special\": {}}','UsersHelperRoute::getUserRoute'),
 (6,'Article Category','com_content.category','{\"special\":{\"dbtable\":\"#__categories\",\"key\":\"id\",\"type\":\"Category\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created_time\",\"core_modified_time\":\"modified_time\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"null\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"null\", \"core_urls\":\"null\", \"core_version\":\"version\", \"core_ordering\":\"null\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"parent_id\", \"core_xreference\":\"null\", \"asset_id\":\"asset_id\"}, \"special\": {\"parent_id\":\"parent_id\",\"lft\":\"lft\",\"rgt\":\"rgt\",\"level\":\"level\",\"path\":\"path\",\"extension\":\"extension\",\"note\":\"note\"}}','ContentHelperRoute::getCategoryRoute'),
 (7,'Contact Category','com_contact.category','{\"special\":{\"dbtable\":\"#__categories\",\"key\":\"id\",\"type\":\"Category\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created_time\",\"core_modified_time\":\"modified_time\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"null\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"null\", \"core_urls\":\"null\", \"core_version\":\"version\", \"core_ordering\":\"null\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"parent_id\", \"core_xreference\":\"null\", \"asset_id\":\"asset_id\"}, \"special\": {\"parent_id\":\"parent_id\",\"lft\":\"lft\",\"rgt\":\"rgt\",\"level\":\"level\",\"path\":\"path\",\"extension\":\"extension\",\"note\":\"note\"}}','ContactHelperRoute::getCategoryRoute'),
 (8,'Newsfeeds Category','com_newsfeeds.category','{\"special\":{\"dbtable\":\"#__categories\",\"key\":\"id\",\"type\":\"Category\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created_time\",\"core_modified_time\":\"modified_time\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"null\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"null\", \"core_urls\":\"null\", \"core_version\":\"version\", \"core_ordering\":\"null\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"parent_id\", \"core_xreference\":\"null\", \"asset_id\":\"asset_id\"}, \"special\": {\"parent_id\":\"parent_id\",\"lft\":\"lft\",\"rgt\":\"rgt\",\"level\":\"level\",\"path\":\"path\",\"extension\":\"extension\",\"note\":\"note\"}}','NewsfeedsHelperRoute::getCategoryRoute'),
 (9,'Weblinks Category','com_weblinks.category','{\"special\":{\"dbtable\":\"#__categories\",\"key\":\"id\",\"type\":\"Category\",\"prefix\":\"JTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created_time\",\"core_modified_time\":\"modified_time\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"null\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"null\", \"core_urls\":\"null\", \"core_version\":\"version\", \"core_ordering\":\"null\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"parent_id\", \"core_xreference\":\"null\", \"asset_id\":\"asset_id\"}, \"special\": {\"parent_id\":\"parent_id\",\"lft\":\"lft\",\"rgt\":\"rgt\",\"level\":\"level\",\"path\":\"path\",\"extension\":\"extension\",\"note\":\"note\"}}','WeblinksHelperRoute::getCategoryRoute'),
 (10,'Tag','com_tags.tag','{\"special\":{\"dbtable\":\"#__tags\",\"key\":\"tag_id\",\"type\":\"Tag\",\"prefix\":\"TagsTable\",\"config\":\"array()\"},\"common\":{\"dbtable\":\"#__ucm_content\",\"key\":\"ucm_id\",\"type\":\"Corecontent\",\"prefix\":\"JTable\",\"config\":\"array()\"}}','','{\"common\":{\"core_content_item_id\":\"id\",\"core_title\":\"title\",\"core_state\":\"published\",\"core_alias\":\"alias\",\"core_created_time\":\"created_time\",\"core_modified_time\":\"modified_time\",\"core_body\":\"description\", \"core_hits\":\"hits\",\"core_publish_up\":\"null\",\"core_publish_down\":\"null\",\"core_access\":\"access\", \"core_params\":\"params\", \"core_featured\":\"featured\", \"core_metadata\":\"metadata\", \"core_language\":\"language\", \"core_images\":\"images\", \"core_urls\":\"urls\", \"core_version\":\"version\", \"core_ordering\":\"null\", \"core_metakey\":\"metakey\", \"core_metadesc\":\"metadesc\", \"core_catid\":\"null\", \"core_xreference\":\"null\", \"asset_id\":\"null\"}, \"special\": {\"parent_id\":\"parent_id\",\"lft\":\"lft\",\"rgt\":\"rgt\",\"level\":\"level\",\"path\":\"path\"}}','TagsHelperRoute::getTagRoute');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_content_types` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_contentitem_tag_map`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_contentitem_tag_map`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_contentitem_tag_map` (
  `type_alias` varchar(255) NOT NULL DEFAULT '',
  `core_content_id` int(10) unsigned NOT NULL COMMENT 'PK from the core content table',
  `content_item_id` int(11) NOT NULL COMMENT 'PK from the content type table',
  `tag_id` int(10) unsigned NOT NULL COMMENT 'PK from the tag table',
  `tag_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date of most recent save for this tag-item',
  `type_id` mediumint(8) NOT NULL COMMENT 'PK from the content_type table',
  UNIQUE KEY `uc_ItemnameTagid` (`type_id`,`content_item_id`,`tag_id`),
  KEY `idx_tag_type` (`tag_id`,`type_id`),
  KEY `idx_date_id` (`tag_date`,`tag_id`),
  KEY `idx_tag` (`tag_id`),
  KEY `idx_type` (`type_id`),
  KEY `idx_core_content_id` (`core_content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maps items from content tables to tags';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_contentitem_tag_map`
--

/*!40000 ALTER TABLE `pmgov2013_contentitem_tag_map` DISABLE KEYS */;
LOCK TABLES `pmgov2013_contentitem_tag_map` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_contentitem_tag_map` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_core_log_searches`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_core_log_searches`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_core_log_searches`
--

/*!40000 ALTER TABLE `pmgov2013_core_log_searches` DISABLE KEYS */;
LOCK TABLES `pmgov2013_core_log_searches` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_core_log_searches` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_extensions`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_extensions`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `element` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `client_id` tinyint(3) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `access` int(10) unsigned NOT NULL DEFAULT '1',
  `protected` tinyint(3) NOT NULL DEFAULT '0',
  `manifest_cache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10021 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_extensions`
--

/*!40000 ALTER TABLE `pmgov2013_extensions` DISABLE KEYS */;
LOCK TABLES `pmgov2013_extensions` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_extensions` VALUES  (1,'com_mailto','component','com_mailto','',0,1,1,1,'{\"name\":\"com_mailto\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_MAILTO_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (2,'com_wrapper','component','com_wrapper','',0,1,1,1,'{\"name\":\"com_wrapper\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_WRAPPER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (3,'com_admin','component','com_admin','',1,1,1,1,'{\"name\":\"com_admin\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_ADMIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (4,'com_banners','component','com_banners','',1,1,1,0,'{\"name\":\"com_banners\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_BANNERS_XML_DESCRIPTION\",\"group\":\"\"}','{\"purchase_type\":\"3\",\"track_impressions\":\"0\",\"track_clicks\":\"0\",\"metakey_prefix\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (5,'com_cache','component','com_cache','',1,1,1,1,'{\"name\":\"com_cache\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CACHE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (6,'com_categories','component','com_categories','',1,1,1,1,'{\"name\":\"com_categories\",\"type\":\"component\",\"creationDate\":\"December 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (7,'com_checkin','component','com_checkin','',1,1,1,1,'{\"name\":\"com_checkin\",\"type\":\"component\",\"creationDate\":\"Unknown\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2008 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CHECKIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (8,'com_contact','component','com_contact','',1,1,1,0,'{\"name\":\"com_contact\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CONTACT_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_contact_category\":\"hide\",\"show_contact_list\":\"0\",\"presentation_style\":\"sliders\",\"show_name\":\"1\",\"show_position\":\"1\",\"show_email\":\"0\",\"show_street_address\":\"1\",\"show_suburb\":\"1\",\"show_state\":\"1\",\"show_postcode\":\"1\",\"show_country\":\"1\",\"show_telephone\":\"1\",\"show_mobile\":\"1\",\"show_fax\":\"1\",\"show_webpage\":\"1\",\"show_misc\":\"1\",\"show_image\":\"1\",\"image\":\"\",\"allow_vcard\":\"0\",\"show_articles\":\"0\",\"show_profile\":\"0\",\"show_links\":\"0\",\"linka_name\":\"\",\"linkb_name\":\"\",\"linkc_name\":\"\",\"linkd_name\":\"\",\"linke_name\":\"\",\"contact_icons\":\"0\",\"icon_address\":\"\",\"icon_email\":\"\",\"icon_telephone\":\"\",\"icon_mobile\":\"\",\"icon_fax\":\"\",\"icon_misc\":\"\",\"show_headings\":\"1\",\"show_position_headings\":\"1\",\"show_email_headings\":\"0\",\"show_telephone_headings\":\"1\",\"show_mobile_headings\":\"0\",\"show_fax_headings\":\"0\",\"allow_vcard_headings\":\"0\",\"show_suburb_headings\":\"1\",\"show_state_headings\":\"1\",\"show_country_headings\":\"1\",\"show_email_form\":\"1\",\"show_email_copy\":\"1\",\"banned_email\":\"\",\"banned_subject\":\"\",\"banned_text\":\"\",\"validate_session\":\"1\",\"custom_reply\":\"0\",\"redirect\":\"\",\"show_category_crumb\":\"0\",\"metakey\":\"\",\"metadesc\":\"\",\"robots\":\"\",\"author\":\"\",\"rights\":\"\",\"xreference\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (9,'com_cpanel','component','com_cpanel','',1,1,1,1,'{\"name\":\"com_cpanel\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CPANEL_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (10,'com_installer','component','com_installer','',1,1,1,1,'{\"name\":\"com_installer\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_INSTALLER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (11,'com_languages','component','com_languages','',1,1,1,1,'{\"name\":\"com_languages\",\"type\":\"component\",\"creationDate\":\"2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_LANGUAGES_XML_DESCRIPTION\",\"group\":\"\"}','{\"administrator\":\"en-GB\",\"site\":\"pt-BR\"}','','',0,'0000-00-00 00:00:00',0,0),
 (12,'com_login','component','com_login','',1,1,1,1,'{\"name\":\"com_login\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (13,'com_media','component','com_media','',1,1,0,1,'{\"name\":\"com_media\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_MEDIA_XML_DESCRIPTION\",\"group\":\"\"}','{\"upload_extensions\":\"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS\",\"upload_maxsize\":\"10\",\"file_path\":\"images\",\"image_path\":\"images\",\"restrict_uploads\":\"1\",\"allowed_media_usergroup\":\"3\",\"check_mime\":\"1\",\"image_extensions\":\"bmp,gif,jpg,png\",\"ignore_extensions\":\"\",\"upload_mime\":\"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip\",\"upload_mime_illegal\":\"text\\/html\"}','','',0,'0000-00-00 00:00:00',0,0),
 (14,'com_menus','component','com_menus','',1,1,1,1,'{\"name\":\"com_menus\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_MENUS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (15,'com_messages','component','com_messages','',1,1,1,1,'{\"name\":\"com_messages\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_MESSAGES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (16,'com_modules','component','com_modules','',1,1,1,1,'{\"name\":\"com_modules\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_MODULES_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (17,'com_newsfeeds','component','com_newsfeeds','',1,1,1,0,'{\"name\":\"com_newsfeeds\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_NEWSFEEDS_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_feed_image\":\"1\",\"show_feed_description\":\"1\",\"show_item_description\":\"1\",\"feed_word_count\":\"0\",\"show_headings\":\"1\",\"show_name\":\"1\",\"show_articles\":\"0\",\"show_link\":\"1\",\"show_description\":\"1\",\"show_description_image\":\"1\",\"display_num\":\"\",\"show_pagination_limit\":\"1\",\"show_pagination\":\"1\",\"show_pagination_results\":\"1\",\"show_cat_items\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (18,'com_plugins','component','com_plugins','',1,1,1,1,'{\"name\":\"com_plugins\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_PLUGINS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (19,'com_search','component','com_search','',1,1,1,0,'{\"name\":\"com_search\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_SEARCH_XML_DESCRIPTION\",\"group\":\"\"}','{\"enabled\":\"0\",\"show_date\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (20,'com_templates','component','com_templates','',1,1,1,1,'{\"name\":\"com_templates\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_TEMPLATES_XML_DESCRIPTION\",\"group\":\"\"}','{\"template_positions_display\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (21,'com_weblinks','component','com_weblinks','',1,1,1,0,'{\"name\":\"com_weblinks\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\n\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_comp_description\":\"1\",\"comp_description\":\"\",\"show_link_hits\":\"1\",\"show_link_description\":\"1\",\"show_other_cats\":\"0\",\"show_headings\":\"0\",\"show_numbers\":\"0\",\"show_report\":\"1\",\"count_clicks\":\"1\",\"target\":\"0\",\"link_icons\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (22,'com_content','component','com_content','',1,1,0,1,'{\"name\":\"com_content\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CONTENT_XML_DESCRIPTION\",\"group\":\"\"}','{\"article_layout\":\"_:default\",\"show_title\":\"1\",\"link_titles\":\"1\",\"show_intro\":\"1\",\"show_category\":\"1\",\"link_category\":\"1\",\"show_parent_category\":\"0\",\"link_parent_category\":\"0\",\"show_author\":\"1\",\"link_author\":\"0\",\"show_create_date\":\"0\",\"show_modify_date\":\"0\",\"show_publish_date\":\"1\",\"show_item_navigation\":\"1\",\"show_vote\":\"0\",\"show_readmore\":\"1\",\"show_readmore_title\":\"1\",\"readmore_limit\":\"100\",\"show_icons\":\"1\",\"show_print_icon\":\"1\",\"show_email_icon\":\"1\",\"show_hits\":\"1\",\"show_noauth\":\"0\",\"show_publishing_options\":\"1\",\"show_article_options\":\"1\",\"show_urls_images_frontend\":\"0\",\"show_urls_images_backend\":\"1\",\"targeta\":0,\"targetb\":0,\"targetc\":0,\"float_intro\":\"left\",\"float_fulltext\":\"left\",\"category_layout\":\"_:blog\",\"show_category_title\":\"0\",\"show_description\":\"0\",\"show_description_image\":\"0\",\"maxLevel\":\"1\",\"show_empty_categories\":\"0\",\"show_no_articles\":\"1\",\"show_subcat_desc\":\"1\",\"show_cat_num_articles\":\"0\",\"show_base_description\":\"1\",\"maxLevelcat\":\"-1\",\"show_empty_categories_cat\":\"0\",\"show_subcat_desc_cat\":\"1\",\"show_cat_num_articles_cat\":\"1\",\"num_leading_articles\":\"1\",\"num_intro_articles\":\"4\",\"num_columns\":\"2\",\"num_links\":\"4\",\"multi_column_order\":\"0\",\"show_subcategory_content\":\"0\",\"show_pagination_limit\":\"1\",\"filter_field\":\"hide\",\"show_headings\":\"1\",\"list_show_date\":\"0\",\"date_format\":\"\",\"list_show_hits\":\"1\",\"list_show_author\":\"1\",\"orderby_pri\":\"order\",\"orderby_sec\":\"rdate\",\"order_date\":\"published\",\"show_pagination\":\"2\",\"show_pagination_results\":\"1\",\"show_feed_link\":\"1\",\"feed_summary\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (23,'com_config','component','com_config','',1,1,0,1,'{\"name\":\"com_config\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_CONFIG_XML_DESCRIPTION\",\"group\":\"\"}','{\"filters\":{\"1\":{\"filter_type\":\"NH\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"6\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"7\":{\"filter_type\":\"NONE\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"2\":{\"filter_type\":\"NH\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"3\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"4\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"5\":{\"filter_type\":\"BL\",\"filter_tags\":\"\",\"filter_attributes\":\"\"},\"8\":{\"filter_type\":\"NONE\",\"filter_tags\":\"\",\"filter_attributes\":\"\"}}}','','',0,'0000-00-00 00:00:00',0,0),
 (24,'com_redirect','component','com_redirect','',1,1,0,1,'{\"name\":\"com_redirect\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_REDIRECT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (25,'com_users','component','com_users','',1,1,0,1,'{\"name\":\"com_users\",\"type\":\"component\",\"creationDate\":\"April 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"COM_USERS_XML_DESCRIPTION\",\"group\":\"\"}','{\"allowUserRegistration\":\"1\",\"new_usertype\":\"2\",\"guest_usergroup\":\"1\",\"sendpassword\":\"1\",\"useractivation\":\"2\",\"mail_to_admin\":\"1\",\"captcha\":\"\",\"frontend_userparams\":\"1\",\"site_language\":\"0\",\"change_login_name\":\"0\",\"reset_count\":\"10\",\"reset_time\":\"1\",\"mailSubjectPrefix\":\"\",\"mailBodySuffix\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (27,'com_finder','component','com_finder','',1,1,0,0,'{\"legacy\":false,\"name\":\"com_finder\",\"type\":\"component\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_FINDER_XML_DESCRIPTION\",\"group\":\"\"}','{\"show_description\":\"1\",\"description_length\":255,\"allow_empty_query\":\"0\",\"show_url\":\"1\",\"show_advanced\":\"1\",\"expand_advanced\":\"0\",\"show_date_filters\":\"0\",\"highlight_terms\":\"1\",\"opensearch_name\":\"\",\"opensearch_description\":\"\",\"batch_size\":\"50\",\"memory_table_limit\":30000,\"title_multiplier\":\"1.7\",\"text_multiplier\":\"0.7\",\"meta_multiplier\":\"1.2\",\"path_multiplier\":\"2.0\",\"misc_multiplier\":\"0.3\",\"stemmer\":\"snowball\"}','','',0,'0000-00-00 00:00:00',0,0),
 (28,'com_joomlaupdate','component','com_joomlaupdate','',1,1,0,1,'{\"legacy\":false,\"name\":\"com_joomlaupdate\",\"type\":\"component\",\"creationDate\":\"February 2012\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\\t\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"COM_JOOMLAUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{\"updatesource\":\"sts\",\"customurl\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (29,'com_tags','component','com_tags','',1,1,1,1,'{\"name\":\"com_tags\",\"type\":\"component\",\"creationDate\":\"December 2013\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.0\",\"description\":\"COM_TAGS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (100,'PHPMailer','library','phpmailer','',0,1,1,1,'{\"name\":\"PHPMailer\",\"type\":\"library\",\"creationDate\":\"2001\",\"author\":\"PHPMailer\",\"copyright\":\"(c) 2001-2003, Brent R. Matzelle, (c) 2004-2009, Andy Prevost. All Rights Reserved., (c) 2010-2013, Jim Jagielski. All Rights Reserved.\",\"authorEmail\":\"jimjag@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/PHPMailer\\/PHPMailer\",\"version\":\"5.2.6\",\"description\":\"LIB_PHPMAILER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (101,'SimplePie','library','simplepie','',0,1,1,1,'{\"name\":\"SimplePie\",\"type\":\"library\",\"creationDate\":\"2004\",\"author\":\"SimplePie\",\"copyright\":\"Copyright (c) 2004-2009, Ryan Parman and Geoffrey Sneddon\",\"authorEmail\":\"\",\"authorUrl\":\"http:\\/\\/simplepie.org\\/\",\"version\":\"1.2\",\"description\":\"LIB_SIMPLEPIE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (102,'phputf8','library','phputf8','',0,1,1,1,'{\"name\":\"phputf8\",\"type\":\"library\",\"creationDate\":\"2006\",\"author\":\"Harry Fuecks\",\"copyright\":\"Copyright various authors\",\"authorEmail\":\"hfuecks@gmail.com\",\"authorUrl\":\"http:\\/\\/sourceforge.net\\/projects\\/phputf8\",\"version\":\"0.5\",\"description\":\"LIB_PHPUTF8_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (103,'Joomla! Platform','library','joomla','',0,1,1,1,'{\"name\":\"Joomla! Platform\",\"type\":\"library\",\"creationDate\":\"2008\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"http:\\/\\/www.joomla.org\",\"version\":\"12.2\",\"description\":\"LIB_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (104,'IDNA Convert','library','idna_convert','',0,1,1,1,'{\"name\":\"IDNA Convert\",\"type\":\"library\",\"creationDate\":\"2004\",\"author\":\"phlyLabs\",\"copyright\":\"2004-2011 phlyLabs Berlin, http:\\/\\/phlylabs.de\",\"authorEmail\":\"phlymail@phlylabs.de\",\"authorUrl\":\"http:\\/\\/phlylabs.de\",\"version\":\"0.8.0\",\"description\":\"LIB_IDNA_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (200,'mod_articles_archive','module','mod_articles_archive','',0,1,1,0,'{\"name\":\"mod_articles_archive\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters.\\n\\t\\tAll rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_ARTICLES_ARCHIVE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (201,'mod_articles_latest','module','mod_articles_latest','',0,1,1,0,'{\"name\":\"mod_articles_latest\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LATEST_NEWS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (202,'mod_articles_popular','module','mod_articles_popular','',0,1,1,0,'{\"name\":\"mod_articles_popular\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_POPULAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (203,'mod_banners','module','mod_banners','',0,1,1,0,'{\"name\":\"mod_banners\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_BANNERS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (204,'mod_breadcrumbs','module','mod_breadcrumbs','',0,1,1,1,'{\"name\":\"mod_breadcrumbs\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_BREADCRUMBS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (205,'mod_custom','module','mod_custom','',0,1,1,1,'{\"name\":\"mod_custom\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_CUSTOM_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (206,'mod_feed','module','mod_feed','',0,1,1,0,'{\"name\":\"mod_feed\",\"type\":\"module\",\"creationDate\":\"July 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_FEED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (207,'mod_footer','module','mod_footer','',0,1,1,0,'{\"name\":\"mod_footer\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_FOOTER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (208,'mod_login','module','mod_login','',0,1,1,1,'{\"name\":\"mod_login\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (209,'mod_menu','module','mod_menu','',0,1,1,1,'{\"name\":\"mod_menu\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_MENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (210,'mod_articles_news','module','mod_articles_news','',0,1,1,0,'{\"name\":\"mod_articles_news\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_ARTICLES_NEWS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (211,'mod_random_image','module','mod_random_image','',0,1,1,0,'{\"name\":\"mod_random_image\",\"type\":\"module\",\"creationDate\":\"July 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_RANDOM_IMAGE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (212,'mod_related_items','module','mod_related_items','',0,1,1,0,'{\"name\":\"mod_related_items\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_RELATED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (213,'mod_search','module','mod_search','',0,1,1,0,'{\"name\":\"mod_search\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_SEARCH_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (214,'mod_stats','module','mod_stats','',0,1,1,0,'{\"name\":\"mod_stats\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_STATS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (215,'mod_syndicate','module','mod_syndicate','',0,1,1,1,'{\"name\":\"mod_syndicate\",\"type\":\"module\",\"creationDate\":\"May 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_SYNDICATE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (216,'mod_users_latest','module','mod_users_latest','',0,1,1,0,'{\"name\":\"mod_users_latest\",\"type\":\"module\",\"creationDate\":\"December 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_USERS_LATEST_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (217,'mod_weblinks','module','mod_weblinks','',0,1,1,0,'{\"name\":\"mod_weblinks\",\"type\":\"module\",\"creationDate\":\"July 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (218,'mod_whosonline','module','mod_whosonline','',0,1,1,0,'{\"name\":\"mod_whosonline\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_WHOSONLINE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (219,'mod_wrapper','module','mod_wrapper','',0,1,1,0,'{\"name\":\"mod_wrapper\",\"type\":\"module\",\"creationDate\":\"October 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_WRAPPER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (220,'mod_articles_category','module','mod_articles_category','',0,1,1,0,'{\"name\":\"mod_articles_category\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_ARTICLES_CATEGORY_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (221,'mod_articles_categories','module','mod_articles_categories','',0,1,1,0,'{\"name\":\"mod_articles_categories\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_ARTICLES_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (222,'mod_languages','module','mod_languages','',0,1,1,1,'{\"name\":\"mod_languages\",\"type\":\"module\",\"creationDate\":\"February 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LANGUAGES_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (223,'mod_finder','module','mod_finder','',0,1,0,0,'{\"legacy\":false,\"name\":\"mod_finder\",\"type\":\"module\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_FINDER_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (300,'mod_custom','module','mod_custom','',1,1,1,1,'{\"name\":\"mod_custom\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_CUSTOM_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (301,'mod_feed','module','mod_feed','',1,1,1,0,'{\"name\":\"mod_feed\",\"type\":\"module\",\"creationDate\":\"July 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_FEED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (302,'mod_latest','module','mod_latest','',1,1,1,0,'{\"name\":\"mod_latest\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LATEST_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (303,'mod_logged','module','mod_logged','',1,1,1,0,'{\"name\":\"mod_logged\",\"type\":\"module\",\"creationDate\":\"January 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LOGGED_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (304,'mod_login','module','mod_login','',1,1,1,1,'{\"name\":\"mod_login\",\"type\":\"module\",\"creationDate\":\"March 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_LOGIN_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (305,'mod_menu','module','mod_menu','',1,1,1,0,'{\"name\":\"mod_menu\",\"type\":\"module\",\"creationDate\":\"March 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_MENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (307,'mod_popular','module','mod_popular','',1,1,1,0,'{\"name\":\"mod_popular\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_POPULAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (308,'mod_quickicon','module','mod_quickicon','',1,1,1,1,'{\"name\":\"mod_quickicon\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_QUICKICON_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (309,'mod_status','module','mod_status','',1,1,1,0,'{\"name\":\"mod_status\",\"type\":\"module\",\"creationDate\":\"Feb 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_STATUS_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (310,'mod_submenu','module','mod_submenu','',1,1,1,0,'{\"name\":\"mod_submenu\",\"type\":\"module\",\"creationDate\":\"Feb 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_SUBMENU_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (311,'mod_title','module','mod_title','',1,1,1,0,'{\"name\":\"mod_title\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_TITLE_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (312,'mod_toolbar','module','mod_toolbar','',1,1,1,1,'{\"name\":\"mod_toolbar\",\"type\":\"module\",\"creationDate\":\"Nov 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_TOOLBAR_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (313,'mod_multilangstatus','module','mod_multilangstatus','',1,1,1,0,'{\"name\":\"mod_multilangstatus\",\"type\":\"module\",\"creationDate\":\"September 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_MULTILANGSTATUS_XML_DESCRIPTION\",\"group\":\"\"}','{\"cache\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (314,'mod_version','module','mod_version','',1,1,1,0,'{\"legacy\":false,\"name\":\"mod_version\",\"type\":\"module\",\"creationDate\":\"January 2012\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"MOD_VERSION_XML_DESCRIPTION\",\"group\":\"\"}','{\"format\":\"short\",\"product\":\"1\",\"cache\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (315,'mod_stats_admin','module','mod_stats_admin','',1,1,1,0,'{\"name\":\"mod_stats_admin\",\"type\":\"module\",\"creationDate\":\"July 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"MOD_STATS_XML_DESCRIPTION\",\"group\":\"\"}','{\"serverinfo\":\"0\",\"siteinfo\":\"0\",\"counter\":\"0\",\"increase\":\"0\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}','','',0,'0000-00-00 00:00:00',0,0),
 (316,'mod_tags_popular','module','mod_tags_popular','',0,1,1,0,'{\"name\":\"mod_tags_popular\",\"type\":\"module\",\"creationDate\":\"January 2013\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.0\",\"description\":\"MOD_TAGS_POPULAR_XML_DESCRIPTION\",\"group\":\"\"}','{\"maximum\":\"5\",\"timeframe\":\"alltime\",\"owncache\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (317,'mod_tags_similar','module','mod_tags_similar','',0,1,1,0,'{\"name\":\"mod_tags_similar\",\"type\":\"module\",\"creationDate\":\"January 2013\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.0\",\"description\":\"MOD_TAGS_SIMILAR_XML_DESCRIPTION\",\"group\":\"\"}','{\"maximum\":\"5\",\"matchtype\":\"any\",\"owncache\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (400,'plg_authentication_gmail','plugin','gmail','authentication',0,0,1,0,'{\"name\":\"plg_authentication_gmail\",\"type\":\"plugin\",\"creationDate\":\"February 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_GMAIL_XML_DESCRIPTION\",\"group\":\"\"}','{\"applysuffix\":\"0\",\"suffix\":\"\",\"verifypeer\":\"1\",\"user_blacklist\":\"\"}','','',0,'0000-00-00 00:00:00',1,0),
 (401,'plg_authentication_joomla','plugin','joomla','authentication',0,1,1,1,'{\"name\":\"plg_authentication_joomla\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_AUTH_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (402,'plg_authentication_ldap','plugin','ldap','authentication',0,0,1,0,'{\"name\":\"plg_authentication_ldap\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_LDAP_XML_DESCRIPTION\",\"group\":\"\"}','{\"host\":\"\",\"port\":\"389\",\"use_ldapV3\":\"0\",\"negotiate_tls\":\"0\",\"no_referrals\":\"0\",\"auth_method\":\"bind\",\"base_dn\":\"\",\"search_string\":\"\",\"users_dn\":\"\",\"username\":\"admin\",\"password\":\"bobby7\",\"ldap_fullname\":\"fullName\",\"ldap_email\":\"mail\",\"ldap_uid\":\"uid\"}','','',0,'0000-00-00 00:00:00',3,0),
 (404,'plg_content_emailcloak','plugin','emailcloak','content',0,1,1,0,'{\"name\":\"plg_content_emailcloak\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CONTENT_EMAILCLOAK_XML_DESCRIPTION\",\"group\":\"\"}','{\"mode\":\"1\"}','','',0,'0000-00-00 00:00:00',1,0),
 (405,'plg_content_geshi','plugin','geshi','content',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_content_geshi\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"\",\"authorUrl\":\"qbnz.com\\/highlighter\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_GESHI_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),
 (406,'plg_content_loadmodule','plugin','loadmodule','content',0,1,1,0,'{\"name\":\"plg_content_loadmodule\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_LOADMODULE_XML_DESCRIPTION\",\"group\":\"\"}','{\"style\":\"xhtml\"}','','',0,'2011-09-18 15:22:50',0,0),
 (407,'plg_content_pagebreak','plugin','pagebreak','content',0,1,1,0,'{\"name\":\"plg_content_pagebreak\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CONTENT_PAGEBREAK_XML_DESCRIPTION\",\"group\":\"\"}','{\"title\":\"1\",\"multipage_toc\":\"1\",\"showall\":\"1\"}','','',0,'0000-00-00 00:00:00',4,0),
 (408,'plg_content_pagenavigation','plugin','pagenavigation','content',0,1,1,0,'{\"name\":\"plg_content_pagenavigation\",\"type\":\"plugin\",\"creationDate\":\"January 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_PAGENAVIGATION_XML_DESCRIPTION\",\"group\":\"\"}','{\"position\":\"1\"}','','',0,'0000-00-00 00:00:00',5,0),
 (409,'plg_content_vote','plugin','vote','content',0,1,1,0,'{\"name\":\"plg_content_vote\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_VOTE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',6,0),
 (410,'plg_editors_codemirror','plugin','codemirror','editors',0,1,1,1,'{\"name\":\"plg_editors_codemirror\",\"type\":\"plugin\",\"creationDate\":\"28 March 2011\",\"author\":\"Marijn Haverbeke\",\"copyright\":\"\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"\",\"version\":\"1.0\",\"description\":\"PLG_CODEMIRROR_XML_DESCRIPTION\",\"group\":\"\"}','{\"linenumbers\":\"0\",\"tabmode\":\"indent\"}','','',0,'0000-00-00 00:00:00',1,0),
 (411,'plg_editors_none','plugin','none','editors',0,1,1,1,'{\"name\":\"plg_editors_none\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Unknown\",\"copyright\":\"\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"\",\"version\":\"3.0.0\",\"description\":\"PLG_NONE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),
 (412,'plg_editors_tinymce','plugin','tinymce','editors',0,1,1,0,'{\"name\":\"plg_editors_tinymce\",\"type\":\"plugin\",\"creationDate\":\"2005-2012\",\"author\":\"Moxiecode Systems AB\",\"copyright\":\"Moxiecode Systems AB\",\"authorEmail\":\"N\\/A\",\"authorUrl\":\"tinymce.moxiecode.com\\/\",\"version\":\"3.5.6\",\"description\":\"PLG_TINY_XML_DESCRIPTION\",\"group\":\"\"}','{\"mode\":\"1\",\"skin\":\"0\",\"entity_encoding\":\"raw\",\"lang_mode\":\"0\",\"lang_code\":\"en\",\"text_direction\":\"ltr\",\"content_css\":\"1\",\"content_css_custom\":\"\",\"relative_urls\":\"1\",\"newlines\":\"0\",\"invalid_elements\":\"script,applet,iframe\",\"extended_elements\":\"\",\"toolbar\":\"top\",\"toolbar_align\":\"left\",\"html_height\":\"550\",\"html_width\":\"750\",\"resizing\":\"true\",\"resize_horizontal\":\"false\",\"element_path\":\"1\",\"fonts\":\"1\",\"paste\":\"1\",\"searchreplace\":\"1\",\"insertdate\":\"1\",\"format_date\":\"%Y-%m-%d\",\"inserttime\":\"1\",\"format_time\":\"%H:%M:%S\",\"colors\":\"1\",\"table\":\"1\",\"smilies\":\"1\",\"media\":\"1\",\"hr\":\"1\",\"directionality\":\"1\",\"fullscreen\":\"1\",\"style\":\"1\",\"layer\":\"1\",\"xhtmlxtras\":\"1\",\"visualchars\":\"1\",\"nonbreaking\":\"1\",\"template\":\"1\",\"blockquote\":\"1\",\"wordcount\":\"1\",\"advimage\":\"1\",\"advlink\":\"1\",\"advlist\":\"1\",\"autosave\":\"1\",\"contextmenu\":\"1\",\"inlinepopups\":\"1\",\"custom_plugin\":\"\",\"custom_button\":\"\"}','','',0,'0000-00-00 00:00:00',3,0),
 (413,'plg_editors-xtd_article','plugin','article','editors-xtd',0,1,1,1,'{\"name\":\"plg_editors-xtd_article\",\"type\":\"plugin\",\"creationDate\":\"October 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_ARTICLE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),
 (414,'plg_editors-xtd_image','plugin','image','editors-xtd',0,1,1,0,'{\"name\":\"plg_editors-xtd_image\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_IMAGE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),
 (415,'plg_editors-xtd_pagebreak','plugin','pagebreak','editors-xtd',0,1,1,0,'{\"name\":\"plg_editors-xtd_pagebreak\",\"type\":\"plugin\",\"creationDate\":\"August 2004\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_EDITORSXTD_PAGEBREAK_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',3,0),
 (416,'plg_editors-xtd_readmore','plugin','readmore','editors-xtd',0,1,1,0,'{\"name\":\"plg_editors-xtd_readmore\",\"type\":\"plugin\",\"creationDate\":\"March 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_READMORE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',4,0),
 (417,'plg_search_categories','plugin','categories','search',0,1,1,0,'{\"name\":\"plg_search_categories\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEARCH_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (418,'plg_search_contacts','plugin','contacts','search',0,1,1,0,'{\"name\":\"plg_search_contacts\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEARCH_CONTACTS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (419,'plg_search_content','plugin','content','search',0,1,1,0,'{\"name\":\"plg_search_content\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEARCH_CONTENT_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (420,'plg_search_newsfeeds','plugin','newsfeeds','search',0,1,1,0,'{\"name\":\"plg_search_newsfeeds\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEARCH_NEWSFEEDS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (421,'plg_search_weblinks','plugin','weblinks','search',0,1,1,0,'{\"name\":\"plg_search_weblinks\",\"type\":\"plugin\",\"creationDate\":\"November 2005\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEARCH_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','{\"search_limit\":\"50\",\"search_content\":\"1\",\"search_archived\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (422,'plg_system_languagefilter','plugin','languagefilter','system',0,0,1,1,'{\"name\":\"plg_system_languagefilter\",\"type\":\"plugin\",\"creationDate\":\"July 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SYSTEM_LANGUAGEFILTER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),
 (423,'plg_system_p3p','plugin','p3p','system',0,1,1,0,'{\"name\":\"plg_system_p3p\",\"type\":\"plugin\",\"creationDate\":\"September 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_P3P_XML_DESCRIPTION\",\"group\":\"\"}','{\"headers\":\"NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM\"}','','',0,'0000-00-00 00:00:00',2,0),
 (424,'plg_system_cache','plugin','cache','system',0,0,1,1,'{\"name\":\"plg_system_cache\",\"type\":\"plugin\",\"creationDate\":\"February 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CACHE_XML_DESCRIPTION\",\"group\":\"\"}','{\"browsercache\":\"0\",\"cachetime\":\"15\"}','','',0,'0000-00-00 00:00:00',9,0),
 (425,'plg_system_debug','plugin','debug','system',0,1,1,0,'{\"name\":\"plg_system_debug\",\"type\":\"plugin\",\"creationDate\":\"December 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_DEBUG_XML_DESCRIPTION\",\"group\":\"\"}','{\"profile\":\"1\",\"queries\":\"1\",\"memory\":\"1\",\"language_files\":\"1\",\"language_strings\":\"1\",\"strip-first\":\"1\",\"strip-prefix\":\"\",\"strip-suffix\":\"\"}','','',0,'0000-00-00 00:00:00',4,0),
 (426,'plg_system_log','plugin','log','system',0,1,1,1,'{\"name\":\"plg_system_log\",\"type\":\"plugin\",\"creationDate\":\"April 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_LOG_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',5,0),
 (427,'plg_system_redirect','plugin','redirect','system',0,1,1,1,'{\"name\":\"plg_system_redirect\",\"type\":\"plugin\",\"creationDate\":\"April 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_REDIRECT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',6,0),
 (428,'plg_system_remember','plugin','remember','system',0,1,1,1,'{\"name\":\"plg_system_remember\",\"type\":\"plugin\",\"creationDate\":\"April 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_REMEMBER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',7,0),
 (429,'plg_system_sef','plugin','sef','system',0,1,1,0,'{\"name\":\"plg_system_sef\",\"type\":\"plugin\",\"creationDate\":\"December 2007\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SEF_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',8,0),
 (430,'plg_system_logout','plugin','logout','system',0,1,1,1,'{\"name\":\"plg_system_logout\",\"type\":\"plugin\",\"creationDate\":\"April 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SYSTEM_LOGOUT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',3,0),
 (431,'plg_user_contactcreator','plugin','contactcreator','user',0,0,1,0,'{\"name\":\"plg_user_contactcreator\",\"type\":\"plugin\",\"creationDate\":\"August 2009\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CONTACTCREATOR_XML_DESCRIPTION\",\"group\":\"\"}','{\"autowebpage\":\"\",\"category\":\"34\",\"autopublish\":\"0\"}','','',0,'0000-00-00 00:00:00',1,0),
 (432,'plg_user_joomla','plugin','joomla','user',0,1,1,0,'{\"name\":\"plg_user_joomla\",\"type\":\"plugin\",\"creationDate\":\"December 2006\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2009 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_USER_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{\"autoregister\":\"1\"}','','',0,'0000-00-00 00:00:00',2,0),
 (433,'plg_user_profile','plugin','profile','user',0,0,1,0,'{\"name\":\"plg_user_profile\",\"type\":\"plugin\",\"creationDate\":\"January 2008\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_USER_PROFILE_XML_DESCRIPTION\",\"group\":\"\"}','{\"register-require_address1\":\"1\",\"register-require_address2\":\"1\",\"register-require_city\":\"1\",\"register-require_region\":\"1\",\"register-require_country\":\"1\",\"register-require_postal_code\":\"1\",\"register-require_phone\":\"1\",\"register-require_website\":\"1\",\"register-require_favoritebook\":\"1\",\"register-require_aboutme\":\"1\",\"register-require_tos\":\"1\",\"register-require_dob\":\"1\",\"profile-require_address1\":\"1\",\"profile-require_address2\":\"1\",\"profile-require_city\":\"1\",\"profile-require_region\":\"1\",\"profile-require_country\":\"1\",\"profile-require_postal_code\":\"1\",\"profile-require_phone\":\"1\",\"profile-require_website\":\"1\",\"profile-require_favoritebook\":\"1\",\"profile-require_aboutme\":\"1\",\"profile-require_tos\":\"1\",\"profile-require_dob\":\"1\"}','','',0,'0000-00-00 00:00:00',0,0),
 (434,'plg_extension_joomla','plugin','joomla','extension',0,1,1,1,'{\"name\":\"plg_extension_joomla\",\"type\":\"plugin\",\"creationDate\":\"May 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_EXTENSION_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),
 (435,'plg_content_joomla','plugin','joomla','content',0,1,1,0,'{\"name\":\"plg_content_joomla\",\"type\":\"plugin\",\"creationDate\":\"November 2010\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CONTENT_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (436,'plg_system_languagecode','plugin','languagecode','system',0,0,1,0,'{\"name\":\"plg_system_languagecode\",\"type\":\"plugin\",\"creationDate\":\"November 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_SYSTEM_LANGUAGECODE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',10,0),
 (437,'plg_quickicon_joomlaupdate','plugin','joomlaupdate','quickicon',0,1,1,1,'{\"name\":\"plg_quickicon_joomlaupdate\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_QUICKICON_JOOMLAUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (438,'plg_quickicon_extensionupdate','plugin','extensionupdate','quickicon',0,1,1,1,'{\"name\":\"plg_quickicon_extensionupdate\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_QUICKICON_EXTENSIONUPDATE_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (439,'plg_captcha_recaptcha','plugin','recaptcha','captcha',0,0,1,0,'{\"name\":\"plg_captcha_recaptcha\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_CAPTCHA_RECAPTCHA_XML_DESCRIPTION\",\"group\":\"\"}','{\"public_key\":\"\",\"private_key\":\"\",\"theme\":\"clean\"}','','',0,'0000-00-00 00:00:00',0,0),
 (440,'plg_system_highlight','plugin','highlight','system',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_system_highlight\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_SYSTEM_HIGHLIGHT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',7,0),
 (441,'plg_content_finder','plugin','finder','content',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_content_finder\",\"type\":\"plugin\",\"creationDate\":\"December 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"PLG_CONTENT_FINDER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (442,'plg_finder_categories','plugin','categories','finder',0,1,1,0,'{\"name\":\"plg_finder_categories\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_CATEGORIES_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',1,0),
 (443,'plg_finder_contacts','plugin','contacts','finder',0,1,1,0,'{\"name\":\"plg_finder_contacts\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_CONTACTS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',2,0),
 (444,'plg_finder_content','plugin','content','finder',0,1,1,0,'{\"name\":\"plg_finder_content\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_CONTENT_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',3,0),
 (445,'plg_finder_newsfeeds','plugin','newsfeeds','finder',0,1,1,0,'{\"name\":\"plg_finder_newsfeeds\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_NEWSFEEDS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',4,0),
 (446,'plg_finder_weblinks','plugin','weblinks','finder',0,1,1,0,'{\"name\":\"plg_finder_weblinks\",\"type\":\"plugin\",\"creationDate\":\"August 2011\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_WEBLINKS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',5,0);
INSERT INTO `portal_modelo_3x`.`pmgov2013_extensions` VALUES  (447,'plg_finder_tags','plugin','tags','finder',0,1,1,0,'{\"name\":\"plg_finder_tags\",\"type\":\"plugin\",\"creationDate\":\"February 2013\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.0.0\",\"description\":\"PLG_FINDER_TAGS_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (500,'atomic','template','atomic','',0,1,1,0,'{\"legacy\":false,\"name\":\"atomic\",\"type\":\"template\",\"creationDate\":\"10\\/10\\/09\",\"author\":\"Ron Severdia\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"contact@kontentdesign.com\",\"authorUrl\":\"http:\\/\\/www.kontentdesign.com\",\"version\":\"2.5.0\",\"description\":\"TPL_ATOMIC_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (502,'bluestork','template','bluestork','',1,1,1,0,'{\"legacy\":false,\"name\":\"bluestork\",\"type\":\"template\",\"creationDate\":\"07\\/02\\/09\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"2.5.0\",\"description\":\"TPL_BLUESTORK_XML_DESCRIPTION\",\"group\":\"\"}','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\",\"textBig\":\"0\",\"highContrast\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (503,'beez_20','template','beez_20','',0,1,1,0,'{\"legacy\":false,\"name\":\"beez_20\",\"type\":\"template\",\"creationDate\":\"25 November 2009\",\"author\":\"Angie Radtke\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"a.radtke@derauftritt.de\",\"authorUrl\":\"http:\\/\\/www.der-auftritt.de\",\"version\":\"2.5.0\",\"description\":\"TPL_BEEZ2_XML_DESCRIPTION\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"templatecolor\":\"nature\"}','','',0,'0000-00-00 00:00:00',0,0),
 (504,'hathor','template','hathor','',1,1,1,0,'{\"name\":\"hathor\",\"type\":\"template\",\"creationDate\":\"May 2010\",\"author\":\"Andrea Tarr\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"hathor@tarrconsulting.com\",\"authorUrl\":\"http:\\/\\/www.tarrconsulting.com\",\"version\":\"3.0.0\",\"description\":\"TPL_HATHOR_XML_DESCRIPTION\",\"group\":\"\"}','{\"showSiteName\":\"0\",\"colourChoice\":\"0\",\"boldText\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (505,'beez5','template','beez5','',0,1,1,0,'{\"legacy\":false,\"name\":\"beez5\",\"type\":\"template\",\"creationDate\":\"21 May 2010\",\"author\":\"Angie Radtke\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"a.radtke@derauftritt.de\",\"authorUrl\":\"http:\\/\\/www.der-auftritt.de\",\"version\":\"2.5.0\",\"description\":\"TPL_BEEZ5_XML_DESCRIPTION\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"html5\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (600,'English (United Kingdom)','language','en-GB','',0,1,1,1,'{\"name\":\"English (United Kingdom)\",\"type\":\"language\",\"creationDate\":\"2013-03-07\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.4\",\"description\":\"en-GB site language\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (601,'English (United Kingdom)','language','en-GB','',1,1,1,1,'{\"name\":\"English (United Kingdom)\",\"type\":\"language\",\"creationDate\":\"2013-03-07\",\"author\":\"Joomla! Project\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.4\",\"description\":\"en-GB administrator language\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (700,'files_joomla','file','joomla','',0,1,1,1,'{\"name\":\"files_joomla\",\"type\":\"file\",\"creationDate\":\"August 2013\",\"author\":\"Joomla! Project\",\"copyright\":\"(C) 2005 - 2013 Open Source Matters. All rights reserved\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"www.joomla.org\",\"version\":\"3.1.5\",\"description\":\"FILES_JOOMLA_XML_DESCRIPTION\",\"group\":\"\"}','','','',0,'0000-00-00 00:00:00',0,0),
 (10000,'PortugusBrasil','language','pt-BR','',0,1,0,0,'{\"legacy\":false,\"name\":\"Portugu\\u00eas(Brasil)\",\"type\":\"language\",\"creationDate\":\"2013-01-29\",\"author\":\"Joomla Brasil\",\"copyright\":\"Copyright 2005 - 2013 Open Source Matters. Todos os Direitos Reservados. Copyright Translation 2005 - 2013 Joomla Brasil. Todos os Direitos Reservados.\",\"authorEmail\":\"traducao@joomla.com.br\",\"authorUrl\":\"www.joomla.com.br\",\"version\":\"2.5.9.1\",\"description\":\"\\n\\t\\t\\t\\tArquivos de Idioma em Portugu\\u00eas Brasileiro para o CMS Joomla! 2.5<br\\/>\\n\\t\\t\\t\\tCopyright 2005 - 2013 Open Source Matters. Todos os Direitos Reservados. Copyright Translation 2005 - 2013 Joomla Brasil. Todos os Direitos Reservados.<br\\/>\\n\\t\\t\\t\\tDe acordo com termo de licenciamento de <a href=\\\"http:\\/\\/www.opensourcematters.org\\\" target=\\\"_blank\\\">OpenSourceMatters<\\/a> e <a href=\\\"http:\\/\\/www.joomla.org\\\" target=\\\"_blank\\\">Joomla!<\\/a> todos os arquivos de idioma devem ser licenciados sob a <a href=\\\"http:\\/\\/www.gnu.org\\/licenses\\/gpl-2.0.html\\\" target=\\\"_blank\\\"> licen\\u00e7a GNU\\/GPL (v.2.0)<\\/a>. Esta tradu\\u00e7\\u00e3o est\\u00e1 licenciada sob esta licen\\u00e7a. Se voc\\u00ea encontrar algum erro de digita\\u00e7\\u00e3o, tradu\\u00e7\\u00f5es incorretas ou se deseja sugerir melhorias, entre em contato com o <a href=\\\"mailto:traducao@joomla.com.br\\\">coordenador<\\/a> da tradu\\u00e7\\u00e3o.\\n\\t\\t\\t\\t\\n\\t\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10001,'PortugusBrasil','language','pt-BR','',1,1,0,0,'{\"legacy\":false,\"name\":\"Portugu\\u00eas(Brasil)\",\"type\":\"language\",\"creationDate\":\"2013-01-29\",\"author\":\"Joomla Brasil\",\"copyright\":\"Copyright 2005 - 2013 Open Source Matters. Todos os Direitos Reservados. Copyright Translation 2005 - 2013 Joomla Brasil. Todos os Direitos Reservados.\",\"authorEmail\":\"traducao@joomla.com.br\",\"authorUrl\":\"www.joomla.com.br\",\"version\":\"2.5.9.1\",\"description\":\"\\n\\t\\t\\t\\tArquivos de Idioma em Portugu\\u00eas Brasileiro para o CMS Joomla! 2.5<br\\/>\\n\\t\\t\\t\\tCopyright 2005 - 2013 Open Source Matters. Todos os Direitos Reservados. Copyright Translation 2005 - 2013 Joomla Brasil. Todos os Direitos Reservados.<br\\/>\\n\\t\\t\\t\\tDe acordo com termo de licenciamento de <a href=\\\"http:\\/\\/www.opensourcematters.org\\\" target=\\\"_blank\\\">OpenSourceMatters<\\/a> e <a href=\\\"http:\\/\\/www.joomla.org\\\" target=\\\"_blank\\\">Joomla!<\\/a> todos os arquivos de idioma devem ser licenciados sob a <a href=\\\"http:\\/\\/www.gnu.org\\/licenses\\/gpl-2.0.html\\\" target=\\\"_blank\\\"> licen\\u00e7a GNU\\/GPL (v.2.0)<\\/a>. Esta tradu\\u00e7\\u00e3o est\\u00e1 licenciada sob esta licen\\u00e7a. Se voc\\u00ea encontrar algum erro de digita\\u00e7\\u00e3o, tradu\\u00e7\\u00f5es incorretas ou se deseja sugerir melhorias, entre em contato com o <a href=\\\"mailto:traducao@joomla.com.br\\\">coordenador<\\/a> da tradu\\u00e7\\u00e3o.\\n\\t\\t\\t\\t\\n\\t\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10002,'pt-BR','package','pkg_pt-BR','',0,1,1,0,'{\"legacy\":false,\"name\":\"Pacote de Idiomas em Portugu\\u00eas Brasileiro\",\"type\":\"package\",\"creationDate\":\"Janeiro 2013\",\"author\":\"Joomla Brasil\",\"copyright\":\"\",\"authorEmail\":\"traducao@joomla.com.br\",\"authorUrl\":\"www.joomla.com.br\",\"version\":\"2.5.9.1\",\"description\":\"\\n\\t\\t\\t\\tArquivos de Idioma em Portugu\\u00eas Brasileiro para o CMS Joomla! 2.5<br\\/>\\n\\t\\t\\t\\tCopyright 2005 - 2013 Open Source Matters. Todos os Direitos Reservados. Copyright Translation 2005 - 2013 Joomla Brasil. Todos os Direitos Reservados.<br\\/>\\n\\t\\t\\t\\tDe acordo com termo de licenciamento de <a href=\\\"http:\\/\\/www.opensourcematters.org\\\" target=\\\"_blank\\\">OpenSourceMatters<\\/a> e <a href=\\\"http:\\/\\/www.joomla.org\\\" target=\\\"_blank\\\">Joomla!<\\/a> todos os arquivos de idioma devem ser licenciados sob a <a href=\\\"http:\\/\\/www.gnu.org\\/licenses\\/gpl-2.0.html\\\" target=\\\"_blank\\\"> licen\\u00e7a GNU\\/GPL (v.2.0)<\\/a>. Esta tradu\\u00e7\\u00e3o est\\u00e1 licenciada sob esta licen\\u00e7a. Se voc\\u00ea encontrar algum erro de digita\\u00e7\\u00e3o, tradu\\u00e7\\u00f5es incorretas ou se deseja sugerir melhorias, entre em contato com o <a href=\\\"mailto:traducao@joomla.com.br\\\">coordenador<\\/a> da tradu\\u00e7\\u00e3o.\\n\\t\\t\\t\\t\\n\\t\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10003,'plg_editors_jce','plugin','jce','editors',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_editors_jce\",\"type\":\"plugin\",\"creationDate\":\"13 July 2013\",\"author\":\"Ryan Demmer\",\"copyright\":\"2006-2010 Ryan Demmer\",\"authorEmail\":\"info@joomlacontenteditor.net\",\"authorUrl\":\"http:\\/\\/www.joomlacontenteditor.net\",\"version\":\"2.3.3.2\",\"description\":\"WF_EDITOR_PLUGIN_DESC\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10004,'plg_quickicon_jcefilebrowser','plugin','jcefilebrowser','quickicon',0,1,1,0,'{\"legacy\":false,\"name\":\"plg_quickicon_jcefilebrowser\",\"type\":\"plugin\",\"creationDate\":\"13 July 2013\",\"author\":\"Ryan Demmer\",\"copyright\":\"Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved\",\"authorEmail\":\"@@email@@\",\"authorUrl\":\"www.joomalcontenteditor.net\",\"version\":\"2.3.3.2\",\"description\":\"PLG_QUICKICON_JCEFILEBROWSER_XML_DESCRIPTION\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10005,'jce','component','com_jce','',1,1,0,0,'{\"legacy\":false,\"name\":\"JCE\",\"type\":\"component\",\"creationDate\":\"13 July 2013\",\"author\":\"Ryan Demmer\",\"copyright\":\"Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved\",\"authorEmail\":\"info@joomlacontenteditor.net\",\"authorUrl\":\"www.joomlacontenteditor.net\",\"version\":\"2.3.3.2\",\"description\":\"WF_ADMIN_DESC\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10007,'padraogoverno01','template','padraogoverno01','',0,1,1,0,'{\"legacy\":false,\"name\":\"padraogoverno01\",\"type\":\"template\",\"creationDate\":\"Outubro 2013\",\"author\":\"Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"copyright\":\"Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios.\",\"authorEmail\":\"joomlagovbr@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/joomlagovbr\",\"version\":\"2.5.0\",\"description\":\"Mais informacoes na URL do github.\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"templatecolor\":\"nature\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10008,'mod_container','module','mod_container','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_container\",\"type\":\"module\",\"creationDate\":\"Outubro 2013\",\"author\":\"Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"copyright\":\"Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"authorEmail\":\"joomlagovbr@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/joomlagovbr\",\"version\":\"1.0\",\"description\":\"Mais informacoes na url do github.\",\"group\":\"\"}','{\"posicao\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10010,'plg_system_scriptsfooter','plugin','scriptsfooter','system',0,0,1,0,'{\"legacy\":false,\"name\":\"plg_system_scriptsfooter\",\"type\":\"plugin\",\"creationDate\":\"Outubro 2013\",\"author\":\"Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"copyright\":\"Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"authorEmail\":\"joomlagovbr@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/joomlagovbr\",\"version\":\"1.0\",\"description\":\"Mais informacoes na url do github.\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0),
 (10012,'HTML Custom','module','mod_htmlcustom','',0,1,1,0,'{\"legacy\":false,\"name\":\"HTML Custom\",\"type\":\"template\",\"creationDate\":\"Outubro 2013\",\"author\":\"Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"copyright\":\"Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios.\",\"authorEmail\":\"joomlagovbr@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/joomlagovbr\",\"version\":\"1.0\",\"description\":\"Mais informacoes na URL do github.\",\"group\":\"\"}','{\"htmlcode\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10014,'Blank Module','module','mod_blank250','',0,1,0,0,'{\"legacy\":false,\"name\":\"Blank Module\",\"type\":\"module\",\"creationDate\":\"August 2013\",\"author\":\"Bob Galway\",\"copyright\":\"Copyright  Bob Galway. All rights reserved.\",\"authorEmail\":\"reply@blackdale.com\",\"authorUrl\":\"www.blackdale.com\",\"version\":\"5.0.2\",\"description\":\"BLANKMODULE250\",\"group\":\"\"}','{\"codeeditor\":\"\",\"textareause\":\"1\",\"phpcode\":\"\",\"phpuse\":\"1\",\"script\":\"\",\"scriptuse\":\"1\",\"itemid\":\"a\",\"contenttitleuse\":\"1\",\"contentuse\":\"1\",\"content1\":\"1\",\"content2\":\"2\",\"content3\":\"3\",\"graphics\":\"1\",\"bgpattern\":\"TinySquare2\",\"colour1\":\"9CA5FF\",\"trans1\":\"1\",\"bordercol\":\"ACB5FF\",\"bordersz\":\"4\",\"shadcol\":\"444444\",\"shadsz\":\"4\",\"margin-leftmodule\":\"\",\"paddingleft\":\"\",\"paddingright\":\"\",\"paddingtop\":\"\",\"paddingbottom\":\"\",\"margin-top\":\"\",\"margin-bottom\":\"\",\"width\":\"100\",\"widthunit\":\"%\",\"colour2\":\"\",\"trans2\":\"1\",\"moduleclass_sfx\":\"\",\"modno_bm\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10015,'mod_chamadas','module','mod_chamadas','',0,1,1,0,'{\"legacy\":false,\"name\":\"mod_chamadas\",\"type\":\"module\",\"creationDate\":\"Outubro 2013\",\"author\":\"Roberson Pinheiro\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.\",\"authorEmail\":\"roberson.augusto@gmail.com\",\"authorUrl\":\"\",\"version\":\"3.0.0\",\"description\":\"MOD_CHAMADA_XML_DESCRICAO\",\"group\":\"\"}','{\"modelo\":\"article_content\",\"quantidade\":\"4\",\"habilitar_mensagem_vazia\":\"0\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"destaque\":\"show\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"owncache\":\"1\",\"cache_time\":\"900\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10016,'mod_barradogoverno','module','mod_barradogoverno','',0,1,0,0,'{\"legacy\":false,\"name\":\"mod_barradogoverno\",\"type\":\"module\",\"creationDate\":\"Outubro 2013\",\"author\":\"Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"copyright\":\"Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Minist\\u00e9rios\",\"authorEmail\":\"joomlagovbr@gmail.com\",\"authorUrl\":\"https:\\/\\/github.com\\/joomlagovbr\",\"version\":\"1.0\",\"description\":\"Mais informacoes na url do github.\",\"group\":\"\"}','{\"layout\":\"default\",\"anexar_js_2014\":\"1\",\"endereco_js_2014\":\"http:\\/\\/barra.brasil.gov.br\\/barra.js?cor=verde\",\"mensagem_ie6_2014\":\"Seu navegador \\u00e9 incompat\\u00edvel com os novos padr\\u00f5es de tecnologia e por isso voc\\u00ea n\\u00e3o pode visualizar a nova barra do Governo Federal. Atualize ou troque seu navegador.\",\"correcoes_ie8_2014\":\"show_css\",\"link_css_ie8_2014\":\"{URL_SITE}\\/modules\\/mod_barradogoverno\\/assets\\/2014\\/css\\/ie8.css\",\"anexar_css_2012\":\"1\",\"cor_2012\":\"\",\"acesso_a_informacao_2012\":\"1\",\"largura_barra_2012\":\"970\",\"alinhamento_barra_2012\":\"\",\"link_acesso_a_informacao_2012\":\"http:\\/\\/www.acessoainformacao.gov.br\\/acessoainformacaogov\\/\",\"link_portal_brasil_2012\":\"http:\\/\\/www.brasil.gov.br\\/\",\"target_links_2012\":\"_blank\",\"head_manual\":\"\",\"html_manual\":\"\",\"anexar_head\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10017,'isis','template','isis','',1,1,1,0,'{\"name\":\"isis\",\"type\":\"template\",\"creationDate\":\"3\\/30\\/2012\",\"author\":\"Kyle Ledbetter\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"\",\"version\":\"1.0\",\"description\":\"TPL_ISIS_XML_DESCRIPTION\",\"group\":\"\"}','{\"templateColor\":\"\",\"logoFile\":\"\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10018,'protostar','template','protostar','',0,1,1,0,'{\"name\":\"protostar\",\"type\":\"template\",\"creationDate\":\"4\\/30\\/2012\",\"author\":\"Kyle Ledbetter\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"admin@joomla.org\",\"authorUrl\":\"\",\"version\":\"1.0\",\"description\":\"TPL_PROTOSTAR_XML_DESCRIPTION\",\"group\":\"\"}','{\"templateColor\":\"\",\"logoFile\":\"\",\"googleFont\":\"1\",\"googleFontName\":\"Open+Sans\",\"fluidContainer\":\"0\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10019,'beez3','template','beez3','',0,1,1,0,'{\"name\":\"beez3\",\"type\":\"template\",\"creationDate\":\"25 November 2009\",\"author\":\"Angie Radtke\",\"copyright\":\"Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.\",\"authorEmail\":\"a.radtke@derauftritt.de\",\"authorUrl\":\"http:\\/\\/www.der-auftritt.de\",\"version\":\"3.1.0\",\"description\":\"TPL_BEEZ3_XML_DESCRIPTION\",\"group\":\"\"}','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"templatecolor\":\"nature\"}','','',0,'0000-00-00 00:00:00',0,0),
 (10020,'blankcomponent','component','com_blankcomponent','',1,1,0,0,'{\"name\":\"Blank Component\",\"type\":\"component\",\"creationDate\":\"03\\/11\\/2012\",\"author\":\"Omar Muhammad\",\"copyright\":\"Copyright \\u00a9 2012, Omar\'s Site, All Rights Reserved.\",\"authorEmail\":\"admin@omar84.com\",\"authorUrl\":\"http:\\/\\/omar84.com\",\"version\":\"3.0.0\",\"description\":\"Blank Component was made to make it possible to create a menu item page that contains only modules and no component.\",\"group\":\"\"}','{}','','',0,'0000-00-00 00:00:00',0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_extensions` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_filters`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_filters`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_filters` (
  `filter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL,
  `created_by_alias` varchar(255) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `map_count` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `params` mediumtext,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_filters`
--

/*!40000 ALTER TABLE `pmgov2013_finder_filters` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_filters` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_filters` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `indexdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `md5sum` varchar(32) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `state` int(5) DEFAULT '1',
  `access` int(5) DEFAULT '0',
  `language` varchar(8) NOT NULL,
  `publish_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `list_price` double unsigned NOT NULL DEFAULT '0',
  `sale_price` double unsigned NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL,
  `object` mediumblob NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `idx_type` (`type_id`),
  KEY `idx_title` (`title`),
  KEY `idx_md5` (`md5sum`),
  KEY `idx_url` (`url`(75)),
  KEY `idx_published_list` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`list_price`),
  KEY `idx_published_sale` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`sale_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms0`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms0`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms0` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms0`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms0` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms0` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms0` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms1`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms1`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms1` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms1`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms1` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms1` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms1` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms2`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms2`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms2` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms2`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms2` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms2` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms2` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms3`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms3`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms3` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms3`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms3` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms3` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms3` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms4`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms4`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms4` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms4`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms4` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms4` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms4` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms5`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms5`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms5` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms5`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms5` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms5` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms5` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms6`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms6`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms6` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms6`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms6` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms6` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms6` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms7`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms7`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms7` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms7`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms7` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms7` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms7` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms8`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms8`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms8` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms8`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms8` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms8` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms8` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_terms9`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_terms9`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_terms9` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_terms9`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_terms9` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_terms9` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_terms9` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termsa`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termsa`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termsa` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termsa`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termsa` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termsa` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termsa` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termsb`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termsb`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termsb` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termsb`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termsb` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termsb` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termsb` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termsc`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termsc`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termsc` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termsc`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termsc` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termsc` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termsc` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termsd`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termsd`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termsd` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termsd`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termsd` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termsd` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termsd` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termse`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termse`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termse` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termse`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termse` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termse` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termse` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_links_termsf`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_links_termsf`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_links_termsf` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_links_termsf`
--

/*!40000 ALTER TABLE `pmgov2013_finder_links_termsf` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_links_termsf` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_links_termsf` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_taxonomy`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_taxonomy`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_taxonomy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `state` (`state`),
  KEY `ordering` (`ordering`),
  KEY `access` (`access`),
  KEY `idx_parent_published` (`parent_id`,`state`,`access`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_taxonomy`
--

/*!40000 ALTER TABLE `pmgov2013_finder_taxonomy` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_taxonomy` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_finder_taxonomy` VALUES  (1,0,'ROOT',0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_taxonomy` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_taxonomy_map`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_taxonomy_map`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_taxonomy_map` (
  `link_id` int(10) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`node_id`),
  KEY `link_id` (`link_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_taxonomy_map`
--

/*!40000 ALTER TABLE `pmgov2013_finder_taxonomy_map` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_taxonomy_map` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_taxonomy_map` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_terms`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_terms`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_terms` (
  `term_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '0',
  `soundex` varchar(75) NOT NULL,
  `links` int(10) NOT NULL DEFAULT '0',
  `language` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `idx_term` (`term`),
  KEY `idx_term_phrase` (`term`,`phrase`),
  KEY `idx_stem_phrase` (`stem`,`phrase`),
  KEY `idx_soundex_phrase` (`soundex`,`phrase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_terms`
--

/*!40000 ALTER TABLE `pmgov2013_finder_terms` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_terms` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_terms` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_terms_common`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_terms_common`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_terms_common` (
  `term` varchar(75) NOT NULL,
  `language` varchar(3) NOT NULL,
  KEY `idx_word_lang` (`term`,`language`),
  KEY `idx_lang` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_terms_common`
--

/*!40000 ALTER TABLE `pmgov2013_finder_terms_common` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_terms_common` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_finder_terms_common` VALUES  ('a','en'),
 ('about','en'),
 ('after','en'),
 ('ago','en'),
 ('all','en'),
 ('am','en'),
 ('an','en'),
 ('and','en'),
 ('ani','en'),
 ('any','en'),
 ('are','en'),
 ('aren\'t','en'),
 ('as','en'),
 ('at','en'),
 ('be','en'),
 ('but','en'),
 ('by','en'),
 ('for','en'),
 ('from','en'),
 ('get','en'),
 ('go','en'),
 ('how','en'),
 ('if','en'),
 ('in','en'),
 ('into','en'),
 ('is','en'),
 ('isn\'t','en'),
 ('it','en'),
 ('its','en'),
 ('me','en'),
 ('more','en'),
 ('most','en'),
 ('must','en'),
 ('my','en'),
 ('new','en'),
 ('no','en'),
 ('none','en'),
 ('not','en'),
 ('noth','en'),
 ('nothing','en'),
 ('of','en'),
 ('off','en'),
 ('often','en'),
 ('old','en'),
 ('on','en'),
 ('onc','en'),
 ('once','en'),
 ('onli','en'),
 ('only','en'),
 ('or','en'),
 ('other','en'),
 ('our','en'),
 ('ours','en'),
 ('out','en'),
 ('over','en'),
 ('page','en'),
 ('she','en'),
 ('should','en'),
 ('small','en'),
 ('so','en'),
 ('some','en'),
 ('than','en'),
 ('thank','en'),
 ('that','en'),
 ('the','en'),
 ('their','en'),
 ('theirs','en'),
 ('them','en'),
 ('then','en'),
 ('there','en'),
 ('these','en'),
 ('they','en'),
 ('this','en'),
 ('those','en'),
 ('thus','en'),
 ('time','en'),
 ('times','en'),
 ('to','en'),
 ('too','en'),
 ('true','en'),
 ('under','en'),
 ('until','en'),
 ('up','en'),
 ('upon','en'),
 ('use','en'),
 ('user','en'),
 ('users','en'),
 ('veri','en'),
 ('version','en'),
 ('very','en'),
 ('via','en'),
 ('want','en'),
 ('was','en'),
 ('way','en'),
 ('were','en'),
 ('what','en'),
 ('when','en'),
 ('where','en'),
 ('whi','en'),
 ('which','en'),
 ('who','en'),
 ('whom','en'),
 ('whose','en'),
 ('why','en'),
 ('wide','en'),
 ('will','en'),
 ('with','en'),
 ('within','en'),
 ('without','en'),
 ('would','en'),
 ('yes','en'),
 ('yet','en'),
 ('you','en'),
 ('your','en'),
 ('yours','en');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_terms_common` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_tokens`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_tokens`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_tokens` (
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '1',
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `language` char(3) NOT NULL DEFAULT '',
  KEY `idx_word` (`term`),
  KEY `idx_context` (`context`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_tokens`
--

/*!40000 ALTER TABLE `pmgov2013_finder_tokens` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_tokens` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_tokens` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_tokens_aggregate`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_tokens_aggregate`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_tokens_aggregate` (
  `term_id` int(10) unsigned NOT NULL,
  `map_suffix` char(1) NOT NULL,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `term_weight` float unsigned NOT NULL,
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `context_weight` float unsigned NOT NULL,
  `total_weight` float unsigned NOT NULL,
  `language` char(3) NOT NULL DEFAULT '',
  KEY `token` (`term`),
  KEY `keyword_id` (`term_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_tokens_aggregate`
--

/*!40000 ALTER TABLE `pmgov2013_finder_tokens_aggregate` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_tokens_aggregate` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_tokens_aggregate` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_finder_types`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_finder_types`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_finder_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `mime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_finder_types`
--

/*!40000 ALTER TABLE `pmgov2013_finder_types` DISABLE KEYS */;
LOCK TABLES `pmgov2013_finder_types` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_finder_types` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_languages`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_languages`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_languages` (
  `lang_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `sef` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `sitename` varchar(1024) NOT NULL DEFAULT '',
  `published` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `idx_sef` (`sef`),
  UNIQUE KEY `idx_image` (`image`),
  UNIQUE KEY `idx_langcode` (`lang_code`),
  KEY `idx_access` (`access`),
  KEY `idx_ordering` (`ordering`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_languages`
--

/*!40000 ALTER TABLE `pmgov2013_languages` DISABLE KEYS */;
LOCK TABLES `pmgov2013_languages` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_languages` VALUES  (1,'en-GB','English (UK)','English (UK)','en','en','','','','',1,0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_languages` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_menu`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_menu`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  `note` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  `published` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The published state of the menu link.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'The parent menu item in the menu tree.',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__extensions.id',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__users.id',
  `checked_out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  `browserNav` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The click behaviour of the link.',
  `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.',
  `language` char(7) NOT NULL DEFAULT '',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_client_id_parent_id_alias_language` (`client_id`,`parent_id`,`alias`,`language`),
  KEY `idx_componentid` (`component_id`,`menutype`,`published`,`access`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_path` (`path`(255)),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_menu`
--

/*!40000 ALTER TABLE `pmgov2013_menu` DISABLE KEYS */;
LOCK TABLES `pmgov2013_menu` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_menu` VALUES  (1,'','Menu_Item_Root',0x726F6F74,'','','','',1,0,0,0,0,'0000-00-00 00:00:00',0,0,'',0,'',0,193,0,'*',0),
 (2,'menu','com_banners',0x42616E6E657273,'','Banners','index.php?option=com_banners','component',0,1,1,4,0,'0000-00-00 00:00:00',0,0,'class:banners',0,'',3,12,0,'*',1),
 (3,'menu','com_banners',0x42616E6E657273,'','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,'0000-00-00 00:00:00',0,0,'class:banners',0,'',4,5,0,'*',1),
 (4,'menu','com_banners_categories',0x43617465676F72696573,'','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,'0000-00-00 00:00:00',0,0,'class:banners-cat',0,'',6,7,0,'*',1),
 (5,'menu','com_banners_clients',0x436C69656E7473,'','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,'0000-00-00 00:00:00',0,0,'class:banners-clients',0,'',8,9,0,'*',1),
 (6,'menu','com_banners_tracks',0x547261636B73,'','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,'0000-00-00 00:00:00',0,0,'class:banners-tracks',0,'',10,11,0,'*',1),
 (7,'menu','com_contact',0x436F6E7461637473,'','Contacts','index.php?option=com_contact','component',0,1,1,8,0,'0000-00-00 00:00:00',0,0,'class:contact',0,'',13,18,0,'*',1),
 (8,'menu','com_contact',0x436F6E7461637473,'','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,'0000-00-00 00:00:00',0,0,'class:contact',0,'',14,15,0,'*',1),
 (9,'menu','com_contact_categories',0x43617465676F72696573,'','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,'0000-00-00 00:00:00',0,0,'class:contact-cat',0,'',16,17,0,'*',1),
 (10,'menu','com_messages',0x4D6573736167696E67,'','Messaging','index.php?option=com_messages','component',0,1,1,15,0,'0000-00-00 00:00:00',0,0,'class:messages',0,'',19,24,0,'*',1),
 (11,'menu','com_messages_add',0x4E65772050726976617465204D657373616765,'','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,'0000-00-00 00:00:00',0,0,'class:messages-add',0,'',20,21,0,'*',1),
 (12,'menu','com_messages_read',0x526561642050726976617465204D657373616765,'','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,'0000-00-00 00:00:00',0,0,'class:messages-read',0,'',22,23,0,'*',1),
 (13,'menu','com_newsfeeds',0x4E657773204665656473,'','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds',0,'',25,30,0,'*',1),
 (14,'menu','com_newsfeeds_feeds',0x4665656473,'','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds',0,'',26,27,0,'*',1),
 (15,'menu','com_newsfeeds_categories',0x43617465676F72696573,'','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,'0000-00-00 00:00:00',0,0,'class:newsfeeds-cat',0,'',28,29,0,'*',1),
 (16,'menu','com_redirect',0x5265646972656374,'','Redirect','index.php?option=com_redirect','component',0,1,1,24,0,'0000-00-00 00:00:00',0,0,'class:redirect',0,'',41,42,0,'*',1),
 (17,'menu','com_search',0x426173696320536561726368,'','Basic Search','index.php?option=com_search','component',0,1,1,19,0,'0000-00-00 00:00:00',0,0,'class:search',0,'',33,34,0,'*',1),
 (18,'menu','com_weblinks',0x5765626C696E6B73,'','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,'0000-00-00 00:00:00',0,0,'class:weblinks',0,'',35,40,0,'*',1),
 (19,'menu','com_weblinks_links',0x4C696E6B73,'','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,'0000-00-00 00:00:00',0,0,'class:weblinks',0,'',36,37,0,'*',1),
 (20,'menu','com_weblinks_categories',0x43617465676F72696573,'','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,'0000-00-00 00:00:00',0,0,'class:weblinks-cat',0,'',38,39,0,'*',1),
 (21,'menu','com_finder',0x536D61727420536561726368,'','Smart Search','index.php?option=com_finder','component',0,1,1,27,0,'0000-00-00 00:00:00',0,0,'class:finder',0,'',31,32,0,'*',1),
 (22,'menu','com_joomlaupdate',0x4A6F6F6D6C612120557064617465,'','Joomla! Update','index.php?option=com_joomlaupdate','component',0,1,1,28,0,'0000-00-00 00:00:00',0,0,'class:joomlaupdate',0,'',43,44,0,'*',1),
 (23,'main','com_tags',0x54616773,'','Tags','index.php?option=com_tags','component',0,1,1,29,0,'0000-00-00 00:00:00',0,1,'class:tags',0,'',45,46,0,'',1),
 (101,'mainmenu','Página inicial',0x686F6D65,'','home','index.php?option=com_blankcomponent&view=default','component',1,1,1,10020,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',1,2,1,'*',0),
 (102,'main','JCE',0x6A6365,'','jce','index.php?option=com_jce','component',0,1,1,10005,0,'0000-00-00 00:00:00',0,1,'components/com_jce/media/img/menu/logo.png',0,'',47,56,0,'',1),
 (103,'main','WF_MENU_CPANEL',0x77662D6D656E752D6370616E656C,'','jce/wf-menu-cpanel','index.php?option=com_jce','component',0,102,2,10005,0,'0000-00-00 00:00:00',0,1,'components/com_jce/media/img/menu/jce-cpanel.png',0,'',48,49,0,'',1),
 (104,'main','WF_MENU_CONFIG',0x77662D6D656E752D636F6E666967,'','jce/wf-menu-config','index.php?option=com_jce&view=config','component',0,102,2,10005,0,'0000-00-00 00:00:00',0,1,'components/com_jce/media/img/menu/jce-config.png',0,'',50,51,0,'',1),
 (105,'main','WF_MENU_PROFILES',0x77662D6D656E752D70726F66696C6573,'','jce/wf-menu-profiles','index.php?option=com_jce&view=profiles','component',0,102,2,10005,0,'0000-00-00 00:00:00',0,1,'components/com_jce/media/img/menu/jce-profiles.png',0,'',52,53,0,'',1),
 (106,'main','WF_MENU_INSTALL',0x77662D6D656E752D696E7374616C6C,'','jce/wf-menu-install','index.php?option=com_jce&view=installer','component',0,102,2,10005,0,'0000-00-00 00:00:00',0,1,'components/com_jce/media/img/menu/jce-install.png',0,'',54,55,0,'',1),
 (107,'assuntos','Editoria A',0x656469746F7269612D61,'','editoria-a','index.php?option=com_content&view=article&id=1','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',113,114,0,'*',0),
 (110,'assuntos','Editoria B',0x656469746F7269612D62,'','editoria-b','index.php?option=com_content&view=article&id=5','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',115,116,0,'*',0),
 (111,'assuntos','Editoria C',0x656469746F7269612D63,'','editoria-c','index.php?option=com_content&view=article&id=6','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',117,118,0,'*',0),
 (112,'sobre','Institucional',0x696E737469747563696F6E616C,'','institucional','index.php?option=com_content&view=article&id=7','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',57,58,0,'*',0),
 (113,'sobre','Ações e Programas',0x61636F65732D652D70726F6772616D6173,'','acoes-e-programas','index.php?option=com_content&view=article&id=8','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',59,60,0,'*',0),
 (114,'sobre','Auditoria',0x61756469746F726961,'','auditoria','index.php?option=com_content&view=article&id=9','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',61,62,0,'*',0),
 (115,'sobre','Convênios',0x636F6E76656E696F73,'','convenios','index.php?option=com_content&view=article&id=10','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',63,64,0,'*',0),
 (116,'sobre','Despesas',0x6465737065736173,'','despesas','index.php?option=com_content&view=article&id=11','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',65,66,0,'*',0),
 (117,'sobre','Licitações e contratos',0x6C6963697461636F65732D652D636F6E747261746F73,'','licitacoes-e-contratos','index.php?option=com_content&view=article&id=12','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',67,68,0,'*',0),
 (118,'sobre','Servidores',0x7365727669646F726573,'','servidores','index.php?option=com_content&view=article&id=13','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',69,70,0,'*',0),
 (119,'sobre','Informações classificadas',0x696E666F726D61636F65732D636C6173736966696361646173,'','informacoes-classificadas','index.php?option=com_content&view=article&id=14','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',71,72,0,'*',0),
 (120,'sobre','Serviço de Informação ao Cidadão (SIC)',0x7365727669636F2D64652D696E666F726D6163616F2D616F2D6369646164616F2D736963,'','servico-de-informacao-ao-cidadao-sic','index.php?option=com_content&view=article&id=15','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',73,74,0,'*',0),
 (121,'mainmenu','Últimas Notícias',0x756C74696D61732D6E6F746963696173,'','ultimas-noticias','index.php?option=com_content&view=category&id=17','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_category_heading_title_text\":\"\",\"show_category_title\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_no_articles\":\"\",\"show_subcat_desc\":\"\",\"show_cat_num_articles\":\"\",\"page_subheading\":\"\",\"show_pagination_limit\":\"\",\"filter_field\":\"\",\"show_headings\":\"\",\"list_show_date\":\"\",\"date_format\":\"\",\"list_show_hits\":\"\",\"list_show_author\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_pagination_results\":\"\",\"display_num\":\"10\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_readmore\":\"\",\"show_readmore_title\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',135,136,0,'*',0),
 (123,'mainmenu','Manuais',0x6D616E75616973,'','manuais','index.php?option=com_content&view=article&id=27','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',137,138,0,'*',0),
 (124,'menu-de-apoio','Conheça a identidade digital do governo',0x636F6E686563612D612D6964656E7469646164652D6469676974616C2D646F2D676F7665726E6F,'','conheca-a-identidade-digital-do-governo','index.php?option=com_content&view=article&id=26','component',1,1,1,22,576,'2013-10-28 14:39:30',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"identidade-digital-1\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',119,120,0,'*',0),
 (125,'mainmenu','Conteudo do Menu Superior',0x636F6E746575646F2D646F2D6D656E752D7375706572696F72,'','conteudo-do-menu-superior','index.php?option=com_content&view=category&id=33','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_category_heading_title_text\":\"\",\"show_category_title\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"maxLevel\":\"\",\"show_empty_categories\":\"\",\"show_no_articles\":\"\",\"show_subcat_desc\":\"\",\"show_cat_num_articles\":\"\",\"page_subheading\":\"\",\"show_pagination_limit\":\"\",\"filter_field\":\"\",\"show_headings\":\"\",\"list_show_date\":\"\",\"date_format\":\"\",\"list_show_hits\":\"\",\"list_show_author\":\"\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_pagination_results\":\"\",\"display_num\":\"10\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_readmore\":\"\",\"show_readmore_title\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',139,140,0,'*',0),
 (126,'em-destaque','Destaque 1',0x323031332D31302D32372D30302D31312D3034,'','2013-10-27-00-11-04','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',75,76,0,'*',0),
 (127,'em-destaque','Destaque 2',0x323031332D31302D32372D30302D31312D35,'','2013-10-27-00-11-5','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',77,78,0,'*',0),
 (128,'em-destaque','Destaque 3',0x323031332D31302D32372D30302D31312D36,'','2013-10-27-00-11-6','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',79,80,0,'*',0),
 (129,'em-destaque','Destaque 4',0x323031332D31302D32372D30302D31312D37,'','2013-10-27-00-11-7','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',81,82,0,'*',0),
 (130,'em-destaque','Destaque 5',0x323031332D31302D32372D30302D31312D38,'','2013-10-27-00-11-8','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',83,84,0,'*',0),
 (131,'servicos','Perguntas frequentes',0x323031332D31302D32372D30312D35352D3038,'','2013-10-27-01-55-08','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',85,86,0,'*',0),
 (132,'servicos','Contato',0x323031332D31302D32372D30312D35352D39,'','2013-10-27-01-55-9','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',87,88,0,'*',0),
 (133,'servicos','Serviços da [Denominação]',0x323031332D31302D32372D30312D35352D3130,'','2013-10-27-01-55-10','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',89,90,0,'*',0),
 (134,'servicos','Dados abertos',0x323031332D31302D32372D30312D35352D3131,'','2013-10-27-01-55-11','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',91,92,0,'*',0),
 (135,'servicos','Área de imprensa',0x323031332D31302D32372D30312D35352D3132,'','2013-10-27-01-55-12','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',93,94,0,'*',0),
 (136,'midias-sociais','Twitter',0x323031332D31302D32372D30322D31322D3336,'twitter-sign','2013-10-27-02-12-36','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',95,96,0,'*',0),
 (137,'midias-sociais','YouTube',0x323031332D31302D32372D30322D31322D3337,'youtube','2013-10-27-02-12-37','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',97,98,0,'*',0),
 (138,'midias-sociais','Facebook',0x323031332D31302D32372D30322D31322D3338,'facebook-sign','2013-10-27-02-12-38','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',99,100,0,'*',0),
 (139,'midias-sociais','Flickr',0x323031332D31302D32372D30322D31322D3339,'flickr','2013-10-27-02-12-39','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',101,102,0,'*',0),
 (140,'acessibilidade','Acessibilidade',0x323031332D31302D32372D30322D35342D3030,'','2013-10-27-02-54-00','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',103,104,0,'*',0),
 (141,'acessibilidade','Alto contraste',0x323031332D31302D32372D30322D35342D31,'','2013-10-27-02-54-1','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',105,106,0,'*',0),
 (142,'acessibilidade','Mapa do site',0x323031332D31302D32372D30322D35342D32,'','2013-10-27-02-54-2','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',107,108,0,'*',0),
 (143,'idiomas','EN',0x323031332D31302D32372D30332D31382D3337,'','2013-10-27-03-18-37','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"language-en\",\"menu_image\":\"\",\"menu_text\":1}',109,110,0,'*',0),
 (144,'idiomas','ES',0x323031332D31302D32372D30332D31382D3338,'','2013-10-27-03-18-38','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"language-es\",\"menu_image\":\"\",\"menu_text\":1}',111,112,0,'*',0),
 (145,'central-de-conteudos','Imagens',0x323031332D31302D32372D31332D32362D3333,'icon-picture','2013-10-27-13-26-33','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"imagens\",\"menu_image\":\"\",\"menu_text\":1}',121,122,0,'*',0),
 (146,'central-de-conteudos','Vídeos',0x323031332D31302D32372D31332D32362D3334,'icon-play','2013-10-27-13-26-34','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"videos\",\"menu_image\":\"\",\"menu_text\":1}',123,124,0,'*',0),
 (147,'central-de-conteudos','Áudios',0x323031332D31302D32372D31332D32362D3335,'icon-volume-up','2013-10-27-13-26-35','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"audios\",\"menu_image\":\"\",\"menu_text\":1}',125,126,0,'*',0),
 (148,'central-de-conteudos','Publicações',0x323031332D31302D32372D31332D32362D3336,'icon-file-text','2013-10-27-13-26-36','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"publicacoes\",\"menu_image\":\"\",\"menu_text\":1}',127,128,0,'*',0),
 (149,'central-de-conteudos','Aplicativos',0x323031332D31302D32372D31332D32362D3337,'icon-mobile-phone','2013-10-27-13-26-37','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"aplicativos\",\"menu_image\":\"\",\"menu_text\":1}',129,130,0,'*',0),
 (150,'central-de-conteudos','Infográficos',0x323031332D31302D32372D31332D32362D3338,'icon-columns','2013-10-27-13-26-38','#','url',0,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"infograficos\",\"menu_image\":\"\",\"menu_text\":1}',131,132,0,'*',0),
 (151,'central-de-conteudos','Estatísticas',0x323031332D31302D32372D31332D32362D3339,'icon-bar-chart','2013-10-27-13-26-39','#','url',0,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"estatisticas\",\"menu_image\":\"\",\"menu_text\":1}',133,134,0,'*',0),
 (152,'assuntos','Editoria D',0x656469746F7269612D64,'','editoria-d','index.php?option=com_content&view=article&id=1','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',141,164,0,'*',0),
 (153,'assuntos','Editoria E',0x656469746F7269612D65,'','editoria-e','index.php?option=com_content&view=article&id=5','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',165,166,0,'*',0),
 (154,'assuntos','Editoria F',0x656469746F7269612D66,'','editoria-f','index.php?option=com_content&view=article&id=6','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',167,168,0,'*',0),
 (155,'assuntos','Editoria G',0x656469746F7269612D67,'','editoria-g','index.php?option=com_content&view=article&id=6','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',169,170,0,'*',0),
 (156,'assuntos','Editoria H',0x656469746F7269612D68,'','editoria-h','index.php?option=com_content&view=article&id=1','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',171,172,0,'*',0),
 (157,'assuntos','Editoria I',0x656469746F7269612D69,'','editoria-i','index.php?option=com_content&view=article&id=5','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',173,174,0,'*',0),
 (158,'assuntos','Editoria J',0x656469746F7269612D6A,'','editoria-j','index.php?option=com_content&view=article&id=6','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',175,176,0,'*',0),
 (159,'assuntos','Subnível 1 - link 1',0x323031332D31302D32372D31332D33322D3438,'','editoria-d/2013-10-27-13-32-48','#','url',1,152,2,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',142,155,0,'*',0),
 (160,'assuntos','Subnível 1 - link 2',0x323031332D31302D32372D31332D33322D3439,'','editoria-d/2013-10-27-13-32-49','#','url',1,152,2,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',156,157,0,'*',0),
 (161,'assuntos','Subnível 1 - link 3',0x323031332D31302D32372D31332D33322D3530,'','editoria-d/2013-10-27-13-32-50','#','url',1,152,2,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',158,159,0,'*',0),
 (162,'assuntos','Subnível 1 - link 4',0x323031332D31302D32372D31332D33322D3531,'','editoria-d/2013-10-27-13-32-51','#','url',1,152,2,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',160,161,0,'*',0),
 (163,'assuntos','Subnível 1 - link 5',0x323031332D31302D32372D31332D33322D3532,'','editoria-d/2013-10-27-13-32-52','#','url',1,152,2,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',162,163,0,'*',0),
 (164,'assuntos','Subnível 2 - link 1',0x323031332D31302D32372D31332D33322D3533,'','editoria-d/2013-10-27-13-32-48/2013-10-27-13-32-53','#','url',1,159,3,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',143,144,0,'*',0),
 (165,'assuntos','Subnível 2 - link 2',0x7375626E6976656C2D322D6C696E6B2D32,'','editoria-d/2013-10-27-13-32-48/subnivel-2-link-2','index.php?option=com_content&view=article&id=33','component',1,159,3,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',145,154,0,'*',0),
 (166,'assuntos','Subnível 3 - link 1',0x7375626E6976656C2D332D6C696E6B2D31,'','editoria-d/2013-10-27-13-32-48/subnivel-2-link-2/subnivel-3-link-1','index.php?option=com_content&view=article&id=33','component',1,165,4,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',146,151,0,'*',0),
 (167,'assuntos','Subnível 3 - link 2',0x7375626E6976656C2D332D6C696E6B2D32,'','editoria-d/2013-10-27-13-32-48/subnivel-2-link-2/subnivel-3-link-2','index.php?option=com_content&view=article&id=33','component',1,165,4,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',152,153,0,'*',0),
 (168,'assuntos','Subnível 4 - link 1',0x7375626E6976656C2D342D6C696E6B2D31,'','editoria-d/2013-10-27-13-32-48/subnivel-2-link-2/subnivel-3-link-1/subnivel-4-link-1','index.php?option=com_content&view=article&id=33','component',1,166,5,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',147,148,0,'*',0),
 (169,'assuntos','Subnível 4 - link 2',0x7375626E6976656C2D342D6C696E6B2D32,'','editoria-d/2013-10-27-13-32-48/subnivel-2-link-2/subnivel-3-link-1/subnivel-4-link-2','index.php?option=com_content&view=article&id=33','component',1,166,5,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"urls_position\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',149,150,0,'*',0),
 (170,'rss','O que é?',0x323031332D31302D32372D31372D34382D3138,'','2013-10-27-17-48-18','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',177,178,0,'*',0),
 (171,'rss','Assine',0x323031332D31302D32372D31372D34382D3538,'','2013-10-27-17-48-58','#','url',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',179,180,0,'*',0),
 (172,'sobre-o-site','Acessibilidade',0x323031332D31302D32372D31382D31312D3137,'','2013-10-27-18-11-17','index.php?Itemid=','alias',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"aliasoptions\":\"140\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',181,182,0,'*',0),
 (173,'sobre-o-site','Mapa do site',0x323031332D31302D32372D31382D31312D3138,'','2013-10-27-18-11-18','index.php?Itemid=','alias',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"aliasoptions\":\"142\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',183,184,0,'*',0),
 (174,'sobre-o-site','Versión en Español - Versão em Espanhol',0x323031332D31302D32372D31382D31332D3034,'','2013-10-27-18-13-04','index.php?Itemid=','alias',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"aliasoptions\":\"144\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',185,186,0,'*',0),
 (175,'sobre-o-site','English version - Versão em Inglês',0x323031332D31302D32372D31382D31332D35,'','2013-10-27-18-13-5','index.php?Itemid=','alias',1,1,1,0,0,'0000-00-00 00:00:00',0,1,'',0,'{\"aliasoptions\":\"143\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1}',187,188,0,'*',0),
 (177,'main','Blank Component',0x626C616E6B2D636F6D706F6E656E74,'','blank-component','index.php?option=com_blankcomponent','component',0,1,1,10020,0,'0000-00-00 00:00:00',0,1,'class:component',0,'',189,190,0,'',1),
 (178,'mainmenu','Galeria de imagens',0x67616C657269612D64652D696D6167656E73,'','galeria-de-imagens','index.php?option=com_content&view=category&layout=blog&id=36','component',1,1,1,22,0,'0000-00-00 00:00:00',0,1,'',0,'{\"layout_type\":\"blog\",\"show_category_heading_title_text\":\"\",\"show_category_title\":\"\",\"show_description\":\"\",\"show_description_image\":\"\",\"maxLevel\":\"2\",\"show_empty_categories\":\"\",\"show_no_articles\":\"\",\"show_subcat_desc\":\"1\",\"show_cat_num_articles\":\"\",\"page_subheading\":\"\",\"num_leading_articles\":\"\",\"num_intro_articles\":\"\",\"num_columns\":\"\",\"num_links\":\"\",\"multi_column_order\":\"\",\"show_subcategory_content\":\"2\",\"orderby_pri\":\"\",\"orderby_sec\":\"\",\"order_date\":\"\",\"show_pagination\":\"\",\"show_pagination_results\":\"\",\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"info_block_position\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_vote\":\"\",\"show_readmore\":\"\",\"show_readmore_title\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"show_feed_link\":\"\",\"feed_summary\":\"\",\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}',191,192,0,'*',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_menu` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_menu_types`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_menu_types`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_menutype` (`menutype`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_menu_types`
--

/*!40000 ALTER TABLE `pmgov2013_menu_types` DISABLE KEYS */;
LOCK TABLES `pmgov2013_menu_types` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_menu_types` VALUES  (1,'mainmenu','Main Menu','The main menu for the site'),
 (3,'sobre','Sobre',''),
 (4,'em-destaque','Em destaque',''),
 (5,'servicos','Serviços',''),
 (6,'midias-sociais','Mídias Sociais',''),
 (7,'acessibilidade','Acessibilidade',''),
 (8,'idiomas','Idiomas',''),
 (9,'assuntos','Assuntos',''),
 (10,'menu-de-apoio','Menu de Apoio',''),
 (11,'central-de-conteudos','Central de conteúdos',''),
 (12,'rss','RSS',''),
 (13,'sobre-o-site','Sobre o site','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_menu_types` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_messages`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_messages`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_messages`
--

/*!40000 ALTER TABLE `pmgov2013_messages` DISABLE KEYS */;
LOCK TABLES `pmgov2013_messages` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_messages` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_messages_cfg`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_messages_cfg`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_messages_cfg`
--

/*!40000 ALTER TABLE `pmgov2013_messages_cfg` DISABLE KEYS */;
LOCK TABLES `pmgov2013_messages_cfg` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_messages_cfg` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_modules`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_modules`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) NOT NULL DEFAULT '',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_modules`
--

/*!40000 ALTER TABLE `pmgov2013_modules` DISABLE KEYS */;
LOCK TABLES `pmgov2013_modules` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_modules` VALUES  (1,'Temas relevantes','','',1,'menu-principal',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"menu-de-apoio\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span9\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menuprincipal\",\"moduleclass_sfx\":\"menu-de-apoio\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (2,'Login','','',1,'login',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_login',1,1,'',1,'*'),
 (3,'Popular Articles','','',3,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_popular',3,1,'{\"count\":\"5\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),
 (4,'Recently Added Articles','','',4,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_latest',3,1,'{\"count\":\"5\",\"ordering\":\"c_dsc\",\"catid\":\"\",\"user_id\":\"0\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),
 (8,'Toolbar','','',1,'toolbar',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_toolbar',3,1,'',1,'*'),
 (9,'Quick Icons','','',1,'icon',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_quickicon',3,1,'',1,'*'),
 (10,'Logged-in Users','','',2,'cpanel',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_logged',3,1,'{\"count\":\"5\",\"name\":\"1\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\",\"automatic_title\":\"1\"}',1,'*'),
 (12,'Admin Menu','','',1,'menu',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',3,1,'{\"layout\":\"\",\"moduleclass_sfx\":\"\",\"shownew\":\"1\",\"showhelp\":\"1\",\"cache\":\"0\"}',1,'*'),
 (13,'Admin Submenu','','',1,'submenu',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_submenu',3,1,'',1,'*'),
 (14,'User Status','','',2,'status',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_status',3,1,'',1,'*'),
 (15,'Title','','',1,'title',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_title',3,1,'',1,'*'),
 (16,'Login Form','','',7,'position-7',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_login',1,1,'{\"greeting\":\"1\",\"name\":\"0\"}',0,'*'),
 (17,'Breadcrumbs','','',1,'position-2',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_breadcrumbs',1,1,'{\"moduleclass_sfx\":\"\",\"showHome\":\"1\",\"homeText\":\"Home\",\"showComponent\":\"1\",\"separator\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (79,'Multilanguage status','','',1,'status',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_multilangstatus',3,1,'{\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*'),
 (86,'Joomla Version','','',1,'footer',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_version',3,1,'{\"format\":\"short\",\"product\":\"1\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"0\"}',1,'*'),
 (87,'Sobre','','',3,'menu-principal',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"sobre\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span9\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menuprincipal\",\"moduleclass_sfx\":\"sobre\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (88,'Editora A - Noticias','','<style type=\"text/css\">\r\n#t1{margin-left: 209px; margin-top: -130px;}\r\n#p1{margin-left: 209px;}\r\n#t2{margin-left: 209px; margin-top: -130px;}\r\n#p2{margin-left: 209px;}\r\n#not{height:150px;}\r\n#not2{height:150px; margin-top: 35px;}\r\n#not3{height:150px; margin-top: 35px;}\r\n#chapeu{margin-bottom:5px;}\r\n#chapeut3{/*margin-bottom: -17px;*/ margin-top: 10px;}\r\n</style>\r\n\r\n<div id=\"not\">\r\n      <p id=\"chapeu\">Chapéu</p>\r\n      <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\"> <img alt=\"Título da notícia em 3 linhas - até 50 caracteres\" src=\"http://portalpadrao.plone.org.br/assuntos/editoria-a/capa-interna/@@nitf/c1e7e8f45dc4438296670befba1af889/@@images/de711a47-37cb-4135-b352-be0faae7c1da.jpeg\" height=\"130\" width=\"200\" /></a>\r\n      <h2 id=\"t1\"> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\">Título da notícia em 3 linhas - até 50 caracteres</a> </h2>\r\n      <p id=\"p1\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n\r\n\r\n<div id=\"not2\">\r\n      <p id=\"chapeu\">Chapéu</p>\r\n      <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\"> <img alt=\"Título da notícia em 3 linhas - até 50 caracteres\" src=\"http://portalpadrao.plone.org.br/assuntos/editoria-a/capa-interna/@@nitf/db631c61295a409e8fe5408dee369c07/@@images/f86ae8f4-36a5-4297-88b4-bd3f9218908c.jpeg\" height=\"130\" width=\"200\" /></a>\r\n      <h2 id=\"t2\"> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\">Título da notícia em 3 linhas - até 50 caracteres</a> </h2>\r\n      <p id=\"p2\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n\r\n\r\n<div id=\"not3\">\r\n      <p id=\"chapeut3\">Chapéu</p>\r\n      <h2> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-3-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas\">Título da notícia em duas linhas cheias com até 90 caracteres. Título da notícia em 2 linhas</a> </h2>\r\n      <p>Subtítulo em três linhas com até 180   caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo   em três linhas com até 180 caracteres. Subtítulo em três linhas</p>\r\n</div>',1,'position-12',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_custom',1,1,'{\"prepare_content\":\"0\",\"backgroundimage\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (89,'Editora A - Vídeos','','<img src=\"images/imagens_menu/video.png\" alt=\"video\" />',1,'position-3',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_custom',1,0,'{\"prepare_content\":\"0\",\"backgroundimage\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (92,'Editora A - Noticias (2)','','<style type=\"text/css\">\r\n#t1{margin-left: 209px; margin-top: -130px;}\r\n#p1{margin-left: 209px;}\r\n#t2{margin-left: 209px; margin-top: -130px;}\r\n#p2{margin-left: 209px;}\r\n#not{height:150px;}\r\n#not2{height:150px; margin-top: 35px;}\r\n#not3{height:150px; margin-top: 35px;}\r\n#chapeu{margin-bottom:5px;}\r\n#chapeut3{/*margin-bottom: -17px;*/ margin-top: 10px;}\r\n</style>\r\n\r\n<div id=\"not\">\r\n      <p id=\"chapeu\">Chapéu</p>\r\n      <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\"> <img alt=\"Título da notícia em 3 linhas - até 50 caracteres\" src=\"http://portalpadrao.plone.org.br/assuntos/editoria-a/capa-interna/@@nitf/c1e7e8f45dc4438296670befba1af889/@@images/de711a47-37cb-4135-b352-be0faae7c1da.jpeg\" height=\"130\" width=\"200\" /></a>\r\n      <h2 id=\"t1\"> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-2-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\">Título da notícia em 3 linhas - até 50 caracteres</a> </h2>\r\n      <p id=\"p1\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n\r\n\r\n<div id=\"not2\">\r\n      <p id=\"chapeu\">Chapéu</p>\r\n      <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\"> <img alt=\"Título da notícia em 3 linhas - até 50 caracteres\" src=\"http://portalpadrao.plone.org.br/assuntos/editoria-a/capa-interna/@@nitf/db631c61295a409e8fe5408dee369c07/@@images/f86ae8f4-36a5-4297-88b4-bd3f9218908c.jpeg\" height=\"130\" width=\"200\" /></a>\r\n      <h2 id=\"t2\"> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-1-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres\">Título da notícia em 3 linhas - até 50 caracteres</a> </h2>\r\n      <p id=\"p2\">Subtítulo em quatro linhas com até 110 caracteres. Subtítulo em quatro linhas com até 110 caracteres</p>\r\n</div>\r\n\r\n\r\n<div id=\"not3\">\r\n      <p id=\"chapeut3\">Chapéu</p>\r\n      <h2> <a href=\"http://portalpadrao.plone.org.br/conteudos-de-marcacao/texto-3-titulo-da-noticia-entre-35-e-90-caracteres\" title=\"Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo em três linhas com até 180 caracteres. Subtítulo em três linhas\">Título da notícia em duas linhas cheias com até 90 caracteres. Título da notícia em 2 linhas</a> </h2>\r\n      <p>Subtítulo em três linhas com até 180   caracteres. Subtítulo em três linhas com até 1800 caracteres. Subtítulo   em três linhas com até 180 caracteres. Subtítulo em três linhas</p>\r\n</div>',1,'position-222',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_custom',1,1,'{\"prepare_content\":\"0\",\"backgroundimage\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (93,'Em destaque','Inclua class duas-linhas para tratar layout.','',1,'topo-main',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"em-destaque\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"em-destaque\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"padraogoverno01:destaque\",\"moduleclass_sfx\":\"span10\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (94,'Serviços','','',1,'menu-sobre',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"servicos\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"1\",\"tag_id\":\"\",\"class_sfx\":\"menu-servicos pull-right\",\"window_open\":\"\",\"layout\":\"padraogoverno01:servicos\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (95,'Redes Sociais','','',2,'header-meio-direita',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"midias-sociais\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"\",\"window_open\":\"\",\"layout\":\"padraogoverno01:redessociais\",\"moduleclass_sfx\":\"social-icons\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (96,'Opções de acessibilidade','','',2,'header-topo-direita',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"acessibilidade\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"0\",\"tag_id\":\"portal-siteactions\",\"class_sfx\":\"\",\"window_open\":\"5\",\"layout\":\"padraogoverno01:menustopo\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (97,'Idiomas','','',1,'header-topo-direita',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_menu',1,1,'{\"menutype\":\"idiomas\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"language\",\"class_sfx\":\"\",\"window_open\":\"8\",\"layout\":\"padraogoverno01:menustopo\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (99,'Assuntos','','',2,'menu-principal',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"assuntos\",\"startLevel\":\"1\",\"endLevel\":\"0\",\"showAllChildren\":\"1\",\"tag_id\":\"\",\"class_sfx\":\"span9\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menuprincipal\",\"moduleclass_sfx\":\"assuntos\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (100,'Central de conteúdos','','',4,'menu-principal',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"central-de-conteudos\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span9\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menuprincipal\",\"moduleclass_sfx\":\"central-conteudos\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (101,'Assuntos','','',1,'menus-rodape',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"assuntos\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span3\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menusrodape\",\"moduleclass_sfx\":\"assuntos\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (102,'Serviços','','',2,'menus-rodape',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"servicos\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span3\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menusrodape\",\"moduleclass_sfx\":\"servicos\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (103,'Redes Sociais','','',3,'menus-rodape',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"midias-sociais\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span3\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menusrodape\",\"moduleclass_sfx\":\"redes-sociais\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (105,'RSS','','',5,'menus-rodape',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"rss\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span3\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menusrodape\",\"moduleclass_sfx\":\"rss\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (106,'Sobre o site','','',6,'menus-rodape',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_menu',1,1,'{\"menutype\":\"sobre-o-site\",\"startLevel\":\"1\",\"endLevel\":\"1\",\"showAllChildren\":\"0\",\"tag_id\":\"\",\"class_sfx\":\"span3\",\"window_open\":\"\",\"layout\":\"padraogoverno01:menusrodape\",\"moduleclass_sfx\":\"sobre\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (107,'Buscar no portal','','',1,'header-meio-direita',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_search',1,1,'{\"label\":\"\",\"width\":\"20\",\"text\":\"\",\"button\":\"\",\"button_pos\":\"right\",\"imagebutton\":\"\",\"button_text\":\"\",\"opensearch\":\"1\",\"opensearch_title\":\"\",\"set_itemid\":\"\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"portal-searchbox\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"itemid\"}',0,'*'),
 (110,'Portal Padrão','','',1,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,1,'{\"htmlcode\":\"<div class=\\\"row-fluid module\\\">                          \\r\\n                                <div class=\\\"outstanding-header\\\">\\r\\n                                    <h2 class=\\\"outstanding-title\\\">Portal Padr\\u00e3o<\\/h2>\\r\\n                                <\\/div>\\r\\n                                <section class=\\\"module-section\\\">\\r\\n                                    <div class=\\\"span4 no-margin\\\">                                   \\r\\n                                        <p class=\\\"subtitle\\\">Identidade Digital de Governo<\\/p>\\r\\n                                        <h1>\\r\\n                                            <a title=\\\"Estrutura re\\u00fane o que h\\u00e1 de mais adequado em solu\\u00e7\\u00f5es digitais de acessibilidade e de divulga\\u00e7\\u00e3o de informa\\u00e7\\u00f5es nos mais variados formatos; conhe\\u00e7a os detalhes deste novo modelo e acesse os manuais de identidade digital, estilo, instala\\u00e7\\u00e3o e gest\\u00e3o de conte\\u00fado\\\" href=\\\"#\\\">Conhe\\u00e7a o novo modelo de plataforma digital<\\/a>\\r\\n                                        <\\/h1>\\r\\n                                        <p>Estrutura re\\u00fane o que h\\u00e1 de mais adequado em solu\\u00e7\\u00f5es digitais de acessibilidade e de divulga\\u00e7\\u00e3o de informa\\u00e7\\u00f5es nos mais variados formatos; conhe\\u00e7a os detalhes deste novo modelo e acesse os manuais de identidade digital, estilo, instala\\u00e7\\u00e3o e gest\\u00e3o de conte\\u00fado<\\/p>                                         \\r\\n                                    <\\/div>\\r\\n                                    <!-- fim .span4 -->\\r\\n                                    <div class=\\\"span8\\\">\\r\\n                                        <object width=\\\"480\\\" height=\\\"246\\\"><param value=\\\"\\/\\/www.youtube.com\\/v\\/BGzfIhIUF68?version=3&amp;hl=pt_BR&amp;rel=0\\\" name=\\\"movie\\\"><param value=\\\"true\\\" name=\\\"allowFullScreen\\\"><param value=\\\"always\\\" name=\\\"allowscriptaccess\\\"><embed width=\\\"480\\\" height=\\\"368\\\" allowfullscreen=\\\"true\\\" allowscriptaccess=\\\"always\\\" type=\\\"application\\/x-shockwave-flash\\\" src=\\\"\\/\\/www.youtube.com\\/v\\/BGzfIhIUF68?version=3&amp;hl=pt_BR&amp;rel=0\\\"><\\/object>\\r\\n                                    <\\/div>\\r\\n                                    <!-- fim .span8 -->\\r\\n                                <\\/section>\\r\\n                            <\\/div>\\r\\n                            <!-- fim .row-fluid -->\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (111,'linha 02','pagina-inicial-container1','',4,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_container',1,0,'{\"posicao\":\"pagina-inicial-container1\",\"moduleclass_sfx\":\"row-fluid\",\"alternative_title\":\"\",\"title_outstanding\":\"1\",\"text_link_title\":\"\",\"url_link_title\":\"\",\"show_footer\":\"0\",\"text_link_footer\":\"\",\"url_link_footer\":\"\",\"disposicao\":\"linhas\",\"auto_divisor\":\"1\",\"title_outstanding_column1\":\"1\",\"text_link_title_column1\":\"\",\"url_link_title_column1\":\"\",\"footer_outstanding_column1\":\"0\",\"text_link_footer_column1\":\"\",\"url_link_footer_column1\":\"\",\"title_outstanding_column2\":\"1\",\"text_link_title_column2\":\"\",\"url_link_title_column2\":\"\",\"footer_outstanding_column2\":\"0\",\"text_link_footer_column2\":\"\",\"url_link_footer_column2\":\"\",\"title_outstanding_column3\":\"1\",\"text_link_title_column3\":\"\",\"url_link_title_column3\":\"\",\"footer_outstanding_column3\":\"0\",\"text_link_footer_column3\":\"\",\"url_link_footer_column3\":\"\",\"container_level1\":\"div\",\"container_level2\":\"div\",\"layout\":\"_:default\",\"cache\":\"1\",\"cache_time\":\"900\",\"numero_limite_colunas\":\"\"}',0,'*'),
 (115,'linha 05','pagina-inicial-container4','',7,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_container',1,1,'{\"posicao\":\"pagina-inicial-container4\",\"moduleclass_sfx\":\"row-fluid\",\"alternative_title\":\"Programas\",\"title_outstanding\":\"1\",\"text_link_title\":\"\",\"url_link_title\":\"\",\"show_footer\":\"0\",\"text_link_footer\":\"\",\"url_link_footer\":\"\",\"disposicao\":\"linhas\",\"auto_divisor\":\"1\",\"title_outstanding_column1\":\"1\",\"text_link_title_column1\":\"\",\"url_link_title_column1\":\"\",\"footer_outstanding_column1\":\"0\",\"text_link_footer_column1\":\"\",\"url_link_footer_column1\":\"\",\"title_outstanding_column2\":\"1\",\"text_link_title_column2\":\"\",\"url_link_title_column2\":\"\",\"footer_outstanding_column2\":\"0\",\"text_link_footer_column2\":\"\",\"url_link_footer_column2\":\"\",\"title_outstanding_column3\":\"1\",\"text_link_title_column3\":\"\",\"url_link_title_column3\":\"\",\"footer_outstanding_column3\":\"0\",\"text_link_footer_column3\":\"\",\"url_link_footer_column3\":\"\",\"container_level1\":\"div\",\"container_level2\":\"div\",\"layout\":\"_:default\",\"cache\":\"1\",\"cache_time\":\"900\",\"numero_limite_colunas\":\"\"}',0,'*'),
 (116,'Programa 1','','',1,'pagina-inicial-container4',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<a href=\\\"#\\\" class=\\\"img-rounded\\\"><img src=\\\"templates\\/padraogoverno01\\/images\\/8309509b-fd4a-4c6c-be30-e8ce75642bcc.jpeg\\\" alt=\\\"imagem decorativa\\\"><\\/a>\\r\\n<h2><a href=\\\"#\\\">Texto 1: T\\u00edtulo da manchete em at\\u00e9 55 caracteres com espa\\u00e7o<\\/a><\\/h2>\\r\\n<p>Subt\\u00edtulo do texto 1. Escrever texto do subt\\u00edtulo da chamada em at\\u00e9 130 caracteres com espa\\u00e7o<\\/p>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"no-margin\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (117,'Programa 2','','',2,'pagina-inicial-container4',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<a href=\\\"#\\\" class=\\\"img-rounded\\\"><img src=\\\"templates\\/padraogoverno01\\/images\\/4ae7baa4-f707-4b34-a01e-9c5fe45f00b9.jpeg\\\" alt=\\\"imagem decorativa\\\"><\\/a>\\r\\n<h2><a href=\\\"#\\\">Texto 2: T\\u00edtulo da manchete em at\\u00e9 55 caracteres com espa\\u00e7o<\\/a><\\/h2>\\r\\n<p>Subt\\u00edtulo do texto 2. Escrever texto do subt\\u00edtulo da chamada em at\\u00e9 130 caracteres com espa\\u00e7o<\\/p>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (118,'Programa 3','','',3,'pagina-inicial-container4',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<a href=\\\"#\\\" class=\\\"img-rounded\\\"><img src=\\\"templates\\/padraogoverno01\\/images\\/c11b62ec-4a89-4707-a39e-9413b20cf235.jpeg\\\" alt=\\\"imagem decorativa\\\"><\\/a>\\r\\n<h2><a href=\\\"#\\\">Texto 3: T\\u00edtulo da manchete em at\\u00e9 55 caracteres com espa\\u00e7o<\\/a><\\/h2>\\r\\n<p>Subt\\u00edtulo do texto 3. Escrever texto do subt\\u00edtulo da chamada em at\\u00e9 130 caracteres com espa\\u00e7o<\\/p>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (119,'linha 04','pagina-inicial-container3','',6,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_container',1,0,'{\"posicao\":\"pagina-inicial-container3\",\"title_outstanding\":\"0\",\"title_outstanding_level2\":\"0\",\"moduleclass_sfx\":\"row-fluid\",\"auto_divisor\":\"0\",\"show_footer\":\"0\",\"footer_outstanding\":\"0\",\"texto_link_title\":\"\",\"url_link_title\":\"\",\"texto_link_footer\":\"\",\"link_footer\":\"\",\"container_level1\":\"div\",\"container_level2\":\"div\",\"layout\":\"_:default\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
 (120,'Twitter','','',1,'pagina-inicial-container3',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<div class=\\\"header tabs row-fluid\\\">\\r\\n<h2 class=\\\"title active span6\\\">Twitter<\\/h2>\\r\\n<h2 class=\\\"title span6 hide\\\">Facebook<\\/h2>\\r\\n<\\/div>\\r\\n<div class=\\\"pane\\\">\\r\\n<div class=\\\"twitter-content\\\">\\r\\n    <a height=\\\"350\\\" data-widget-id=\\\"388035310119964672\\\" href=\\\"https:\\/\\/twitter.com\\/Portal%20Brasil\\\" class=\\\"twitter-timeline\\\"><br \\/>Tweets do Portal Brasil<\\/a>\\r\\n    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=\\/^http:\\/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\\\":\\/\\/platform.twitter.com\\/widgets.js\\\";fjs.parentNode.insertBefore(js,fjs);}}(document,\\\"script\\\",\\\"twitter-wjs\\\");<\\/script><noscript>\\r\\n        <div class=\\\"error\\\">\\r\\n          <p>Javascript desativado.<\\/p>\\r\\n          <p><a href=\\\"https:\\/\\/twitter.com\\/portalbrasil\\\">Micro-blog Twitter do Portal Brasil<\\/a><\\/p>\\r\\n        <\\/div>\\r\\n    <\\/noscript>\\r\\n<\\/div>                                     \\r\\n<\\/div>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"span4 module-twitter-facebook\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (121,'Galeria de imagens','','',2,'pagina-inicial-container3',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<div class=\\\"header\\\">\\r\\n<h2 class=\\\"title\\\">Galeria de imagens<\\/h2>\\r\\n<\\/div>\\r\\n<div class=\\\"gallery-pane\\\">\\r\\n<!-- inicio galeria -->\\r\\n<div class=\\\"carousel slide\\\" id=\\\"gallery-carousel\\\">\\r\\n  <div class=\\\"carousel-inner\\\">\\r\\n    <div class=\\\"item\\\">\\r\\n      <img alt=\\\"Foto de paisagem de montanhas\\\" src=\\\"templates\\/padraogoverno01\\/images\\/image.jpg\\\">\\r\\n      \\r\\n      <div class=\\\"galleria-info\\\">\\r\\n        <div class=\\\"galleria-info-text\\\">\\r\\n            <div class=\\\"galleria-info-title\\\">\\r\\n                <h3><a href=\\\"http:\\/\\/portalpadrao.plone.org.br\\/conteudos-de-marcacao\\/imagem-1-titulo-com-ate-45-caracteres\\/view\\\">Imagem 1: t\\u00edtulo com at\\u00e9 45 caracteres<\\/a><\\/h3>\\r\\n            <\\/div>\\r\\n            <div class=\\\"galleria-info-description\\\">Espa\\u00e7o para incluir a legenda\\/descri\\u00e7\\u00e3o da imagem<\\/div>\\r\\n            <div data-index=\\\"0\\\" class=\\\"rights\\\">Nome do autor da imagem<\\/div>\\r\\n        <\\/div>\\r\\n      <\\/div>\\r\\n\\r\\n    <\\/div>\\r\\n    <div class=\\\"item active\\\">\\r\\n      <img alt=\\\"Foto de flor delicada cercada de folhas verdes\\\" src=\\\"templates\\/padraogoverno01\\/images\\/image2.jpg\\\">\\r\\n      \\r\\n      <div class=\\\"galleria-info\\\">\\r\\n        <div class=\\\"galleria-info-text\\\">\\r\\n            <div class=\\\"galleria-info-title\\\">\\r\\n                <h3><a href=\\\"http:\\/\\/portalpadrao.plone.org.br\\/conteudos-de-marcacao\\/imagem-1-titulo-com-ate-45-caracteres\\/view\\\">Imagem 2: t\\u00edtulo com at\\u00e9 45 caracteres<\\/a><\\/h3>\\r\\n            <\\/div>\\r\\n            <div class=\\\"galleria-info-description\\\">Espa\\u00e7o para incluir a legenda\\/descri\\u00e7\\u00e3o da imagem<\\/div>\\r\\n            <div data-index=\\\"0\\\" class=\\\"rights\\\">Nome do autor da imagem<\\/div>\\r\\n        <\\/div>\\r\\n      <\\/div>\\r\\n      \\r\\n    <\\/div>\\r\\n    <div class=\\\"item\\\">\\r\\n      <img alt=\\\"Foto de 3 pinguins\\\" src=\\\"templates\\/padraogoverno01\\/images\\/image3.jpg\\\">\\r\\n      \\r\\n      <div class=\\\"galleria-info\\\">\\r\\n        <div class=\\\"galleria-info-text\\\">\\r\\n            <div class=\\\"galleria-info-title\\\">\\r\\n                <h3><a href=\\\"http:\\/\\/portalpadrao.plone.org.br\\/conteudos-de-marcacao\\/imagem-1-titulo-com-ate-45-caracteres\\/view\\\">Imagem 3: t\\u00edtulo com at\\u00e9 45 caracteres<\\/a><\\/h3>\\r\\n            <\\/div>\\r\\n            <div class=\\\"galleria-info-description\\\">Espa\\u00e7o para incluir a legenda\\/descri\\u00e7\\u00e3o da imagem<\\/div>\\r\\n            <div data-index=\\\"0\\\" class=\\\"rights\\\">Nome do autor da imagem<\\/div>\\r\\n        <\\/div>\\r\\n      <\\/div>\\r\\n      \\r\\n    <\\/div>\\r\\n    <div class=\\\"item\\\">\\r\\n      <img alt=\\\"Foto de v\\u00e1rias flores\\\" src=\\\"templates\\/padraogoverno01\\/images\\/image4.jpg\\\">\\r\\n      \\r\\n      <div class=\\\"galleria-info\\\">\\r\\n        <div class=\\\"galleria-info-text\\\">\\r\\n            <div class=\\\"galleria-info-title\\\">\\r\\n                <h3><a href=\\\"http:\\/\\/portalpadrao.plone.org.br\\/conteudos-de-marcacao\\/imagem-1-titulo-com-ate-45-caracteres\\/view\\\">Imagem 4: t\\u00edtulo com at\\u00e9 45 caracteres<\\/a><\\/h3>\\r\\n            <\\/div>\\r\\n            <div class=\\\"galleria-info-description\\\">Espa\\u00e7o para incluir a legenda\\/descri\\u00e7\\u00e3o da imagem<\\/div>\\r\\n            <div data-index=\\\"0\\\" class=\\\"rights\\\">Nome do autor da imagem<\\/div>\\r\\n        <\\/div>\\r\\n      <\\/div>\\r\\n      \\r\\n    <\\/div>\\r\\n  <\\/div>\\r\\n  <a data-slide=\\\"prev\\\" href=\\\"#gallery-carousel\\\" class=\\\"left carousel-control\\\"><i class=\\\"icon-angle-left\\\"><\\/i><span class=\\\"hide\\\">Mover foto esquerda<\\/span><\\/a>\\r\\n  <!-- separador para fins de acessibilidade <--><span class=\\\"hide\\\">&nbsp;<\\/span><\\/--><!-- fim separador para fins de acessibilidade -->\\r\\n  <a data-slide=\\\"next\\\" href=\\\"#gallery-carousel\\\" class=\\\"right carousel-control\\\"><i class=\\\"icon-angle-right\\\"><\\/i><span class=\\\"hide\\\">Mover foto esquerda<\\/span><\\/a>\\r\\n<\\/div>\\r\\n<!-- fim galeria -->\\r\\n<\\/div>\\r\\n<div class=\\\"footer\\\">\\r\\n<a href=\\\"#\\\" class=\\\"outstanding-link\\\"><span class=\\\"text\\\">Acesse a lista completa<\\/span>\\r\\n    <span class=\\\"icon-box\\\">                                          \\r\\n      <i class=\\\"icon-angle-right icon-light\\\"><span class=\\\"hide\\\">&nbsp;<\\/span><\\/i>\\r\\n    <\\/span>\\r\\n<\\/a>\\r\\n<\\/div>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"span8 module-box-01 module-box-01-top-adjust\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (122,'Áudio do órgão','','',1,'pagina-inicial-container2',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_htmlcustom',1,1,'{\"htmlcode\":\"<img src=\\\"templates\\/padraogoverno01\\/images\\/fake-player-audio.png\\\" alt=\\\"Player ainda ser\\u00e1 desenvolvido ou adaptado.\\\">\\r\\n<p>Espa\\u00e7o para inserir a legenda do \\u00e1udio 1<\\/p>\\r\\n<div class=\\\"outstanding-footer\\\">\\r\\n\\t<a href=\\\"#\\\" class=\\\"outstanding-link\\\">\\r\\n\\t    <span class=\\\"text\\\">Mais \\u00e1udios<\\/span>\\r\\n\\t    <span class=\\\"icon-box\\\">                                          \\r\\n\\t      <i class=\\\"icon-angle-right icon-light\\\"><span class=\\\"hide\\\">&nbsp;<\\/span><\\/i>\\r\\n\\t    <\\/span>\\r\\n\\t<\\/a>\\r\\n<\\/div>\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"span4\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (123,'linha 03','pagina-inicial-container2','',5,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_container',1,0,'{\"posicao\":\"pagina-inicial-container2\",\"moduleclass_sfx\":\"row-fluid\",\"alternative_title\":\"\",\"title_outstanding\":\"0\",\"text_link_title\":\"\",\"url_link_title\":\"\",\"show_footer\":\"0\",\"text_link_footer\":\"\",\"url_link_footer\":\"\",\"auto_divisor\":\"0\",\"title_outstanding_column1\":\"1\",\"text_link_title_column1\":\"\",\"url_link_title_column1\":\"\",\"footer_outstanding_column1\":\"0\",\"text_link_footer_column1\":\"\",\"url_link_footer_column1\":\"\",\"title_outstanding_column2\":\"0\",\"text_link_title_column2\":\"\",\"url_link_title_column2\":\"\",\"footer_outstanding_column2\":\"0\",\"text_link_footer_column2\":\"\",\"url_link_footer_column2\":\"\",\"title_outstanding_column3\":\"1\",\"text_link_title_column3\":\"\",\"url_link_title_column3\":\"\",\"footer_outstanding_column3\":\"0\",\"text_link_footer_column3\":\"\",\"url_link_footer_column3\":\"\",\"container_level1\":\"div\",\"container_level2\":\"div\",\"layout\":\"_:default\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
 (124,'Últimas notícias','','',3,'pagina-inicial-container2',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,0,'{\"htmlcode\":\"<div class=\\\"header\\\">\\r\\n    <h2 class=\\\"title\\\">\\u00daltimas not\\u00edcias<\\/h2>\\r\\n<\\/div>\\r\\n<ul class=\\\"lista row-fluid\\\">\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 1. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 2. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 2. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 3. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 3. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 4. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 4. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 5. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 5. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 6. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 6. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 7. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 7. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<li class=\\\"span4\\\">\\r\\n    <h3><a href=\\\"#\\\" title=\\\"Subt\\u00edtulo do texto 8. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres\\\">Subt\\u00edtulo do texto 8. Se em uma linha, usar 90 caracteres. Se em duas linhas usar 190 caracteres<\\/a><\\/h3>\\r\\n<\\/li>\\r\\n<\\/ul>\\r\\n<div class=\\\"footer\\\">\\r\\n    <a href=\\\"#\\\" class=\\\"link\\\">Acesse a lista completa<\\/a>\\r\\n<\\/div> \",\"layout\":\"_:default\",\"moduleclass_sfx\":\"span8 module-box-01\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (126,'mod_barradogoverno','','',1,'barra-do-governo',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_barradogoverno',1,0,'{\"layout\":\"_:default\",\"anexar_js_2014\":\"0\",\"endereco_js_2014\":\"http:\\/\\/barra.brasil.gov.br\\/barra.js?cor=verde\",\"mensagem_ie6_2014\":\"Seu navegador \\u00e9 incompat\\u00edvel com os novos padr\\u00f5es de tecnologia e por isso voc\\u00ea n\\u00e3o pode visualizar a nova barra do Governo Federal. Atualize ou troque seu navegador.\",\"correcoes_ie8_2014\":\"show_css\",\"link_css_ie8_2014\":\"{URL_SITE}\\/modules\\/mod_barradogoverno\\/assets\\/2014\\/css\\/ie8.css\",\"anexar_css_2012\":\"1\",\"cor_2012\":\"\",\"acesso_a_informacao_2012\":\"1\",\"largura_barra_2012\":\"970\",\"alinhamento_barra_2012\":\"\",\"link_acesso_a_informacao_2012\":\"http:\\/\\/www.acessoainformacao.gov.br\\/acessoainformacaogov\\/\",\"link_portal_brasil_2012\":\"http:\\/\\/www.brasil.gov.br\\/\",\"target_links_2012\":\"_blank\",\"head_manual\":\"\",\"html_manual\":\"\",\"anexar_head\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}',0,'*'),
 (127,'Portal Padrão ','','',2,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_htmlcustom',1,1,'{\"htmlcode\":\"                                    <div class=\\\"span4 no-margin\\\">                                   \\r\\n                                        <p class=\\\"subtitle\\\">Identidade Digital de Governo<\\/p>\\r\\n                                        <h1>\\r\\n                                            <a title=\\\"Estrutura re\\u00fane o que h\\u00e1 de mais adequado em solu\\u00e7\\u00f5es digitais de acessibilidade e de divulga\\u00e7\\u00e3o de informa\\u00e7\\u00f5es nos mais variados formatos; conhe\\u00e7a os detalhes deste novo modelo e acesse os manuais de identidade digital, estilo, instala\\u00e7\\u00e3o e gest\\u00e3o de conte\\u00fado\\\" href=\\\"#\\\">Conhe\\u00e7a o novo modelo de plataforma digital<\\/a>\\r\\n                                        <\\/h1>\\r\\n                                        <p>Estrutura re\\u00fane o que h\\u00e1 de mais adequado em solu\\u00e7\\u00f5es digitais de acessibilidade e de divulga\\u00e7\\u00e3o de informa\\u00e7\\u00f5es nos mais variados formatos; conhe\\u00e7a os detalhes deste novo modelo e acesse os manuais de identidade digital, estilo, instala\\u00e7\\u00e3o e gest\\u00e3o de conte\\u00fado<\\/p>                                         \\r\\n                                    <\\/div>\\r\\n                                    <!-- fim .span4 -->\\r\\n                                    <div class=\\\"span8\\\">\\r\\n                                        <object width=\\\"480\\\" height=\\\"246\\\"><param value=\\\"\\/\\/www.youtube.com\\/v\\/BGzfIhIUF68?version=3&amp;hl=pt_BR&amp;rel=0\\\" name=\\\"movie\\\"><param value=\\\"true\\\" name=\\\"allowFullScreen\\\"><param value=\\\"always\\\" name=\\\"allowscriptaccess\\\"><embed width=\\\"480\\\" height=\\\"368\\\" allowfullscreen=\\\"true\\\" allowscriptaccess=\\\"always\\\" type=\\\"application\\/x-shockwave-flash\\\" src=\\\"\\/\\/www.youtube.com\\/v\\/BGzfIhIUF68?version=3&amp;hl=pt_BR&amp;rel=0\\\"><\\/object>\\r\\n                                    <\\/div>\\r\\n\",\"layout\":\"_:default\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}',0,'*'),
 (128,'Portal Padrão','','',3,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:manchete-texto-lateral\",\"variacao\":\"0\",\"modelo\":\"article_content\",\"quantidade\":\"1\",\"id_article_unico\":\"26\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"catid\":[\"2\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"Conhe\\u00e7a o novo modelo de plataforma digital\",\"desc_item1\":\"\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"8\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"header_tag\":\"h1\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\",\"MOD_CHAMADA_FIELD_LIMITE_ITENS\":\"\"}',0,'*'),
 (130,'Planejamento','','',3,'pagina-inicial-container1',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:chamada-secundaria\",\"variacao\":\"1\",\"modelo\":\"manual\",\"quantidade\":\"1\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"Conhe\\u00e7a as etapas para o desenvolvimento de portais\",\"desc_item1\":\"<p>Apresenta\\u00e7\\u00e3o mostra como desenvolver um site de acordo com a Identidade digital do governo federal<\\/p>\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"{site}\\/images\\/manuais\\/apresentacao_identidade_digital_governo_federal_20131102.pdf\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"subitem_class\":\"\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\",\"module_tag\":\"div\",\"bootstrap_size\":\"0\",\"header_class\":\"\",\"style\":\"0\"}',0,'*'),
 (131,'Lei de acesso à informação','','',4,'pagina-inicial-container1',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:chamada-secundaria\",\"variacao\":\"2\",\"modelo\":\"article_content\",\"quantidade\":\"1\",\"id_article_unico\":\"34\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"0\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"<p>\\u00d3rg\\u00e3os do governo federal devem disponibilizar em seu site um menu especificado pela legisla\\u00e7\\u00e3o<\\/p>\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\"}',0,'*'),
 (132,'Agenda','','',7,'pagina-inicial-container1',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:chamada-secundaria\",\"variacao\":\"0\",\"modelo\":\"manual\",\"quantidade\":\"1\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"<p>O m\\u00f3dulo agenda, que ocupar\\u00e1 este espa\\u00e7o est\\u00e1 em desenvolvimento e ficar\\u00e1 dispon\\u00edvel at\\u00e9 o fim do m\\u00eas de outubro.<\\/p>\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\"}',0,'*'),
 (133,'linha 05 (2)','pagina-inicial-container4','',8,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',-2,'mod_container',1,1,'{\"posicao\":\"pagina-inicial-container4\",\"moduleclass_sfx\":\"row-fluid\",\"alternative_title\":\"Programas\",\"title_outstanding\":\"1\",\"text_link_title\":\"\",\"url_link_title\":\"\",\"show_footer\":\"0\",\"text_link_footer\":\"\",\"url_link_footer\":\"\",\"disposicao\":\"linhas\",\"auto_divisor\":\"1\",\"title_outstanding_column1\":\"1\",\"text_link_title_column1\":\"\",\"url_link_title_column1\":\"\",\"footer_outstanding_column1\":\"0\",\"text_link_footer_column1\":\"\",\"url_link_footer_column1\":\"\",\"title_outstanding_column2\":\"1\",\"text_link_title_column2\":\"\",\"url_link_title_column2\":\"\",\"footer_outstanding_column2\":\"0\",\"text_link_footer_column2\":\"\",\"url_link_footer_column2\":\"\",\"title_outstanding_column3\":\"1\",\"text_link_title_column3\":\"\",\"url_link_title_column3\":\"\",\"footer_outstanding_column3\":\"0\",\"text_link_footer_column3\":\"\",\"url_link_footer_column3\":\"\",\"container_level1\":\"div\",\"container_level2\":\"div\",\"layout\":\"_:default\",\"cache\":\"1\",\"cache_time\":\"900\",\"numero_limite_colunas\":\"\"}',0,'*'),
 (134,'linha 05','','',9,'pagina-inicial',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:chamada-secundaria\",\"variacao\":\"0\",\"modelo\":\"article_content\",\"quantidade\":\"4\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"Programas\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"catid\":[\"35\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"ordering\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\"}',0,'*'),
 (135,'Últimas notícias','','',4,'pagina-inicial-container2',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:listagem-box01\",\"variacao\":\"0\",\"modelo\":\"article_content\",\"quantidade\":\"8\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"121\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"Acesse a lista completa\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"0\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"catid\":[\"17\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"1\",\"limite_caractere\":\"90\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"subitem_class\":\"span4\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"module-box-01 span8\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\"}',0,'*'),
 (136,'Galeria de imagens','','',1,'pagina-inicial-container3',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',1,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:listagem-box01-galeria\",\"variacao\":\"0\",\"modelo\":\"article_content\",\"quantidade\":\"4\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"178\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"Acesse a lista completa\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"1\",\"buscar_cat_tag\":\"1\",\"catid\":[\"37\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"subitem_class\":\"\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"module-box-01 span8\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\",\"module_tag\":\"div\",\"bootstrap_size\":\"0\",\"header_class\":\"\",\"style\":\"0\"}',0,'*'),
 (137,'Áudio do órgão','','',2,'pagina-inicial-container2',0,'0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'mod_chamadas',1,1,'{\"layout\":\"padraogoverno01:listagem-audio\",\"variacao\":\"0\",\"modelo\":\"article_content\",\"quantidade\":\"4\",\"id_article_unico\":\"\",\"id_item_unico\":0,\"titulo_alternativo\":\"\",\"link_saiba_mais\":\"\",\"link_saiba_mais_menu\":\"\",\"link_saiba_mais_article\":\"\",\"texto_saiba_mais\":\"Mais \\u00e1udios\",\"habilitar_mensagem_vazia\":\"0\",\"mensagem_vazia\":\"\",\"chapeu\":\"cont.xreference\",\"destaque\":\"0\",\"somente_imagem\":\"1\",\"buscar_cat_tag\":\"1\",\"tags\":[\"\"],\"catid\":[\"37\"],\"visualizar_filho\":\"0\",\"nivel\":\"10\",\"ordem\":\"title\",\"ordem_direction\":\"ASC\",\"chapeu_item1\":\"\",\"title_item1\":\"\",\"desc_item1\":\"\",\"image_item1\":\"\",\"image_item1_align\":\"\",\"image_item1_alt\":\"\",\"url_simple_item1\":\"\",\"url_menu_item1\":\"\",\"url_article_item1\":\"\",\"ordering_item1\":\"1\",\"chapeu_item2\":\"\",\"title_item2\":\"\",\"desc_item2\":\"\",\"image_item2\":\"\",\"image_item2_align\":\"\",\"image_item2_alt\":\"\",\"url_simple_item2\":\"\",\"url_menu_item2\":\"\",\"url_article_item2\":\"\",\"ordering_item2\":\"2\",\"chapeu_item3\":\"\",\"title_item3\":\"\",\"desc_item3\":\"\",\"image_item3\":\"\",\"image_item3_align\":\"\",\"image_item3_alt\":\"\",\"url_simple_item3\":\"\",\"url_menu_item3\":\"\",\"url_article_item3\":\"\",\"ordering_item3\":\"3\",\"limitar_caractere\":\"0\",\"limite_caractere\":\"\",\"exibir_imagem\":\"1\",\"exibir_introtext\":\"1\",\"exibir_title\":\"1\",\"subitem_class\":\"\",\"header_tag\":\"h3\",\"moduleclass_sfx\":\"span4\",\"owncache\":\"1\",\"cache_time\":\"900\",\"limite_campos_preenchimento_manual\":\"\"}',0,'*');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_modules` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_modules_menu`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_modules_menu`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_modules_menu`
--

/*!40000 ALTER TABLE `pmgov2013_modules_menu` DISABLE KEYS */;
LOCK TABLES `pmgov2013_modules_menu` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_modules_menu` VALUES  (1,0),
 (2,0),
 (3,0),
 (4,0),
 (6,0),
 (7,0),
 (8,0),
 (9,0),
 (10,0),
 (12,0),
 (13,0),
 (14,0),
 (15,0),
 (16,0),
 (17,0),
 (79,0),
 (86,0),
 (87,0),
 (88,101),
 (89,101),
 (92,101),
 (93,101),
 (94,0),
 (95,0),
 (96,0),
 (97,0),
 (99,0),
 (100,0),
 (101,0),
 (102,0),
 (103,0),
 (105,0),
 (106,0),
 (107,0),
 (110,0),
 (111,0),
 (115,0),
 (116,0),
 (117,0),
 (118,0),
 (119,0),
 (120,0),
 (121,0),
 (122,0),
 (123,0),
 (124,0),
 (126,0),
 (127,0),
 (128,0),
 (130,0),
 (131,0),
 (132,0),
 (133,0),
 (134,0),
 (135,0),
 (136,0),
 (137,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_modules_menu` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_newsfeeds`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_newsfeeds`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(10) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(10) unsigned NOT NULL DEFAULT '3600',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_newsfeeds`
--

/*!40000 ALTER TABLE `pmgov2013_newsfeeds` DISABLE KEYS */;
LOCK TABLES `pmgov2013_newsfeeds` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_newsfeeds` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_overrider`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_overrider`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_overrider` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `constant` varchar(255) NOT NULL,
  `string` text NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2147 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_overrider`
--

/*!40000 ALTER TABLE `pmgov2013_overrider` DISABLE KEYS */;
LOCK TABLES `pmgov2013_overrider` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (1,'COM_CONTACT_ADDRESS','Endereço','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (2,'COM_CONTACT_ARTICLES_HEADING','Artigos do Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (3,'COM_CONTACT_CAPTCHA_LABEL','Captcha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (4,'COM_CONTACT_CAPTCHA_DESC','Digite na caixa de texto, o que deseja ver na imagem.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (5,'COM_CONTACT_CAT_NUM','# de Contatos :','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (6,'COM_CONTACT_CONTACT_EMAIL_A_COPY_DESC','Envia uma cópia desta mensagem para o endereço fornecido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (7,'COM_CONTACT_CONTACT_EMAIL_A_COPY_LABEL','Me enviar uma cópia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (8,'COM_CONTACT_CONTACT_EMAIL_NAME_DESC','Seu nome','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (9,'COM_CONTACT_CONTACT_EMAIL_NAME_LABEL','Nome','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (10,'COM_CONTACT_CONTACT_ENTER_MESSAGE_DESC','Informe sua mensagem aqui.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (11,'COM_CONTACT_CONTACT_ENTER_MESSAGE_LABEL','Mensagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (12,'COM_CONTACT_CONTACT_ENTER_VALID_EMAIL','Por Favor, informe um e-mail válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (13,'COM_CONTACT_CONTACT_MESSAGE_SUBJECT_DESC','Informe o assunto da sua mensagem aqui.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (14,'COM_CONTACT_CONTACT_MESSAGE_SUBJECT_LABEL','Assunto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (15,'COM_CONTACT_CONTACT_SEND','Enviar E-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (16,'COM_CONTACT_COPYSUBJECT_OF','Cópia de: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (17,'COM_CONTACT_COPYTEXT_OF','Esta é uma cópia da seguinte mensagem que você enviou para %s via %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (18,'COM_CONTACT_COUNT','Número de contato:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (19,'COM_CONTACT_COUNTRY','País','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (20,'COM_CONTACT_DEFAULT_PAGE_TITLE','Contatos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (21,'COM_CONTACT_DETAILS','Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (22,'COM_CONTACT_DOWNLOAD_INFORMATION_AS','Baixar Informações como:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (23,'COM_CONTACT_EMAIL_BANNEDTEXT','O %s do seu e-mail contém texto proibido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (24,'COM_CONTACT_EMAIL_DESC','E-mail para contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (25,'COM_CONTACT_EMAIL_FORM','Formulário de Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (26,'COM_CONTACT_EMAIL_LABEL','E-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (27,'COM_CONTACT_EMAIL_THANKS','Obrigado pelo E-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (28,'COM_CONTACT_ENQUIRY_TEXT','Este é um e-mail de consulta via %s enviado por:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (29,'COM_CONTACT_ERROR_CONTACT_NOT_FOUND','Contato não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (30,'COM_CONTACT_FAX','Fax','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (31,'COM_CONTACT_FORM_LABEL','Enviar uma mensagem. Todos os campos com * são obrigatórios.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (32,'COM_CONTACT_FORM_NC','Por favor, certifique-se que o formulário está completo e válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (33,'COM_CONTACT_IMAGE_DETAILS','Imagem do Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (34,'COM_CONTACT_LINKS','Links','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (35,'COM_CONTACT_MAILENQUIRY','%s Esclarecimentos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (36,'COM_CONTACT_MOBILE','Celular','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (37,'COM_CONTACT_NO_ARTICLES','Nenhum artigo a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (38,'COM_CONTACT_NO_CONTACTS','Não há contatos a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (39,'COM_CONTACT_NOT_MORE_THAN_ONE_EMAIL_ADDRESS','Você não pode inserir mais de um endereço de e-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (40,'COM_CONTACT_OPTIONAL','(opcional)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (41,'COM_CONTACT_OTHER_INFORMATION','Outras Informações','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (42,'COM_CONTACT_POSITION','Posição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (43,'COM_CONTACT_PROFILE','Perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (44,'COM_CONTACT_PROFILE_HEADING','Perfil do Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (45,'COM_CONTACT_SELECT_CONTACT','Selecione um contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (46,'COM_CONTACT_STATE','Estado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (47,'COM_CONTACT_SUBURB','Cidade','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (48,'COM_CONTACT_TELEPHONE','Telefone','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (49,'COM_CONTACT_VCARD','vCard','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_contact.ini'),
 (50,'COM_CONTENT_ACCESS_DELETE_DESC','Nova configuração para <strong>ações de exclusão</strong> neste artigo e calcula a configuração baseado na categoria pai ou nas permissões do grupo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (51,'COM_CONTENT_ACCESS_EDITSTATE_DESC','Nova configuração parar <strong>ações de edição de estado</strong> neste artigo e calcula a configuração baseado na categoria pai ou nas permissões do grupo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (52,'COM_CONTENT_ACCESS_EDIT_DESC','Nova configuração para <strong>ações de edição</strong> neste artigo e calcula a configuração baseado na categoria pai ou nas permissões do grupo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (53,'COM_CONTENT_ARTICLE_HITS','Acessos: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (54,'COM_CONTENT_ARTICLE_INFO','Detalhes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (55,'COM_CONTENT_ARTICLE_VOTE_SUCCESS','Obrigado por avaliar este Artigo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (56,'COM_CONTENT_ARTICLE_VOTE_FAILURE','Você já avaliou este Artigo hoje!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (57,'COM_CONTENT_AUTHOR_FILTER_LABEL','Filtro por Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (58,'COM_CONTENT_CATEGORY','Categoria: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (59,'COM_CONTENT_CHECKED_OUT_BY','Verificado por %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (60,'COM_CONTENT_CREATE_ARTICLE','Enviar um novo Artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (61,'COM_CONTENT_CREATED_DATE','Data de criação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (62,'COM_CONTENT_CREATED_DATE_ON','Criado em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (63,'COM_CONTENT_EDIT_ITEM','Editar Artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (64,'COM_CONTENT_ERROR_ARTICLE_NOT_FOUND','Artigo não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (65,'COM_CONTENT_ERROR_LOGIN_TO_VIEW_ARTICLE','Por favor se autentique para visualizar o artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (66,'COM_CONTENT_ERROR_CATEGORY_NOT_FOUND','Categoria não encontrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (67,'COM_CONTENT_ERROR_PARENT_CATEGORY_NOT_FOUND','Categoria pai não encontrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (68,'COM_CONTENT_FEED_READMORE','Leia Mais...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (69,'COM_CONTENT_FILTER_SEARCH_DESC','Buscar pelo Título ou Apelido. Adicione \'id:\' (Sem Aspas) no começo para buscar Artigos pelo seu id.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (70,'COM_CONTENT_FORM_EDIT_ARTICLE','Editar um artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (71,'COM_CONTENT_HEADING_TITLE','Título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (72,'COM_CONTENT_HITS_FILTER_LABEL','Filtro por acesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (73,'COM_CONTENT_INTROTEXT','O Artigo deve conter algum conteúdo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (74,'COM_CONTENT_INVALID_RATING','Avaliação do artigo: avaliação inválida: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (75,'COM_CONTENT_LAST_UPDATED','Última atualização em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (76,'COM_CONTENT_METADATA','Metadados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (77,'COM_CONTENT_MODIFIED_DATE','Data de modificação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (78,'COM_CONTENT_MONTH','Mês','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (79,'COM_CONTENT_MORE_ARTICLES','Mais artigos...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (80,'COM_CONTENT_NEW_ARTICLE','Novo Artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (81,'COM_CONTENT_NO_ARTICLES','Não há artigos nesta categoria. Se há subcategorias mostradas nesta página, elas podem conter artigos. ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (82,'COM_CONTENT_NUM_ITEMS','Total de artigos:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (83,'COM_CONTENT_ON_NEW_CONTENT','Um novo artigo foi enviado por \'%1$s\' intitulado \'%2$s\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (84,'COM_CONTENT_ORDERING','Ordenando:<br/>Novos artigos por padrão na primeira posição na Categoria. A ordenação pode ser alterada na administração.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (85,'COM_CONTENT_PAGEBREAK_DOC_TITLE','Quebra de Página','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (86,'COM_CONTENT_PAGEBREAK_INSERT_BUTTON','Inserir Quebra de Página','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (87,'COM_CONTENT_PAGEBREAK_TITLE','Título da Página:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (88,'COM_CONTENT_PAGEBREAK_TOC','Título da Tabela de Artigos:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (89,'COM_CONTENT_PARENT','Categoria pai: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (90,'COM_CONTENT_PUBLISHED_DATE','Data de publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (91,'COM_CONTENT_PUBLISHED_DATE_ON','Publicado em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (92,'COM_CONTENT_PUBLISHING','Publicando','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (93,'COM_CONTENT_READ_MORE','Leia mais:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (94,'COM_CONTENT_READ_MORE_TITLE','Leia mais...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (95,'COM_CONTENT_REGISTER_TO_READ_MORE','Registre-se para ler mais...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (96,'COM_CONTENT_SAVE_SUCCESS','Artigo salvo com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (97,'COM_CONTENT_SUBMIT_SAVE_SUCCESS','Artigo enviado com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (98,'COM_CONTENT_TITLE_FILTER_LABEL','Filtro por título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (99,'COM_CONTENT_WRITTEN_BY','Escrito por %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (100,'COM_CONTENT_FIELD_FULL_DESC','Imagem para mostrar um artigo simples','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (101,'COM_CONTENT_FIELD_FULL_LABEL','Imagem do artigo completo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (102,'COM_CONTENT_FIELD_IMAGE_DESC','A imagem a ser mostrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (103,'COM_CONTENT_FIELD_IMAGE_ALT_DESC','Texto alternativo utilizado para visitantes sem acesso a imagens. Será substituído pelo texto do subtítulo se o mesmo estiver presente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (104,'COM_CONTENT_FIELD_IMAGE_ALT_LABEL','Texto alternativo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (105,'COM_CONTENT_FIELD_IMAGE_CAPTION_DESC','Legenda anexa a imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (106,'COM_CONTENT_FIELD_IMAGE_CAPTION_LABEL','Subtítulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (107,'COM_CONTENT_FIELD_INTRO_DESC','Imagem para a introdução de texto com leiaute como de blogs e destacados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (108,'COM_CONTENT_FIELD_INTRO_LABEL','Imagem de introdução','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (109,'COM_CONTENT_FIELD_URLC_LABEL','Atalho C','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (110,'COM_CONTENT_FIELD_URL_DESC','Atalho para mostrar. Precisa ser uma URL completa.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (111,'COM_CONTENT_FIELD_URLA_LABEL','Atalho A','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (112,'COM_CONTENT_FIELD_URLA_LINK_TEXT_LABEL','Texto do atalho A','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (113,'COM_CONTENT_FIELD_URLB_LABEL','Atalho B','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (114,'COM_CONTENT_FIELD_URL_LINK_TEXT_DESC','O texto do atalho a ser mostrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (115,'COM_CONTENT_FIELD_URLB_LINK_TEXT_LABEL','Texto do atalho B','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (116,'COM_CONTENT_FIELD_URLC_LINK_TEXT_LABEL','Texto do atalho C','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (117,'COM_CONTENT_FLOAT_DESC','Controles de localização da imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (118,'COM_CONTENT_FLOAT_LABEL','Imagem Flutuante','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (119,'COM_CONTENT_FLOAT_INTRO_LABEL','Imagem flutuante de introdução','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (120,'COM_CONTENT_FLOAT_FULLTEXT_LABEL','Texto completo da imagem flutuante','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (121,'COM_CONTENT_LEFT','Esquerda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (122,'COM_CONTENT_RIGHT','Direita','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (123,'COM_CONTENT_FIELD_URL_LINK_TEXT_LABEL','Texto do atalho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (124,'COM_CONTENT_IMAGES_AND_URLS','Imagens e atalhos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_content.ini'),
 (125,'COM_FINDER','Busca inteligente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (126,'COM_FINDER_ADVANCED_SEARCH_TOGGLE','Busca avançada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (127,'COM_FINDER_ADVANCED_TIPS','<p>Eis alguns exemplos de como você pode usar a funcionalidade de busca:</p><p>Digitando <span class=\"term\">isso e aquilo</span> no formulário de busca retornará resultados contendo ambos \"isso\" e \"aquilo\".</p><p>Digitando <span class=\"term\">isso não aquilo</span> no formulário de busca retornará resultados contendo \"isso\" e não \"aquilo\".</p><p>Digitando <span class=\"term\">isso ou aquilo</span> no formulário de busca retornará resultados contendo ou \"isso\" ou \"aquilo\".</p><p>Digitando <span class=\"term\">\"isso e aquilo\"</span> (com aspas) no formulário de busca retornará resultados contendo a frase exata \"isso e aquilo\".</p><p>Resultados de busca também podem ser filtrados com o uso de uma variedade de critérios. Selecione um ou mais filtros abaixo para começar.</p>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (128,'COM_FINDER_DEFAULT_PAGE_TITLE','Resultados da busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (129,'COM_FINDER_FILTER_BRANCH_LABEL','Buscar por %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (130,'COM_FINDER_FILTER_DATE_BEFORE','Antes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (131,'COM_FINDER_FILTER_DATE_EXACTLY','Exatamente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (132,'COM_FINDER_FILTER_DATE_AFTER','Depois','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (133,'COM_FINDER_FILTER_DATE1','Data de início','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (134,'COM_FINDER_FILTER_DATE1_DESC','Informe uma data no formato YYYY-MM-DD','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (135,'COM_FINDER_FILTER_DATE2','Data de término','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (136,'COM_FINDER_FILTER_DATE2_DESC','Informe uma data no formato YYYY-MM-DD','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (137,'COM_FINDER_FILTER_SELECT_ALL_LABEL','Buscar tudo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (138,'COM_FINDER_FILTER_WHEN_AFTER','Depois','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (139,'COM_FINDER_FILTER_WHEN_BEFORE','Antes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (140,'COM_FINDER_QUERY_END_DATE','terminando pela data <span class=\"when\">%s</span> <span class=\"date\">%s</span>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (141,'COM_FINDER_QUERY_OPERATOR_AND','e','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (142,'COM_FINDER_QUERY_OPERATOR_OR','ou','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (143,'COM_FINDER_QUERY_OPERATOR_NOT','não','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (144,'COM_FINDER_QUERY_FILTER_BRANCH_VENUE','local','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (145,'COM_FINDER_QUERY_START_DATE','começando pela data <span class=\"when\">%s</span> <span class=\"date\">%s</span>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (146,'COM_FINDER_QUERY_TAXONOMY_NODE','com <span class=\"node\">%s</span> as <span class=\"branch\">%s</span>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (147,'COM_FINDER_QUERY_TOKEN_EXCLUDED','<span class=\"term\">%s</span> devem ser excluídos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (148,'COM_FINDER_QUERY_TOKEN_GLUE',', e','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (149,'COM_FINDER_QUERY_TOKEN_INTERPRETED','Assumindo %s, os seguintes resultados foram encontrados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (150,'COM_FINDER_QUERY_TOKEN_OPTIONAL','<span class=\"term\">%s</span> é opcional','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (151,'COM_FINDER_QUERY_TOKEN_REQUIRED','<span class=\"term\">%s</span> é necessário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (152,'COM_FINDER_SEARCH_NO_RESULTS_BODY','Sem resultados encontrados para a consulta %s.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (153,'COM_FINDER_SEARCH_NO_RESULTS_BODY_MULTILANG','Não foram encontrados resultados (em Português-BR) para a consulta: %s.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (154,'COM_FINDER_SEARCH_NO_RESULTS_HEADING','Nenhum resultado encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (155,'COM_FINDER_SEARCH_RESULTS_OF','Resultados <strong>%s</strong> - <strong>%s</strong> de <strong>%s</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (156,'COM_FINDER_SEARCH_SIMILAR','Você quis dizer: %s?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (157,'COM_FINDER_SEARCH_TERMS','Termos da busca:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_finder.ini'),
 (158,'COM_MAILTO','E-mail para','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (159,'COM_MAILTO_CANCEL','Cancelar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (160,'COM_MAILTO_CLOSE_WINDOW','Fechar Janela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (161,'COM_MAILTO_EMAIL_ERR_NOINFO','Por favor, informe um e-mail válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (162,'COM_MAILTO_EMAIL_INVALID','O endereço \'%s\' parece não ser um endereço válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (163,'COM_MAILTO_EMAIL_MSG','Este é um e-mail de (%s) enviado por %s (%s). Você também pode achar interessante o seguinte link : %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (164,'COM_MAILTO_EMAIL_NOT_SENT','O e-mail não pode ser enviado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (165,'COM_MAILTO_EMAIL_SENT','O e-mail foi enviado com sucesso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (166,'COM_MAILTO_EMAIL_TO','E-mail para','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (167,'COM_MAILTO_EMAIL_TO_A_FRIEND','Envie este link a um amigo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (168,'COM_MAILTO_LINK_IS_MISSING','Faltando link','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (169,'COM_MAILTO_SEND','Enviar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (170,'COM_MAILTO_SENDER','De','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (171,'COM_MAILTO_SENT_BY','Item enviado por','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (172,'COM_MAILTO_SUBJECT','Assunto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (173,'COM_MAILTO_YOUR_EMAIL','Seu E-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_mailto.ini'),
 (174,'COM_MEDIA_ALIGN','Alinhar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (175,'COM_MEDIA_ALIGN_DESC','Se \'Não Configurado\', o alinhamento é definido pela classe \'.img_caption.none\'. Usualmente centraliza a imagem na página.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (176,'COM_MEDIA_BROWSE_FILES','Explorar Arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (177,'COM_MEDIA_CAPTION','Legenda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (178,'COM_MEDIA_CAPTION_DESC','Se configurado como \'Sim\', o título da imagem será usado como legenda.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (179,'COM_MEDIA_CLEAR_LIST','Limpar Lista','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (180,'COM_MEDIA_CONFIGURATION','Configurações do Gerenciador de Mídia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (181,'COM_MEDIA_CREATE_FOLDER','Criar Pasta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (182,'COM_MEDIA_CURRENT_PROGRESS','Progresso Atual','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (183,'COM_MEDIA_DESCFTP','Para enviar, alterar ou remover arquivos de mídia, o Joomla! precisa dos detalhes de sua conta FTP. Por favor, informe-os nos campos do formulário abaixo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (184,'COM_MEDIA_DESCFTPTITLE','Detalhes do FTP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (185,'COM_MEDIA_DETAIL_VIEW','Exibição Detalhada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (186,'COM_MEDIA_DIRECTORY','Pasta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (187,'COM_MEDIA_DIRECTORY_UP','Pasta Acima','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (188,'COM_MEDIA_ERROR_BAD_REQUEST','Requisição Incorreta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (189,'COM_MEDIA_ERROR_FILE_EXISTS','Arquivo já existe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (190,'COM_MEDIA_ERROR_UNABLE_TO_CREATE_FOLDER_WARNDIRNAME','Não foi possível criar pasta. O Nome da Pasta deve conter apenas caracteres alfanuméricos, sem espaços.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (191,'COM_MEDIA_ERROR_UNABLE_TO_DELETE','Não foi possível remover:&#160;','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (192,'COM_MEDIA_ERROR_UNABLE_TO_DELETE_FILE_WARNFILENAME','Não foi possível remover:&nbsp;%s. O Nome do Arquivo deve conter apenas caracteres alfanuméricos, sem espaços.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (193,'COM_MEDIA_ERROR_UNABLE_TO_DELETE_FOLDER_NOT_EMPTY','Não foi possível remover:&nbsp;%s. A Pasta não está vazia!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (194,'COM_MEDIA_ERROR_UNABLE_TO_DELETE_FOLDER_WARNDIRNAME','Não foi possível remover:&nbsp;%s. O Nome da Pasta deve conter apenas caracteres alfanuméricos, sem espaços.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (195,'COM_MEDIA_ERROR_UNABLE_TO_UPLOAD_FILE','Erro. Não foi possível enviar arquivo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (196,'COM_MEDIA_ERROR_UPLOAD_INPUT','Por favor, insira um arquivo para enviar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (197,'COM_MEDIA_ERROR_WARNFILENAME','Por favor, nome do arquivo deve conter apenas caracteres alfanuméricos, sem espaços.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (198,'COM_MEDIA_ERROR_WARNFILETOOLARGE','Este arquivo é muito grande para ser enviado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (199,'COM_MEDIA_ERROR_WARNFILETYPE','Este tipo de arquivo não é suportado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (200,'COM_MEDIA_ERROR_WARNIEXSS','Encontrado possível ataque IE XSS.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (201,'COM_MEDIA_ERROR_WARNINVALID_IMG','Não é uma imagem válida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (202,'COM_MEDIA_ERROR_WARNINVALID_MIME','Tipo MIME detectado é inválido ou proibido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (203,'COM_MEDIA_ERROR_WARNNOTADMIN','Arquivo enviado não é um arquivo de imagem ou você não possui permissão de envio.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (204,'COM_MEDIA_ERROR_WARNNOTEMPTY','A pasta não está vazio!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (205,'COM_MEDIA_FIELD_CHECK_MIME_DESC','Usar MIME Magic ou Fileinfo para tentar verificar arquivos. Desative se você receber erros de tipo MIME inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (206,'COM_MEDIA_FIELD_CHECK_MIME_LABEL','Verificar Tipos MIME','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (207,'COM_MEDIA_FIELD_ENABLE_FLASH_UPLOADER_DESC','O uploader em Flash permite que você envie vários arquivos ao mesmo tempo. Talvez possa não funcionar com suas configurações','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (208,'COM_MEDIA_FIELD_ENABLE_FLASH_UPLOADER_LABEL','Habilitar Flash Uploader','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (209,'COM_MEDIA_FIELD_IGNORED_EXTENSIONS_DESC','Extensões de arquivos ignoradas para a verificação de tipo MIME e upload restrito','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (210,'COM_MEDIA_FIELD_IGNORED_EXTENSIONS_LABEL','Extensões Ignoradas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (211,'COM_MEDIA_FIELD_ILLEGAL_MIME_TYPES_DESC','Uma lista, separada por vírgula, dos tipos MIME proibidos para uploads (lista negra)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (212,'COM_MEDIA_FIELD_ILLEGAL_MIME_TYPES_LABEL','Tipos MIME Proibidos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (213,'COM_MEDIA_FIELD_LEGAL_EXTENSIONS_DESC','Extensões (Tipos de Arquivo) que você pode enviar (separadas por vírgula).','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (214,'COM_MEDIA_FIELD_LEGAL_EXTENSIONS_LABEL','Extensões Permitidas (Tipos de Arquivo)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (215,'COM_MEDIA_FIELD_LEGAL_IMAGE_EXTENSIONS_DESC','Extensões de Imagem (Tipo de Arquivo) que você pode fazer o upload (separados por vírgula). São usadas para verificar se os cabeçalhos da imagem é válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (216,'COM_MEDIA_FIELD_LEGAL_IMAGE_EXTENSIONS_LABEL','Extensões de Imagens Permitidas (Tipos de Arquivo)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (217,'COM_MEDIA_FIELD_LEGAL_MIME_TYPES_DESC','Uma lista, separada por vírgula, dos tipos MIME permitidos para uploads','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (218,'COM_MEDIA_FIELD_LEGAL_MIME_TYPES_LABEL','Tipos MIME Permitidos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (219,'COM_MEDIA_FIELD_MAXIMUM_SIZE_DESC','O tamanho máximo permitido para o envio (em megabytes). Use zero para ilimitado. Obs.: Seu servidor tem um limite máximo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (220,'COM_MEDIA_FIELD_MAXIMUM_SIZE_LABEL','Tamanho Máximo (em MB)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (221,'COM_MEDIA_FIELD_PATH_FILE_FOLDER_DESC','Informe aqui o caminho do diretório de arquivo a partir da raiz','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (222,'COM_MEDIA_FIELD_PATH_FILE_FOLDER_LABEL','Caminho para o diretório de mídia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (223,'COM_MEDIA_FIELD_PATH_IAMGE_FOLDER_DESC','Informe aqui o caminho do diretório de imagem a partir da raiz','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (224,'COM_MEDIA_FIELD_PATH_IMAGE_FOLDER_LABEL','Caminho para o diretório de imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (225,'COM_MEDIA_FIELD_RESTRICT_UPLOADS_DESC','Restringir uploads de usuários com autoridade inferior a gerentes, para apenas imagens se o Fileinfo ou MIME Magic não estiver instalado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (226,'COM_MEDIA_FIELD_RESTRICT_UPLOADS_LABEL','Restringir Uploads','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (227,'COM_MEDIA_FILES','Arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (228,'COM_MEDIA_FILESIZE','Tamanho do Arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (229,'COM_MEDIA_FILESIZE_BYTES','%s bytes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (230,'COM_MEDIA_FILESIZE_KILOBYTES','%s KB','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (231,'COM_MEDIA_FILESIZE_MEGABYTES','%s MB','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (232,'COM_MEDIA_FOLDER','Pasta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (233,'COM_MEDIA_FOLDERS','Pastas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (234,'COM_MEDIA_IMAGE_DESCRIPTION','Descrição da Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (235,'COM_MEDIA_IMAGE_URL','URL da Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (236,'COM_MEDIA_INSERT','Inserir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (237,'COM_MEDIA_INSERT_IMAGE','Inserir Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (238,'COM_MEDIA_MAXIMUM_SIZE','Tamanho Máximo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (239,'COM_MEDIA_MEDIA','Mídia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (240,'COM_MEDIA_NAME','Nome da Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (241,'COM_MEDIA_NO_IMAGES_FOUND','Imagens não encontradas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (242,'COM_MEDIA_NOT_SET','Não Configurado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (243,'COM_MEDIA_OVERALL_PROGRESS','Progresso Total','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (244,'COM_MEDIA_PIXEL_DIMENSIONS','Dimensões em Pixel (L x A)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (245,'COM_MEDIA_START_UPLOAD','Iniciar Envio','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (246,'COM_MEDIA_THUMBNAIL_VIEW','Visualização em Miniatura','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (247,'COM_MEDIA_TITLE','Título da Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (248,'COM_MEDIA_UP','Acima','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (249,'COM_MEDIA_UPLOAD','Enviar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (250,'COM_MEDIA_UPLOAD_COMPLETE','Envio Completo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (251,'COM_MEDIA_UPLOAD_FILE','Enviar arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (252,'COM_MEDIA_UPLOAD_FILES','Enviar arquivos (Tamanho Máximo: %s MB)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (253,'COM_MEDIA_UPLOAD_FILES_NOLIMIT','Enviar arquivos (Sem tamanho máximo)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (254,'COM_MEDIA_UPLOAD_SUCCESSFUL','Enviado com Sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_media.ini'),
 (255,'COM_MESSAGES_ERR_SEND_FAILED','Erro no envio da Mensagem. O usuário bloqueou a caixa de mensagem dele.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_messages.ini'),
 (256,'COM_MESSAGES_NEW_MESSAGE_ARRIVED','Você tem uma nova mensagem privada de %','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_messages.ini'),
 (257,'COM_MESSAGES_PLEASE_LOGIN','Por favor, acesse %s para ler sua mensagem.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_messages.ini'),
 (258,'COM_NEWSFEEDS_CACHE_DIRECTORY_UNWRITABLE','O diretório de cache não tem permissão de escrita. A fonte de notícias não pode ser exibida. Por favor, informe o administrador do site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (259,'COM_NEWSFEEDS_CAT_NUM','# de Newsfeeds :','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (260,'COM_NEWSFEEDS_DEFAULT_PAGE_TITLE','Newsfeeds','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (261,'COM_NEWSFEEDS_ERROR_FEED_NOT_FOUND','Erro. Feed não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (262,'COM_NEWSFEEDS_ERRORS_FEED_NOT_RETRIEVED','Erro. Feed não pôde ser recuperado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (263,'COM_NEWSFEEDS_FEED_LINK','Link do Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (264,'COM_NEWSFEEDS_FEED_NAME','Nome do Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (265,'COM_NEWSFEEDS_NO_ARTICLES','Nenhum artigos para este newsfeed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (266,'COM_NEWSFEEDS_NUM_ARTICLES','# Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_newsfeeds.ini'),
 (267,'COM_SEARCH_ALL_WORDS','Todas as Palavras','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (268,'COM_SEARCH_ALPHABETICAL','Alfabética','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (269,'COM_SEARCH_ANY_WORDS','Qualquer palavra','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (270,'COM_SEARCH_ERROR_ENTERKEYWORD','Insira uma palavra chave','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (271,'COM_SEARCH_ERROR_IGNOREKEYWORD','Uma ou mais palavras comuns foram ignoradas na busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (272,'COM_SEARCH_ERROR_SEARCH_MESSAGE','O termo a buscar deve ter no mínimo 3 caracteres e um máximo de 20 caracteres.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (273,'COM_SEARCH_EXACT_PHRASE','Frase exata','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (274,'COM_SEARCH_FIELD_SEARCH_AREAS_DESC','Exibir caixas de seleção das buscas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (275,'COM_SEARCH_FIELD_SEARCH_AREAS_LABEL','Usar áreas de busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (276,'COM_SEARCH_FOR','Buscar por:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (277,'COM_SEARCH_MOST_POPULAR','Mais populares','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (278,'COM_SEARCH_NEWEST_FIRST','Recentes primeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (279,'COM_SEARCH_OLDEST_FIRST','Antigos Primeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (280,'COM_SEARCH_ORDERING','Ordenação:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (281,'COM_SEARCH_SEARCH','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (282,'COM_SEARCH_SEARCH_AGAIN','Buscar novamente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (283,'COM_SEARCH_SEARCH_KEYWORD','Buscar palavra-chave:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (284,'COM_SEARCH_SEARCH_KEYWORD_N_RESULTS_1','<strong>Total: Um resultado encontrado.</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (285,'COM_SEARCH_SEARCH_KEYWORD_N_RESULTS','<strong>Total: %s resultados encontrados.</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (286,'COM_SEARCH_SEARCH_ONLY','Buscar Somente:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (287,'COM_SEARCH_SEARCH_RESULT','Resultados da Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_search.ini'),
 (288,'COM_USERS_ACTIVATION_TOKEN_NOT_FOUND','Código de verificação não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (289,'COM_USERS_CAPTCHA_LABEL','Captcha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (290,'COM_USERS_CAPTCHA_DESC','Digite na caixa de texto o que deseja ver na imagem.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (291,'COM_USERS_DATABASE_ERROR','Erro ao obter o usuário do banco de dados: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (292,'COM_USERS_DESIRED_PASSWORD','Digite a senha desejada - No mínimo 4 caracteres','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (293,'COM_USERS_DESIRED_USERNAME','Digite seu nome de usuário desejado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (294,'COM_USERS_EDIT_PROFILE','Editar perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (295,'COM_USERS_EMAIL_ACCOUNT_DETAILS','Detalhes da conta de %s em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (296,'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY','Olá administrador, um novo usuário registrou-se em %s.\\nO usuário confirmou seu e-mail e solicita que você aprove sua conta..\\n Este endereço de e-mail contém os detalhes:\\n\\n  Nome :  %s \\n  E-mail:  %s \\n Usuário:  %s \\n\\nVocê pode ativar o usuário, clicando no link abaixo:\\n %s \\n','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (297,'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT','Aprovação de registro requerido para a conta de %s em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (298,'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY','Olá %s,\\n\\nSua conta foi ativada pelo Administrador. Agora você pode logar em %s usando o login %s e a senha que você escolheu no registro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (299,'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT','Conta ativada por %s em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (300,'COM_USERS_EMAIL_PASSWORD_RESET_BODY','Olá,\\n\\num pedido foi feito para repor a sua % s senha da conta. Para recuperar sua senha, você terá de apresentar o código de verificação, a fim de verificar que o pedido era legítimo.\\n\\nO código de verificação é %s .\\n\\nClique na URL abaixo para inserir o código de verificação e prosseguir com a redefinir a senha.\\n\\n %s \\n\\nObrigado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (301,'COM_USERS_EMAIL_PASSWORD_RESET_SUBJECT','Seu %s pedido de redefinição de senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (302,'COM_USERS_EMAIL_REGISTERED_BODY','Olá %s,\\n\\nObrigado por se registar no %s.\\n\\nVocê pode fazer o login %s usando o nome de usuário e senha que você registrou.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (303,'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY','Olá administrador, \\n\\nUm novo usuário \'%s\' com o nome de usuário \'%s\', se registrou em %s.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (304,'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY','Olá %s,\\n\\nObrigado por se registar em %s. Sua conta será criada e deve ser ativada antes que você possa usá-la.\\nPara ativar a conta clique no link abaixo ou copie e cole no seu navegador:\\n%s \\n\\nApós ativação você pode entrar em %s usando o seguinte usuário e senha:\\n\\nNome de usuário: %s\\nSenha: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (305,'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW','Olá %s,\\n\\nObrigado por se registar em %s. Sua conta será criada e deve ser ativada antes que você possa usá-la.\\nPara ativar a conta clique no link abaixo ou copie e cole no seu navegador:\\n%s \\n\\nApós ativação você pode entrar em %s usando o seguinte usuário e senha:\\n\\nNome de usuário: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (306,'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY','Olá %s,\\n\\nObrigado por se registrar em %s. Sua conta foi criada e deve ser verificada antes que você possa utilizá-la.\\nPara verificar a conta clique no seguinte link, ou copie-e-cole no seu navegador:\\n %s \\n\\nApós sua verificação um administrador será notificado para ativar sua conta e então você receberá uma confirmação.\\nAssim que sua conta for ativada, então você poderá fazer login em %s utilizando o seguinte nome de usuário e senha:\\n\\nNome de Usuário: %s\\nSenha: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (307,'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW','Olá %s,\\n\\nObrigado por se registrar em %s. Sua conta foi criada e deve ser verificada antes que você possa utilizá-la.\\nPara verificar a conta clique no seguinte link, ou copie-e-cole no seu navegador:\\n %s \\n\\nApós sua verificação um administrador será notificado para ativar sua conta e então você receberá uma confirmação.\\nAssim que sua conta for ativada, então você poderá fazer login em %s utilizando o seguinte nome de usuário e senha:\\n\\nNome de Usuário: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (308,'COM_USERS_EMAIL_USERNAME_REMINDER_BODY','Hello,\\n\\nA username reminder has been requested for your %s account.\\n\\nYour username is %s.\\n\\nTo login to your account, click on the link below.\\n\\n%s \\n\\nThank you.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (309,'COM_USERS_EMAIL_USERNAME_REMINDER_SUBJECT','Your %s username','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (310,'COM_USERS_FIELD_PASSWORD_RESET_DESC','Por favor, forneça o endereço de e-mail que está associado à sua conta de usuário.<br />Um código de verificação será enviado a você. Uma vez recebido o código de verificação, você estará apto a escolher uma nova senha para sua conta.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (311,'COM_USERS_FIELD_PASSWORD_RESET_LABEL','Endereço de E-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (312,'COM_USERS_FIELD_REMIND_EMAIL_DESC','Por favor, indique o endereço de e-mail associado à sua conta de utilizador.<br /> Seu Nome de Usuário será enviado por correio eletrônico para o endereço de e-mail em arquivo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (313,'COM_USERS_FIELD_REMIND_EMAIL_LABEL','Endereço de E-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (314,'COM_USERS_FIELD_RESET_CONFIRM_TOKEN_DESC','Digite o código de verificação que você recebeu por e-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (315,'COM_USERS_FIELD_RESET_CONFIRM_TOKEN_LABEL','Código de Verificação:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (316,'COM_USERS_FIELD_RESET_CONFIRM_USERNAME_DESC','Informe seu nome de usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (317,'COM_USERS_FIELD_RESET_CONFIRM_USERNAME_LABEL','Nome de Usuário:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (318,'COM_USERS_FIELD_RESET_PASSWORD1_DESC','Insira a nova senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (319,'COM_USERS_FIELD_RESET_PASSWORD1_LABEL','Senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (320,'COM_USERS_FIELD_RESET_PASSWORD1_MESSAGE','As senhas que você digitou não são iguais. Por favor, digite a senha desejada no campo de senha e confirme a sua entrada, inserindo-o no campo Confirmar senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (321,'COM_USERS_FIELD_RESET_PASSWORD2_DESC','Confirme sua nova senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (322,'COM_USERS_FIELD_RESET_PASSWORD2_LABEL','Confirmar senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (323,'COM_USERS_FIELD_RESET_PASSWORD2_MESSAGE','Senha inválida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (324,'COM_USERS_INVALID_EMAIL','Endereço de e-mail inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (325,'COM_USERS_LOGIN_REGISTER','Não possui uma conta?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (326,'COM_USERS_LOGIN_REMIND','Esqueceu seu nome de usuário?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (327,'COM_USERS_LOGIN_RESET','Esqueceu sua senha?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (328,'COM_USERS_LOGIN_USERNAME_LABEL','Nome de Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (329,'COM_USERS_MAIL_FAILED','Falha ao enviar e-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (330,'COM_USERS_MAIL_SEND_FAILURE_BODY','Ocorreu um erro no envio de e-mail de ativação. O erro foi: %s O usuário que tentou se registrar foi: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (331,'COM_USERS_MAIL_SEND_FAILURE_SUBJECT','Erro ao enviar email','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (332,'COM_USERS_OPTIONAL','(opcional)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (333,'COM_USERS_OR','ou','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (334,'COM_USERS_PROFILE','Perfil do Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (335,'COM_USERS_PROFILE_CORE_LEGEND','Perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (336,'COM_USERS_PROFILE_CUSTOM_LEGEND','Perfil customizado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (337,'COM_USERS_PROFILE_DEFAULT_LABEL','Edite seu perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (338,'COM_USERS_PROFILE_EMAIL1_DESC','Digite seu e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (339,'COM_USERS_PROFILE_EMAIL1_LABEL','E-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (340,'COM_USERS_PROFILE_EMAIL1_MESSAGE','Seu endereço de e-mail já está sendo usado, ou não é inválido. Por favor, entre com outro endereço.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (341,'COM_USERS_PROFILE_EMAIL2_DESC','Confirme seu endereço de e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (342,'COM_USERS_PROFILE_EMAIL2_LABEL','Confirme o endereço de e-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (343,'COM_USERS_PROFILE_EMAIL2_MESSAGE','Os endereços de e-mail que você digitou não são iguais. Por favor, indique o seu endereço de e-mail no campo de e-mail e confirme a sua entrada, inserindo-o novamente no campo de confirmação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (344,'COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL','Data da última visita','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (345,'COM_USERS_PROFILE_MY_PROFILE','Meu perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (346,'COM_USERS_PROFILE_NAME_DESC','Digite seu nome completo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (347,'COM_USERS_PROFILE_NAME_LABEL','Nome:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (348,'COM_USERS_PROFILE_NAME_MESSAGE','O nome que você digitou é inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (349,'COM_USERS_PROFILE_NEVER_VISITED','Esta é a sua primeira visita','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (350,'COM_USERS_PROFILE_NOCHANGE_USERNAME_DESC','Se você quer mudar seu nome de usuário, por favor contate o administrador do site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (351,'COM_USERS_PROFILE_PASSWORD1_LABEL','Senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (352,'COM_USERS_PROFILE_PASSWORD1_MESSAGE','As senhas que você digitou não são iguais. Por favor, digite a senha desejada no campo de senha e confirme a sua entrada, inserindo-a no campo confirmar senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (353,'COM_USERS_PROFILE_PASSWORD2_DESC','Confirme sua senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (354,'COM_USERS_PROFILE_PASSWORD2_LABEL','Confirmar senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (355,'COM_USERS_PROFILE_PASSWORD2_MESSAGE','Senha inválida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (356,'COM_USERS_PROFILE_REGISTERED_DATE_LABEL','Data de registro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (357,'COM_USERS_PROFILE_SAVE_FAILED','Perfil não pode ser salvo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (358,'COM_USERS_PROFILE_SAVE_SUCCESS','Perfil salvo com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (359,'COM_USERS_PROFILE_USERNAME_DESC','Digite o nome de usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (360,'COM_USERS_PROFILE_USERNAME_LABEL','Nome de usuário:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (361,'COM_USERS_PROFILE_USERNAME_MESSAGE','O nome de usuário que você digitou não está disponível. Por favor, escolha outro nome.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (362,'COM_USERS_PROFILE_VALUE_NOT_FOUND','Nada informado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (363,'COM_USERS_PROFILE_WELCOME','Bem vindo(a), %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (364,'COM_USERS_REGISTER_DEFAULT_LABEL','Criar uma conta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (365,'COM_USERS_REGISTER_EMAIL1_DESC','Entre com seu endereço de e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (366,'COM_USERS_REGISTER_EMAIL1_LABEL','Endereço de e-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (367,'COM_USERS_REGISTER_EMAIL1_MESSAGE','O endereço de e-mail que você digitou já está em uso ou inválido. Por favor, indique outro endereço de e-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (368,'COM_USERS_REGISTER_EMAIL2_DESC','Confirme seu endereço de e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (369,'COM_USERS_REGISTER_EMAIL2_LABEL','Confirme o endereço de e-mail:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (370,'COM_USERS_REGISTER_EMAIL2_MESSAGE','Os endereços de e-mail que você digitou não são iguais. Por favor, indique o seu endereço de e-mail no campo de endereço de e-mail e confirme a sua entrada, inserindo-o no campo de confirmação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (371,'COM_USERS_REGISTER_NAME_DESC','Digite seu nome completo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (372,'COM_USERS_REGISTER_NAME_LABEL','Nome:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (373,'COM_USERS_REGISTER_NAME_MESSAGE','O nome que você digitou é inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (374,'COM_USERS_REGISTER_PASSWORD1_LABEL','Senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (375,'COM_USERS_REGISTER_PASSWORD1_MESSAGE','As senhas que você digitou não são iguais. Por favor, digite a senha desejada no campo de senha e confirme a sua entrada, inserindo-a no campo confirmar senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (376,'COM_USERS_REGISTER_PASSWORD2_DESC','Confirme sua senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (377,'COM_USERS_REGISTER_PASSWORD2_LABEL','Confirme sua senha:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (378,'COM_USERS_REGISTER_PASSWORD2_MESSAGE','Senha inválida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (379,'COM_USERS_REGISTER_REQUIRED','<strong class=\"red\">*</strong> Campo obrigatório','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (380,'COM_USERS_REGISTER_USERNAME_DESC','Entre com o nome de usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (381,'COM_USERS_REGISTER_USERNAME_LABEL','Nome de usuário:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (382,'COM_USERS_REGISTER_USERNAME_MESSAGE','O nome de usuário que você digitou é inválido. Por favor, Escolha outro nome.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (383,'COM_USERS_REGISTRATION','Cadastramento de Usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (384,'COM_USERS_REGISTRATION_ACTIVATE_SUCCESS','Sua conta foi ativada com sucesso. Agora você pode efetuar login usando o nome de usuário e senha que escolheu durante o registo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (385,'COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED','Foi encontrado um erro ao enviar o  e-mail de notificação de ativação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (386,'COM_USERS_REGISTRATION_ACTIVATION_SAVE_FAILED','Falha ao salvar dados de ativação: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (387,'COM_USERS_REGISTRATION_ADMINACTIVATE_SUCCESS','A conta do usuário foi ativada com êxito e o usuário foi notificado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (388,'COM_USERS_REGISTRATION_BIND_FAILED','Falha ao vincular dados cadastrais: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (389,'COM_USERS_REGISTRATION_COMPLETE_ACTIVATE','Sua conta foi criada e um link de ativação foi enviado para o endereço de e-mail digitado. Observe que você deve ativar a conta clicando no link de ativação no e-mail antes de poder fazer o login.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (390,'COM_USERS_REGISTRATION_COMPLETE_VERIFY','Sua conta foi criada e um link de confirmação foi enviado para o endereço de e-mail digitado. Observe que você deve verificar sua conta clicando no link de confirmação no e-mail e, em seguida, um administrador irá ativar sua conta antes que você possa fazer o login.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (391,'COM_USERS_REGISTRATION_DEFAULT_LABEL','Registro de Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (392,'COM_USERS_REGISTRATION_SAVE_FAILED','Registro falhou: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (393,'COM_USERS_REGISTRATION_SAVE_SUCCESS','Obrigado por se registar. Agora você pode efetuar login usando o nome de usuário e senha com as quais se registrou.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (394,'COM_USERS_REGISTRATION_SEND_MAIL_FAILED','Ocorreu um erro no envio de e-mail de ativação. Uma mensagem foi enviada para o administrador deste site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (395,'COM_USERS_REGISTRATION_VERIFY_SUCCESS','Seu e-mail foi verificado. Depois que um administrador aprovar a sua conta, você será notificado por e-mail e poderá acessar o site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (396,'COM_USERS_REMIND','Lembrete','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (397,'COM_USERS_REMIND_DEFAULT_LABEL','Digite o endereço de e-mail associado à sua conta de usuário. Seu Nome de Usuário será enviado por e-mail para o endereço de e-mail em arquivo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (398,'COM_USERS_REMIND_EMAIL_LABEL','Seu e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (399,'COM_USERS_REMIND_LIMIT_ERROR_N_HOURS','Você excedeu o número máximo de redefinições de senha permitido. Por favor, tente novamente em %s horas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (400,'COM_USERS_REMIND_LIMIT_ERROR_N_HOURS_1','Você excedeu o número máximo de redefinições de senha permitido. Por favor, tente novamente em uma horas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (401,'COM_USERS_REMIND_REQUEST_FAILED','Lembrete falhou. Verifique se você digitou um e-mail válido no campo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (402,'COM_USERS_REMIND_REQUEST_SUCCESS','Lembrete enviado com sucesso. Por favor, verifique seu e-mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (403,'COM_USERS_REMIND_SUPERADMIN_ERROR','Um Super Administrador não pode requisitar um lembrete de senha. Por favor, entre em contato com outro Super Administrador ou use um método alternativo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (404,'COM_USERS_RESET_COMPLETE_ERROR','Ocorreu um erro ao processar a reinicialização da sua senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (405,'COM_USERS_RESET_COMPLETE_FAILED','Erro ao processar a reinicialização da senha: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (406,'COM_USERS_RESET_COMPLETE_LABEL','Para completar o processo de reinicialização de senha, por favor indique sua nova senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (407,'COM_USERS_RESET_COMPLETE_SUCCESS','Senha alterada com sucesso. Agora você poderá usar a nova senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (408,'COM_USERS_RESET_CONFIRM_ERROR','Erro na confirmação da senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (409,'COM_USERS_RESET_CONFIRM_FAILED','A alteração de sua senha não pode ser concluída porque o código de verificação não é válido. %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (410,'COM_USERS_RESET_CONFIRM_LABEL','Uma mensagem será enviada para seu endereço de e-mail. Esta mensagem conterá o código de verificação, que deve ser copiado-e-colado no campo abaixo para provar que você é realmente o proprietário dessa conta.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (411,'COM_USERS_RESET_COMPLETE_TOKENS_MISSING','Sua solicitação de alteração de senha falhou porque está faltando o código de verificação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (412,'COM_USERS_RESET_REQUEST_ERROR','Erro na solicitação de alteração de senha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (413,'COM_USERS_RESET_REQUEST_FAILED','Falha na alteração da senha: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (414,'COM_USERS_RESET_REQUEST_LABEL','Por favor, informe o endereço de e-mail para esta conta. Um código de verificação será enviado a você. Um vez recebido o código, você poderá escolher uma nova senha para esta conta.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (415,'COM_USERS_SETTINGS_FIELDSET_LABEL','Configurações Básicas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (416,'COM_USERS_USER_BLOCKED','Este usuário está bloqueado. Se isso for um erro, por favor, contate o administrador.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (417,'COM_USERS_USER_FIELD_BACKEND_LANGUAGE_DESC','Selecione aqui um idioma padrão para a administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (418,'COM_USERS_USER_FIELD_BACKEND_LANGUAGE_LABEL','Idioma da Administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (419,'COM_USERS_USER_FIELD_BACKEND_TEMPLATE_DESC','Selecione o estilo para a interface do Painel de Administração. Isto só afetará este usuário.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (420,'COM_USERS_USER_FIELD_BACKEND_TEMPLATE_LABEL','O estilo do tema da adminstração pode ser configurado no painel de administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (421,'COM_USERS_USER_FIELD_EDITOR_DESC','Selecione aqui o editor de texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (422,'COM_USERS_USER_FIELD_EDITOR_LABEL','Editor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (423,'COM_USERS_USER_FIELD_FRONTEND_LANGUAGE_DESC','Escolha aqui o idioma padrão para o site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (424,'COM_USERS_USER_FIELD_FRONTEND_LANGUAGE_LABEL','Idioma do site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (425,'COM_USERS_USER_FIELD_HELPSITE_DESC','Site de Ajuda para a Administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (426,'COM_USERS_USER_FIELD_HELPSITE_LABEL','Site de Ajuda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (427,'COM_USERS_USER_FIELD_TIMEZONE_DESC','Escolha aqui o seu fuso horário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (428,'COM_USERS_USER_FIELD_TIMEZONE_LABEL','Fuso Horário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (429,'COM_USERS_USER_NOT_FOUND','Usuário não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (430,'COM_USERS_USER_SAVE_FAILED','Erro ao salvar o usuário: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_users.ini'),
 (431,'COM_WEBLINKS_DEFAULT_PAGE_TITLE','Weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (432,'COM_WEBLINKS_EDIT','Editar Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (433,'COM_WEBLINKS_ERR_TABLES_NAME','Já existe um Weblink com este nome nesta categoria. Por favor, tente novamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (434,'COM_WEBLINKS_ERR_TABLES_PROVIDE_URL','Por favor, informe uma URL válida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (435,'COM_WEBLINKS_ERR_TABLES_TITLE','Seu Weblink deve ter um título.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (436,'COM_WEBLINKS_ERROR_CATEGORY_NOT_FOUND','Categoria de Weblink não encontrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (437,'COM_WEBLINKS_ERROR_UNIQUE_ALIAS','Outro weblink desta categoria tem o mesmo apelido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (438,'COM_WEBLINKS_ERROR_WEBLINK_NOT_FOUND','Weblink não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (439,'COM_WEBLINKS_ERROR_WEBLINK_URL_INVALID','Weblink inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (440,'COM_WEBLINKS_FIELD_ALIAS_DESC','O apelido é somente para uso interno. Deixe em branco e o Joomla preencherá com o valor padrão para o título. Deve ser único para cada weblink desta mesma categoria.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (441,'COM_WEBLINKS_FIELD_CATEGORY_DESC','Você deve selecionar uma categoria.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (442,'COM_WEBLINKS_FIELD_DESCRIPTION_DESC','Você pode inserir aqui uma descrição para o weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (443,'COM_WEBLINKS_FIELD_TITLE_DESC','Seu Weblink precisa ter um título.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (444,'COM_WEBLINKS_FIELD_URL_DESC','Você deve digitar uma URL.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (445,'COM_WEBLINKS_FIELD_URL_LABEL','URL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (446,'COM_WEBLINKS_FORM_CREATE_WEBLINK','Enviar um Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (447,'COM_WEBLINKS_GRID_TITLE','Título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (448,'COM_WEBLINKS_LINK','Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (449,'COM_WEBLINKS_NAME','Nome','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (450,'COM_WEBLINKS_NO_WEBLINKS','Não existem Weblinks nesta categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (451,'COM_WEBLINKS_NUM','# de links:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (452,'COM_WEBLINKS_FORM_EDIT_WEBLINK','Editar um Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (453,'COM_WEBLINKS_FORM_SUBMIT_WEBLINK','Enviar um Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (454,'COM_WEBLINKS_SAVE_SUCCESS','Weblink salvo com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (455,'COM_WEBLINKS_SUBMIT_SAVE_SUCCESS','Weblink enviado com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (456,'COM_WEBLINKS_WEB_LINKS','Weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (457,'JGLOBAL_NEWITEMSLAST_DESC','Novos Weblinks serão posicionados no final da lista. Você poderá alterar e salvar a ordem dos mesmos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_weblinks.ini'),
 (458,'COM_WRAPPER_NO_IFRAMES','Esta opção não irá funcionar corretamente. Infelizmente, seu navegador não suporta frames.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.com_wrapper.ini'),
 (459,'FILES_JOOMLA','CMS Joomla','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.files_joomla.sys.ini'),
 (460,'FILES_JOOMLA_ERROR_FILE_FOLDER','Erro na remoção do arquivo ou pasta %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.files_joomla.sys.ini'),
 (461,'FILES_JOOMLA_ERROR_MANIFEST','Erro na atualização do cache do manifesto: (tipo, elemento, pasta, cliente) = (%s, %s, %s, %s)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.files_joomla.sys.ini'),
 (462,'FILES_JOOMLA_XML_DESCRIPTION','Sistema de Gerenciamento de Conteúdo Joomla! 2.5','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.files_joomla.sys.ini'),
 (463,'FINDER_CLI','Indexador de Busca Inteligente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (464,'FINDER_CLI_BATCH_COMPLETE','* Lote %s processado em %s segundos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (465,'FINDER_CLI_PROCESS_COMPLETE','Tempo Total de Processamento: %s segundos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (466,'FINDER_CLI_STARTING_INDEXER','Iniciando Indexação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (467,'FINDER_CLI_SETTING_UP_PLUGINS','Configurando plugins Finder','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (468,'FINDER_CLI_SETUP_ITEMS','Configurados %s itens em %s segundos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.finder_cli.ini'),
 (469,'JERROR_PARSING_LANGUAGE_FILE','&#160;: Erro(s) na(s) linha(s) %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (470,'ERROR','Erro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (471,'MESSAGE','Mensagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (472,'NOTICE','Aviso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (473,'WARNING','Atenção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (474,'J1','1','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (475,'J2','2','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (476,'J3','3','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (477,'J4','4','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (478,'J5','5','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (479,'J10','10','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (480,'J15','15','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (481,'J20','20','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (482,'J25','25','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (483,'J30','30','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (484,'J50','50','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (485,'J100','100','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (486,'JACTION_ADMIN','Configurar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (487,'JACTION_ADMIN_GLOBAL','Super Administrador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (488,'JACTION_COMPONENT_SETTINGS','Definições de Componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (489,'JACTION_CREATE','Criar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (490,'JACTION_DELETE','Deletar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (491,'JACTION_EDIT','Editar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (492,'JACTION_EDITOWN','Editar Próprio','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (493,'JACTION_EDITSTATE','Editar Estado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (494,'JACTION_LOGIN_ADMIN','Login do Administrador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (495,'JACTION_LOGIN_SITE','Login do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (496,'JACTION_MANAGE','Acessar Componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (497,'JADMINISTRATOR','Administrador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (498,'JALL','Tudo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (499,'JALL_LANGUAGE','Tudo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (500,'JARCHIVED','Arquivado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (501,'JAUTHOR','Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (502,'JCANCEL','Cancelar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (503,'JCATEGORY','Categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (504,'JDATE','Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (505,'JDEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (506,'JDETAILS','Detalhes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (507,'JDISABLED','Desabilitado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (508,'JEDITOR','Editor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (509,'JENABLED','Habilitado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (510,'JFALSE','Falso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (511,'JFEATURED','Destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (512,'JHIDE','Oculto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (513,'JINVALID_TOKEN','Token Inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (514,'JLOGIN','Entrar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (515,'JLOGOUT','Sair','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (516,'JNEW','Novo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (517,'JNEXT','Próx','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (518,'JNO','Não','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (519,'JNONE','Nenhum','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (520,'JNOTICE','Aviso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (521,'JOFF','Desativar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (522,'JOFFLINE_MESSAGE','Este site está em manutenção. <br> Por favor, volte em breve.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (523,'JON','Ativar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (524,'JOPTIONS','Opções','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (525,'JPAGETITLE','%1$s - %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (526,'JPREV','Ant','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (527,'JPREVIOUS','Anterior','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (528,'JPUBLISHED','Publicado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (529,'JREGISTER','Registrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (530,'JREQUIRED','Requerido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (531,'JSAVE','Salvo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (532,'JSHOW','Exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (533,'JSITE','Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (534,'JSTATUS','Estado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (535,'JSUBMIT','Enviar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (536,'JTRASH','Lixeira','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (537,'JTRASHED','Lixo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (538,'JTRUE','Verdadeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (539,'JUNPUBLISHED','Despublicado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (540,'JYEAR','Ano','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (541,'JYES','Sim','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (542,'JBROWSERTARGET_MODAL','Modal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (543,'JBROWSERTARGET_NEW','Abrir em uma nova janela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (544,'JBROWSERTARGET_PARENT','Abrir na mesma janela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (545,'JBROWSERTARGET_POPUP','Abrir em popup','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (546,'JERROR_ALERTNOAUTHOR','Você não está autorizado a visualizar este recurso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (547,'JERROR_ALERTNOTEMPLATE','<strong>O tema para esta visualização não está disponível. Por favor, entre em contato com o administrador do site.</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (548,'JERROR_AN_ERROR_HAS_OCCURRED','Ocorreu um erro.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (549,'JERROR_ERROR','Erro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (550,'JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE','um <strong>bookmark/favorito desatualizado</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (551,'JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST','Ocorreu um erro ao processar o pedido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (552,'JERROR_LAYOUT_GO_TO_THE_HOME_PAGE','Ir para página inicial','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (553,'JERROR_LAYOUT_HOME_PAGE','Página Inicial','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (554,'JERROR_LAYOUT_SEARCH_PAGE','Buscar neste site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (555,'JERROR_LAYOUT_MIS_TYPED_ADDRESS','a <strong>endereço digitado incorretamente</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (556,'JERROR_LAYOUT_NOT_ABLE_TO_VISIT','Você pode não ser capaz de visitar esta página porque:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (557,'JERROR_LAYOUT_PAGE_NOT_FOUND','A página solicitada não pôde ser encontrada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (558,'JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR','Se as dificuldades persistirem, contate o administrador deste site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (559,'JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES','Por favor, tente uma das seguintes páginas:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (560,'JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND','O recurso requisitado não foi encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (561,'JERROR_LAYOUT_SEARCH','Você pode querer buscar o site ou visitar a página inicial.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (562,'JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING','um mecanismo de busca que tem uma <strong>listagem desatualizada deste site</strong>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (563,'JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE','Você <strong>não tem acesso</strong> a esta página','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (564,'JERROR_LOGIN_DENIED','Você não pode acessar a seção privada deste site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (565,'JERROR_NOLOGIN_BLOCKED','Login negado! Sua conta foi bloqueada, ou você ainda não a ativou.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (566,'JERROR_TABLE_BIND_FAILED','hmm %s ...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (567,'JERROR_USERS_PROFILE_NOT_FOUND','Perfil do usuário não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (568,'JFIELD_ACCESS_DESC','Nível de acesso para este conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (569,'JFIELD_ACCESS_LABEL','Acesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (570,'JFIELD_ALIAS_DESC','O apelido será usado na URL do SEF. Deixe em branco e o Joomla! tentará preencher com os valores padrões do título. Este valor dependerá das configurações do SEO (Configuração Global->Site). <br />Usando Unicode produzirá apelidos UTF-8. Você pode informar manualmente qualquer caracter utf8. Espaços e caracteres proibidos serão transformados em hifens.<br />Quando usando a transliteração padrão produzirá um apelido em caixa baixa e traços no lugar de espaços. Você pode informar o apelido manualmente. Use caracteres minúsculos e hifens (-). Espaços ou sublinhados não são permitidos. Valor padrão será a data e hora se o título for digitado em caracteres não-latinos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (571,'JFIELD_ALIAS_LABEL','Apelido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (572,'JFIELD_CATEGORY_DESC','Categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (573,'JFIELD_LANGUAGE_DESC','Atribuir um idioma para este artigo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (574,'JFIELD_LANGUAGE_LABEL','Linguagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (575,'JFIELD_META_DESCRIPTION_DESC','Meta descrição dos dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (576,'JFIELD_META_DESCRIPTION_LABEL','Meta Descrição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (577,'JFIELD_META_KEYWORDS_DESC','Palavras-chave que descrevem o conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (578,'JFIELD_META_KEYWORDS_LABEL','Palavras-chave','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (579,'JFIELD_META_RIGHTS_DESC','Descrever os direitos para usar esse conteúdo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (580,'JFIELD_META_RIGHTS_LABEL','Direitos de conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (581,'JFIELD_ORDERING_DESC','Ordenar o artigo dentro da categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (582,'JFIELD_ORDERING_LABEL','Ordenar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (583,'JFIELD_PUBLISHED_DESC','Definir o estado de publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (584,'JFIELD_TITLE_DESC','Título para o artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (585,'JGLOBAL_ARTICLE_MUST_HAVE_TEXT','O artigo deve conter algum texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (586,'JGLOBAL_ARTICLES','Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (587,'JGLOBAL_AUTH_ACCESS_DENIED','Acesso Negado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (588,'JGLOBAL_AUTH_ACCESS_GRANTED','Acesso Autorizado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (589,'JGLOBAL_AUTH_BIND_FAILED','Falha ao acessar servidor LDAP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (590,'JGLOBAL_AUTH_CANCEL','Autenticação cancelada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (591,'JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED','Não é permitido senha em branco','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (592,'JGLOBAL_AUTH_FAIL','Falha na autenticação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (593,'JGLOBAL_AUTH_FAILED','Falha ao autenticar: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (594,'JGLOBAL_AUTH_INCORRECT','Nome de usuário/senha incorreto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (595,'JGLOBAL_AUTH_INVALID_PASS','Senha inválida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (596,'JGLOBAL_AUTH_NO_BIND','Não possível acessar o LDAP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (597,'JGLOBAL_AUTH_NO_CONNECT','Não foi possível conectador ao servidor LDAP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (598,'JGLOBAL_AUTH_NO_REDIRECT','Não foi possível redirecionar para o servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (599,'JGLOBAL_AUTH_NO_USER','Usuário não existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (600,'JGLOBAL_AUTH_NOT_CREATE_DIR','Não foi possível criar diretório FileStore %s. Verifique as permissões.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (601,'JGLOBAL_AUTH_PASS_BLANK','LDAP não aceita senha em branco','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (602,'JGLOBAL_AUTH_UNKNOWN_ACCESS_DENIED','Resultado Desconhecido. Acesso Negado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (603,'JGLOBAL_AUTH_USER_BLACKLISTED','Usuário na lista-negra','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (604,'JGLOBAL_AUTH_USER_NOT_FOUND','Impossível encontrar usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (605,'JGLOBAL_AUTO','Auto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (606,'JGLOBAL_CATEGORY_NOT_FOUND','Categoria não encontrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (607,'JGLOBAL_CLICK_TO_SORT_THIS_COLUMN','Clique para classificar por essa coluna','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (608,'JGLOBAL_CREATED_DATE_ON','Criado em %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (609,'JGLOBAL_DESCRIPTION','Descrição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (610,'JGLOBAL_DISPLAY_NUM','Exibir #','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (611,'JGLOBAL_EDIT','Editar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (612,'JGLOBAL_EMAIL','E-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (613,'JGLOBAL_FIELD_CREATED_BY_ALIAS_DESC','Exibe o apelido do autor ao invés do nome do autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (614,'JGLOBAL_FIELD_CREATED_BY_ALIAS_LABEL','Apelido do Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (615,'JGLOBAL_FIELD_FEATURED_DESC','Atribui artigo ao layout de destaques do blog','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (616,'JGLOBAL_FIELD_FEATURED_LABEL','Destaques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (617,'JGLOBAL_FIELD_PUBLISH_DOWN_DESC','Uma data de opcional para interromper a publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (618,'JGLOBAL_FIELD_PUBLISH_DOWN_LABEL','Fim da Publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (619,'JGLOBAL_FIELD_PUBLISH_UP_DESC','Uma data opcional para inciar a publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (620,'JGLOBAL_FIELD_PUBLISH_UP_LABEL','Publicar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (621,'JGLOBAL_FILTER_BUTTON','Filtro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (622,'JGLOBAL_FILTER_LABEL','Filtro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (623,'JGLOBAL_FULL_TEXT','Texto completo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (624,'JGLOBAL_GT','&gt;','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (625,'JGLOBAL_HITS','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (626,'JGLOBAL_ICON_SEP','|','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (627,'JGLOBAL_INHERIT','Herdado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (628,'JGLOBAL_INTRO_TEXT','Texto de Introdução','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (629,'JGLOBAL_LEFT','Esquerda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (630,'JGLOBAL_LT','&lt;','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (631,'JGLOBAL_NEWITEMSLAST_DESC','Os novos itens são colocados nas últimas posições. A ordem pode ser alterada após este item ser salvo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (632,'JGLOBAL_NUM','#','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (633,'JGLOBAL_PASSWORD','Senha','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (634,'JGLOBAL_PRINT','Imprimir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (635,'JGLOBAL_RECORD_NUMBER','Registro ID: %d','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (636,'JGLOBAL_REMEMBER_ME','Lembrar-me','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (637,'JGLOBAL_RESOURCE_NOT_FOUND','Recurso não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (638,'JGLOBAL_RIGHT','Direita','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (639,'JGLOBAL_ROOT','Raiz','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (640,'JGLOBAL_START_PUBLISH_AFTER_FINISH','Item data de publicação de início deve ser antes de data de encerramento da publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (641,'JGLOBAL_SUBCATEGORIES','Sub-categorias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (642,'JGLOBAL_TITLE','Título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (643,'JGLOBAL_USE_GLOBAL','Usar Global','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (644,'JGLOBAL_USERNAME','Nome de Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (645,'JGLOBAL_VALIDATION_FORM_FAILED','Formulário inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (646,'JGLOBAL_YOU_MUST_LOGIN_FIRST','Por favor, faça login primeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (647,'JGRID_HEADING_ACCESS','Acesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (648,'JGRID_HEADING_ID','ID','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (649,'JGRID_HEADING_LANGUAGE','Idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (650,'JLIB_DATABASE_ERROR_ADAPTER_MYSQL','O adaptador do MySQL \'mysql\' não está disponível.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (651,'JLIB_DATABASE_ERROR_ADAPTER_MYSQLI','O adaptador do MySQL \'mysqli\' não está disponível.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (652,'JLIB_DATABASE_ERROR_CONNECT_DATABASE','Não foi possível conectar ao banco de dados: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (653,'JLIB_DATABASE_ERROR_CONNECT_MYSQL','Não foi possível conectar ao MySQL.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (654,'JLIB_DATABASE_ERROR_DATABASE_CONNECT','Não foi possível conectar ao banco de dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (655,'JLIB_DATABASE_ERROR_LOAD_DATABASE_DRIVER','Não foi possível carregar o driver do banco de dados: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (656,'JLIB_ERROR_INFINITE_LOOP','Loop infinito detectado em JError','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (657,'JOPTION_SELECT_ACCESS','- Selecionar Acesso -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (658,'JOPTION_SELECT_CATEGORY','- Selecionar Categoria -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (659,'JOPTION_SELECT_LANGUAGE','- Selecionar Idioma -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (660,'JOPTION_SELECT_PUBLISHED','- Selecionar Estado -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (661,'JOPTION_USE_DEFAULT','- Usar Padrão -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (662,'JSEARCH_FILTER_CLEAR','Limpar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (663,'JSEARCH_FILTER_LABEL','Filtrar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (664,'JSEARCH_FILTER_SUBMIT','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (665,'DATE_FORMAT_LC','l, d F Y','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (666,'DATE_FORMAT_LC1','l, d F Y','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (667,'DATE_FORMAT_LC2','l, d F Y H:i','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (668,'DATE_FORMAT_LC3','d F Y','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (669,'DATE_FORMAT_LC4','d.m.y','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (670,'DATE_FORMAT_JS1','d-m-y','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (671,'JANUARY_SHORT','Jan','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (672,'JANUARY','Janeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (673,'FEBRUARY_SHORT','Fev','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (674,'FEBRUARY','Fevereiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (675,'MARCH_SHORT','Mar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (676,'MARCH','Março','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (677,'APRIL_SHORT','Abr','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (678,'APRIL','Abril','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (679,'MAY_SHORT','Mai','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (680,'MAY','Maio','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (681,'JUNE_SHORT','Jun','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (682,'JUNE','Junho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (683,'JULY_SHORT','Jul','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (684,'JULY','Julho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (685,'AUGUST_SHORT','Ago','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (686,'AUGUST','Agosto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (687,'SEPTEMBER_SHORT','Set','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (688,'SEPTEMBER','Setembro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (689,'OCTOBER_SHORT','Out','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (690,'OCTOBER','Outubro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (691,'NOVEMBER_SHORT','Nov','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (692,'NOVEMBER','Novembro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (693,'DECEMBER_SHORT','Dez','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (694,'DECEMBER','Dezembro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (695,'SAT','Sab','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (696,'SATURDAY','Sábado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (697,'SUN','Dom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (698,'SUNDAY','Domingo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (699,'MON','Seg','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (700,'MONDAY','Segunda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (701,'TUE','Ter','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (702,'TUESDAY','Terça','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (703,'WED','Qua','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (704,'WEDNESDAY','Quarta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (705,'THU','Qui','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (706,'THURSDAY','Quinta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (707,'FRI','Sex','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (708,'FRIDAY','Sexta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (709,'UTC__12_00__INTERNATIONAL_DATE_LINE_WEST','(UTC -12:00) International Date Line West','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (710,'UTC__11_00__MIDWAY_ISLAND__SAMOA','(UTC -11:00) Midway Island, Samoa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (711,'UTC__10_00__HAWAII','(UTC -10:00) Hawaii','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (712,'UTC__09_30__TAIOHAE__MARQUESAS_ISLANDS','(UTC -09:30) Taiohae, Marquesas Islands','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (713,'UTC__09_00__ALASKA','(UTC -09:00) Alaska','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (714,'UTC__08_00__PACIFIC_TIME__US__AMP__CANADA_','(UTC -08:00) Pacific Time (US &amp; Canada)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (715,'UTC__07_00__MOUNTAIN_TIME__US__AMP__CANADA_','(UTC -07:00) Mountain Time (US &amp; Canada)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (716,'UTC__06_00__CENTRAL_TIME__US__AMP__CANADA___MEXICO_CITY','(UTC -06:00) Central Time (US &amp; Canada), Mexico City','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (717,'UTC__05_00__EASTERN_TIME__US__AMP__CANADA___BOGOTA__LIMA','(UTC -05:00) Eastern Time (US &amp; Canada), Bogota, Lima','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (718,'UTC__04_30__VENEZUELA','(UTC -04:30) Venezuela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (719,'UTC__04_00__ATLANTIC_TIME__CANADA___CARACAS__LA_PAZ','(UTC -04:00) Atlantic Time (Canada), Caracas, La Paz','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (720,'UTC__03_30__ST__JOHN_S__NEWFOUNDLAND__LABRADOR','(UTC -03:30) St. John\'s, Newfoundland, Labrador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (721,'UTC__03_00__BRAZIL__BUENOS_AIRES__GEORGETOWN','(UTC -03:00) Brasil, Buenos Aires, Georgetown','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (722,'UTC__02_00__MID_ATLANTIC','(UTC -02:00) Mid-Atlantic','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (723,'UTC__01_00__AZORES__CAPE_VERDE_ISLANDS','(UTC -01:00) Azores, Cape Verde Islands','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (724,'UTC_00_00__WESTERN_EUROPE_TIME__LONDON__LISBON__CASABLANCA','(UTC 00:00) Western Europe Time, London, Lisbon, Casablanca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (725,'UTC__01_00__AMSTERDAM__BERLIN__BRUSSELS__COPENHAGEN__MADRID__PARIS','(UTC +01:00) Amsterdam, Berlin, Brussels, Copenhagen, Madrid, Paris','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (726,'UTC__02_00__ISTANBUL__JERUSALEM__KALININGRAD__SOUTH_AFRICA','(UTC +02:00) Istanbul, Jerusalem, Kaliningrad, South Africa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (727,'UTC__03_00__BAGHDAD__RIYADH__MOSCOW__ST__PETERSBURG','(UTC +03:00) Baghdad, Riyadh, Moscow, St. Petersburg','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (728,'UTC__03_30__TEHRAN','(UTC +03:30) Tehran','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (729,'UTC__04_00__ABU_DHABI__MUSCAT__BAKU__TBILISI','(UTC +04:00) Abu Dhabi, Muscat, Baku, Tbilisi','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (730,'UTC__04_30__KABUL','(UTC +04:30) Kabul','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (731,'UTC__05_00__EKATERINBURG__ISLAMABAD__KARACHI__TASHKENT','(UTC +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (732,'UTC__05_30__BOMBAY__CALCUTTA__MADRAS__NEW_DELHI__COLOMBO','(UTC +05:30) Mumbai, Calcutta, Madras, New Delhi, Colombo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (733,'UTC__05_45__KATHMANDU','(UTC +05:45) Kathmandu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (734,'UTC__06_00__ALMATY__DHAKA','(UTC +06:00) Almaty, Dhaka','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (735,'UTC__06_30__YAGOON','(UTC +06:30) Yagoon','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (736,'UTC__07_00__BANGKOK__HANOI__JAKARTA__PHNOM_PENH','(UTC +07:00) Bangkok, Hanoi, Jakarta, Phnom Penh','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (737,'UTC__08_00__BEIJING__PERTH__SINGAPORE__HONG_KONG','(UTC +08:00) Beijing, Perth, Singapore, Hong Kong','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (738,'UTC__08_00__WESTERN_AUSTRALIA','(UTC +08:00) Western Australia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (739,'UTC__09_00__TOKYO__SEOUL__OSAKA__SAPPORO__YAKUTSK','(UTC +09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (740,'UTC__09_30__ADELAIDE__DARWIN__YAKUTSK','(UTC +09:30) Adelaide, Darwin, Yakutsk','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (741,'UTC__10_00__EASTERN_AUSTRALIA__GUAM__VLADIVOSTOK','(UTC +10:00) Eastern Australia, Guam, Vladivostok','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (742,'UTC__10_30__LORD_HOWE_ISLAND__AUSTRALIA_','(UTC +10:30) Lord Howe Island (Australia)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (743,'UTC__11_00__MAGADAN__SOLOMON_ISLANDS__NEW_CALEDONIA','(UTC +11:00) Magadan, Solomon Islands, New Caledonia','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (744,'UTC__11_30__NORFOLK_ISLAND','(UTC +11:30) Norfolk Island','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (745,'UTC__12_00__AUCKLAND__WELLINGTON__FIJI__KAMCHATKA','(UTC +12:00) Auckland, Wellington, Fiji, Kamchatka','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (746,'UTC__12_45__CHATHAM_ISLAND','(UTC +12:45) Chatham Island','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (747,'UTC__13_00__TONGA','(UTC +13:00) Tonga','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (748,'UTC__14_00__KIRIBATI','(UTC +14:00) Kiribati','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (749,'PHPMAILER_PROVIDE_ADDRESS','Você deve fornecer pelo menos um destinatário.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (750,'PHPMAILER_MAILER_IS_NOT_SUPPORTED','Sistema de e-mail não suportado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (751,'PHPMAILER_EXECUTE','Não foi possível executar:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (752,'PHPMAILER_INSTANTIATE','Não foi possível instanciar a função mail.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (753,'PHPMAILER_AUTHENTICATE','Erro no SMTP! Não foi possível autenticar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (754,'PHPMAILER_FROM_FAILED','O seguinte endereço falhou:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (755,'PHPMAILER_RECIPIENTS_FAILED','Erro no SMTP! Erro nos seguintes destinatários:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (756,'PHPMAILER_DATA_NOT_ACCEPTED','Erro no SMTP! Dados não aceitos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (757,'PHPMAILER_CONNECT_HOST','Erro no SMTP! Não foi possível conectar ao servidor SMTP.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (758,'PHPMAILER_FILE_ACCESS','Não foi possível acessar arquivo:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (759,'PHPMAILER_FILE_OPEN','Erro de Arquivo: Não foi possível abrir arquivo:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (760,'PHPMAILER_ENCODING','Codificação desconhecida:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (761,'PHPMAILER_SIGNING_ERROR','Erro de assinatura:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (762,'PHPMAILER_SMTP_ERROR','Erro no servidor SMTP:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (763,'PHPMAILER_EMPTY_MESSAGE','Corpo da mensagem vazio','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (764,'PHPMAILER_INVALID_ADDRESS','Endereço inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (765,'PHPMAILER_VARIABLE_SET','Não é possível definir ou redefinir variável:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (766,'PHPMAILER_SMTP_CONNECT_FAILED','Falha na conexão SMTP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (767,'PHPMAILER_TLS','Não foi possível iniciar TLS','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.ini'),
 (768,'JERROR_PARSING_LANGUAGE_FILE','&#160;: Erro(s) na(s) linha(s) %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (769,'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN','Acesso negado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (770,'JLIB_APPLICATION_ERROR_APPLICATION_GET_NAME','JApplication: :getName() : Não foi possível obter ou analisar o nome da classe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (771,'JLIB_APPLICATION_ERROR_APPLICATION_LOAD','Não foi possível carregar o aplicativo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (772,'JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE','Você não tem permissão para criar novos itens nesta categoria.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (773,'JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT','Você não tem permissão para editar um ou mais destes itens.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (774,'JLIB_APPLICATION_ERROR_BATCH_FAILED','Processo em lote falhou com o seguinte erro: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (775,'JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND','A categoria de destino não pôde ser encontrada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (776,'JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND','Não foi possível encontrar o item a ser movido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (777,'JLIB_APPLICATION_ERROR_CHECKIN_FAILED','O desbloqueio falhou por causa do seguinte erro: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (778,'JLIB_APPLICATION_ERROR_CHECKIN_NOT_CHECKED','O item não está bloqueado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (779,'JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH','A verificação do usuário não coincidiu com o usuário que fez o checagem do item.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (780,'JLIB_APPLICATION_ERROR_CHECKOUT_FAILED','O bloqueio falhou por causa do seguinte erro: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (781,'JLIB_APPLICATION_ERROR_CHECKOUT_USER_MISMATCH','A verificação do usuário não coincidiu com o usuário que fez o checagem do item.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (782,'JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND','Componente não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (783,'JLIB_APPLICATION_ERROR_COMPONENT_NOT_LOADING','Erro ao carregar componente: %1$s, %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (784,'JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME','JController: :getName() : Não é possível obter ou analisar nome da classe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (785,'JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED','Não foi permitido criar registro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (786,'JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED','Exclusão não permitida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (787,'JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED','Estado de edição não permitido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (788,'JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED','Editar não é permitido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (789,'JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED','Edição não permitida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (790,'JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION','Informação insuficiente para realizar operação em massa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (791,'JLIB_APPLICATION_ERROR_INVALID_CONTROLLER_CLASS','Classe controladora inválida: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (792,'JLIB_APPLICATION_ERROR_INVALID_CONTROLLER','Controlador inválido: nome=\'%s\', formato=\'%s\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (793,'JLIB_APPLICATION_ERROR_LAYOUTFILE_NOT_FOUND','Layout %s não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (794,'JLIB_APPLICATION_ERROR_MODEL_GET_NAME','JModel: :getName() : Não foi possível obter ou analisar o nome da classe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (795,'JLIB_APPLICATION_ERROR_MODULE_LOAD','Erro ao carregar módulo %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (796,'JLIB_APPLICATION_ERROR_PATHWAY_LOAD','Não foi possível carregar o caminho: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (797,'JLIB_APPLICATION_ERROR_REORDER_FAILED','Reordenar falhou. Erro: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (798,'JLIB_APPLICATION_ERROR_ROUTER_LOAD','Não foi possível carregar o roteador: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (799,'JLIB_APPLICATION_ERROR_MODELCLASS_NOT_FOUND','Classe modelo %s não encontrada no arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (800,'JLIB_APPLICATION_ERROR_SAVE_FAILED','O salvamento falhou por conta do seguinte erro: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (801,'JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED','Não é permitido salvar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (802,'JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED','Tabela %s não suportada. Arquivo não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (803,'JLIB_APPLICATION_ERROR_TASK_NOT_FOUND','Tarefa [%s] não encontrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (804,'JLIB_APPLICATION_ERROR_UNHELD_ID','Você não tem permissão para usar este link para acessar diretamente esta página (#%d).','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (805,'JLIB_APPLICATION_ERROR_VIEW_CLASS_NOT_FOUND','Classe de visualização não encontrada [class, file]: %1$s, %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (806,'JLIB_APPLICATION_ERROR_VIEW_GET_NAME_SUBSTRING','JView: :getName() : Seu classname contém a substring \'view\'. Isso causa problemas ao extrair o nome da classe a partir do nome de seus objetos de exibição. Evite nomes de objeto com a substring \'view\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (807,'JLIB_APPLICATION_ERROR_VIEW_GET_NAME','JView: :getName() : Não foi possível obter ou analisar o nome da classe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (808,'JLIB_APPLICATION_ERROR_VIEW_NOT_FOUND','Visualização não encontrada [nome, tipo, prefixo]: %1$s, %2$s, %3$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (809,'JLIB_APPLICATION_SAVE_SUCCESS','Item salvo com sucesso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (810,'JLIB_APPLICATION_SUBMIT_SAVE_SUCCESS','Item enviando com sucesso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (811,'JLIB_APPLICATION_SUCCESS_BATCH','Processamento em massa finalizado com sucesso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (812,'JLIB_APPLICATION_SUCCESS_ITEM_REORDERED','Item reordenado com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (813,'JLIB_APPLICATION_SUCCESS_ORDERING_SAVED','A ordenação foi salva com sucesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (814,'JLIB_LOGIN_AUTHENTICATE','Nome de usuário e a senha não correspondem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (815,'JLIB_CACHE_ERROR_CACHE_HANDLER_LOAD','Não foi possível carregar Cache Handler: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (816,'JLIB_CACHE_ERROR_CACHE_STORAGE_LOAD','Não foi possivel carregar Cache Storage: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (817,'JLIB_CAPTCHA_ERROR_PLUGIN_NOT_FOUND','Plugin captcha não configurado ou não encontrado. Por favor, entre em contato com o administrador do site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (818,'JLIB_CLIENT_ERROR_JFTP_NO_CONNECT','JFTP: :connect: Não foi possível conectar ao host \' %1$s \' na porta \' %2$s \'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (819,'JLIB_CLIENT_ERROR_JFTP_NO_CONNECT_SOCKET','JFTP: :connect: Não foi possível conectar ao host \' %1$s \' na porta \' %2$s \'. Número de erro do socket: %3$s e mensagem de erro: %4$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (820,'JLIB_CLIENT_ERROR_JFTP_BAD_RESPONSE','JFTP: :connect: Má resposta. Resposta do servidor: %s [Expected: 220]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (821,'JLIB_CLIENT_ERROR_JFTP_BAD_USERNAME','JFTP: :login: Nome de usuário inválido. Resposta do servidor: %1$s [Expected: 331]. Nome de usuário enviado: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (822,'JLIB_CLIENT_ERROR_JFTP_BAD_PASSWORD','JFTP: :login: Senha inválida. Resposta do servidor: %1$s [Expected: 230]. Senha enviada: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (823,'JLIB_CLIENT_ERROR_JFTP_PWD_BAD_RESPONSE_NATIVE','FTP: :pwd: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (824,'JLIB_CLIENT_ERROR_JFTP_PWD_BAD_RESPONSE','JFTP: :pwd: Má resposta. Resposta do servidor: %s [Expected: 257]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (825,'JLIB_CLIENT_ERROR_JFTP_SYST_BAD_RESPONSE_NATIVE','JFTP: :syst: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (826,'JLIB_CLIENT_ERROR_JFTP_SYST_BAD_RESPONSE','JFTP: :syst: Má resposta. Resposta do servidor: %s [Expected: 215]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (827,'JLIB_CLIENT_ERROR_JFTP_CHDIR_BAD_RESPONSE_NATIVE','JFTP: :chdir: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (828,'JLIB_CLIENT_ERROR_JFTP_CHDIR_BAD_RESPONSE','JFTP: :chdir: Má resposta. Resposta do servidor: %1$s [Expected: 250]. Caminho enviado: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (829,'JLIB_CLIENT_ERROR_JFTP_REINIT_BAD_RESPONSE_NATIVE','JFTP: :reinit: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (830,'JLIB_CLIENT_ERROR_JFTP_REINIT_BAD_RESPONSE','JFTP: :reinit: Má resposta. Resposta do servidor: %s [Expected: 220]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (831,'JLIB_CLIENT_ERROR_JFTP_RENAME_BAD_RESPONSE_NATIVE','JFTP: :rename: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (832,'JLIB_CLIENT_ERROR_JFTP_RENAME_BAD_RESPONSE_FROM','JFTP: :rename: Má resposta. Resposta do servidor: %1$s [Expected: 250]. Para o caminho enviado: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (833,'JLIB_CLIENT_ERROR_JFTP_RENAME_BAD_RESPONSE_TO','JFTP: :rename: Resposta incorreta. Resposta do servidor: %1$s [Esperado: 250]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (834,'JLIB_CLIENT_ERROR_JFTP_CHMOD_BAD_RESPONSE_NATIVE','JFTP: :chmod: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (835,'JLIB_CLIENT_ERROR_JFTP_CHMOD_BAD_RESPONSE','JFTP: :chmod: Má resposta. Resposta do Servidor: %1$s [Esperado: 250]. Caminho do envio: %2$s. Modo enviado: %3$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (836,'JLIB_CLIENT_ERROR_JFTP_DELETE_BAD_RESPONSE_NATIVE','JFTP: :deletar: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (837,'JLIB_CLIENT_ERROR_JFTP_DELETE_BAD_RESPONSE','JFTP: :deletar: Má resposta. Resposta do Servidor: %1$s [Esperado: 250]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (838,'JLIB_CLIENT_ERROR_JFTP_MKDIR_BAD_RESPONSE_NATIVE','JFTP: :mkdir: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (839,'JLIB_CLIENT_ERROR_JFTP_MKDIR_BAD_RESPONSE','JFTP: :mkdir: Má resposta. Resposta do Servidor: %1$s [Esperado: 257]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (840,'JLIB_CLIENT_ERROR_JFTP_RESTART_BAD_RESPONSE_NATIVE','JFTP: :reiniciar: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (841,'JLIB_CLIENT_ERROR_JFTP_RESTART_BAD_RESPONSE','JFTP: :restart: Má resposta. Resposta do Servidor: %1$s [Esperado: 350]. Reiniciar do ponto enviado: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (842,'JLIB_CLIENT_ERROR_JFTP_CREATE_BAD_RESPONSE_BUFFER','JFTP: :criado: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (843,'JLIB_CLIENT_ERROR_JFTP_CREATE_BAD_RESPONSE_PASSIVE','JFTP: :criado: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (844,'JLIB_CLIENT_ERROR_JFTP_CREATE_BAD_RESPONSE','JFTP: :criado: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 or 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (845,'JLIB_CLIENT_ERROR_JFTP_CREATE_BAD_RESPONSE_TRANSFER','JFTP: :criado: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (846,'JLIB_CLIENT_ERROR_JFTP_READ_BAD_RESPONSE_BUFFER','JFTP: :leitura: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (847,'JLIB_CLIENT_ERROR_JFTP_READ_BAD_RESPONSE_PASSIVE','JFTP: :leitura: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (848,'JLIB_CLIENT_ERROR_JFTP_READ_BAD_RESPONSE','JFTP: :leitura: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 or 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (849,'JLIB_CLIENT_ERROR_JFTP_READ_BAD_RESPONSE_TRANSFER','JFTP: :leitura: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (850,'JLIB_CLIENT_ERROR_JFTP_GET_BAD_RESPONSE','JFTP: :get: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (851,'JLIB_CLIENT_ERROR_JFTP_GET_PASSIVE','JFTP: :get: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (852,'JLIB_CLIENT_ERROR_JFTP_GET_WRITING_LOCAL','JFTP: :get: Não é possível abrir o arquivo local para escrita. Caminho do local: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (853,'JLIB_CLIENT_ERROR_JFTP_GET_BAD_RESPONSE_RETR','JFTP: :get: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 or 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (854,'JLIB_CLIENT_ERROR_JFTP_GET_BAD_RESPONSE_TRANSFER','JFTP: :get: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (855,'JLIB_CLIENT_ERROR_JFTP_STORE_PASSIVE','JFTP: :store: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (856,'JLIB_CLIENT_ERROR_JFTP_STORE_BAD_RESPONSE','JFTP: :store: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (857,'JLIB_CLIENT_ERROR_JFTP_STORE_READING_LOCAL','JFTP: :store: Não é possível abrir o arquivo local para leitura. Caminho do local: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (858,'JLIB_CLIENT_ERROR_JFTP_STORE_FIND_LOCAL','JFTP: :store: Não é possível encontrar o arquivo local. Caminho do local: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (859,'JLIB_CLIENT_ERROR_JFTP_STORE_BAD_RESPONSE_STOR','JFTP: :store: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 ou 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (860,'JLIB_CLIENT_ERROR_JFTP_STORE_DATA_PORT','JFTP: :store: Não é possível gravar dados da porta especifica','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (861,'JLIB_CLIENT_ERROR_JFTP_STORE_BAD_RESPONSE_TRANSFER','JFTP: :store: Falha de transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (862,'JLIB_CLIENT_ERROR_JFTP_WRITE_PASSIVE','JFTP: :write: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (863,'JLIB_CLIENT_ERROR_JFTP_WRITE_BAD_RESPONSE','JFTP: :write: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (864,'JLIB_CLIENT_ERROR_JFTP_WRITE_BAD_RESPONSE_STOR','JFTP: :write: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 ou 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (865,'JLIB_CLIENT_ERROR_JFTP_WRITE_DATA_PORT','JFTP: :write: Não é possível gravar dados da porta especifica','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (866,'JLIB_CLIENT_ERROR_JFTP_WRITE_BAD_RESPONSE_TRANSFER','JFTP: :write: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (867,'JLIB_CLIENT_ERROR_JFTP_LISTNAMES_PASSIVE','JFTP: :listNames: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (868,'JLIB_CLIENT_ERROR_JFTP_LISTNAMES_BAD_RESPONSE','JFTP: :listNames: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (869,'JLIB_CLIENT_ERROR_JFTP_LISTNAMES_BAD_RESPONSE_NLST','JFTP: :listNames: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 ou 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (870,'JLIB_CLIENT_ERROR_JFTP_LISTNAMES_BAD_RESPONSE_TRANSFER','JFTP: :listNames: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (871,'JLIB_CLIENT_ERROR_JFTP_LISTDETAILS_BAD_RESPONSE','JFTP: :listDetails: Má resposta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (872,'JLIB_CLIENT_ERROR_JFTP_LISTDETAILS_PASSIVE','JFTP: :listDetails: Não é possível usar o modo passivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (873,'JLIB_CLIENT_ERROR_JFTP_LISTDETAILS_BAD_RESPONSE_LIST','JFTP: :listDetails: Má resposta. Resposta do Servidor: %1$s [Esperado: 150 ou 125]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (874,'JLIB_CLIENT_ERROR_JFTP_LISTDETAILS_BAD_RESPONSE_TRANSFER','JFTP: :listDetails: Falha na transferência. Resposta do Servidor: %1$s [Esperado: 226]. Caminho do envio: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (875,'JLIB_CLIENT_ERROR_JFTP_LISTDETAILS_UNRECOGNISED','JFTP: :listDetails: listagem do diretório com formato desconhecido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (876,'JLIB_CLIENT_ERROR_JFTP_PUTCMD_UNCONNECTED','JFTP: :_putCmd: Não conectado à porta de controle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (877,'JLIB_CLIENT_ERROR_JFTP_PUTCMD_SEND','JFTP ::_putCmd: Não foi possível enviar o comando: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (878,'JLIB_CLIENT_ERROR_JFTP_VERIFYRESPONSE','JFTP: :_verifyResponse: Tempo limite ou resposta desconhecida enquanto aguarda uma resposta do servidor. Resposta do Servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (879,'JLIB_CLIENT_ERROR_JFTP_PASSIVE_CONNECT_PORT','JFTP: :_passive: Não conectado à porta de controle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (880,'JLIB_CLIENT_ERROR_JFTP_PASSIVE_RESPONSE','JFTP: :_passive: Tempo limite ou resposta desconhecida enquanto aguarda uma resposta do servidor. Resposta do Servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (881,'JLIB_CLIENT_ERROR_JFTP_PASSIVE_IP_OBTAIN','JFTP: :_passive: Não foi possível obter IP da porta para a transferência de dados. Resposta do Servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (882,'JLIB_CLIENT_ERROR_JFTP_PASSIVE_IP_VALID','JFTP: :_passive: IP da porta para a transferência de dados não é válido. Resposta do Servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (883,'JLIB_CLIENT_ERROR_JFTP_PASSIVE_CONNECT','JFTP: :_passive: Não foi possível conectar ao host %1$s na porta %2$s. Número de erro do Socket: %3$s a mensagem de erro: %4$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (884,'JLIB_CLIENT_ERROR_JFTP_MODE_BINARY','JFTP: :_mode: Retorno ruim. Resposta do Servidor: %s [Esperado: 200]. Modo de envio: Binário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (885,'JLIB_CLIENT_ERROR_JFTP_MODE_ASCII','JFTP: :_mode: Má resposta. Resposta do Servidor: %s [Esperado: 200]. Modo de envio: AscII','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (886,'JLIB_CLIENT_ERROR_HELPER_SETCREDENTIALSFROMREQUEST_FAILED','Parece que as credenciais do usuário não são boas ...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (887,'JLIB_CLIENT_ERROR_LDAP_ADDRESS_NOT_AVAILABLE','Endereço não disponível.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (888,'JLIB_DATABASE_ERROR_ADAPTER_MYSQL','O adaptador do MySQL \'mysql\' não está disponível.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (889,'JLIB_DATABASE_ERROR_ADAPTER_MYSQLI','O adaptador do MySQL \'mysqli\' não está disponível.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (890,'JLIB_DATABASE_ERROR_ANCESTOR_NODES_LOWER_STATE','Não é permitido trocar o estado de publicação quando o item pai possuí hierarquia mais baixa.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (891,'JLIB_DATABASE_ERROR_BIND_FAILED_INVALID_SOURCE_ARGUMENT','%s: :falha ao vincular. Fonte do argumento é inválida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (892,'JLIB_DATABASE_ERROR_ARTICLE_UNIQUE_ALIAS','Outro artigo desta categoria tem o mesmo apelido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (893,'JLIB_DATABASE_ERROR_CATEGORY_UNIQUE_ALIAS','Outra categoria com a mesma categoria pai tem o mesmo apelido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (894,'JLIB_DATABASE_ERROR_CHECK_FAILED','%s: :Falha ao verificar - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (895,'JLIB_DATABASE_ERROR_CHECKIN_FAILED','%s: :Falha ao verificar a entrada - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (896,'JLIB_DATABASE_ERROR_CHECKOUT_FAILED','%s: :Falha ao verificar a saída - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (897,'JLIB_DATABASE_ERROR_CHILD_ROWS_CHECKED_OUT','linhas filhas desbloqueadas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (898,'JLIB_DATABASE_ERROR_CLASS_DOES_NOT_SUPPORT_ORDERING','%s não suporta ordenação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (899,'JLIB_DATABASE_ERROR_CLASS_IS_MISSING_FIELD','Falta de campo no banco de dados: %s &#160;%s.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (900,'JLIB_DATABASE_ERROR_CLASS_NOT_FOUND_IN_FILE','Classe da tabela %s arquivo não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (901,'JLIB_DATABASE_ERROR_CONNECT_DATABASE','Não foi possível conectar ao banco de dados: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (902,'JLIB_DATABASE_ERROR_CONNECT_MYSQL','Não foi possível conectar ao MySQL.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (903,'JLIB_DATABASE_ERROR_DATABASE_CONNECT','Não foi possível conectar ao banco de dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (904,'JLIB_DATABASE_ERROR_DELETE_CATEGORY','Inconsistência de dados. Não é possível excluir a categoria.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (905,'JLIB_DATABASE_ERROR_DELETE_FAILED','%s: :Falha ao deletar - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (906,'JLIB_DATABASE_ERROR_DELETE_ROOT_CATEGORIES','categorias da raiz não podem ser deletadas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (907,'JLIB_DATABASE_ERROR_EMAIL_INUSE','Este e-mail já está cadastrado!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (908,'JLIB_DATABASE_ERROR_EMPTY_ROW_RETURNED','A linha do banco de dados está vazio.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (909,'JLIB_DATABASE_ERROR_FUNCTION_FAILED','Função do Banco de Dados falhou. Número do erro %s <br /><font color=\"red\">%s</font>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (910,'JLIB_DATABASE_ERROR_GET_NEXT_ORDER_FAILED','%s: :getNextOrder falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (911,'JLIB_DATABASE_ERROR_GET_TREE_FAILED','%s: :getTree Falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (912,'JLIB_DATABASE_ERROR_GETNODE_FAILED','%s: :_getNode Falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (913,'JLIB_DATABASE_ERROR_GETROOTID_FAILED','%s: :getRootId Falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (914,'JLIB_DATABASE_ERROR_HIT_FAILED','%s: :hit Falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (915,'JLIB_DATABASE_ERROR_INVALID_LOCATION','%s: :setLocation - Local inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (916,'JLIB_DATABASE_ERROR_INVALID_NODE_RECURSION','%s: :Falhar ao mover - Não é possível mover o nó, pode ser uma infantilidade de si mesmo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (917,'JLIB_DATABASE_ERROR_INVALID_PARENT_ID','ID pai inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (918,'JLIB_DATABASE_ERROR_LANGUAGE_NO_TITLE','A linguagem deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (919,'JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_IMAGE','Uma linguagem de conteúdo já existe com este Prefixo de Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (920,'JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_LANG_CODE','Uma linguagem de conteúdo já existe com este Tag de Linguagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (921,'JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_SEF','Uma linguagem de conteúdo já existe com este Código de Linguagem de URL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (922,'JLIB_DATABASE_ERROR_LOAD_DATABASE_DRIVER','Não foi possível carregar o driver do banco de dados: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (923,'JLIB_DATABASE_ERROR_MENUTYPE_EMPTY','Tipo de Menu vazio','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (924,'JLIB_DATABASE_ERROR_MENUTYPE_EXISTS','Tipo de Menu existe: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (925,'JLIB_DATABASE_ERROR_MENUTYPE','Alguns itens de menu ou alguns módulos de menu relacionados com este tipo de menu são checados por um outro usuário ou o item de menu é padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (926,'JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT','O menu inicial para os idiomas não podem ser removidos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (927,'JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT_DEFAULT','Pelo menos um item de menu deve estar configurado como padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (928,'JLIB_DATABASE_ERROR_MENU_UNPUBLISH_DEFAULT_HOME','Não é possível despublicar a página inical padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (929,'JLIB_DATABASE_ERROR_MENU_DEFAULT_CHECKIN_USER_MISMATCH','O menu inicia atual para esse idioma esta checado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (930,'JLIB_DATABASE_ERROR_MENU_UNIQUE_ALIAS','Outro item principal tem esse mesmo apelido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (931,'JLIB_DATABASE_ERROR_MENU_UNIQUE_ALIAS_ROOT','Outro item de menu tem o mesmo apelido do menu raiz. Raiz é o pais de nível mais alto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (932,'JLIB_DATABASE_ERROR_MENU_HOME_NOT_COMPONENT','O item de menu deve ser um componente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (933,'JLIB_DATABASE_ERROR_MENU_HOME_NOT_UNIQUE_IN_MENU','Um menu deve conter apenas um home Padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (934,'JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_COMPONENT','Um item de menu de primeiro nível não pode ter o apelido de \'componente\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (935,'JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_FOLDER','Um item de menu de primeiro nível não ser \'%s\' porque \'%s\' é uma sub-pasta de sua instalação do Joomla.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (936,'JLIB_DATABASE_ERROR_MOVE_FAILED','%s: :falha ao remover - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (937,'JLIB_DATABASE_ERROR_MUSTCONTAIN_A_TITLE_CATEGORY','A categoria deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (938,'JLIB_DATABASE_ERROR_MUSTCONTAIN_A_TITLE_EXTENSION','A extensão deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (939,'JLIB_DATABASE_ERROR_MUSTCONTAIN_A_TITLE_MODULE','O módulo deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (940,'JLIB_DATABASE_ERROR_NEGATIVE_NOT_PERMITTED','%s não pode ser negativo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (941,'JLIB_DATABASE_ERROR_NO_ROWS_SELECTED','Nenhuma linha selecionada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (942,'JLIB_DATABASE_ERROR_NOT_SUPPORTED_FILE_NOT_FOUND','Tabela %s não são suportadas. Arquivo não encontrado!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (943,'JLIB_DATABASE_ERROR_NULL_PRIMARY_KEY','Chave primária não é permitida, está nula!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (944,'JLIB_DATABASE_ERROR_ORDERDOWN_FAILED','%s: :orderDown falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (945,'JLIB_DATABASE_ERROR_ORDERUP_FAILED','%s: :orderUp falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (946,'JLIB_DATABASE_ERROR_PLEASE_ENTER_A_USER_NAME','Por favor, informe o nome de usuário.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (947,'JLIB_DATABASE_ERROR_PLEASE_ENTER_YOUR_NAME','Por favor, informe seu nome.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (948,'JLIB_DATABASE_ERROR_PUBLISH_FAILED','%s: :publish falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (949,'JLIB_DATABASE_ERROR_REBUILD_FAILED','%s: :rebuild falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (950,'JLIB_DATABASE_ERROR_REBUILDPATH_FAILED','%s: :rebuildPath falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (951,'JLIB_DATABASE_ERROR_REORDER_FAILED','%s: :reorder falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (952,'JLIB_DATABASE_ERROR_REORDER_UPDATE_ROW_FAILED','%s: :reorder atualizar a linha %s falhou - %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (953,'JLIB_DATABASE_ERROR_ROOT_NODE_NOT_FOUND','Nó raiz não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (954,'JLIB_DATABASE_ERROR_STORE_FAILED_UPDATE_ASSET_ID','O campo asset_id não pôde ser atualizado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (955,'JLIB_DATABASE_ERROR_STORE_FAILED','%1$s: :store falhou<br />%2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (956,'JLIB_DATABASE_ERROR_USERGROUP_TITLE','Grupo de usuário deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (957,'JLIB_DATABASE_ERROR_USERGROUP_TITLE_EXISTS','Já existe um grupo de usuários com este título. Títulos devem ser únicos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (958,'JLIB_DATABASE_ERROR_USERNAME_CANNOT_CHANGE','Não pode usar este nome de usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (959,'JLIB_DATABASE_ERROR_USERNAME_INUSE','Nome de usuário já existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (960,'JLIB_DATABASE_ERROR_VALID_AZ09','Por favor, informe um nome de usuário válido. Sem espaços, com o mínimo de %d caracteres e <strong>não</strong> pode conter os seguintes caracteres: < > \\ \" \' &#37; ; ( ) &','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (961,'JLIB_DATABASE_ERROR_VALID_MAIL','Por favor, informe um endereço de e-mail válido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (962,'JLIB_DATABASE_ERROR_VIEWLEVEL','Nível de visualização deve ter um título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (963,'JLIB_DATABASE_FUNCTION_NOERROR','Funções de DB não informaram erros','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (964,'JLIB_DOCUMENT_ERROR_UNABLE_LOAD_DOC_CLASS','Incapaz de carregar a classe do documento','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (965,'JLIB_ENVIRONMENT_SESSION_EXPIRED','Sua sessão expirou. Por favor acesse novamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (966,'JLIB_ERROR_INFINITE_LOOP','Loop infinito detectado em JError','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (967,'JLIB_EVENT_ERROR_DISPATCHER','JDispatcher: :register: Manipulador de eventos não reconhecido. Manipulador: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (968,'JLIB_FILESYSTEM_BZIP_NOT_SUPPORTED','BZip2 não suportado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (969,'JLIB_FILESYSTEM_BZIP_UNABLE_TO_READ','Incapaz de ler o arquivo (bz2)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (970,'JLIB_FILESYSTEM_BZIP_UNABLE_TO_WRITE','Incapaz de escrever o arquivo (bz2)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (971,'JLIB_FILESYSTEM_BZIP_UNABLE_TO_WRITE_FILE','Incapaz de escrever o arquivo (bz2)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (972,'JLIB_FILESYSTEM_GZIP_NOT_SUPPORTED','Zlib não suportada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (973,'JLIB_FILESYSTEM_GZIP_UNABLE_TO_READ','Incapaz de ler o arquivo (gz)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (974,'JLIB_FILESYSTEM_GZIP_UNABLE_TO_WRITE','Incapaz de escrever o arquivo (gz)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (975,'JLIB_FILESYSTEM_GZIP_UNABLE_TO_WRITE_FILE','Incapaz de escrever o arquivo (gz)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (976,'JLIB_FILESYSTEM_GZIP_UNABLE_TO_DECOMPRESS','Incapaz de descompactar os dados (gz)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (977,'JLIB_FILESYSTEM_TAR_UNABLE_TO_READ','Incapaz de ler o arquivo (tar)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (978,'JLIB_FILESYSTEM_TAR_UNABLE_TO_DECOMPRESS','Incapaz de descompactar os dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (979,'JLIB_FILESYSTEM_TAR_UNABLE_TO_CREATE_DESTINATION','Incapaz de criar o destino','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (980,'JLIB_FILESYSTEM_TAR_UNABLE_TO_WRITE_ENTRY','Incapaz de escrever a entrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (981,'JLIB_FILESYSTEM_ZIP_NOT_SUPPORTED','Zlib não suportada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (982,'JLIB_FILESYSTEM_ZIP_UNABLE_TO_READ','Incapaz de ler o arquivo (zip)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (983,'JLIB_FILESYSTEM_ZIP_INFO_FAILED','Falha ao obter a informação ZIP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (984,'JLIB_FILESYSTEM_ZIP_UNABLE_TO_CREATE_DESTINATION','Incapaz de criar o destino','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (985,'JLIB_FILESYSTEM_ZIP_UNABLE_TO_WRITE_ENTRY','Incapaz de escrever a entrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (986,'JLIB_FILESYSTEM_ZIP_UNABLE_TO_READ_ENTRY','Incapaz de ler a entrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (987,'JLIB_FILESYSTEM_ZIP_UNABLE_TO_OPEN_ARCHIVE','Incapaz de abrir o arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (988,'JLIB_FILESYSTEM_ZIP_INVALID_ZIP_DATA','Dados ZIP inválidos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (989,'JLIB_FILESYSTEM_STREAM_FAILED','Falha ao registrar fluxo da string','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (990,'JLIB_FILESYSTEM_UNKNOWNARCHIVETYPE','Tipo de arquivo desconhecido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (991,'JLIB_FILESYSTEM_UNABLE_TO_LOAD_ARCHIVE','Incapaz de carregar o arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (992,'JLIB_FILESYSTEM_ERROR_JFILE_FIND_COPY','JFile: :copy: Não foi possível encontrar ou ler o arquivo: $%s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (993,'JLIB_FILESYSTEM_ERROR_JFILE_STREAMS','JFile: :copy(%1$s, %2$s): %3$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (994,'JLIB_FILESYSTEM_ERROR_COPY_FAILED','Falha ao copiar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (995,'JLIB_FILESYSTEM_DELETE_FAILED','Falha ao deletar %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (996,'JLIB_FILESYSTEM_CANNOT_FIND_SOURCE_FILE','Não foi possível encontrar o arquivo de origem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (997,'JLIB_FILESYSTEM_ERROR_JFILE_MOVE_STREAMS','JFile: :move: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (998,'JLIB_FILESYSTEM_ERROR_RENAME_FILE','Falha ao tentar renomear','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (999,'JLIB_FILESYSTEM_ERROR_READ_UNABLE_TO_OPEN_FILE','JFile: :read: Incapaz de abrir arquivo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1000,'JLIB_FILESYSTEM_ERROR_WRITE_STREAMS','JFile: :write(%1$s): %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1001,'JLIB_FILESYSTEM_ERROR_UPLOAD','JFile: :upload: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1002,'JLIB_FILESYSTEM_ERROR_WARNFS_ERR01','Atenção: falha ao alterar permissões de arquivo!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1003,'JLIB_FILESYSTEM_ERROR_WARNFS_ERR02','Aviso: Falha ao mover o arquivo!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1004,'JLIB_FILESYSTEM_ERROR_FIND_SOURCE_FOLDER','Não é possível encontrar a diretório de origem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1005,'JLIB_FILESYSTEM_ERROR_FOLDER_EXISTS','O diretório já existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1006,'JLIB_FILESYSTEM_ERROR_FOLDER_CREATE','Não é possível criar um diretório de destino','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1007,'JLIB_FILESYSTEM_ERROR_FOLDER_OPEN','Não é possível abrir o diretório de origem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1008,'JLIB_FILESYSTEM_ERROR_FOLDER_LOOP','Loop infinito detectado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1009,'JLIB_FILESYSTEM_ERROR_FOLDER_PATH','Caminho não incluído nos caminhos open_basedir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1010,'JLIB_FILESYSTEM_ERROR_COULD_NOT_CREATE_DIRECTORY','Não foi possível criar um diretório','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1011,'JLIB_FILESYSTEM_ERROR_DELETE_BASE_DIRECTORY','Você não pode excluir o diretório base.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1012,'JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER','JFolder::delete: O caminho não é um diretório válido. Caminho: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1013,'JLIB_FILESYSTEM_ERROR_FOLDER_DELETE','JFolder::delete: Não foi possível excluir o diretório. Caminho: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1014,'JLIB_FILESYSTEM_ERROR_FOLDER_RENAME','Falha ao tentar renomear: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1015,'JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER_FILES','JFolder::files: O caminho não é um diretório. Caminho: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1016,'JLIB_FILESYSTEM_ERROR_PATH_IS_NOT_A_FOLDER_FOLDER','JFolder::folder: O caminho não é um diretório. Caminho: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1017,'JLIB_FILESYSTEM_ERROR_STREAMS_FILE_SIZE','Falha ao obter o tamanho do arquivo. Isso pode não funcionar para todos os streams!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1018,'JLIB_FILESYSTEM_ERROR_STREAMS_FILE_NOT_OPEN','Arquivo não abre','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1019,'JLIB_FILESYSTEM_ERROR_STREAMS_FILENAME','Nome do arquivo não definido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1020,'JLIB_FILESYSTEM_ERROR_NO_DATA_WRITTEN','Atenção: Não há dados escritos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1021,'JLIB_FILESYSTEM_ERROR_STREAMS_FAILED_TO_OPEN_WRITER','Falha ao abrir: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1022,'JLIB_FILESYSTEM_ERROR_STREAMS_FAILED_TO_OPEN_READER','Erro na leitura: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1023,'JLIB_FILESYSTEM_ERROR_STREAMS_NOT_UPLOADED_FILE','O arquivo não pode ser enviado!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1024,'JLIB_FORM_BUTTON_CLEAR','Limpar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1025,'JLIB_FORM_BUTTON_SELECT','Selecionar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1026,'JLIB_FORM_CHANGE_IMAGE','Mudar imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1027,'JLIB_FORM_CHANGE_IMAGE_BUTTON','Mudar imagem do botão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1028,'JLIB_FORM_CHANGE_USER','Selecione um usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1029,'JLIB_FORM_ERROR_FIELDS_CATEGORY_ERROR_EXTENSION_EMPTY','Atributo da extensão está vazio no campo da categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1030,'JLIB_FORM_ERROR_FIELDS_GROUPEDLIST_ELEMENT_NAME','Tipo de elemento desconhecido: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1031,'JLIB_FORM_ERROR_NO_DATA','Sem dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1032,'JLIB_FORM_ERROR_VALIDATE_FIELD','Campo XML inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1033,'JLIB_FORM_ERROR_XML_FILE_DID_NOT_LOAD','O arquivo XML não carrega','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1034,'JLIB_FORM_INVALID_FORM_OBJECT','Objeto de formulário inválido: :%s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1035,'JLIB_FORM_INVALID_FORM_RULE','Regra inválida do formulário: :%s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1036,'JLIB_FORM_MEDIA_PREVIEW_ALT','Imagem selecionada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1037,'JLIB_FORM_MEDIA_PREVIEW_EMPTY','Nenhuma imagem selecionada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1038,'JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE','Imagem selecionada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1039,'JLIB_FORM_MEDIA_PREVIEW_TIP_TITLE','Exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1040,'JLIB_FORM_SELECT_USER','Selecione um usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1041,'JLIB_FORM_VALIDATE_FIELD_INVALID','Campo inválido: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1042,'JLIB_FORM_VALIDATE_FIELD_REQUIRED','Campo de preenchimento obrigatório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1043,'JLIB_FORM_VALIDATE_FIELD_RULE_MISSING','Regra de validação faltando: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1044,'JLIB_FORM_VALUE_CACHE_APC','APC - Alternative PHP Cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1045,'JLIB_FORM_VALUE_CACHE_CACHELITE','Cache_Lite','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1046,'JLIB_FORM_VALUE_CACHE_EACCELERATOR','eAccelerator','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1047,'JLIB_FORM_VALUE_CACHE_FILE','Arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1048,'JLIB_FORM_VALUE_CACHE_MEMCACHE','Memcache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1049,'JLIB_FORM_VALUE_CACHE_WINCACHE','Cache do Windows','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1050,'JLIB_FORM_VALUE_CACHE_XCACHE','XCache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1051,'JLIB_FORM_VALUE_SESSION_APC','APC - Alternative PHP Cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1052,'JLIB_FORM_VALUE_SESSION_DATABASE','Banco de dados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1053,'JLIB_FORM_VALUE_SESSION_EACCELERATOR','eAccelerator','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1054,'JLIB_FORM_VALUE_SESSION_MEMCACHE','Memcache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1055,'JLIB_FORM_VALUE_SESSION_NONE','Nenhum','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1056,'JLIB_FORM_VALUE_SESSION_WINCACHE','Cache do Windows','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1057,'JLIB_FORM_VALUE_SESSION_XCACHE','XCache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1058,'JLIB_FORM_VALUE_TIMEZONE_UTC','Tempo Universal Coordenado (UTC)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1059,'JLIB_HTML_ACCESS_MODIFY_DESC_CAPTION_ACL','Controle de Acesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1060,'JLIB_HTML_ACCESS_MODIFY_DESC_CAPTION_TABLE','Tabela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1061,'JLIB_HTML_ACCESS_SUMMARY_DESC_CAPTION','Quadro Resumo ACL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1062,'JLIB_HTML_ACCESS_SUMMARY_DESC','Abaixo é exibida uma visão geral das permissões para este artigo. Clique nas guias acima para personalizar essas configurações por ação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1063,'JLIB_HTML_ACCESS_SUMMARY','Resumo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1064,'JLIB_HTML_ADD_TO_ROOT','Adicionar à raiz','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1065,'JLIB_HTML_ADD_TO_THIS_MENU','Adicione a este menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1066,'JLIB_HTML_BATCH_ACCESS_LABEL','Definir Nível de Acesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1067,'JLIB_HTML_BATCH_ACCESS_LABEL_DESC','Não selecionando nada, será mantido o nível de acesso original quando processado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1068,'JLIB_HTML_BATCH_COPY','Copiar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1069,'JLIB_HTML_BATCH_LANGUAGE_LABEL','Definir Idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1070,'JLIB_HTML_BATCH_LANGUAGE_LABEL_DESC','Não fazer uma seleção irá manter o idioma original ao processar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1071,'JLIB_HTML_BATCH_LANGUAGE_NOCHANGE','- Mantenha Idioma Original -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1072,'JLIB_HTML_BATCH_MENU_LABEL','Selecione a Categoria para Mover/Copiar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1073,'JLIB_HTML_BATCH_MOVE','Mover','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1074,'JLIB_HTML_BATCH_NOCHANGE','- Manter Nível de Acesso Original -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1075,'JLIB_HTML_BATCH_USER_LABEL','Definir Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1076,'JLIB_HTML_BATCH_USER_LABEL_DESC','Não fazer uma seleção irá manter o usuário original quando processando.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1077,'JLIB_HTML_BATCH_USER_NOCHANGE','- Manter Usuário Original -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1078,'JLIB_HTML_BATCH_USER_NOUSER','Nenhum Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1079,'JLIB_HTML_BEHAVIOR_ABOUT_THE_CALENDAR','Sobre o calendário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1080,'JLIB_HTML_BEHAVIOR_CLOSE','Fechar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1081,'JLIB_HTML_BEHAVIOR_DATE_SELECTION','Data da seleção:\\n','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1082,'JLIB_HTML_BEHAVIOR_DISPLAY_S_FIRST','Exibir %s primeiro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1083,'JLIB_HTML_BEHAVIOR_DRAG_TO_MOVE','Arraste para mover','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1084,'JLIB_HTML_BEHAVIOR_GO_TODAY','Ir para hoje','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1085,'JLIB_HTML_BEHAVIOR_GREEN','Verde','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1086,'JLIB_HTML_BEHAVIOR_HOLD_MOUSE','- Segure o botão do mouse sobre qualquer um dos botões para seleção rápida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1087,'JLIB_HTML_BEHAVIOR_MONTH_SELECT','- Use os botões < e > para selecionar o mês\\n','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1088,'JLIB_HTML_BEHAVIOR_NEXT_MONTH_HOLD_FOR_MENU','Clique para mudar para o próximo mês. Mantenha o botão do mouse pressionado para que seja exibida a lista de meses.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1089,'JLIB_HTML_BEHAVIOR_NEXT_YEAR_HOLD_FOR_MENU','Clique para mudar para o próximo ano. Mantenha o botão do mouse pressionado para que seja exibida a lista de anos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (1090,'JLIB_HTML_BEHAVIOR_PREV_MONTH_HOLD_FOR_MENU','Clique para mudar para o mês anterior. Mantenha o botão do mouse pressionado para que seja exibida a lista de meses.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1091,'JLIB_HTML_BEHAVIOR_PREV_YEAR_HOLD_FOR_MENU','Clique para mudar para o ano anterior. Mantenha o botão do mouse pressionado para que seja exibida a lista de anos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1092,'JLIB_HTML_BEHAVIOR_SELECT_DATE','Selecionar data.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1093,'JLIB_HTML_BEHAVIOR_SHIFT_CLICK_OR_DRAG_TO_CHANGE_VALUE','(Shift-)Clique ou arraste para trocar valor.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1094,'JLIB_HTML_BEHAVIOR_TIME','Hora:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1095,'JLIB_HTML_BEHAVIOR_TODAY','Hoje','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1096,'JLIB_HTML_BEHAVIOR_TT_DATE_FORMAT','%a, %b %e','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1097,'JLIB_HTML_BEHAVIOR_WK','Semana','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1098,'JLIB_HTML_BEHAVIOR_YEAR_SELECT','- Use os botões seta « e » para selecionar um ano\\n','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1099,'JLIB_HTML_BEHAVIOR_UPLOADER_ERROR_HTTPSTATUS','Resposta do Servidor Inválida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1100,'JLIB_HTML_BEHAVIOR_UPLOADER_ERROR_IOERROR','Erro de Transferência','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1101,'JLIB_HTML_BEHAVIOR_UPLOADER_ERROR_SECURITYERROR','Erro de Segurança','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1102,'JLIB_HTML_BEHAVIOR_UPLOADER_UPLOAD_COMPLETED','Envio Completo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1103,'JLIB_HTML_BEHAVIOR_UPLOADER_FILE_SUCCESSFULLY_UPLOADED','Arquivo enviado com sucesso.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1104,'JLIB_HTML_BEHAVIOR_UPLOADER_ERROR_OCCURRED','Ocorreu um erro: {error}','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1105,'JLIB_HTML_BEHAVIOR_UPLOADER_ALL_FILES','Todos arquivos (*.*)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1106,'JLIB_HTML_BEHAVIOR_UPLOADER_PROGRESS_OVERALL','Progresso total {total}','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1107,'JLIB_HTML_BEHAVIOR_UPLOADER_CURRENT_TITLE','Fazer upload de arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1108,'JLIB_HTML_BEHAVIOR_UPLOADER_REMOVE','Remover','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1109,'JLIB_HTML_BEHAVIOR_UPLOADER_REMOVE_TITLE','Remover título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1110,'JLIB_HTML_BEHAVIOR_UPLOADER_FILENAME','{nome}','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1111,'JLIB_HTML_BEHAVIOR_UPLOADER_CURRENT_FILE','Arquivo selecionado: {nome}','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1112,'JLIB_HTML_BEHAVIOR_UPLOADER_CURRENT_PROGRESS','Progresso do upload','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1113,'JLIB_HTML_BEHAVIOR_UPLOADER_FILE_ERROR','Erro no Arquivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1114,'JLIB_HTML_BEHAVIOR_UPLOADER_VALIDATION_ERROR_DUPLICATE','Arquivo Já Existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1115,'JLIB_HTML_BEHAVIOR_UPLOADER_VALIDATION_ERROR_FILELISTMAX','Arquivos em Excesso','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1116,'JLIB_HTML_BEHAVIOR_UPLOADER_VALIDATION_ERROR_FILELISTSIZEMAX','Tamanho do arquivo a enviar é muito grande','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1117,'JLIB_HTML_BEHAVIOR_UPLOADER_VALIDATION_ERROR_SIZELIMITMAX','Aquivo é muito grande','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1118,'JLIB_HTML_BEHAVIOR_UPLOADER_VALIDATION_ERROR_SIZELIMITMIN','Arquivo é muito pequeno','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1119,'JLIB_HTML_BUTTON_BASE_CLASS','não foi possível carregar no botão da classe base.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1120,'JLIB_HTML_BUTTON_NO_LOAD','não foi possível carregar no botão %s (%s);','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1121,'JLIB_HTML_BUTTON_NOT_DEFINED','Tipo de botão não definido = %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1122,'JLIB_HTML_CALENDAR','Calendário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1123,'JLIB_HTML_CHECKED_OUT','Bloqueado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1124,'JLIB_HTML_CHECKIN','Desbloqueado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1125,'JLIB_HTML_CLOAKING','O endereço de e-mail address está sendo protegido de spambots. Você precisa ativar o JavaScript enabled para vê-lo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1126,'JLIB_HTML_DATE_RELATIVE_DAYS','%s dias atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1127,'JLIB_HTML_DATE_RELATIVE_DAYS_1','%s dia atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1128,'JLIB_HTML_DATE_RELATIVE_DAYS_0','%s dias atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1129,'JLIB_HTML_DATE_RELATIVE_HOURS','%s horas atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1130,'JLIB_HTML_DATE_RELATIVE_HOURS_1','%s hora atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1131,'JLIB_HTML_DATE_RELATIVE_HOURS_0','%s horas atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1132,'JLIB_HTML_DATE_RELATIVE_LESSTHANAMINUTE','Menos de um minuto atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1133,'JLIB_HTML_DATE_RELATIVE_MINUTES','%s minutos atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1134,'JLIB_HTML_DATE_RELATIVE_MINUTES_1','%s minuto atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1135,'JLIB_HTML_DATE_RELATIVE_MINUTES_0','%s minutos atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1136,'JLIB_HTML_DATE_RELATIVE_WEEKS','%s semanas atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1137,'JLIB_HTML_DATE_RELATIVE_WEEKS_1','%s semana atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1138,'JLIB_HTML_DATE_RELATIVE_WEEKS_0','%s semanas atrás','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1139,'JLIB_HTML_EDITOR_CANNOT_LOAD','Não foi possível carregar o editor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1140,'JLIB_HTML_END','Fim','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1141,'JLIB_HTML_ERROR_FUNCTION_NOT_SUPPORTED','Função não suportada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1142,'JLIB_HTML_ERROR_NOTFOUNDINFILE','%s: :%s Arquivo não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1143,'JLIB_HTML_ERROR_NOTSUPPORTED_NOFILE','%s: :%s não suportado. Arquivo não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1144,'JLIB_HTML_ERROR_NOTSUPPORTED','%s: :%s Não suportado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1145,'JLIB_HTML_MOVE_DOWN','Mover para cima','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1146,'JLIB_HTML_MOVE_UP','Mover para baixo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1147,'JLIB_HTML_NO_PARAMETERS_FOR_THIS_ITEM','Não existem parâmetros para este item','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1148,'JLIB_HTML_NO_RECORDS_FOUND','Registro não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1149,'JLIB_HTML_PAGE_CURRENT_OF_TOTAL','Pagina %s de %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1150,'JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST','Por favor, selecione através da lista','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1151,'JLIB_HTML_PUBLISH_ITEM','Publicar artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1152,'JLIB_HTML_PUBLISHED_EXPIRED_ITEM','Publicado, mas Expirado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1153,'JLIB_HTML_PUBLISHED_FINISHED','Fim: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1154,'JLIB_HTML_PUBLISHED_ITEM','Publicado e Atual','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1155,'JLIB_HTML_PUBLISHED_PENDING_ITEM','Publicado, mas Pendente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1156,'JLIB_HTML_PUBLISHED_START','Início: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1157,'JLIB_HTML_RESULTS_OF','Resultados %s - %s de %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1158,'JLIB_HTML_SAVE_ORDER','Salvar ordem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1159,'JLIB_HTML_SELECT_STATE','Selecionar estado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1160,'JLIB_HTML_START','Iniciar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1161,'JLIB_HTML_UNPUBLISH_ITEM','Despublicar artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1162,'JLIB_HTML_VIEW_ALL','Ver todos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1163,'JLIB_HTML_SETDEFAULT_ITEM','Definir padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1164,'JLIB_HTML_UNSETDEFAULT_ITEM','Desfazer padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1165,'JLIB_INSTALLER_ABORT','Parar instalação de idioma: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1166,'JLIB_INSTALLER_ABORT_ALREADYINSTALLED','A extensão já está instalada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1167,'JLIB_INSTALLER_ABORT_COMP_BUILDADMINMENUS_FAILED','Erro ao construir menus da administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1168,'JLIB_INSTALLER_ABORT_COMP_INSTALL_COPY_SETUP','Instalação de Componente: Não foi possível copiar arquivo de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1169,'JLIB_INSTALLER_ABORT_COMP_INSTALL_CUSTOM_INSTALL_FAILURE','Instalação de Componente: Falha na instalação personalizada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1170,'JLIB_INSTALLER_ABORT_COMP_INSTALL_MANIFEST','Instalação de Componente: Não foi possível copiar os arquivos de manifesto php.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1171,'JLIB_INSTALLER_ABORT_COMP_INSTALL_PHP_INSTALL','Instalação de Componente: Não foi possível copiar os arquivos de instalação do php.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1172,'JLIB_INSTALLER_ABORT_COMP_INSTALL_PHP_UNINSTALL','Instalação de Componente: Não foi possível copiar os arquivos de desinstalação do php.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1173,'JLIB_INSTALLER_ABORT_COMP_INSTALL_ROLLBACK','Instalação de Componente: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1174,'JLIB_INSTALLER_ABORT_COMP_INSTALL_SQL_ERROR','Instalação de Componente: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1175,'JLIB_INSTALLER_ABORT_COMP_UPDATE_ADMIN_ELEMENT','Atualização de componente: O arquivo xml não contêm um elemento necessário à administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1176,'JLIB_INSTALLER_ABORT_COMP_UPDATE_COPY_SETUP','Atualização de componente: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1177,'JLIB_INSTALLER_ABORT_COMP_UPDATE_MANIFEST','Atualização de componente: Não foi possível copiar os arquivos de manifesto php.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1178,'JLIB_INSTALLER_ABORT_COMP_UPDATE_PHP_INSTALL','Atualização de componente: Não foi possível copiar os arquivos de instalação do php.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1179,'JLIB_INSTALLER_ABORT_COMP_UPDATE_PHP_UNINSTALL','Atualização de componente: Não foi possível copiar os arquivos de desinstalação do php','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1180,'JLIB_INSTALLER_ABORT_COMP_UPDATE_ROLLBACK','Atualização de componente: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1181,'JLIB_INSTALLER_ABORT_COMP_UPDATE_SQL_ERROR','Atualização de componente: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1182,'JLIB_INSTALLER_ABORT_DEBUG','Instalação terminou inesperadamente:','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1183,'JLIB_INSTALLER_ABORT_DETECTMANIFEST','Não é possível detectar o arquivo de manifesto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1184,'JLIB_INSTALLER_ABORT_EXTENSIONNOTVALID','Extensão desconhecida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1185,'JLIB_INSTALLER_ABORT_FILE_INSTALL_COPY_SETUP','Arquivos de instalação: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1186,'JLIB_INSTALLER_ABORT_FILE_INSTALL_CUSTOM_INSTALL_FAILURE','Arquivos de instalação: Falha na rotina de instalação personalizada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1187,'JLIB_INSTALLER_ABORT_FILE_INSTALL_FAIL_SOURCE_DIRECTORY','Arquivos de instalação: Falha ao localizar diretório de origem: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1188,'JLIB_INSTALLER_ABORT_FILE_INSTALL_ROLLBACK','Arquivos de instalação: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1189,'JLIB_INSTALLER_ABORT_FILE_INSTALL_SQL_ERROR','Arquivos de instalação: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1190,'JLIB_INSTALLER_ABORT_FILE_ROLLBACK','Arquivos de instalação: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1191,'JLIB_INSTALLER_ABORT_FILE_SAME_NAME','Arquivos de instalação: Existe uma extensão com o mesmo nome.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1192,'JLIB_INSTALLER_ABORT_FILE_UPDATE_SQL_ERROR','Atualizações de Arquivos: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1193,'JLIB_INSTALLER_ABORT_LIB_INSTALL_ALREADY_INSTALLED','Instalação de Bibliotecas: Biblioteca já instalada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1194,'JLIB_INSTALLER_ABORT_LIB_INSTALL_COPY_SETUP','Instalação de Bibliotecas: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1195,'JLIB_INSTALLER_ABORT_LIB_INSTALL_FAILED_TO_CREATE_DIRECTORY','Instalação de Bibliotecas: Falha ao criar diretório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1196,'JLIB_INSTALLER_ABORT_LIB_INSTALL_NOFILE','Instalação de Bibliotecas: Nenhum arquivo de biblioteca selecionado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1197,'JLIB_INSTALLER_ABORT_LIB_INSTALL_ROLLBACK','Instalação de Bibliotecas: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1198,'JLIB_INSTALLER_ABORT_LOAD_DETAILS','Falha ao carregar extensão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1199,'JLIB_INSTALLER_ABORT_METHODNOTSUPPORTED','O  método não é suportado por este tipo de extensão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1200,'JLIB_INSTALLER_ABORT_METHODNOTSUPPORTED_TYPE','O  método não é suportado por este tipo de extensão: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1201,'JLIB_INSTALLER_ABORT_MOD_INSTALL_COPY_SETUP','Instalação de Módulos: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1202,'JLIB_INSTALLER_ABORT_MOD_INSTALL_CREATE_DIRECTORY','Módulo %1$s: Falha ao criar diretório: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1203,'JLIB_INSTALLER_ABORT_MOD_INSTALL_CUSTOM_INSTALL_FAILURE','Instalação de Módulos: Falha na instalação personalizada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1204,'JLIB_INSTALLER_ABORT_MOD_INSTALL_DIRECTORY','Módulo %1$s: Outro módulo já está usando o diretório: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1205,'JLIB_INSTALLER_ABORT_MOD_INSTALL_MANIFEST','Instalação de Módulo: Não foi possível copiar arquivo de manifesto do PHP.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1206,'JLIB_INSTALLER_ABORT_MOD_INSTALL_NOFILE','Módulo %s: Nenhum arquivo de módulo especificado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1207,'JLIB_INSTALLER_ABORT_MOD_INSTALL_SQL_ERROR','Módulo %1$s: Erro no arquivo SQL %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1208,'JLIB_INSTALLER_ABORT_MOD_ROLLBACK','Módulo %1$s: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1209,'JLIB_INSTALLER_ABORT_MOD_UNINSTALL_UNKNOWN_CLIENT','Desinstalar módulo: Tipo de cliente desconhecido [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1210,'JLIB_INSTALLER_ABORT_MOD_UNKNOWN_CLIENT','Módulo %1$s: Tipo de cliente desconhecido [%2$s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1211,'JLIB_INSTALLER_ABORT_NOINSTALLPATH',' Caminho de instalação não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1212,'JLIB_INSTALLER_ABORT_NOUPDATEPATH','Caminho de atualização não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1213,'JLIB_INSTALLER_ABORT_PACK_INSTALL_COPY_SETUP','Pacote de instalação: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1214,'JLIB_INSTALLER_ABORT_PACK_INSTALL_CREATE_DIRECTORY','Pacote de instalação: Falha na criação do diretório:%s ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1215,'JLIB_INSTALLER_ABORT_PACK_INSTALL_ERROR_EXTENSION','Pacote %1$s: Houve um erro durante a instalação da extensão: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1216,'JLIB_INSTALLER_ABORT_PACK_INSTALL_NO_FILES','Pacote %s: Não há arquivos a instalar!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1217,'JLIB_INSTALLER_ABORT_PACK_INSTALL_NO_PACK','Pacote %s: Pacote de instalação não selecionado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1218,'JLIB_INSTALLER_ABORT_PACK_INSTALL_ROLLBACK','Pacote de instalação: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1219,'JLIB_INSTALLER_ABORT_PLG_INSTALL_ALLREADY_EXISTS','Plugin %1$s: Plugin %2$s Já existente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1220,'JLIB_INSTALLER_ABORT_PLG_INSTALL_COPY_SETUP','Plugin %s: Não foi possível copiar os arquivos de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1221,'JLIB_INSTALLER_ABORT_PLG_INSTALL_CREATE_DIRECTORY','Plugin %1$s: Falha ao tentar criar o diretório: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1222,'JLIB_INSTALLER_ABORT_PLG_INSTALL_CUSTOM_INSTALL_FAILURE','Instalação de plugin: Falha na instalação personalizada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1223,'JLIB_INSTALLER_ABORT_PLG_INSTALL_DIRECTORY','Plugin %1$s: Já existe um plugin usando este diretório: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1224,'JLIB_INSTALLER_ABORT_PLG_INSTALL_MANIFEST','Plugin %s: Não foi possível copiar os arquivos de manifesto PHP.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1225,'JLIB_INSTALLER_ABORT_PLG_INSTALL_NO_FILE','Plugin %s: Plugin não selecionado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1226,'JLIB_INSTALLER_ABORT_PLG_INSTALL_ROLLBACK','Plugin %1$s: %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1227,'JLIB_INSTALLER_ABORT_PLG_INSTALL_SQL_ERROR','Plugin %1$s: Erro no arquivo SQL %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1228,'JLIB_INSTALLER_ABORT_PLG_UNINSTALL_SQL_ERROR','Desinstalar Plugin: Erro em arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1229,'JLIB_INSTALLER_ABORT_REFRESH_MANIFEST_CACHE','Atualização do cache do manifesto falhou: A extensão não está instalada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1230,'JLIB_INSTALLER_ABORT_REFRESH_MANIFEST_CACHE_VALID','Atualização do cache do manifesto falhou: A extensão não é válida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1231,'JLIB_INSTALLER_ABORT_TPL_INSTALL_ALREADY_INSTALLED','Instalação de Layout: O Layout já está instalado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1232,'JLIB_INSTALLER_ABORT_TPL_INSTALL_ANOTHER_TEMPLATE_USING_DIRECTORY','Instalação de Layout: já existe um modelo usando o diretório: %s. Você está tentando instalar o mesmo modelo novamente?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1233,'JLIB_INSTALLER_ABORT_TPL_INSTALL_COPY_SETUP','Instalação de Layout: Não foi possível copiar arquivo de instalação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1234,'JLIB_INSTALLER_ABORT_TPL_INSTALL_FAILED_CREATE_DIRECTORY','Instalação de Layout: Falha ao criar diretório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1235,'JLIB_INSTALLER_ABORT_TPL_INSTALL_ROLLBACK','Instalação de Layout: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1236,'JLIB_INSTALLER_ABORT_TPL_INSTALL_UNKNOWN_CLIENT','Instalação de Layout: Tipo de cliente desconhecido [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1237,'JLIB_INSTALLER_DEFAULT_STYLE','%s - Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1238,'JLIB_INSTALLER_DISCOVER','Procurar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1239,'JLIB_INSTALLER_ERROR_COMP_DISCOVER_STORE_DETAILS','Procurar componente: Falha ao armazenar dados do componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1240,'JLIB_INSTALLER_ERROR_COMP_INSTALL_ADMIN_ELEMENT','Instalação de Componentes: O arquivo xml não contêm um elemento da administração','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1241,'JLIB_INSTALLER_ERROR_COMP_INSTALL_DIR_ADMIN','Instalação de Componentes: Outro componente já está instalado neste diretório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1242,'JLIB_INSTALLER_ERROR_COMP_INSTALL_DIR_SITE','Instalação de Componentes: Outro componente já está instalado neste diretório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1243,'JLIB_INSTALLER_ERROR_COMP_INSTALL_FAILED_TO_CREATE_DIRECTORY_ADMIN','Instalação de Componentes: Falha ao criar o diretório admin : %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1244,'JLIB_INSTALLER_ERROR_COMP_INSTALL_FAILED_TO_CREATE_DIRECTORY_SITE','Instalação de Componentes: Falha ao criar o diretório do site : %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1245,'JLIB_INSTALLER_ERROR_COMP_REFRESH_MANIFEST_CACHE','Atualizar exibição do componente: Falha ao armazenar detalhes do componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1246,'JLIB_INSTALLER_ERROR_COMP_REMOVING_ADMIN_MENUS_FAILED','Não é permitido remover o menu da administração.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1247,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_CUSTOM','Desinstalar Componente: Roteiro de desinstalação personalizada sem êxito','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1248,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_FAILED_DELETE_CATEGORIES','Desinstalação de Componente: Não foi possível remover as categorias do componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1249,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_ERRORREMOVEMANUALLY','Desinstalar Componente: Não foi possivel desinstalar. Por favor remova manualmente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1250,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_ERRORUNKOWNEXTENSION','Desinstalar Componente: Extensão desconhecida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1251,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_FAILED_REMOVE_DIRECTORY_ADMIN','Desinstalar Componente: Não é possível remover o componente admin diretório','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1252,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_FAILED_REMOVE_DIRECTORY_SITE','Desinstalar Componente: Não é possível remover o componente site diretório','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1253,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_NO_OPTION','Desinstalar Componente: Opção de campo vazio, não é possível remover arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1254,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_SQL_ERROR','Desinstalar Componente: SQL arquivo de erro %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1255,'JLIB_INSTALLER_ERROR_COMP_UNINSTALL_WARNCORECOMPONENT','Desinstalar Componente: Tentando desinstalar um componente central','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1256,'JLIB_INSTALLER_ERROR_COMP_UPDATE_FAILED_TO_CREATE_DIRECTORY_ADMIN','Atualização de componente: Falha ao criar o diretório da administração: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1257,'JLIB_INSTALLER_ERROR_COMP_UPDATE_FAILED_TO_CREATE_DIRECTORY_SITE','Atualização de componente: Falha ao criar o diretório do site: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1258,'JLIB_INSTALLER_ERROR_CREATE_DIRECTORY','JInstaller: :Install: Falha ao criar o diretório: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1259,'JLIB_INSTALLER_ERROR_CREATE_FOLDER_FAILED','Falha ao criar o diretório [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1260,'JLIB_INSTALLER_ERROR_DEPRECATED_FORMAT','Formato de instalação obsoleto (client=\"both\"), use Pacote de instalação da próxima vez','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1261,'JLIB_INSTALLER_ERROR_DOWNLOAD_SERVER_CONNECT','Erro ao conectar ao servidor: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1262,'JLIB_INSTALLER_ERROR_FAIL_COPY_FILE','JInstaller: :Install: Falha ao copiar o arquivo %1$s para %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1263,'JLIB_INSTALLER_ERROR_FAIL_COPY_FOLDER','JInstaller: :Install: Falha ao copiar o diretório %1$s para %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1264,'JLIB_INSTALLER_ERROR_FAILED_READING_NETWORK_RESOURCES','Falha ao ler o recurso de rede: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1265,'JLIB_INSTALLER_ERROR_FILE_EXISTS','JInstaller: :Install: Arquivo já existente %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1266,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_INVALID_MANIFEST','Desinstalação de Arquivos: Arquivo de manifesto inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1267,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalação de Arquivos: Arquivo de manifesto inválido ou não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1268,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_LOAD_ENTRY','Desinstalação de Arquivos: Não foi possível carregar a entrada de extensão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1269,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_LOAD_MANIFEST','Desinstalação de Arquivos: Não foi possível carregar o arquivo de manifesto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1270,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_SQL_ERROR','Desinstalação de Arquivos: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1271,'JLIB_INSTALLER_ERROR_FILE_UNINSTALL_WARNCOREFILE','Desinstalação de Arquivos: Tentando desinstalar arquivos do core','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1272,'JLIB_INSTALLER_ERROR_FOLDER_IN_USE','Outra extensão já está utilizando o diretório [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1273,'JLIB_INSTALLER_ERROR_LANG_DISCOVER_STORE_DETAILS','Instalar Idioma encontrado: Falha ao armazenar detalhes do idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1274,'JLIB_INSTALLER_ERROR_LANG_UNINSTALL_DEFAULT','Este idioma não pode ser desinstalado enquanto estiver definido como um idioma padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1275,'JLIB_INSTALLER_ERROR_LANG_UNINSTALL_DIRECTORY','Desinstalar Idioma: Não é possível remover o diretório do idioma especificado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1276,'JLIB_INSTALLER_ERROR_LANG_UNINSTALL_ELEMENT_EMPTY','Desinstalar Idioma: O elemento está vazio, os arquivos não podem ser desinstalados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1277,'JLIB_INSTALLER_ERROR_LANG_UNINSTALL_PATH_EMPTY','Desinstalar Idioma: O caminho do idioma está vazio, os arquivos não podem ser desinstalados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1278,'JLIB_INSTALLER_ERROR_LANG_UNINSTALL_PROTECTED','Este idioma não pode ser desinstalado. Está protegido no banco de dados (normalmente en-GB)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1279,'JLIB_INSTALLER_ERROR_LIB_DISCOVER_STORE_DETAILS','Instalar Biblioteca encontrada: Falha ao armazenar detalhes da biblioteca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1280,'JLIB_INSTALLER_ERROR_LIB_UNINSTALL_INVALID_MANIFEST','Desinstalar Biblioteca: Arquivo de manifesto inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1281,'JLIB_INSTALLER_ERROR_LIB_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalar Biblioteca: Arquivo de manifesto inválido ou não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1282,'JLIB_INSTALLER_ERROR_LIB_UNINSTALL_LOAD_MANIFEST','Desinstalar Biblioteca: Não foi possível carregar o arquivo de manifesto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1283,'JLIB_INSTALLER_ERROR_LIB_UNINSTALL_WARNCORELIBRARY','Desinstalar Biblioteca: Tentando desinstalar uma biblioteca do núcleo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1284,'JLIB_INSTALLER_ERROR_LOAD_XML','JInstaller: :Install: Falha ao carregar o Arquivo XML: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1285,'JLIB_INSTALLER_ERROR_MOD_DISCOVER_STORE_DETAILS','Instalar Módulo encontrado: Falha ao armazenar detalhes do módulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1286,'JLIB_INSTALLER_ERROR_MOD_REFRESH_MANIFEST_CACHE','Atualizar cache do manifesto do Módulo: Falha ao armazenar detalhes do módulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1287,'JLIB_INSTALLER_ERROR_MOD_UNINSTALL_ERRORUNKOWNEXTENSION','Desinstalar Módulo: Extensão desconhecida','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1288,'JLIB_INSTALLER_ERROR_MOD_UNINSTALL_EXCEPTION','Desinstalar Módulo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1289,'JLIB_INSTALLER_ERROR_MOD_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalar Módulo: Arquivo de manifesto inválido ou não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1290,'JLIB_INSTALLER_ERROR_MOD_UNINSTALL_SQL_ERROR','Desinstalar Módulo: Erro no arquivo SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1291,'JLIB_INSTALLER_ERROR_MOD_UNINSTALL_WARNCOREMODULE','Desinstalar Módulo: Tentando desinstalar um módulo do núcleo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1292,'JLIB_INSTALLER_ERROR_NO_CORE_LANGUAGE','Não existe um pacote no núcleo para o idioma [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1293,'JLIB_INSTALLER_ERROR_NO_FILE','JInstaller: :Install: Arquivo não existe %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1294,'JLIB_INSTALLER_ERROR_NO_LANGUAGE_TAG','O pacote não especifica uma identificação para o idioma. Você está tentando instalar um pacote de idioma antigo?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1295,'JLIB_INSTALLER_ERROR_NOTFINDJOOMLAXMLSETUPFILE','JInstaller: :Install: Não é possível encontrar o arquivo XML de instalação do Joomla','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1296,'JLIB_INSTALLER_ERROR_NOTFINDXMLSETUPFILE','JInstaller: :Install: Não é possível encontrar o arquivo XML de instalação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1297,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_INVALID_MANIFEST','Desinstalar Pacote: Arquivo de manifesto inválido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1298,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalar Pacote: Arquivo de manifesto inválido ou não encontrado: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1299,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_LOAD_MANIFEST','Desinstalar Pacote: Não foi possível carregar o arquivo de manifesto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1300,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_MANIFEST_NOT_REMOVED','Desinstalar Pacote: Erros foram detectados, arquivo de manifesto não removido!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1301,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_MISSINGMANIFEST','Pacote Desinstalação: Arquivo de manifesto não encontrado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1302,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_NOT_PROPER','Desinstalar Pacote: Esta extensão pode já ter sido desinstalada ou pode não ter sido desinstalada corretamente: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1303,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_WARNCOREPACK','Desinstalação de Pacote: Tentando desinstalar pacote do core','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1304,'JLIB_INSTALLER_ERROR_PLG_DISCOVER_STORE_DETAILS','Instalar Plugin encontrado: Falha ao armazenar detalhes do plugin','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1305,'JLIB_INSTALLER_ERROR_PLG_REFRESH_MANIFEST_CACHE','Atualizar cache do manifesto do Plugin: Falha ao armazenar detalhes do plugin','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1306,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_ERRORUNKOWNEXTENSION','Desinstalar Plugin: Extensão desconhecida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1307,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_FOLDER_FIELD_EMPTY','Desinstalar Plugin: Campo do diretório vazio, não foi possível remover os aquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1308,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_INVALID_MANIFEST','Desinstalar Plugin: Arquivo de manifesto inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1309,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalar Plugin: Arquivo de manifesto inválido ou não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1310,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_LOAD_MANIFEST','Desinstalar Plugin: Não foi possível carregar o arquivo de manifesto.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1311,'JLIB_INSTALLER_ERROR_PLG_UNINSTALL_WARNCOREPLUGIN','Desinstalar Plugin: Tentando desinstalar um plugin do núcleo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1312,'JLIB_INSTALLER_ERROR_SQL_ERROR','JInstaller: :Install: Erro de SQL %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1313,'JLIB_INSTALLER_ERROR_SQL_FILENOTFOUND','JInstaller: :Install: Arquivo SQL não encontrado %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1314,'JLIB_INSTALLER_ERROR_SQL_READBUFFER','JInstaller: :Install: Erro de leitura do Buffer do arquivo SQL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1315,'JLIB_INSTALLER_ERROR_TPL_DISCOVER_STORE_DETAILS','Instalar layout encontrado: Falha ao armazenar detalhes do layout','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1316,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_ERRORUNKOWNEXTENSION','Desinstalar layout: Extensão desconhecida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1317,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_INVALID_CLIENT','Desinstalar layout: Cliente inválido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1318,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_INVALID_NOTFOUND_MANIFEST','Desinstalar layout: Arquivo de manifesto inválido ou não encontrado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1319,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_TEMPLATE_DEFAULT','Desinstalar layout: Não é possível remover o layout padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1320,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_TEMPLATE_DIRECTORY','Desinstalar layout: Diretório inexistente, não foi possível remover os arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1321,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_TEMPLATE_ID_EMPTY','Desinstalar layout: ID do layout está vazia, não foi possível desinstalar os arquivos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1322,'JLIB_INSTALLER_ERROR_TPL_UNINSTALL_WARNCORETEMPLATE','Desinstalar layout: Tentando desinstalar um layout de núcleo: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1323,'JLIB_INSTALLER_ERROR_UNKNOWN_CLIENT_TYPE','Tipo de cliente desconhecido [%s]','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1324,'JLIB_INSTALLER_INSTALL','Instalar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1325,'JLIB_INSTALLER_NOTICE_LANG_RESET_USERS','Idioma definido como padrão para %d usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1326,'JLIB_INSTALLER_NOTICE_LANG_RESET_USERS_1','Idioma definido como padrão para %d usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1327,'JLIB_INSTALLER_UNINSTALL','Desinstalar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1328,'JLIB_INSTALLER_UPDATE','Atualizar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1329,'JLIB_INSTALLER_ERROR_EXTENSION_INVALID_CLIENT_IDENTIFIER','Identificador de cliente inválido especificado no arquivo de manifesto.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1330,'JLIB_INSTALLER_ERROR_PACK_UNINSTALL_UNKNOWN_EXTENSION','Tentando desinstalar extensão desconhecida do pacote. Esta extensão já pode ter sido removida anteriormente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1331,'JLIB_MAIL_FUNCTION_DISABLED','A função mail() foi desabilitada e o e-mail não pôde ser enviado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1332,'JLIB_MAIL_INVALID_EMAIL_SENDER','JMail: : Remetente de e-mail inválido: %s, JMail::setSender(%s)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1333,'JLIB_PLUGIN_ERROR_LOADING_PLUGINS','Erro ao carregar Plugins: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1334,'JLIB_REGISTRY_EXCEPTION_LOAD_FORMAT_CLASS','Não é possível carregar o formato da classe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1335,'JLIB_RULES_ACTION','Ação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1336,'JLIB_RULES_ALLOWED','Permitido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1337,'JLIB_RULES_ALLOWED_ADMIN','Permitido (Super Admin)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1338,'JLIB_RULES_CALCULATED_SETTING','Configurações Calculadas <sup>2</sup>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1339,'JLIB_RULES_CONFLICT','Conflito','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1340,'JLIB_RULES_DENIED','Negador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1341,'JLIB_RULES_GROUP','%s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1342,'JLIB_RULES_GROUPS','Grupos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1343,'JLIB_RULES_INHERIT','Herança','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1344,'JLIB_RULES_INHERITED','Herdado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1345,'JLIB_RULES_NOT_ALLOWED','Não Permitido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1346,'JLIB_RULES_NOT_ALLOWED_ADMIN_CONFLICT','Conflito','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1347,'JLIB_RULES_NOT_ALLOWED_LOCKED','Não Permitido (Bloquado)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1348,'JLIB_RULES_NOT_SET','Não Definido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1349,'JLIB_RULES_SELECT_ALLOW_DENY_GROUP','Permite ou nega %s para os usuários do grupo %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1350,'JLIB_RULES_SELECT_SETTING','Selecione Nova Configuração <sup>1</sup>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1351,'JLIB_RULES_SETTING_NOTES','1. Se você alterar a configuração, isto se aplicará para este e todos os grupos filhos, componentes e conteúdo. Note que <em>Negado</em> sobreporá qualquer configuração herdada, e também a configuração de qualquer grupo filho, componente ou conteúdo. Em caso de conflito, <em>Negar</em> terá precedência. <em>Não Definido</em> é equivalente a <em>Negado</em> mas pode ser alterado nos grupos filhos, componentes e conteúdo.<br />2. Se você selecionar uma nova configuração, clique em <em>Salvar</em> para atualizar as configurações calculadas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1352,'JLIB_RULES_SETTING_NOTES_ITEM','1. Se você alterar a configuração, isto se aplicará para este item. Note que:<br /><em>Herdado</em> significa que as permissões das configurações globais, grupo pai e categoria serão usados.<br /><em>Negado</em> significa que não importa a configuração da configuração global, grupo pai ou categoria, o grupo sendo editado não pode fazer esta ação para este item.<br /><em>Permitido</em> significa que o grupo sendo editado poderá fazer esta ação a este item (mas se estiver em conflito com a configuração global, grupo pai ou categoria, isto não terá impacto; um conflito será indicado por <em>Não Permitido (Bloqueado)</em> nas Configurações Calculadas).<br />2. Se você selecionar uma nova configuração, clique em <em>Salvar</em> para atualizar as configurações calculadas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1353,'JLIB_RULES_SETTINGS_DESC','Gerenciar abaixo as configurações de permissão para grupos de usuários. Veja as notas abaixo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1354,'JLIB_UNKNOWN','Desconhecido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1355,'JLIB_UPDATER_ERROR_COLLECTION_FOPEN','A configuração do PHP allow_url_fopen está desativada. Essa configuração deve estar ativa para o atualizador funcionar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1356,'JLIB_UPDATER_ERROR_COLLECTION_OPEN_URL','Update: :Collection: Não foi possível abrir %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1357,'JLIB_UPDATER_ERROR_COLLECTION_PARSE_URL','Update: :Collection: Não foi possível analisar %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1358,'JLIB_UPDATER_ERROR_EXTENSION_OPEN_URL','Update: :Extension: Não foi possível abrir %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1359,'JLIB_UPDATER_ERROR_EXTENSION_PARSE_URL','Update: :Extension: Não foi possível analisar %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1360,'JLIB_USER_ERROR_AUTHENTICATION_FAILED_LOAD_PLUGIN','JAuthentication: :authenticate: Falha ao carregar plugin: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1361,'JLIB_USER_ERROR_AUTHENTICATION_LIBRARIES','JAuthentication: :__construct: Não foi possível carregar bibliotecas de autenticação.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1362,'JLIB_USER_ERROR_BIND_ARRAY','Não é possível vincular a matriz de objeto de usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1363,'JLIB_USER_ERROR_CANNOT_DEMOTE_SELF','Você não não pode remover suas permissões de Super Admin.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1364,'JLIB_USER_ERROR_ID_NOT_EXISTS','JUser: :_load: Usuário %s não existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1365,'JLIB_USER_ERROR_NOT_SUPERADMIN','Somente usuários Super Admin podem alterar a conta de outro Super Admin.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1366,'JLIB_USER_ERROR_PASSWORD_NOT_MATCH','Senha não confere. Por favor, informe a senha novamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1367,'JLIB_USER_ERROR_UNABLE_TO_FIND_USER','Não foi possível encontrar um usuário com sequência de ativação fornecida.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1368,'JLIB_USER_ERROR_UNABLE_TO_LOAD_USER','JUser: :_load: Não foi possível carregar usuário com ID: %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1369,'JLIB_USER_EXCEPTION_ACCESS_USERGROUP_INVALID','O grupo de usuários não existe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1370,'JLIB_UTIL_ERROR_APP_INSTANTIATION','Erro ao Instanciar Aplicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1371,'JLIB_UTIL_ERROR_CONNECT_DATABASE','JDatabase: :getInstance: Não foi possível conectar a base de dados <br />joomla.library: %1$s - %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1372,'JLIB_UTIL_ERROR_DOMIT','DommitDocument está obsoleto.  Use DomDocument','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1373,'JLIB_UTIL_ERROR_LOADING_FEED_DATA','Erro ao carregar dados do feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1374,'JLIB_UTIL_ERROR_XML_LOAD','Erro ao carregar arquivo XML','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.ini'),
 (1375,'LIB_JOOMLA_XML_DESCRIPTION','O Framework de Aplicação Web Joomla! é o núcleo Sistema de Gerenciamento de Conteúdo Joomla!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_joomla.sys.ini'),
 (1376,'LIB_PHPMAILER_XML_DESCRIPTION','Classes para enviar e-mail','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_phpmailer.sys.ini'),
 (1377,'LIB_PHPUTF8_XML_DESCRIPTION','Classes para UTF-8','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_phputf8.sys.ini'),
 (1378,'LIB_SIMPLEPIE_XML_DESCRIPTION','Framework para fontes de notícias RSS e Atom baseado em PHP.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.lib_simplepie.sys.ini'),
 (1379,'MOD_ARTICLES_ARCHIVE','Artigos Arquivados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.ini'),
 (1380,'MOD_ARTICLES_ARCHIVE_FIELD_COUNT_LABEL','# de Meses','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.ini'),
 (1381,'MOD_ARTICLES_ARCHIVE_FIELD_COUNT_DESC','O número de meses a exibir (o padrão é 10)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.ini'),
 (1382,'MOD_ARTICLES_ARCHIVE_XML_DESCRIPTION','Este módulo exibe um calendário de meses contendo artigos arquivados. Após você alterar o estado de um artigo para arquivado, este será gerado automaticamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.ini'),
 (1383,'MOD_ARTICLES_ARCHIVE_DATE','%1$s, %2$s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.ini'),
 (1384,'MOD_ARTICLES_ARCHIVE','Artigos Arquivados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.sys.ini'),
 (1385,'MOD_ARTICLES_ARCHIVE_XML_DESCRIPTION','Este módulo exibe uma lista dos meses do calendário contendo artigos arquivados. Depois de ter alterado o status de um artigo de arquivo, esta lista será gerada automaticamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.sys.ini'),
 (1386,'MOD_ARTICLES_ARCHIVE_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_archive.sys.ini'),
 (1387,'MOD_ARTICLES_CATEGORIES','Categorias de Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1388,'MOD_ARTICLES_CATEGORIES_FIELD_COUNT_DESC','Digite aqui o número de categorias subordinadas a exibir. O valor padrão é \"0\" e exibirá todas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1389,'MOD_ARTICLES_CATEGORIES_FIELD_COUNT_LABEL','Níveis de Categoria Subordinadas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1390,'MOD_ARTICLES_CATEGORIES_FIELD_MAXLEVEL_DESC','Informe aqui a quantidade máxima de níveis para cada categoria subordinada. O padrão é \'0\' e exibirá todas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1391,'MOD_ARTICLES_CATEGORIES_FIELD_MAXLEVEL_LABEL','Quantidade Máxima de Níveis','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1392,'MOD_ARTICLES_CATEGORIES_FIELD_PARENT_DESC','Escolha uma categoria superior','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1393,'MOD_ARTICLES_CATEGORIES_FIELD_PARENT_LABEL','Categoria Superior','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1394,'MOD_ARTICLES_CATEGORIES_FIELD_SHOW_CHILDREN_DESC','Exibir ou não as categorias subordinadas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1395,'MOD_ARTICLES_CATEGORIES_FIELD_SHOW_CHILDREN_LABEL','Categorias Subordinadas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1396,'MOD_ARTICLES_CATEGORIES_FIELD_SHOW_DESCRIPTION_DESC','Exibir ou não as descrições das categorias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1397,'MOD_ARTICLES_CATEGORIES_FIELD_SHOW_DESCRIPTION_LABEL','Descrição das Categorias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1398,'MOD_ARTICLES_CATEGORIES_XML_DESCRIPTION','Este módulo exibe uma lista de categorias subordinadas a uma categoria superior.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1399,'MOD_ARTICLES_CATEGORIES_TITLE_HEADING_LABEL','Estilo do Cabeçalho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1400,'MOD_ARTICLES_CATEGORIES_TITLE_HEADING_DESC','Configurar o estilo do cabeçalho usado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.ini'),
 (1401,'MOD_ARTICLES_CATEGORIES','Categoria de Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.sys.ini'),
 (1402,'MOD_ARTICLES_CATEGORIES_XML_DESCRIPTION','Este módulo exibe uma lista de categorias de uma categoria pai.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.sys.ini'),
 (1403,'MOD_ARTICLES_CATEGORIES_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_categories.sys.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (1404,'MOD_ARTICLES_CATEGORY','Categoria dos Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1405,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEGROUPING_DESC','Selecione como você gostaria que os artigos sejam ser agrupados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1406,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEGROUPING_LABEL','Grupo de Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1407,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEGROUPINGDIR_DESC','Selecione a direção que deseja para que Grupos de Artigos sejam ordenados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1408,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEGROUPINGDIR_LABEL','Agrupamento de Direção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1409,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEORDERING_DESC','Selecione por qual campo deseja ordenar os Artigos. Ordenação dos Destaques somente deve ser usada quando opções de ordenação para artigos destaques estiver definido como \'Apenas\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1410,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEORDERING_LABEL','Artigo Ordenado por','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1411,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEORDERINGDIR_DESC','Selecione a direção para o qual deseja que os Artigos sejam ordenados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1412,'MOD_ARTICLES_CATEGORY_FIELD_ARTICLEORDERINGDIR_LABEL','Ordenar Direção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1413,'MOD_ARTICLES_CATEGORY_FIELD_AUTHOR_DESC','Selecione um ou mais autores da lista abaixo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1414,'MOD_ARTICLES_CATEGORY_FIELD_AUTHOR_LABEL','Autores','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1415,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORALIAS_DESC','Selecione um ou mais apelidos do autor a partir da lista abaixo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1416,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORALIAS_LABEL','Apelido do Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1417,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORALIASFILTERING_DESC','Selecione Incluído para Incluir os Apelidos dos Autores Selecionados, Excluído para Excluir os Apelidos dos Autores Selecionados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1418,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORALIASFILTERING_LABEL','Tipo de Filtragem de Apelido de Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1419,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORFILTERING_DESC','Selecione Incluído para Incluir os Autores Selecionados, Excluído para Excluir os Autores Selecionados.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1420,'MOD_ARTICLES_CATEGORY_FIELD_AUTHORFILTERING_LABEL','Tipo de Filtragem de Autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1421,'MOD_ARTICLES_CATEGORY_FIELD_CATDEPTH_DESC','O número de níveis filhos a retornar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1422,'MOD_ARTICLES_CATEGORY_FIELD_CATDEPTH_LABEL','Níveis de Categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1423,'MOD_ARTICLES_CATEGORY_FIELD_CATEGORY_DESC','Por favor selecione uma ou mais categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1424,'MOD_ARTICLES_CATEGORY_FIELD_CATFILTERINGTYPE_DESC','Selecione Incluído para Incluir as Categorias Selecionadas, Excluído para Excluir as Categorias Selecionadas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1425,'MOD_ARTICLES_CATEGORY_FIELD_CATFILTERINGTYPE_LABEL','Tipo de Filtragem de Categoria','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1426,'MOD_ARTICLES_CATEGORY_FIELD_COUNT_DESC','Número de itens a serem exibidos. O valor padrão de 0 irá exibir todos os artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1427,'MOD_ARTICLES_CATEGORY_FIELD_COUNT_LABEL','Contagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1428,'MOD_ARTICLES_CATEGORY_FIELD_DATERANGEFIELD_DESC','Selecione qual campo você deseja aplicar no intervalo de data.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1429,'MOD_ARTICLES_CATEGORY_FIELD_DATERANGEFIELD_LABEL','Range do Campo Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1430,'MOD_ARTICLES_CATEGORY_FIELD_DATEFIELD_DESC','Selecione qual campo de data que você deseja exibir.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1431,'MOD_ARTICLES_CATEGORY_FIELD_DATEFIELD_LABEL','Campo de Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1432,'MOD_ARTICLES_CATEGORY_FIELD_DATEFIELDFORMAT_DESC','Por favor entre com um formato de data válido.Veja também: http://php.net/date para informações sobre datas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1433,'MOD_ARTICLES_CATEGORY_FIELD_DATEFIELDFORMAT_LABEL','Formato da Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1434,'MOD_ARTICLES_CATEGORY_FIELD_DATEFILTERING_DESC','Selecione o Formato da Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1435,'MOD_ARTICLES_CATEGORY_FIELD_DATEFILTERING_LABEL','Formato da Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1436,'MOD_ARTICLES_CATEGORY_FIELD_ENDDATE_DESC','Se o intervalo de datas for selecionado acima, digite uma data final.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1437,'MOD_ARTICLES_CATEGORY_FIELD_ENDDATE_LABEL','Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1438,'MOD_ARTICLES_CATEGORY_FIELD_EXCLUDEDARTICLES_DESC','Por favor, indique cada ID do artigo em uma nova linha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1439,'MOD_ARTICLES_CATEGORY_FIELD_EXCLUDEDARTICLES_LABEL','IDs de Artigos a Excluir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1440,'MOD_ARTICLES_CATEGORY_FIELD_GROUP_DISPLAY_LABEL','Opções de Exibição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1441,'MOD_ARTICLES_CATEGORY_FIELD_GROUP_DYNAMIC_LABEL','Opções de Modo Dinâmico','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1442,'MOD_ARTICLES_CATEGORY_FIELD_GROUP_FILTERING_LABEL','Opções de Filtros','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1443,'MOD_ARTICLES_CATEGORY_FIELD_GROUP_GROUPING_LABEL','Opções de Agrupamento','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1444,'MOD_ARTICLES_CATEGORY_FIELD_GROUP_ORDERING_LABEL','Opções de Ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1445,'MOD_ARTICLES_CATEGORY_FIELD_INTROTEXTLIMIT_DESC','Por favor, insira um valor numérico para limitar os caracteres.  O texto inserido será cortado conforme o número de caracteres que você digita.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1446,'MOD_ARTICLES_CATEGORY_FIELD_INTROTEXTLIMIT_LABEL','Limite de Caracteres','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1447,'MOD_ARTICLES_CATEGORY_FIELD_LINKTITLES_LABEL','Links nos Títulos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1448,'MOD_ARTICLES_CATEGORY_FIELD_LINKTITLES_DESC','Links no Títulos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1449,'MOD_ARTICLES_CATEGORY_FIELD_MODE_DESC','Por favor, selecione o modo que você gostaria de usar. Se o modo Normal for escolhido, simplesmente configure o módulo e ele exibirá uma lista estática de Artigos nos itens de menu que você atribuir ao módulo. Se o modo dinâmico for escolhido, então vocẽ poderá o módulo normalmente, contudo agora as opções de categoria não serão usadas. Ao invés disso, o módulo detectará automaticamente se você está em uma visualização de categoria, e exibirá a lista de artigos desta categoria. Quando o modo dinâmico for escolhido, é melhor deixar o módulo configurado para ser exibido em todas as páginas, e ele decidirá se exibe, ou não, qualquer coisa aleatoriamente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1450,'MOD_ARTICLES_CATEGORY_FIELD_MODE_LABEL','Modo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1451,'MOD_ARTICLES_CATEGORY_FIELD_MONTHYEARFORMAT_DESC','Por favor insira um formato de data válido. Ver: http://php.net/date Informações de formato de datas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1452,'MOD_ARTICLES_CATEGORY_FIELD_MONTHYEARFORMAT_LABEL','Formato de Exibição da Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1453,'MOD_ARTICLES_CATEGORY_FIELD_RELATIVEDATE_DESC','Se Data Relativa estiver selecionada abaixo, por favor, informe um valor de dia numérico. Resultados serão obtidos para a data atual e valor informado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1454,'MOD_ARTICLES_CATEGORY_FIELD_RELATIVEDATE_LABEL','Data Relativa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1455,'MOD_ARTICLES_CATEGORY_FIELD_SHOWAUTHOR_DESC','Selecionar Exibir se deseja o autor (ou apelido do autor, se disponível) para ser exibido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1456,'MOD_ARTICLES_CATEGORY_FIELD_SHOWCATEGORY_DESC','Selecione exibir se você quer exibir o nome da categoria.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1457,'MOD_ARTICLES_CATEGORY_FIELD_SHOWCHILDCATEGORYARTICLES_DESC','Incluir ou excluir artigos das categorias Infantil.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1458,'MOD_ARTICLES_CATEGORY_FIELD_SHOWCHILDCATEGORYARTICLES_LABEL','Categoria de Artigos Relacionados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1459,'MOD_ARTICLES_CATEGORY_FIELD_SHOWDATE_DESC','Selecione exibir para publicar a data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1460,'MOD_ARTICLES_CATEGORY_FIELD_SHOWFEATURED_DESC','Selecione exibir, ocultar ou exibir apenas Arquivos em Destaque.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1461,'MOD_ARTICLES_CATEGORY_FIELD_SHOWFEATURED_LABEL','Artigos em Destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1462,'MOD_ARTICLES_CATEGORY_FIELD_SHOWHITS_DESC','Se deseja exibe o número de acessos de cada artigo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1463,'MOD_ARTICLES_CATEGORY_FIELD_SHOWHITS_LABEL','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1464,'MOD_ARTICLES_CATEGORY_FIELD_SHOWINTROTEXT_DESC','Selecione exibir se você gostaria de publicar a introdução.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1465,'MOD_ARTICLES_CATEGORY_FIELD_SHOWINTROTEXT_LABEL','Introdução','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1466,'MOD_ARTICLES_CATEGORY_FIELD_SHOWONARTICLEPAGE_DESC','Selecione exibir ou ocultar a lista decorrente da página de artigos. Isso significa que o módulo irá apenas exibir dinâmicamente na categoria artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1467,'MOD_ARTICLES_CATEGORY_FIELD_SHOWONARTICLEPAGE_LABEL','Exibir na Página de Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1468,'MOD_ARTICLES_CATEGORY_FIELD_STARTDATE_DESC','Se o intervalo de datas for selecionado acima, digite uma data inicial.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1469,'MOD_ARTICLES_CATEGORY_FIELD_STARTDATE_LABEL','Início da Faixa de Datas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1470,'MOD_ARTICLES_CATEGORY_OPTION_ASCENDING_VALUE','Crescente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1471,'MOD_ARTICLES_CATEGORY_OPTION_CREATED_VALUE','Criado em','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1472,'MOD_ARTICLES_CATEGORY_OPTION_DATERANGE_VALUE','Período','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1473,'MOD_ARTICLES_CATEGORY_OPTION_DESCENDING_VALUE','Decrescente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1474,'MOD_ARTICLES_CATEGORY_OPTION_DYNAMIC_VALUE','Dinâmico','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1475,'MOD_ARTICLES_CATEGORY_OPTION_EXCLUDE_VALUE','Excluir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1476,'MOD_ARTICLES_CATEGORY_OPTION_EXCLUSIVE_VALUE','Exclusivo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1477,'MOD_ARTICLES_CATEGORY_OPTION_HITS_VALUE','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1478,'MOD_ARTICLES_CATEGORY_OPTION_ID_VALUE','ID','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1479,'MOD_ARTICLES_CATEGORY_OPTION_INCLUDE_VALUE','Incluir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1480,'MOD_ARTICLES_CATEGORY_OPTION_INCLUSIVE_VALUE','Incluído','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1481,'MOD_ARTICLES_CATEGORY_OPTION_MODIFIED_VALUE','Modificado em','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1482,'MOD_ARTICLES_CATEGORY_OPTION_MONTHYEAR_VALUE','Mês e Ano','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1483,'MOD_ARTICLES_CATEGORY_OPTION_NORMAL_VALUE','Normal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1484,'MOD_ARTICLES_CATEGORY_OPTION_OFF_VALUE','Desativado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1485,'MOD_ARTICLES_CATEGORY_OPTION_ONLYFEATURED_VALUE','Somente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1486,'MOD_ARTICLES_CATEGORY_OPTION_ORDERING_VALUE','Ordenação do Joomla!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1487,'MOD_ARTICLES_CATEGORY_OPTION_ORDERINGFEATURED_VALUE','Ordem dos Destaques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1488,'MOD_ARTICLES_CATEGORY_OPTION_RELATIVEDAY_VALUE','Data Relativa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1489,'MOD_ARTICLES_CATEGORY_OPTION_STARTPUBLISHING_VALUE','Início da Publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1490,'MOD_ARTICLES_CATEGORY_OPTION_FINISHPUBLISHING_VALUE','Fim da Publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1491,'MOD_ARTICLES_CATEGORY_OPTION_YEAR_VALUE','Ano','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1492,'MOD_ARTICLES_CATEGORY_READ_MORE','Leia mais: ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1493,'MOD_ARTICLES_CATEGORY_READ_MORE_TITLE','Leia Mais...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1494,'MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE','Cadastre-se para ler mais','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1495,'MOD_ARTICLES_CATEGORY_TITLE_HEADING','Nível do Ttítulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1496,'MOD_ARTICLES_CATEGORY_TITLE_HEADING_DESCRIPTION','Selecione a tag HTML para os títulos de artigo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1497,'MOD_ARTICLES_CATEGORY_XML_DESCRIPTION','Este módulo exibe uma lista de Artigos de uma ou mais categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.ini'),
 (1498,'MOD_ARTICLES_CATEGORY','Categoria de Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.sys.ini'),
 (1499,'MOD_ARTICLES_CATEGORY_XML_DESCRIPTION','Este Modulo exibe uma lista de artigos de uma ou mais categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.sys.ini'),
 (1500,'MOD_ARTICLES_CATEGORY_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_category.sys.ini'),
 (1501,'MOD_ARTICLES_LATEST','Últimas Notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1502,'MOD_LATEST_NEWS_FIELD_CATEGORY_DESC','Selecione artigos de uma, ou mais categorias. Se nenhuma seleção for feita, exibirá por padrão todas as categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1503,'MOD_LATEST_NEWS_FIELD_COUNT_DESC','O número de artigos para exibir(padrão: 5)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1504,'MOD_LATEST_NEWS_FIELD_COUNT_LABEL','Contagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1505,'MOD_LATEST_NEWS_FIELD_FEATURED_DESC','Exibir/Ocultar artigos designados como Destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1506,'MOD_LATEST_NEWS_FIELD_FEATURED_LABEL','Artigos em Destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1507,'MOD_LATEST_NEWS_FIELD_ORDERING_DESC','Primeiro os Mais Recentes: ordenar os artigos usando a data de criação deles<br />Primeiro os Modificados Recentemente: ordenar os artigos usando a data de modificação deles<br />Primeiros os Publicados Recentemente: ordenar os artigos usando a data de publicação deles.<br />Primeiros os Acessados Recentemente: ordenar os artigos usando a data de modificação ou criação deles.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1508,'MOD_LATEST_NEWS_FIELD_ORDERING_LABEL','Ordenar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1509,'MOD_LATEST_NEWS_FIELD_USER_DESC','Filtrar por autor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1510,'MOD_LATEST_NEWS_FIELD_USER_LABEL','Autores','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1511,'MOD_LATEST_NEWS_VALUE_ADDED_BY_ME','Adicionado ou modificado por mim','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1512,'MOD_LATEST_NEWS_VALUE_ANYONE','Qualquer um','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1513,'MOD_LATEST_NEWS_VALUE_NOTADDED_BY_ME','Não adicionado ou modificado por mim','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1514,'MOD_LATEST_NEWS_VALUE_ONLY_SHOW_FEATURED','Exibir somente artigos destaques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1515,'MOD_LATEST_NEWS_VALUE_RECENT_ADDED','Primeiro os adicionados recentemente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1516,'MOD_LATEST_NEWS_VALUE_RECENT_MODIFIED','Primeiro os modificados recentemente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1517,'MOD_LATEST_NEWS_VALUE_RECENT_PUBLISHED','Primeiro os recém publicados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1518,'MOD_LATEST_NEWS_VALUE_RECENT_TOUCHED','Primeiro os acessados recentemente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1519,'MOD_LATEST_NEWS_XML_DESCRIPTION','Este módulo exibe uma lista com os artigos mais atuais e os publicados recentemente. Alguns que estão apresentados podem ter expirado embora sejam recentes.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.ini'),
 (1520,'MOD_ARTICLES_LATEST','Últimas Notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.sys.ini'),
 (1521,'MOD_LATEST_NEWS_XML_DESCRIPTION','Este módulo exibe uma lista dos mais recentemente artigos publicados. Alguns que estão apresentadas podem ter expirado, embora sejam os mais recentes.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.sys.ini'),
 (1522,'MOD_ARTICLES_LATEST_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_latest.sys.ini'),
 (1523,'MOD_ARTICLES_NEWS','Artigos - Flash de Notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1524,'MOD_ARTICLES_NEWS_FIELD_CATEGORY_DESC','Selecione artigos de uma, ou mais categorias. Se nenhuma seleção for feita, exibirá por padrão todas as categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1525,'MOD_ARTICLES_NEWS_FIELD_IMAGES_DESC','Exibir imagens no artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1526,'MOD_ARTICLES_NEWS_FIELD_IMAGES_LABEL','Exibir Imagens','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1527,'MOD_ARTICLES_NEWS_FIELD_ITEMS_DESC','O número de artigos a exibir dentro deste módulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1528,'MOD_ARTICLES_NEWS_FIELD_ITEMS_LABEL','# Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1529,'MOD_ARTICLES_NEWS_FIELD_LINKTITLE_DESC','Ligar títulos do artigos para os artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1530,'MOD_ARTICLES_NEWS_FIELD_LINKTITLE_LABEL','Ligar Títulos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1531,'MOD_ARTICLES_NEWS_FIELD_ORDERING_DESC','Selecione a ordem que deseja para os resultados da busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1532,'MOD_ARTICLES_NEWS_FIELD_ORDERING_LABEL','Ordem dos Resultados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1533,'MOD_ARTICLES_NEWS_FIELD_ORDERING_CREATED_DATE','Data da Criação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1534,'MOD_ARTICLES_NEWS_FIELD_ORDERING_PUBLISHED_DATE','Data de Publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1535,'MOD_ARTICLES_NEWS_FIELD_ORDERING_ORDERING','Ordem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1536,'MOD_ARTICLES_NEWS_FIELD_ORDERING_RANDOM','Aleatória','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1537,'MOD_ARTICLES_NEWS_FIELD_READMORE_DESC','Se configurado como exibir, o link \'Leia mais...\' será exibido se o artigo tiver o texto principal.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1538,'MOD_ARTICLES_NEWS_FIELD_READMORE_LABEL','Link \'Leia mais...\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1539,'MOD_ARTICLES_NEWS_FIELD_SEPARATOR_DESC','Exibir separador após último artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1540,'MOD_ARTICLES_NEWS_FIELD_SEPARATOR_LABEL','Exibir Último Separador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1541,'MOD_ARTICLES_NEWS_FIELD_TITLE_DESC','Exibir/Ocultar títulos de artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1542,'MOD_ARTICLES_NEWS_FIELD_TITLE_LABEL','Exibir Títulos do Artigo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1543,'MOD_ARTICLES_NEWS_READMORE','Leia mais...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1544,'MOD_ARTICLES_NEWS_READMORE_REGISTER','Cadastre-se para ler mais','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1545,'MOD_ARTICLES_NEWS_TITLE_HEADING','Nível de Cabeçalho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1546,'MOD_ARTICLES_NEWS_TITLE_HEADING_DESCRIPTION','Selecione o nível desejado de cabeçalho HTML para os títulos dos artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1547,'MOD_ARTICLES_NEWS_XML_DESCRIPTION','O Módulo Newsflash exibirá um número fixo de artigos de uma categoria específica.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.ini'),
 (1548,'MOD_ARTICLES_NEWS','Artigos - Newsflash','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.sys.ini'),
 (1549,'MOD_ARTICLES_NEWS_XML_DESCRIPTION','O Módulo Newsflash exibirá um número fixo de artigos de uma categoria específica.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.sys.ini'),
 (1550,'MOD_ARTICLES_NEWS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_news.sys.ini'),
 (1551,'MOD_ARTICLES_POPULAR','Conteúdo Mais Lido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1552,'MOD_POPULAR_FIELD_CATEGORY_DESC','Seleciona os artigos de uma categoria específica. Se nenhuma seleção for feita, exibirá por padrão todas as categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1553,'MOD_POPULAR_FIELD_COUNT_DESC','O número de artigos para exibir (o padrão é 5)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1554,'MOD_POPULAR_FIELD_COUNT_LABEL','Contagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1555,'MOD_POPULAR_FIELD_FEATURED_DESC','Exibir / Ocultar artigos designados como destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1556,'MOD_POPULAR_FIELD_FEATURED_LABEL','Os Melhores Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1557,'MOD_POPULAR_XML_DESCRIPTION','Este módulo exibe uma lista dos artigos publicados atualmente que têm o maior número de visualizações por páginas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.ini'),
 (1558,'MOD_ARTICLES_POPULAR','Conteúdo Mais Lido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.sys.ini'),
 (1559,'MOD_POPULAR_XML_DESCRIPTION','Este módulo exibe uma lista dos artigos publicados atualmente que têm o maior número de páginas visitadas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.sys.ini'),
 (1560,'MOD_ARTICLES_POPULAR_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_articles_popular.sys.ini'),
 (1561,'COM_BANNERS_NO_CLIENT','- Sem cliente -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1562,'MOD_BANNERS','Banners','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1563,'MOD_BANNERS_BANNER','Banner','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1564,'MOD_BANNERS_FIELD_BANNERCLIENT_DESC','Selecione banners apenas a partir de um único cliente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1565,'MOD_BANNERS_FIELD_BANNERCLIENT_LABEL','Cliente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1566,'MOD_BANNERS_FIELD_CACHETIME_DESC','Tempo até o módulo ser atualizado no cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1567,'MOD_BANNERS_FIELD_CACHETIME_LABEL','Tempo de Cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1568,'MOD_BANNERS_FIELD_CATEGORY_DESC','Selecione banners a partir de uma categoria, ou de uma seleção de categorias. Se nenhuma seleção for feita, exibirá por padrão todas as categorias.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1569,'MOD_BANNERS_FIELD_COUNT_DESC','O número de faixas a exibir (padrão 5)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1570,'MOD_BANNERS_FIELD_COUNT_LABEL','Contagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1571,'MOD_BANNERS_FIELD_FOOTER_DESC','Texto ou HTML a ser exibido depois do grupo de banners','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1572,'MOD_BANNERS_FIELD_FOOTER_LABEL','Texto do Rodapé ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1573,'MOD_BANNERS_FIELD_HEADER_DESC','Texto ou HTML a ser exibido antes do grupo de banners','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1574,'MOD_BANNERS_FIELD_HEADER_LABEL','Texto do Cabeçalho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1575,'MOD_BANNERS_FIELD_RANDOMISE_DESC','Randomizar a ordem dos banners','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1576,'MOD_BANNERS_FIELD_RANDOMISE_LABEL','Randomizar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1577,'MOD_BANNERS_FIELD_TAG_DESC','O banner é selecionado combinando as tags do banner, e as palavras-chave do documento atual.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1578,'MOD_BANNERS_FIELD_TAG_LABEL','Busca por Tag','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1579,'MOD_BANNERS_FIELD_TARGET_DESC','Forma de abertura quando o link for clicado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1580,'MOD_BANNERS_FIELD_TARGET_LABEL','Alvo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1581,'MOD_BANNERS_VALUE_STICKYORDERING','Manter, Ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1582,'MOD_BANNERS_VALUE_STICKYRANDOMISE','Manter, Randomizado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1583,'MOD_BANNERS_XML_DESCRIPTION','O Módulo Banner permite exibir os Banners ativos fora do componente dentro do seu site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.ini'),
 (1584,'MOD_BANNERS','Banners','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.sys.ini'),
 (1585,'MOD_BANNERS_XML_DESCRIPTION','O Módulo Banner permite exibir os Banners ativos fora do componente dentro do seu site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.sys.ini'),
 (1586,'MOD_BANNERS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_banners.sys.ini'),
 (1587,'MOD_BREADCRUMBS','Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1588,'MOD_BREADCRUMBS_FIELD_HOMETEXT_DESC','Este texto será exibido no início. Se deixado em branco, será usado o valor padrão do mod_breadcrumbs.ini do arquivo de idioma.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1589,'MOD_BREADCRUMBS_FIELD_HOMETEXT_LABEL','Texto da Pagina Principal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1590,'MOD_BREADCRUMBS_FIELD_SEPARATOR_DESC','Um separador de texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1591,'MOD_BREADCRUMBS_FIELD_SEPARATOR_LABEL','Texto Separador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1592,'MOD_BREADCRUMBS_FIELD_SHOWHERE_DESC','Exibir/Ocultar texto \"Você está aqui\" no Pathway','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1593,'MOD_BREADCRUMBS_FIELD_SHOWHERE_LABEL','Exibir \"Você está aqui\"','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1594,'MOD_BREADCRUMBS_FIELD_SHOWHOME_DESC','Exibir/Ocultar o caminho da Página Principal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1595,'MOD_BREADCRUMBS_FIELD_SHOWHOME_LABEL','Exibir Página Principal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1596,'MOD_BREADCRUMBS_FIELD_SHOWLAST_DESC','Exibir/Ocultar as últimas entradas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1597,'MOD_BREADCRUMBS_FIELD_SHOWLAST_LABEL','Exibir Últimas Entradas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1598,'MOD_BREADCRUMBS_HERE','Você está aqui: ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1599,'MOD_BREADCRUMBS_HOME','Pagina Principal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1600,'MOD_BREADCRUMBS_XML_DESCRIPTION','Esse módulo apresenta o Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.ini'),
 (1601,'MOD_BREADCRUMBS','Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.sys.ini'),
 (1602,'MOD_BREADCRUMBS_XML_DESCRIPTION','Este módulo exibe o Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.sys.ini'),
 (1603,'MOD_BREADCRUMBS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_breadcrumbs.sys.ini'),
 (1604,'MOD_CHAMADA_XML_DESCRICAO','Módulo padrão do projeto de identidade de governo para exibição de artigos na página inicial. Permite diversos comportamentos diferentes.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1605,'MOD_CHAMADA_FIELD_VARIACAO_LAYOUT_LABEL','Variação de cor de módulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1606,'MOD_CHAMADA_FIELD_VARIACAO_LAYOUT_DESC','Variação de cor de layout, a ser utilizada como classe. Atualmente, há três: 01 (roxo), 02 (laranja) e 03 (azul escuro), independentemente da cor principal do tema.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1607,'MOD_CHAMADA_FIELD_MODELO_LABEL','Fonte de dados (model)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1608,'MOD_CHAMADA_FIELD_MODELO_DESC','Fonte dos dados do módulo. Pode ser a tabela de conteúdo principal do CMS Joomla, a tabela do componente K2 ou outro modelo a ser criado e configurado, de acordo com a necessidade do órgão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1609,'MOD_CHAMADA_FIELD_TITULO_ALTERNATIVO_LABEL','Título alternativo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1610,'MOD_CHAMADA_FIELD_TITULO_ALTERNATIVO_DESC','Título alternativo que substituirá o título do módulo, caso algum valor seja informado. Útil para os casos em que o título do módulo na área administrativa difere do que se pretende usar no site, por questões de organização.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1611,'MOD_CHAMADA_FIELD_LINK_SAIBA_MAIS_LABEL','Texto link \'saiba mais\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1612,'MOD_CHAMADA_FIELD_LINK_SAIBA_MAIS_DESC','Texto do link de \'saiba mais\'. Caso não seja informado, o valor padrão será \'saiba mais\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1613,'MOD_CHAMADA_FIELD_TEXTO_SAIBA_MAIS_LABEL','Texto link \'saiba mais\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1614,'MOD_CHAMADA_FIELD_TEXTO_SAIBA_MAIS_DESC','Texto do link de \'saiba mais\'. Caso não seja informado, o valor padrão será \'saiba mais\'.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1615,'MOD_CHAMADA_FIELD_QUANTIDADE_DESTAQUES_LABEL','Quantidade de itens','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1616,'MOD_CHAMADA_FIELD_QUANTIDADE_DESTAQUES_DESC','Quantidade de itens trazidos a partir da consulta.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1617,'MOD_CHAMADA_FIELD_HABILITAR_MENSAGEM_VAZIA_LABEL','Habilitar mensagem vazia?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1618,'MOD_CHAMADA_FIELD_HABILITAR_MENSAGEM_VAZIA_DESC','Caso habilitado, permite a configuração de uma mensagem, quando nenhum valor for retornado pelo módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1619,'MOD_CHAMADA_FIELD_MENSAGEM_VAZIA_LABEL','Texto de mensagem \'vazia\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1620,'MOD_CHAMADA_FIELD_MENSAGEM_VAZIA_DESC','Informe o texto que será apresentado quando a consulta configurada para o módulo não retornar nenhum valor.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1621,'MOD_CHAMADA_FIELDSET_ITEM_UNICO_LABEL','Configuração para item único','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1622,'MOD_CHAMADA_FIELD_ID_ITEM_UNICO_LABEL','ID único do item a ser carregado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1623,'MOD_CHAMADA_FIELD_ID_ITEM_UNICO_DESC','Informe manualmente o ID do único item a ser carregado por este módulo. ATENÇÃO: informar um ID para este módulo sobrescreve as configurações realizadas nas demais opções de múltiplos itens.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1624,'MOD_CHAMADA_FIELDSET_MULTIPLOS_ITENS_LABEL','Configuração para múltiplos itens','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1625,'MOD_CHAMADA_FIELD_URL_LABEL','URL (verificar se este campo será mesmo utilizado)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1626,'MOD_CHAMADA_FIELD_URL_DESC','VERIFICAR SE ESTE CAMPO SERÁ MESMO UTILIZADO.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1627,'MOD_CHAMADA_FIELD_LIMITAR_CARACTERE_LABEL','Limitar caracteres?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1628,'MOD_CHAMADA_FIELD_LIMITAR_CARACTERE_DESC','Informe se será utilizado um limitador de caracteres ou não.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1629,'MOD_CHAMADA_FIELD_LIMITE_CARACTERE_LABEL','Número de caracteres','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1630,'MOD_CHAMADA_FIELD_LIMITE_CARACTERE_DESC','Informar limite de caracteres até o qual será procurada a última palavra completa, de forma a evitar o corte de palavras de forma indesejada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1631,'MOD_CHAMADA_FIELD_CHAPEU_LABEL','Campo de \'chapéu\'','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1632,'MOD_CHAMADA_FIELD_CHAPEU_DESC','Informe nome_tabela.nome_campo para o qual o chapéu será utilizado. Utilize \'cont\' para se referir à tabela de conteúdo, \'cat\' para a categoria e \'mtag\' para o mapeamento de tags (este último somente na versão 3.x).','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1633,'MOD_CHAMADA_FIELD_DESTAQUE_LABEL','Itens em destaque','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1634,'MOD_CHAMADA_FIELD_DESTAQUE_DESC','Escolha que tratamento será dado na consulta para os itens em destaque.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1635,'MOD_CHAMADA_OPTION_TODOS_VALUE','Trazer todos os itens','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1636,'MOD_CHAMADA_OPTION_EXCETO_DESTAQUE_VALUE','Trazer todos, exceto destaques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1637,'MOD_CHAMADA_OPTION_SOMENTE_DESTAQUE_VALUE','Trazer somente destaques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1638,'MOD_CHAMADA_FIELD_SOMENTE_IMAGEM_LABEL','Somente itens com imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1639,'MOD_CHAMADA_FIELD_SOMENTE_IMAGEM_DESC','Escolha se somente os itens para os quais se tenha informado uma imagem sejam tragos. Importante: caso as imagens estejam em um campo de parâmetros, como ocorre no componente principal do joomla, a consulta será feita utilizando um comando LIKE, tornando-a mais lenta.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1640,'MOD_CHAMADA_FIELD_BUSCAR_CAT_TAG_LABEL','Tratamento de categorias e tags','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1641,'MOD_CHAMADA_FIELD_BUSCAR_CAT_TAG_DESC','Informe como os itens serão carregados: somente itens de uma categoria, somente itens com uma tag ou somente itens de uma categoria QUE CONTENHAM a tag informada. As informações de tag funcionam somente para o joomla 3.x, quando a fonte de dados for a tabela de conteúdos padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1642,'MOD_CHAMADA_OPTION_SOMENTE_CATEGORIA_VALUE','Somente categorias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1643,'MOD_CHAMADA_OPTION_SOMENTE_TAG_VALUE','Somente tags','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1644,'MOD_CHAMADA_OPTION_AMBOS_VALUE','Categoria E tag','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1645,'JTAG','Tag','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1646,'JTAG_DESC','Disponível somente para o Joomla 3.x. Informe as tags para as quais a consulta será realizada. Separe as tags por ponto-e-vírgula.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1647,'MOD_CHAMADA_FIELD_CATEGORY_DESC','Informe a(s) categoria(s) a partir da(s) qual(is) os dados serão carregados. IMPORTANTE: a consideração das categorias filhas nos resultados da consulta somente ocorrem quando uma única categoria for selecionada. A seleção multi categorias é permitida, mas desabilita o carregamento de categorias filhas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1648,'MOD_CHAMADA_FIELD_VISUALIZAR_FILHO_LABEL','Visualizar itens de categorias filhas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1649,'MOD_CHAMADA_FIELD_VISUALIZAR_FILHO_DESC','Funciona somente quando uma única categoria for informada. Escolha se os itens de categorias filhas da categoria principal informada serão trazidos ou não na consulta principal.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1650,'MOD_CHAMADA_FIELD_NIVEL_LABEL','Nível de categorias filhas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1651,'MOD_CHAMADA_FIELD_NIVEL_DESC','Caso a visualização de itens de categorias filhas esteja habilitada, informe o nível máximo para o qual as categorias filhas serão consideradas. Configurar corretamente este campo resulta em ganhos de performance na consulta ao banco realizada pelo módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1652,'MOD_CHAMADA_FIELD_EXIBIR_IMAGEM_LABEL','Exibir imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1653,'MOD_CHAMADA_FIELD_EXIBIR_IMAGEM_DESC','Informe se a imagem trazida na consulta será exibida ou não.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1654,'MOD_CHAMADA_FIELD_EXIBIR_INTROTEXT_LABEL','Informar texto de introdução','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1655,'MOD_CHAMADA_FIELD_EXIBIR_INTROTEXT_DESC','Informe se o texto de introdução ou equivalente será apresentado ou não para esta instância de módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1656,'MOD_CHAMADA_FIELD_EXIBIR_TITLE_LABEL','Exibir título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1657,'MOD_CHAMADA_FIELD_EXIBIR_TITLE_DESC','Informe se o título do item carregado será exibido ou não.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1658,'MOD_CHAMADA_FIELD_ORDEM_LABEL','Ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1659,'MOD_CHAMADA_FIELD_ORDEM_DESC','Informe os critérios de ordenação utilizados na consulta. Depende do componente com o qual o módulo está sendo integrado. Poder ser que nem todas as opções estejam disponíveis para a fonte de dados desejada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1660,'MOD_CHAMADA_OPTION_TITULO_VALUE','Título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1661,'MOD_CHAMADA_OPTION_DATA_PUBLISH_UP_VALUE','Data de publicação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1662,'MOD_CHAMADA_OPTION_DATA_CREATED_VALUE','Data de criação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1663,'MOD_CHAMADA_OPTION_DATA_MODIFIED_VALUE','Data de modificação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1664,'MOD_CHAMADA_OPTION_ORDEM_VALUE','Ordem informada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1665,'MOD_CHAMADA_OPTION_HITS_VALUE','Quantidade de acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1666,'MOD_CHAMADA_FIELD_ORDEMDIRECTION_LABEL','Direção da ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1667,'MOD_CHAMADA_FIELD_ORDEMDIRECTION_DESC','Informe a direção da ordenação, se será decrescente (do maior para o menor) ou crescente (do menor para o maior), de acordo com o critério principal de ordenação informado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1668,'MOD_CHAMADA_OPTION_DESC_VALUE','Descrescente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1669,'MOD_CHAMADA_OPTION_ASC_VALUE','Crescente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1670,'MOD_CHAMADA_FIELDSET_CONF_MANUAIS_ITEM01_LABEL','Configurações manuais do item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1671,'MOD_CHAMADA_FIELD_MANUAL_CHAPEU01_LABEL','Chapéu item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1672,'MOD_CHAMADA_FIELD_MANUAL_CHAPEU01_DESC','Informe chapéu de preenchimento manual ou sobrescreva os dados do chapéu do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1673,'MOD_CHAMADA_FIELD_MANUAL_TITLE01_LABEL','Título item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1674,'MOD_CHAMADA_FIELD_MANUAL_TITLE01_DESC','Informe título de preenchimento manual ou sobrescreva os dados do título do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1675,'MOD_CHAMADA_FIELD_MANUAL_DESC01_LABEL','Descrição item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1676,'MOD_CHAMADA_FIELD_MANUAL_DESC01_DESC','Informe descrição de preenchimento manual ou sobrescreva os dados da descrição do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1677,'MOD_CHAMADA_FIELD_MANUAL_IMAGE01_LABEL','Imagem item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1678,'MOD_CHAMADA_FIELD_MANUAL_IMAGE01_DESC','Informe imagem de preenchimento manual ou sobrescreva os dados da imagem do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1679,'MOD_CHAMADA_FIELD_MANUAL_URL01_LABEL','URL simples para item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1680,'MOD_CHAMADA_FIELD_MANUAL_URL01_DESC','Informe URL simples de preenchimento manual ou sobrescreva os dados da URL simples do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1681,'MOD_CHAMADA_FIELD_MANUAL_MENU01_LABEL','URL de item de menu para item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1682,'MOD_CHAMADA_FIELD_MANUAL_MENU01_DESC','Informe URL de item de menu, de preenchimento manual, ou sobrescreva os dados da URL de item de menu do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1683,'MOD_CHAMADA_FIELD_MANUAL_ARTICLE01_LABEL','URL de artigo de conteúdo para item 01','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1684,'MOD_CHAMADA_FIELD_MANUAL_ARTICLE01_DESC','Informe URL de artigo de menu, de preenchimento manual, ou sobrescreva os dados da URL de artigo de menu do primeiro item retornado pela consulta do módulo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.ini'),
 (1685,'MOD_CHAMADA_XML_DESCRICAO','Módulo padrão do projeto de identidade de governo para exibição de artigos na página inicial. Permite diversos comportamentos diferentes.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_chamadas.sys.ini'),
 (1686,'MOD_CUSTOM','Personalizar HTML','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1687,'MOD_CUSTOM_FIELD_PREPARE_CONTENT_DESC','Opcionalmente preparar o conteúdo com o conteúdo Joomla Plug-ins.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1688,'MOD_CUSTOM_FIELD_PREPARE_CONTENT_LABEL','Preparar Conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1689,'MOD_CUSTOM_XML_DESCRIPTION','Este módulo permite que você crie seu próprio módulo HTML usando um editor WYSIWYG.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1690,'MOD_CUSTOM_FIELD_BACKGROUNDIMAGE_LABEL','Imagem de Fundo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1691,'MOD_BACKGROUNDIMAGE_FIELD_LOGO_DESC','Se selecionou uma imagem aqui, ela será inserida automaticamente como um estilo inline do elemento div wrapping','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.ini'),
 (1692,'MOD_CUSTOM','Personalizar HTML','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.sys.ini'),
 (1693,'MOD_CUSTOM_XML_DESCRIPTION','Este módulo permite que você crie seu próprio módulo HTML usando um editor WYSIWYG.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.sys.ini'),
 (1694,'MOD_CUSTOM_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_custom.sys.ini'),
 (1695,'MOD_FEED','Exibir Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1696,'MOD_FEED_ERR_CACHE','Por favor, permissão de escrita no diretório de cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1697,'MOD_FEED_ERR_NO_URL','Nenhuma URL especificada para o feed.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1698,'MOD_FEED_FIELD_DESCRIPTION_DESC','Exibir o texto de descrição para a alimentação feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1699,'MOD_FEED_FIELD_DESCRIPTION_LABEL','Descrição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1700,'MOD_FEED_FIELD_IMAGE_DESC','Exibir a imagem associada com a alimentação do feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1701,'MOD_FEED_FIELD_IMAGE_LABEL','Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1702,'MOD_FEED_FIELD_ITEMDESCRIPTION_DESC','Exibir texto a descrição ou a introdução de itens individuais RSS','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1703,'MOD_FEED_FIELD_ITEMDESCRIPTION_LABEL','Descrição do Item','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1704,'MOD_FEED_FIELD_ITEMS_DESC','Digite o número de itens RSS a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1705,'MOD_FEED_FIELD_ITEMS_LABEL','Itens do Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1706,'MOD_FEED_FIELD_RSSTITLE_DESC','Exibir título do feed de notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1707,'MOD_FEED_FIELD_RSSTITLE_LABEL','Título','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1708,'MOD_FEED_FIELD_RSSURL_DESC','Digite a URL do feed RSS / RDF','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1709,'MOD_FEED_FIELD_RSSURL_LABEL','URL do Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1710,'MOD_FEED_FIELD_RTL_DESC','Exibir feed na direção da RTL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1711,'MOD_FEED_FIELD_RTL_LABEL','RTL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1712,'MOD_FEED_FIELD_WORDCOUNT_DESC','Permite limitar a quantidade de texto visível descrição do item. 0 irá exibir todo o texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1713,'MOD_FEED_FIELD_WORDCOUNT_LABEL','Contagem de Palavra','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1714,'MOD_FEED_XML_DESCRIPTION','Este módulo permite a exibição de um feed em syndicated','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.ini'),
 (1715,'MOD_FEED','Exibir Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.sys.ini'),
 (1716,'MOD_FEED_XML_DESCRIPTION','Este módulo permite a exibição de uma fonte de notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.sys.ini'),
 (1717,'MOD_FEED_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_feed.sys.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (1718,'COM_FINDER_FILTER_BRANCH_LABEL','Buscar por %s','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1719,'COM_FINDER_FILTER_SELECT_ALL_LABEL','Buscar Tudo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1720,'COM_FINDER_ADVANCED_SEARCH','Busca Avançada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1721,'COM_FINDER_SELECT_SEARCH_FILTER','- Sem Filtro -','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1722,'MOD_FINDER','Módulo de Busca Inteligente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1723,'MOD_FINDER_CONFIG_OPTION_BOTTOM','Rodapé','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1724,'MOD_FINDER_CONFIG_OPTION_TOP','Topo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1725,'MOD_FINDER_FIELDSET_ADVANCED_ALT_DESCRIPTION','Um rótulo alternativo para o campo de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1726,'MOD_FINDER_FIELDSET_ADVANCED_ALT_LABEL','Rótulo Alternativo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1727,'MOD_FINDER_FIELDSET_ADVANCED_BUTTON_POS_DESCRIPTION','A posição do botão de busca relativa ao campo de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1728,'MOD_FINDER_FIELDSET_ADVANCED_BUTTON_POS_LABEL','Posição do Botão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1729,'MOD_FINDER_FIELDSET_ADVANCED_FIELD_SIZE_DESCRIPTION','A largura do campo de busca por número de caracteres.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1730,'MOD_FINDER_FIELDSET_ADVANCED_FIELD_SIZE_LABEL','Tamanho do Campo de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1731,'MOD_FINDER_FIELDSET_ADVANCED_LABEL_POS_DESCRIPTION','A posição do rótulo de busca relativo ao campo de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1732,'MOD_FINDER_FIELDSET_ADVANCED_LABEL_POS_LABEL','Posição do Rótulo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1733,'MOD_FINDER_FIELDSET_ADVANCED_SHOW_BUTTON_DESCRIPTION','Alternar se um botão deve ser apresentado em um formulário de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1734,'MOD_FINDER_FIELDSET_ADVANCED_SHOW_BUTTON_LABEL','Botão de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1735,'MOD_FINDER_FIELDSET_ADVANCED_SHOW_LABEL_DESCRIPTION','Alternar se um rótulo deve ser mostrado para o campo de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1736,'MOD_FINDER_FIELDSET_ADVANCED_SHOW_LABEL_LABEL','Rótulo do Campo de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1737,'MOD_FINDER_FIELDSET_BASIC_AUTOSUGGEST_DESCRIPTION','Alternar automaticamente se sugestões de busca devem ser mostradas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1738,'MOD_FINDER_FIELDSET_BASIC_AUTOSUGGEST_LABEL','Sugestões de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1739,'MOD_FINDER_FIELDSET_BASIC_SEARCHFILTER_DESCRIPTION','A seleção de um filtro de busca pode limitar quaisquer buscas submetidas por este módulo para usar o filtro selecionado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1740,'MOD_FINDER_FIELDSET_BASIC_SEARCHFILTER_LABEL','Filtro de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1741,'MOD_FINDER_FIELDSET_BASIC_SHOW_ADVANCED_DESCRIPTION','Alternar se usuários devem ter permissão para ver opções avançadas de busca. Se configurado para a opção de Link do Componente, é criado um link para a Busca Inteligente que redireciona-os para a visão de busca inteligente. Se configurado para mostrar, as opções avançadas podem ser mostradas em linha.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1742,'MOD_FINDER_FIELDSET_BASIC_SHOW_ADVANCED_LABEL','Busca Avançada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1743,'MOD_FINDER_FIELDSET_BASIC_SHOW_ADVANCED_OPTION_LINK','Link para o Componente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1744,'MOD_FINDER_FIELD_OPENSEARCH_DESCRIPTION','Alguns navegadores pode adicionar suporte para a busca de seu site se esta opção estiver habilitada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1745,'MOD_FINDER_FIELD_OPENSEARCH_LABEL','Busca Automática OpenSearch','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1746,'MOD_FINDER_FIELD_OPENSEARCH_TEXT_DESCRIPTION','Texto apresentado em navegadores suportados quando adicionar seu site como um provedor de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1747,'MOD_FINDER_FIELD_OPENSEARCH_TEXT_LABEL','Título OpenSearch','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1748,'MOD_FINDER_SEARCHBUTTON_TEXT','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1749,'MOD_FINDER_SEARCH_BUTTON','Ir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1750,'MOD_FINDER_SEARCH_VALUE','Buscar...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1751,'MOD_FINDER_XML_DESCRIPTION','Este é um módulo Busca Inteligente.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.ini'),
 (1752,'MOD_FINDER','Módulo de Busca Inteligente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.sys.ini'),
 (1753,'MOD_FINDER_XML_DESCRIPTION','Este é o módulo para o sistema de Busca Inteligente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_finder.sys.ini'),
 (1754,'MOD_FOOTER','Rodapé','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.ini'),
 (1755,'MOD_FOOTER_LINE1','Copyright &#169; %date% %sitename%. Todos os direitos reservados.<br />','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.ini'),
 (1756,'MOD_FOOTER_LINE2','<a href=\"http://www.joomla.org\">Joomla!</a> é um software livre com <a href=\"http://www.gnu.org/licenses/gpl-2.0.html\">licença GNU/GPL v2.0</a>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.ini'),
 (1757,'MOD_FOOTER_XML_DESCRIPTION','Este módulo exibe as informações dos direitos autorais do Joomla!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.ini'),
 (1758,'MOD_FOOTER','Rodapé','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.sys.ini'),
 (1759,'MOD_FOOTER_XML_DESCRIPTION','Este módulo exibe as informações dos direitos autorais do Joomla!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.sys.ini'),
 (1760,'MOD_FOOTER_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_footer.sys.ini'),
 (1761,'MOD_LANGUAGES','Troca de Idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1762,'MOD_LANGUAGES_FIELD_ACTIVE_DESC','Exibe ou não o idioma ativo. Se exibido, a classe \'lang-active\' será adicionada ao elemento.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1763,'MOD_LANGUAGES_FIELD_ACTIVE_LABEL','Idioma Ativo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1764,'MOD_LANGUAGES_FIELD_DROPDOWN_DESC','Se definido como \'\'Sim\'\', os parâmetros de exibição abaixo serão ignorados. Os nomes de idioma nativos do conteúdo serão exibido em uma caixa de seleção.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1765,'MOD_LANGUAGES_FIELD_DROPDOWN_LABEL','Usar Caixa de Seleção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1766,'MOD_LANGUAGES_FIELD_FOOTER_DESC','Este é o texto ou HTML que será exibido abaixo do seletor de idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1767,'MOD_LANGUAGES_FIELD_FOOTER_LABEL','Após o Texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1768,'MOD_LANGUAGES_FIELD_FULL_NAME_DESC','Se definido como \'\'Sim\'\' e as imagens de bandeira como \'\'Não\'\', os nomes completos do idioma nativo serão exibidos. Se definido como \'\'Não\'\', abreviações em letras maiúsculas do código de idioma do conteúdo serão usadas. Exemplo: EN para Inglês, PT para Português.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1769,'MOD_LANGUAGES_FIELD_FULL_NAME_LABEL','Nomes dos Idiomas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1770,'MOD_LANGUAGES_FIELD_HEADER_DESC','Este é o texto ou HTML que é exibido acima do idioma selecionado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1771,'MOD_LANGUAGES_FIELD_HEADER_LABEL','Antes do Texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1772,'MOD_LANGUAGES_FIELD_INLINE_DESC','O Padrão está configurado como \'\'Sim\'\', ex. para exibição horizontal.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1773,'MOD_LANGUAGES_FIELD_INLINE_LABEL','Exibição Horizontal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1774,'MOD_LANGUAGES_FIELD_MODULE_LAYOUT_DESC','Usar um layout diferente do módulo fornecido ou sobreposições no modelo padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1775,'MOD_LANGUAGES_FIELD_USEIMAGE_DESC','Se definido como \'\'Sim\'\', irá exibir imagens de bandeiras para a seleção do idioma. Caso contrário, serão usados os nomes do idioma nativo do conteúdo.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1776,'MOD_LANGUAGES_FIELD_USEIMAGE_LABEL','Imagens de Bandeiras','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1777,'MOD_LANGUAGES_OPTION_DEFAULT_LANGUAGE','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1778,'MOD_LANGUAGES_SPACERDROP_LABEL','<u>Se \'\'Usar Caixa de Seleção\'\' estiver definido como \'\'Sim\'\',<br />as opções de exibição abaixo serão ignoradas</u>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1779,'MOD_LANGUAGES_SPACERNAME_LABEL','<u>Se \'\'Usar Imagens de Bandeiras\'\' definido como \'Sim\', <br />as opções de exibição abaixo serão ignoradas</u>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1780,'MOD_LANGUAGES_XML_DESCRIPTION','Esse módulo exibe uma lista de idiomas de conteúdo disponíveis (como definido e publicado na guia \'\'Gerenciador de Idioma de Conteúdo\'\') para que se alterne entre eles quando se desejar utilizar o Joomla! como um site multi-idioma. <br />--O plugin \'\'Sistema - Filtro de Idioma\'\' deve estar publicado.--<br />Caso se alterne os idiomas e o item de menu exibindo a página não estiver associado a outro item de menu, o módulo irá redirecionar para a página inicial definida para o idioma escolhido. Posteriormente, a navegação será a definida para aquele idioma.<br />Se o plugin <strong>\'\'Sistema - Filtro de Idioma\'\'</strong> estiver desativado, pode haver resultados indesejados.<br /><strong>Método:</strong><br />1. Abra a guia Gerenciador de Idiomas de Conteúdo e certifique-se que os idiomas que você deseja usar no conteúdo estão publicados e possuem um Código de URL bem como um prefixo para a imagem usada na exibição do módulo.<br />2. Crie uma página inicial ao atribuir um idioma para um item de menu e defini-lo como Página Inicial Padrão para cada Idioma de Conteúdo publicado.<br />3. Depois disso, você pode atribuir um idioma para qualquer Artigo, Categoria, Módulo, Newsfeed ou Weblinks no Joomla.<br />4. Certifique-se que o módulo esteja publicado e o plugin esteja ativado.<br />5. Quando estiver utilizando itens de menu associados, certifique-se que o módulo está sendo exibido nas respectivas páginas.<br />6. O modo como as bandeiras ou nomes de idioma são exibidas é definido pelo ordenador no Gerenciador de Idioma - Idiomas de Conteúdo.<br /><br />Se o módulo estiver publicado, sugere-se que seja publicado o módulo de status multi-idioma no Administrador.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.ini'),
 (1781,'MOD_LANGUAGES','Seletor de idioma','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.sys.ini'),
 (1782,'MOD_LANGUAGES_XML_DESCRIPTION','Este módulo exibe uma lista dos idiomas de conteúdo disponíveis (como definido e publicado na aba do Gerenciador de Idioma de Conteúdo) para se alternar entre eles quando desejar um site Joomla! multi-idiomas.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.sys.ini'),
 (1783,'MOD_LANGUAGES_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_languages.sys.ini'),
 (1784,'MOD_LOGIN','Acessar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1785,'MOD_LOGIN_FIELD_GREETING_DESC','Exibir/Ocultar o texto de saudação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1786,'MOD_LOGIN_FIELD_GREETING_LABEL','Exibir Saudações','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1787,'MOD_LOGIN_FIELD_LOGIN_REDIRECTURL_DESC','Selecione a página que o usuário será redirecionado após efetuar o login. Selecione uma na lista de páginas. Caso não seja selecionada alguma, a página principal será carregada por padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1788,'MOD_LOGIN_FIELD_LOGIN_REDIRECTURL_LABEL','Página de Redirecionamento do Login','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1789,'MOD_LOGIN_FIELD_LOGOUT_REDIRECTURL_DESC','Selecione a página que o usuário será redirecionado após efetuar o logout. Selecione uma na lista de páginas. Caso não seja selecionada alguma, a página principal será carregada por padrão.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1790,'MOD_LOGIN_FIELD_LOGOUT_REDIRECTURL_LABEL','Página de Redirecionamento do Logout','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1791,'MOD_LOGIN_FIELD_NAME_DESC','Exibe nome ou usuário após o login','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1792,'MOD_LOGIN_FIELD_NAME_LABEL','Exibir Nome/Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1793,'MOD_LOGIN_FIELD_POST_TEXT_DESC','Este é o Texto ou HTML que será exibido abaixo do formulário de login.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1794,'MOD_LOGIN_FIELD_POST_TEXT_LABEL','Pós-texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1795,'MOD_LOGIN_FIELD_PRE_TEXT_DESC','Este é o Texto ou HTML que será exibido acima do formulário de login.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1796,'MOD_LOGIN_FIELD_PRE_TEXT_LABEL','Pré-texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1797,'MOD_LOGIN_FIELD_USESECURE_DESC','Enviar dados de login criptografados (requer SSL). Não habilite esta opção se o Joomla não for acessível através do prefixo https:// ','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1798,'MOD_LOGIN_FIELD_USESECURE_LABEL','Encriptar Formulário de Login','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1799,'MOD_LOGIN_FORGOT_YOUR_PASSWORD','Esqueceu sua senha?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1800,'MOD_LOGIN_FORGOT_YOUR_USERNAME','Esqueceu seu usuário?','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1801,'MOD_LOGIN_HINAME','Olá %s,','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1802,'MOD_LOGIN_REGISTER','Criar uma conta','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1803,'MOD_LOGIN_REMEMBER_ME','Lembrar-me','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1804,'MOD_LOGIN_VALUE_NAME','Nome','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1805,'MOD_LOGIN_VALUE_USERNAME','Nome de Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1806,'MOD_LOGIN_XML_DESCRIPTION','Este módulo exibe um formulário com nome de usuário e senha. Também exibe um link para recuperar senha perdida. Caso o cadastro de usuários esteja ativado (referindo-se as Configurações do Sistema), um outro link será exibido para novos usuários se registrarem.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.ini'),
 (1807,'MOD_LOGIN','Acessar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.sys.ini'),
 (1808,'MOD_LOGIN_XML_DESCRIPTION','Este módulo exibe um nome de usuário e senha formulário de login. Também exibe um link para recuperar uma senha esquecida. Se o registro de usuário estiver habilitada (nas configurações Global Configuration), outro link será exibido para habilitar auto-registro para os usuários.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.sys.ini'),
 (1809,'MOD_LOGIN_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_login.sys.ini'),
 (1810,'MOD_MENU','Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1811,'MOD_MENU_FIELD_ALLCHILDREN_DESC','Expandir o menu e deixar os sub-menus sempre visíveis','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1812,'MOD_MENU_FIELD_ALLCHILDREN_LABEL','Itens do Sub-Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1813,'MOD_MENU_FIELD_CLASS_DESC','Um sufixo a ser aplicado à classe CSS do menu Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1814,'MOD_MENU_FIELD_CLASS_LABEL','Sufixo de Classe do Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1815,'MOD_MENU_FIELD_ENDLEVEL_DESC','Nível de parada onde a renderização deve parar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1816,'MOD_MENU_FIELD_ENDLEVEL_LABEL','Nível Final','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1817,'MOD_MENU_FIELD_MENUTYPE_DESC','Selecione um menu na lista','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1818,'MOD_MENU_FIELD_MENUTYPE_LABEL','Selecione o Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1819,'MOD_MENU_FIELD_STARTLEVEL_DESC','Nível para início, onde a renderização deve iniciar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1820,'MOD_MENU_FIELD_STARTLEVEL_LABEL','Nível Incial','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1821,'MOD_MENU_FIELD_TAG_ID_DESC','Um atributo ID para atribuir a tag UL raiz do menu (opcional)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1822,'MOD_MENU_FIELD_TAG_ID_LABEL','ID Tag do Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1823,'MOD_MENU_FIELD_TARGET_DESC','Valores JavaScript para o posicionamento da janela popup, ex. top=50,left=50,width=200,height=300','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1824,'MOD_MENU_FIELD_TARGET_LABEL','Posição e Direção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1825,'MOD_MENU_XML_DESCRIPTION','Este módulo exibe um menu no frontend.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.ini'),
 (1826,'MOD_MENU','Menu','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.sys.ini'),
 (1827,'MOD_MENU_XML_DESCRIPTION','Este módulo exibe um menu no site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.sys.ini'),
 (1828,'MOD_MENU_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_menu.sys.ini'),
 (1829,'MOD_RANDOM_IMAGE','Imagem Randômica','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1830,'MOD_RANDOM_IMAGE_FIELD_FOLDER_DESC','Caminho para a pasta de imagem em relação ao URL do site (imagens por exemplo).','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1831,'MOD_RANDOM_IMAGE_FIELD_FOLDER_LABEL','Pasta de Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1832,'MOD_RANDOM_IMAGE_FIELD_HEIGHT_DESC','Altura da imagem força todas as imagens a serem exibidas com a altura em pixels.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1833,'MOD_RANDOM_IMAGE_FIELD_HEIGHT_LABEL','Altura (px)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1834,'MOD_RANDOM_IMAGE_FIELD_LINK_DESC','Uma URL para redirecionar quando a imagem for clicada (http://www.joomla.org, por exemplo).','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1835,'MOD_RANDOM_IMAGE_FIELD_LINK_LABEL','Link','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1836,'MOD_RANDOM_IMAGE_FIELD_TYPE_DESC','Tipo de imagem PNG/GIF/JPG etc. (o padrão é JPG)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1837,'MOD_RANDOM_IMAGE_FIELD_TYPE_LABEL','Tipo de Imagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1838,'MOD_RANDOM_IMAGE_FIELD_WIDTH_DESC','Largura da imagem força todas as imagens a serem exibidas com uma largura em pixels.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1839,'MOD_RANDOM_IMAGE_FIELD_WIDTH_LABEL','Largura (px)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1840,'MOD_RANDOM_IMAGE_NO_IMAGES','Sem Imagens','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1841,'MOD_RANDOM_IMAGE_XML_DESCRIPTION','Este módulo exibe uma imagem aleatória a partir do seu diretório escolhido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.ini'),
 (1842,'MOD_RANDOM_IMAGE','Imagem randomica','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.sys.ini'),
 (1843,'MOD_RANDOM_IMAGE_XML_DESCRIPTION','Este módulo exibe uma imagem aleatória a partir de um diretório escolhido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.sys.ini'),
 (1844,'MOD_RANDOM_IMAGE_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_random_image.sys.ini'),
 (1845,'MOD_RELATED_FIELD_SHOWDATE_DESC','Exibir/Ocultar Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.ini'),
 (1846,'MOD_RELATED_FIELD_SHOWDATE_LABEL','Exibir Data','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.ini'),
 (1847,'MOD_RELATED_ITEMS','Artigos - Artigos Relacionados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.ini'),
 (1848,'MOD_RELATED_XML_DESCRIPTION','Este módulo exibe outros artigos que estão relacionados ao que está sendo visualizado. Essas relações são estabelecidas pelas Palavras-chaves. <br />Todas as palavras do artigo são comparadas com todas as palavras-chave de todos os outros artigos publicados. Por exemplo: você pode ter um artigo sobre \"Joomla\" e outro sobre \"Gerenciadores de Conteúdo\". Se você incluir a palavra \"cms\" em ambos os artigos, o módulo ira listar todos os artigos com as palavras \"cms\", ao exibir qualque um dos artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.ini'),
 (1849,'MOD_RELATED_ITEMS','Artigos - Artigos Relacionados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.sys.ini'),
 (1850,'MOD_RELATED_XML_DESCRIPTION','Este módulo exibe outros artigos que estão relacionados ao que está sendo visualizado. Essas relações são estabelecidas pelas Palavras-chaves. <br />Todas as palavras do artigo são comparadas com todas as palavras-chave de todos os outros artigos publicados. Por exemplo: você pode ter um artigo sobre \"Joomla\" e outro sobre \"Gerenciadores de Conteúdo\". Se você incluir a palavra \"cms\" em ambos os artigos, o módulo ira listar todos os artigos com as palavras \"cms\", ao exibir qualque um dos artigos.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.sys.ini'),
 (1851,'MOD_RELATED_ITEMS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_related_items.sys.ini'),
 (1852,'MOD_SEARCH','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1853,'MOD_SEARCH_FIELD_BOXWIDTH_DESC','Tamanho do campo de busca em caracteres.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1854,'MOD_SEARCH_FIELD_BOXWIDTH_LABEL','Largura da Caixa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1855,'MOD_SEARCH_FIELD_BUTTON_DESC','Exibir um Botão de Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1856,'MOD_SEARCH_FIELD_BUTTON_LABEL','Botão Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1857,'MOD_SEARCH_FIELD_BUTTONPOS_DESC','Posição do botão em relação ao campo de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1858,'MOD_SEARCH_FIELD_BUTTONPOS_LABEL','Posição do Botão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1859,'MOD_SEARCH_FIELD_BUTTONTEXT_DESC','O texto que aparece no botão de busca. Se deixado em branco irá carregar a string \'searchbutton\' do seu arquivo de idioma.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1860,'MOD_SEARCH_FIELD_BUTTONTEXT_LABEL','Texto do Botão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1861,'MOD_SEARCH_FIELD_IMAGEBUTTON_DESC','Usar uma imagem como botão. Esta imagem deve ser nomeado como searchButton.gif e colocado em /images/','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1862,'MOD_SEARCH_FIELD_IMAGEBUTTON_LABEL','Imagem do Botão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1863,'MOD_SEARCH_FIELD_SETITEMID_DESC','Atribuir um ItemID para a amostragem dos resultados da busca caso não haja menu com_search e você deseja uma amostragem específica. O ItemID pode ser escolhido entre os disponíveis no gerenciador de menus. Se você não sabe o que isso significa, talvez não necessite usar.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1864,'MOD_SEARCH_FIELD_SETITEMID_LABEL','Configurar ItemID','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1865,'MOD_SEARCH_FIELD_LABEL_TEXT_DESC','O texto que aparece na caixa de busca. Se for deixado em branco, irá carregar a string \'label\' do seu arquivo de idioma.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1866,'MOD_SEARCH_FIELD_LABEL_TEXT_LABEL','Nome da Caixa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1867,'MOD_SEARCH_FIELD_OPENSEARCH_LABEL','Auto-descoberta do OpenSearch','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1868,'MOD_SEARCH_FIELD_OPENSEARCH_TEXT_LABEL','Título do OpenSearch','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1869,'MOD_SEARCH_FIELD_OPENSEARCH_TEXT_DESC','Texto exibido nos navegadores suportados quando adicionando seu site como um provedor de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1870,'MOD_SEARCH_FIELD_OPENSEARCH_DESC','Alguns navegadores pode adicionar suporte para seu site de busca quando esta opção estiver ativada.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1871,'MOD_SEARCH_FIELD_TEXT_DESC','O texto que aparece na caixa de texto da busca. Se deixado em branco irá carregar a string \'search box\' do seu arquivo de idioma.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1872,'MOD_SEARCH_FIELD_TEXT_LABEL','Texto da Caixa','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1873,'MOD_SEARCH_FIELD_VALUE_BOTTOM','Em baixo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1874,'MOD_SEARCH_FIELD_VALUE_LEFT','Esquerda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1875,'MOD_SEARCH_FIELD_VALUE_RIGHT','Direita','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1876,'MOD_SEARCH_FIELD_VALUE_TOP','Topo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1877,'MOD_SEARCH_SEARCHBOX_TEXT','Buscar...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1878,'MOD_SEARCH_LABEL_TEXT','Buscar...','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1879,'MOD_SEARCH_SEARCHBUTTON_TEXT','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1880,'MOD_SEARCH_XML_DESCRIPTION','Este módulo irá exibir uma caixa de texto de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.ini'),
 (1881,'MOD_SEARCH','Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.sys.ini'),
 (1882,'MOD_SEARCH_XML_DESCRIPTION','Este módulo irá exibir uma caixa de texto de busca.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.sys.ini'),
 (1883,'MOD_SEARCH_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_search.sys.ini'),
 (1884,'MOD_STATS','Estatística','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1885,'MOD_STATS_ARTICLES','Artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1886,'MOD_STATS_ARTICLES_VIEW_HITS','Ver quantos acessos teve os artigos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1887,'MOD_STATS_CACHING','Cache','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1888,'MOD_STATS_FIELD_COUNTER_DESC','Exibir contador de visitas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1889,'MOD_STATS_FIELD_COUNTER_LABEL','Contador de Visitas','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1890,'MOD_STATS_FIELD_INCREASECOUNTER_DESC','Digite a quantidade de visitas para aumentar o contador.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1891,'MOD_STATS_FIELD_INCREASECOUNTER_LABEL','Aumento Contador','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1892,'MOD_STATS_FIELD_SERVERINFO_DESC','Exibir informações do Servidor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1893,'MOD_STATS_FIELD_SERVERINFO_LABEL','Informações do Servidor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1894,'MOD_STATS_FIELD_SITEINFO_DESC','Exibir informações do site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1895,'MOD_STATS_FIELD_SITEINFO_LABEL','Informações do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1896,'MOD_STATS_GZIP','GZip','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1897,'MOD_STATS_MYSQL','MySQL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1898,'MOD_STATS_OS','S.O.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1899,'MOD_STATS_PHP','PHP','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1900,'MOD_STATS_TIME','Hora','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1901,'MOD_STATS_USERS','Visitantes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1902,'MOD_STATS_WEBLINKS','Weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1903,'MOD_STATS_XML_DESCRIPTION','O módulo de estatísticas mostra informações sobre a instalaçõo do servidor juntamente com estatísticas sobre os usuários do site, o número de artigos em seu banco de dados e o número de links da Web que você fornecer','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.ini'),
 (1904,'MOD_STATS','Estatística','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.sys.ini'),
 (1905,'MOD_STATS_XML_DESCRIPTION','O módulo de estatísticas exibe informações sobre a instalação do servidor juntamente com estatísticas sobre os usuários do site, o número de artigos em seu banco de dados e o número de links da Web que você fornecer.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.sys.ini'),
 (1906,'MOD_STATS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_stats.sys.ini'),
 (1907,'MOD_SYNDICATE','Fonte de Notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1908,'MOD_SYNDICATE_DEFAULT_FEED_ENTRIES','Entradas do Feed','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1909,'MOD_SYNDICATE_FIELD_DISPLAYTEXT_DESC','Se é definido como \"Sim\", o texto será exibido ao lado do ícone','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1910,'MOD_SYNDICATE_FIELD_DISPLAYTEXT_LABEL','Exibir Texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1911,'MOD_SYNDICATE_FIELD_FORMAT_DESC','Selecione o formato para a fonte de notícias','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1912,'MOD_SYNDICATE_FIELD_FORMAT_LABEL','Formato da Fonte','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1913,'MOD_SYNDICATE_FIELD_TEXT_DESC','Entre com o texto que será exibido ao longo dos Links RSS','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1914,'MOD_SYNDICATE_FIELD_TEXT_LABEL','Texto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1915,'MOD_SYNDICATE_FIELD_VALUE_ATOM','Atom 1.0','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1916,'MOD_SYNDICATE_FIELD_VALUE_RSS','RSS 2.0','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1917,'MOD_SYNDICATE_XML_DESCRIPTION','Módulo Inteligente de publicação que cria um fonte de notícias para a página onde o módulo é exibido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.ini'),
 (1918,'MOD_SYNDICATE','Publicação de Feeds','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.sys.ini'),
 (1919,'MOD_SYNDICATE_XML_DESCRIPTION','O módulo Syndication cria uma publicação de feed para a página onde é exibido.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.sys.ini'),
 (1920,'MOD_SYNDICATE_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_syndicate.sys.ini'),
 (1921,'MOD_USERS_LATEST','Últimos Usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1922,'MOD_USERS_LATEST_FIELD_FILTER_GROUPS_DESC','Escolha para filtrar por grupos do usuário conectado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1923,'MOD_USERS_LATEST_FIELD_FILTER_GROUPS_LABEL','Filtrar Grupos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1924,'MOD_USERS_LATEST_FIELD_LINKTOWHAT_DESC','Escolha o tipo de informações a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1925,'MOD_USERS_LATEST_FIELD_LINKTOWHAT_LABEL','Informações do Usuário','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1926,'MOD_USERS_LATEST_FIELD_NUMBER_DESC','Número de usuários registrados recentemente a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1927,'MOD_USERS_LATEST_FIELD_NUMBER_LABEL','Numero de Usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1928,'MOD_USERS_LATEST_FIELD_VALUE_CONTACT','Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1929,'MOD_USERS_LATEST_FIELD_VALUE_PROFILE','Perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1930,'MOD_USERS_LATEST_XML_DESCRIPTION','Este módulo exibe os últimos usuários registrados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.ini'),
 (1931,'MOD_USERS_LATEST','Últimos usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.sys.ini'),
 (1932,'MOD_USERS_LATEST_XML_DESCRIPTION','Este módulo exibe os últimos usuários registrados','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.sys.ini'),
 (1933,'MOD_USERS_LATEST_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_users_latest.sys.ini'),
 (1934,'MOD_WEBLINKS','Weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1935,'MOD_WEBLINKS_FIELD_CATEGORY_DESC','Escolha a categoria weblinks a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1936,'MOD_WEBLINKS_FIELD_COUNT_DESC','Número de links a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1937,'MOD_WEBLINKS_FIELD_COUNT_LABEL','Contagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1938,'MOD_WEBLINKS_FIELD_COUNTCLICKS_DESC','Se definido como sim, o número de vezes que o link foi clicado será gravado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1939,'MOD_WEBLINKS_FIELD_COUNTCLICKS_LABEL','Contar Cliques','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1940,'MOD_WEBLINKS_FIELD_DESCRIPTION_DESC','Exibe a descrição do Weblink','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1941,'MOD_WEBLINKS_FIELD_DESCRIPTION_LABEL','Descrição','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1942,'MOD_WEBLINKS_FIELD_FOLLOW_DESC','Indexação de Robôs - permite, ou não seguir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1943,'MOD_WEBLINKS_FIELD_FOLLOW_LABEL','Seguir/Não Seguir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1944,'MOD_WEBLINKS_FIELD_HITS_DESC','Exibir Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1945,'MOD_WEBLINKS_FIELD_HITS_LABEL','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1946,'MOD_WEBLINKS_FIELD_ORDERDIRECTION_DESC','Definir a direção da ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1947,'MOD_WEBLINKS_FIELD_ORDERDIRECTION_LABEL','Direção','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1948,'MOD_WEBLINKS_FIELD_ORDERING_DESC','Ordenação para os weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1949,'MOD_WEBLINKS_FIELD_ORDERING_LABEL','Ordenação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1950,'MOD_WEBLINKS_FIELD_TARGET_DESC','Forma de abertura quando o link for clicado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1951,'MOD_WEBLINKS_FIELD_TARGET_LABEL','Alvo da janela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1952,'MOD_WEBLINKS_FIELD_VALUE_ASCENDING','Ascendente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1953,'MOD_WEBLINKS_FIELD_VALUE_DESCENDING','Descendente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1954,'MOD_WEBLINKS_FIELD_VALUE_FOLLOW','Seguir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1955,'MOD_WEBLINKS_FIELD_VALUE_HITS','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1956,'MOD_WEBLINKS_FIELD_VALUE_NOFOLLOW','Não Seguir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1957,'MOD_WEBLINKS_FIELD_VALUE_ORDER','Ordem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1958,'MOD_WEBLINKS_HITS','Acessos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1959,'MOD_WEBLINKS_XML_DESCRIPTION','Este módulo exibe links de uma categoria definida no componente Weblinks.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.ini'),
 (1960,'MOD_WEBLINKS','Weblinks','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.sys.ini'),
 (1961,'MOD_WEBLINKS_XML_DESCRIPTION','Este módulo exibe links de uma categoria definida no componente Weblinks.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.sys.ini'),
 (1962,'MOD_WEBLINKS_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_weblinks.sys.ini'),
 (1963,'MOD_WHOSONLINE','Quem está Online','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1964,'MOD_WHOSONLINE_FIELD_FILTER_GROUPS_DESC','Escolha para filtrar pelos grupos do usuário conectado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1965,'MOD_WHOSONLINE_FIELD_FILTER_GROUPS_LABEL','Filtro de grupos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1966,'MOD_WHOSONLINE_FIELD_LINKTOWHAT_DESC','Escolha o tipo de informações a exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1967,'MOD_WHOSONLINE_FIELD_LINKTOWHAT_LABEL','Informações','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1968,'MOD_WHOSONLINE_FIELD_VALUE_BOTH','Ambos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1969,'MOD_WHOSONLINE_FIELD_VALUE_CONTACT','Contato','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1970,'MOD_WHOSONLINE_FIELD_VALUE_NAMES','Nomes de Usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1971,'MOD_WHOSONLINE_FIELD_VALUE_NUMBER','# de Visitantes / Usuários','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1972,'MOD_WHOSONLINE_FIELD_VALUE_PROFILE','Perfil','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1973,'MOD_WHOSONLINE_GUESTS','%s&#160;visitantes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1974,'MOD_WHOSONLINE_GUESTS_1','Um&#160;visitante','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1975,'MOD_WHOSONLINE_GUESTS_0','Nenum visitante','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1976,'MOD_WHOSONLINE_MEMBERS','%s&#160;membros','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1977,'MOD_WHOSONLINE_MEMBERS_1','Um&#160;membro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1978,'MOD_WHOSONLINE_MEMBERS_0','Nenhum membro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1979,'MOD_WHOSONLINE_SAME_GROUP_MESSAGE','Lista de usuários que pertencem ao seus grupos ou aos groups filhos dos seus grupos','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1980,'MOD_WHOSONLINE_SHOWMODE_DESC','Selecione o que deve ser exibido','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1981,'MOD_WHOSONLINE_SHOWMODE_LABEL','Exibir','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1982,'MOD_WHOSONLINE_XML_DESCRIPTION','O módulo \'Quem está Online\' exibe o número de usuários anônimos (visitantes, por exemplo) e usuários conhecidos (aqueles registrados) que estão atualmente acessando o site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1983,'MOD_WHOSONLINE_WE_HAVE','Temos %1$s e %2$s online','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.ini'),
 (1984,'MOD_WHOSONLINE','Quem está On-line','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.sys.ini'),
 (1985,'MOD_WHOSONLINE_XML_DESCRIPTION','O módulo \'Quem está On-line\'  apresenta o número de usuários anônimos (visitantes, por exemplo) e usuários registrados (aqueles logados) que estão acessando o site.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.sys.ini'),
 (1986,'MOD_WHOSONLINE_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_whosonline.sys.ini'),
 (1987,'MOD_WRAPPER','Wrapper','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1988,'MOD_WRAPPER_FIELD_ADD_DESC','Por padrão, http:// será adicionado a menos que seja detectado http:// ou https:// na URL que você forneceu. Isso permite que você desligue essa funcionalidade.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1989,'MOD_WRAPPER_FIELD_ADD_LABEL','Adicionar Automaticamente','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1990,'MOD_WRAPPER_FIELD_AUTOHEIGHT_DESC','A altura será configurada automaticamente para o tamanho da página externa. Isso funciona apenas para páginas dentro do seu domínio.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1991,'MOD_WRAPPER_FIELD_AUTOHEIGHT_LABEL','Altura Automática','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1992,'MOD_WRAPPER_FIELD_FRAME_LABEL','Frame border','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1993,'MOD_WRAPPER_FIELD_FRAME_DESC','Mostrar borda que envolve o iframe','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1994,'MOD_WRAPPER_FIELD_HEIGHT_DESC','Altura da Janela IFrame','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1995,'MOD_WRAPPER_FIELD_HEIGHT_LABEL','Altura','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1996,'MOD_WRAPPER_FIELD_SCROLL_DESC','Exibir/Ocultar barras de rolagem horizontais &amp; verticais.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1997,'MOD_WRAPPER_FIELD_SCROLL_LABEL','Barras de Rolagem','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1998,'MOD_WRAPPER_FIELD_TARGET_DESC','Nome do iframe alvo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (1999,'MOD_WRAPPER_FIELD_TARGET_LABEL','Nome do Alvo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2000,'MOD_WRAPPER_FIELD_URL_DESC','URL para o site/arquivo que você quer que seja exibido dentro do iframe.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2001,'MOD_WRAPPER_FIELD_URL_LABEL','URL','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2002,'MOD_WRAPPER_FIELD_VALUE_AUTO','Automático','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2003,'MOD_WRAPPER_FIELD_WIDTH_DESC','Largura da janela iframe. Você pode usar um valor absoluto em pixels ou relativo adicionando %.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2004,'MOD_WRAPPER_FIELD_WIDTH_LABEL','Largura','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2005,'MOD_WRAPPER_NO_IFRAMES','Sem Iframes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2006,'MOD_WRAPPER_XML_DESCRIPTION','Este módulo exibe uma janela iframe para o endereço especificado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.ini'),
 (2007,'MOD_WRAPPER','Wrapper','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.sys.ini'),
 (2008,'MOD_WRAPPER_NO_IFRAMES','Sem iframes','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.sys.ini'),
 (2009,'MOD_WRAPPER_XML_DESCRIPTION','Esse módulo exibe uma janela iframe para o endereço especificado.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.sys.ini'),
 (2010,'MOD_WRAPPER_LAYOUT_DEFAULT','Padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.mod_wrapper.sys.ini'),
 (2011,'PKG_JOOMLA','Sistema de Gerenciamento de Conteúdo Joomla!','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.pkg_joomla.sys.ini'),
 (2012,'PKG_JOOMLA_XML_DESCRIPTION','O Joomla! é um do mais populares gerenciadores de conteúdo disponíveis hoje.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.pkg_joomla.sys.ini'),
 (2013,'TPL_ATOMIC_XML_DESCRIPTION','Atomic é um exemplo fácil de ser modificado de como elementos básicos de tema se parecem. Atomic inclui o <a href=\"https://github.com/joshuaclayton/blueprint-css/wiki\"> Framework Blueprint CSS</a>.</p><p><a href=\"http://www.garethjmsaunders.co.uk/blueprint/cheatsheets/\">Blueprint Cheat Sheets</a> </p><p>O PSD do fundo pode ser encontrado em <a href=\"http://help.joomla.org/files/blueprint_psd.zip\">joomla.org</a></p>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.ini'),
 (2014,'TPL_ATOMIC_POSITION_ATOMIC-BOTTOMLEFT','Fundo da Esquerda','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2015,'TPL_ATOMIC_POSITION_ATOMIC-BOTTOMMIDDLE','Fundo do Centro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2016,'TPL_ATOMIC_POSITION_ATOMIC-SEARCH','Buscar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2017,'TPL_ATOMIC_POSITION_ATOMIC-SIDEBAR','Barra Lateral','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2018,'TPL_ATOMIC_POSITION_ATOMIC-TOPMENU','Menu do Topo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2019,'TPL_ATOMIC_POSITION_ATOMIC-TOPQUOTE','Citação do Topo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2020,'TPL_ATOMIC_XML_DESCRIPTION','Atomic é um exemplo fácil de ser modificado de como elementos básicos de tema se parecem. Atomic inclui o <a href=\"https://github.com/joshuaclayton/blueprint-css/wiki\"> Framework Blueprint CSS</a>.</p><p><a href=\"http://www.garethjmsaunders.co.uk/blueprint/cheatsheets/\">Blueprint Cheat Sheets</a> </p><p>O PSD do fundo pode ser encontrado em <a href=\"http://help.joomla.org/files/blueprint_psd.zip\">joomla.org</a></p>','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_atomic.sys.ini'),
 (2021,'TPL_BEEZ5_ADDITIONAL_INFORMATION','Informações adicionais','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2022,'TPL_BEEZ5_ALTCLOSE','fechado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2023,'TPL_BEEZ5_ALTOPEN','aberto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2024,'TPL_BEEZ5_BIGGER','Maior','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2025,'TPL_BEEZ5_CLICK','clique','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2026,'TPL_BEEZ5_DECREASE_SIZE','Diminiuir tamanho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2027,'TPL_BEEZ5_ERROR_JUMP_TO_NAV','Ir para navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2028,'TPL_BEEZ5_FIELD_DESCRIPTION_DESC','Por favor, digite a descrição do seu site aqui','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2029,'TPL_BEEZ5_FIELD_DESCRIPTION_LABEL','Descrição do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2030,'TPL_BEEZ5_FIELD_HTML5_DESC','Escolha xhtml ou html5','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2031,'TPL_BEEZ5_FIELD_HTML5_LABEL','Versão do HTML','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2032,'TPL_BEEZ5_FIELD_LOGO_DESC','Por favor, selecione uma imagem. Se você não deseja exibir um logotipo, clique em Selecionar, não escolha qualquer imagem e clique em Inserir no diálogo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2033,'TPL_BEEZ5_FIELD_LOGO_LABEL','Logotipo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2034,'TPL_BEEZ5_FIELD_NAVPOSITION_DESC','Navegação antes ou após o conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2035,'TPL_BEEZ5_FIELD_NAVPOSITION_LABEL','Posição da Navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2036,'TPL_BEEZ5_FIELD_SITETITLE_DESC','Por favor, adicione o título do site aqui. Será exibido apenas se você não usar um logotipo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2037,'TPL_BEEZ5_FIELD_SITETITLE_LABEL','Título do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2038,'TPL_BEEZ5_FIELD_WRAPPERLARGE_DESC','Largura do wrapper com colunas adicionais fechadas, em percentual','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2039,'TPL_BEEZ5_FIELD_WRAPPERLARGE_LABEL','Wrapper Grande (%)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2040,'TPL_BEEZ5_FIELD_WRAPPERSMALL_DESC','Largura do wrapper com colunas adicionais abertas, em percentual','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2041,'TPL_BEEZ5_FIELD_WRAPPERSMALL_LABEL','Wrapper Pequeno (%)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2042,'TPL_BEEZ5_FONTSIZE','Tamanho da fonte','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2043,'TPL_BEEZ5_INCREASE_SIZE','Aumentar tamanho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2044,'TPL_BEEZ5_JUMP_TO_INFO','Ir para informações adicionais','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2045,'TPL_BEEZ5_JUMP_TO_NAV','Ir para a navegação e login','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2046,'TPL_BEEZ5_LOGO','Logo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2047,'TPL_BEEZ5_NAV_VIEW_SEARCH','Navegação da busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2048,'TPL_BEEZ5_NAVIGATION','Navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2049,'TPL_BEEZ5_NEXTTAB','Próxima Aba','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2050,'TPL_BEEZ5_OPTION_AFTER_CONTENT','depois do conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2051,'TPL_BEEZ5_OPTION_BEFORE_CONTENT','antes do conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini');
INSERT INTO `portal_modelo_3x`.`pmgov2013_overrider` VALUES  (2052,'TPL_BEEZ5_OPTION_HTML5','html5','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2053,'TPL_BEEZ5_OPTION_XHTML','xhtml','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2054,'TPL_BEEZ5_POWERED_BY','Fornecido por','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2055,'TPL_BEEZ5_RESET','Zerar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2056,'TPL_BEEZ5_REVERT_STYLES_TO_DEFAULT','Reverter estilos para padrão','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2057,'TPL_BEEZ5_SEARCH','Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2058,'TPL_BEEZ5_SKIP_TO_CONTENT','Ir para conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2059,'TPL_BEEZ5_SKIP_TO_ERROR_CONTENT','Ir para mensagem de erro e busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2060,'TPL_BEEZ5_SMALLER','Menor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2061,'TPL_BEEZ5_SYSTEM_MESSAGE','Informações','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2062,'TPL_BEEZ5_TEXTRIGHTCLOSE','Fechar info','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2063,'TPL_BEEZ5_TEXTRIGHTOPEN','Abrir info','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2064,'TPL_BEEZ5_XML_DESCRIPTION','Beez tema acessível para Joomla!, versão HTML5.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2065,'TPL_BEEZ5_YOUR_SITE_DESCRIPTION','Descrição do site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.ini'),
 (2066,'TPL_BEEZ5_POSITION_DEBUG','Debug','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2067,'TPL_BEEZ5_POSITION_POSITION-0','Search','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2068,'TPL_BEEZ5_POSITION_POSITION-1','Top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2069,'TPL_BEEZ5_POSITION_POSITION-2','Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2070,'TPL_BEEZ5_POSITION_POSITION-3','Right bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2071,'TPL_BEEZ5_POSITION_POSITION-4','Left middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2072,'TPL_BEEZ5_POSITION_POSITION-5','Left bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2073,'TPL_BEEZ5_POSITION_POSITION-6','Right top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2074,'TPL_BEEZ5_POSITION_POSITION-7','Left top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2075,'TPL_BEEZ5_POSITION_POSITION-8','Right middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2076,'TPL_BEEZ5_POSITION_POSITION-9','Footer top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2077,'TPL_BEEZ5_POSITION_POSITION-10','Footer middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2078,'TPL_BEEZ5_POSITION_POSITION-11','Footer bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2079,'TPL_BEEZ5_POSITION_POSITION-12','Middle top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2080,'TPL_BEEZ5_POSITION_POSITION-13','Unused','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2081,'TPL_BEEZ5_POSITION_POSITION-14','Footer last','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2082,'TPL_BEEZ5_POSITION_POSITION-15','Header','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2083,'TPL_BEEZ5_XML_DESCRIPTION','Beez tema acessível para Joomla!, versão HTML5.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez5.sys.ini'),
 (2084,'TPL_BEEZ2_ADDITIONAL_INFORMATION','Informações Adicionais','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2085,'TPL_BEEZ2_ALTCLOSE','está fechado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2086,'TPL_BEEZ2_ALTOPEN','está aberto','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2087,'TPL_BEEZ2_BIGGER','Maior','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2088,'TPL_BEEZ2_CLICK','clique','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2089,'TPL_BEEZ2_DECREASE_SIZE','Diminuir o tamanho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2090,'TPL_BEEZ2_ERROR_JUMP_TO_NAV','Ir para navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2091,'TPL_BEEZ2_FIELD_DESCRIPTION_DESC','Por favor informe a descrição do seu site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2092,'TPL_BEEZ2_FIELD_DESCRIPTION_LABEL','Descrição do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2093,'TPL_BEEZ2_FIELD_LOGO_DESC','Por favor, escolha uma imagem. Se você não deseja exibir um logotipo, clique em Selecionar, não destacar qualquer imagem e clique em Inserir na janela','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2094,'TPL_BEEZ2_FIELD_LOGO_LABEL','Logotipo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2095,'TPL_BEEZ2_FIELD_NAVPOSITION_DESC','Navegação antes ou após o conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2096,'TPL_BEEZ2_FIELD_NAVPOSITION_LABEL','Posição da Navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2097,'TPL_BEEZ2_FIELD_SITETITLE_DESC','Por favor, adicione o título do site aqui, é exibida apenas se você não usar um logotipo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2098,'TPL_BEEZ2_FIELD_SITETITLE_LABEL','Título do Site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2099,'TPL_BEEZ2_FIELD_TEMPLATECOLOR_DESC','Cor do Modelo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2100,'TPL_BEEZ2_FIELD_TEMPLATECOLOR_LABEL','Cor do Tema','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2101,'TPL_BEEZ2_FIELD_WRAPPERLARGE_DESC','Wrapper com largura fechado colunas adicionais por cento','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2102,'TPL_BEEZ2_FIELD_WRAPPERLARGE_LABEL','Wrapper Largo (%)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2103,'TPL_BEEZ2_FIELD_WRAPPERSMALL_DESC','Largura do wrapper com colunas adicionais abertas em percentual','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2104,'TPL_BEEZ2_FIELD_WRAPPERSMALL_LABEL','Wrapper Pequeno (%)','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2105,'TPL_BEEZ2_FONTSIZE','Tamanho da fonte','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2106,'TPL_BEEZ2_INCREASE_SIZE','Aumentar tamanho','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2107,'TPL_BEEZ2_JUMP_TO_INFO','Ir para informação adicional','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2108,'TPL_BEEZ2_JUMP_TO_NAV','Ir para navegação principal e entrada','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2109,'TPL_BEEZ2_NAVIGATION','Navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2110,'TPL_BEEZ2_NAV_VIEW_SEARCH','Busca de navegação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2111,'TPL_BEEZ2_NEXTTAB','Próxima aba','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2112,'TPL_BEEZ2_OPTION_AFTER_CONTENT','depois do conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2113,'TPL_BEEZ2_OPTION_BEFORE_CONTENT','antes do conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2114,'TPL_BEEZ2_OPTION_BLACK','Branco','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2115,'TPL_BEEZ2_OPTION_NATURE','Natureza','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2116,'TPL_BEEZ2_OPTION_PERSONAL','Pessoal','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2117,'TPL_BEEZ2_POWERED_BY','Fornecido por','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2118,'TPL_BEEZ2_RESET','Zerar','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2119,'TPL_BEEZ2_REVERT_STYLES_TO_DEFAULT','Reverter estilos para valores padrões','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2120,'TPL_BEEZ2_SEARCH','Busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2121,'TPL_BEEZ2_SKIP_TO_CONTENT','Ir para o conteúdo','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2122,'TPL_BEEZ2_SKIP_TO_ERROR_CONTENT','Ir para mensagem de erro e busca','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2123,'TPL_BEEZ2_SMALLER','Menor','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2124,'TPL_BEEZ2_SYSTEM_MESSAGE','Erro','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2125,'TPL_BEEZ2_TEXTRIGHTCLOSE','Fechar informação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2126,'TPL_BEEZ2_TEXTRIGHTOPEN','Abrir informação','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2127,'TPL_BEEZ2_XML_DESCRIPTION','Beez tema acessível para Joomla!, versão HTML4.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2128,'TPL_BEEZ2_YOUR_SITE_DESCRIPTION','Descrição do seu site','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.ini'),
 (2129,'TPL_BEEZ_20_POSITION_DEBUG','Debug','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2130,'TPL_BEEZ_20_POSITION_POSITION-0','Search','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2131,'TPL_BEEZ_20_POSITION_POSITION-10','Footer middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2132,'TPL_BEEZ_20_POSITION_POSITION-11','Footer bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2133,'TPL_BEEZ_20_POSITION_POSITION-12','Middle top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2134,'TPL_BEEZ_20_POSITION_POSITION-13','Não usado','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2135,'TPL_BEEZ_20_POSITION_POSITION-14','Footer last','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2136,'TPL_BEEZ_20_POSITION_POSITION-15','Header','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2137,'TPL_BEEZ_20_POSITION_POSITION-1','Top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2138,'TPL_BEEZ_20_POSITION_POSITION-2','Breadcrumbs','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2139,'TPL_BEEZ_20_POSITION_POSITION-3','Right bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2140,'TPL_BEEZ_20_POSITION_POSITION-4','Left middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2141,'TPL_BEEZ_20_POSITION_POSITION-5','Left bottom','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2142,'TPL_BEEZ_20_POSITION_POSITION-6','Right top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2143,'TPL_BEEZ_20_POSITION_POSITION-7','Left top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2144,'TPL_BEEZ_20_POSITION_POSITION-8','Right middle','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2145,'TPL_BEEZ_20_POSITION_POSITION-9','Footer top','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini'),
 (2146,'TPL_BEEZ2_XML_DESCRIPTION','Beez tema acessível para Joomla!, versão HTML4.','/Users/rafaelberlanda/Documents/www/joomlagovbr/joomla-2.5/language/pt-BR/pt-BR.tpl_beez_20.sys.ini');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_overrider` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_redirect_links`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_redirect_links`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_redirect_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) NOT NULL,
  `new_url` varchar(255) NOT NULL,
  `referer` varchar(150) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_link_old` (`old_url`),
  KEY `idx_link_modifed` (`modified_date`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_redirect_links`
--

/*!40000 ALTER TABLE `pmgov2013_redirect_links` DISABLE KEYS */;
LOCK TABLES `pmgov2013_redirect_links` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_redirect_links` VALUES  (1,'http://10.200.1.43/INTERNET/portal_modelo_3x/index.php/editoria-a/menu-de-3-nivel','','http://10.200.1.43/INTERNET/portal_modelo_3x/index.php/editoria-a/menu-de-2-nivel','',1,0,'2013-10-21 17:28:46','0000-00-00 00:00:00'),
 (2,'http://localhost/joomlagovbr/joomla-2.5/index.php/component/content/category','','http://localhost/joomlagovbr/joomla-2.5/','',1,0,'2013-11-03 13:00:02','0000-00-00 00:00:00'),
 (3,'http://localhost/joomlagovbr/joomla-3.1/index.php/component/content/category/37-galeria-de-imagens/galeria-1','','http://localhost/joomlagovbr/joomla-3.1/','',1,0,'2013-11-03 17:12:50','0000-00-00 00:00:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_redirect_links` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_schemas`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_schemas`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_schemas` (
  `extension_id` int(11) NOT NULL,
  `version_id` varchar(20) NOT NULL,
  PRIMARY KEY (`extension_id`,`version_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_schemas`
--

/*!40000 ALTER TABLE `pmgov2013_schemas` DISABLE KEYS */;
LOCK TABLES `pmgov2013_schemas` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_schemas` VALUES  (700,'3.1.5');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_schemas` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_session`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_session`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_session` (
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` mediumtext,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_session`
--

/*!40000 ALTER TABLE `pmgov2013_session` DISABLE KEYS */;
LOCK TABLES `pmgov2013_session` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_session` VALUES  ('19fefe58776dd640ca48524fac22c763',0,1,'1383503849','__default|a:7:{s:15:\"session.counter\";i:1;s:19:\"session.timer.start\";i:1383503848;s:18:\"session.timer.last\";i:1383503848;s:17:\"session.timer.now\";i:1383503848;s:22:\"session.client.browser\";s:81:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0\";s:8:\"registry\";O:9:\"JRegistry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":0:{}}s:4:\"user\";O:5:\"JUser\":24:{s:9:\"\\0\\0\\0isRoot\";N;s:2:\"id\";i:0;s:4:\"name\";N;s:8:\"username\";N;s:5:\"email\";N;s:8:\"password\";N;s:14:\"password_clear\";s:0:\"\";s:5:\"block\";N;s:9:\"sendEmail\";i:0;s:12:\"registerDate\";N;s:13:\"lastvisitDate\";N;s:10:\"activation\";N;s:6:\"params\";N;s:6:\"groups\";a:1:{i:0;s:1:\"1\";}s:5:\"guest\";i:1;s:13:\"lastResetTime\";N;s:10:\"resetCount\";N;s:10:\"\\0\\0\\0_params\";O:9:\"JRegistry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":0:{}}s:14:\"\\0\\0\\0_authGroups\";N;s:14:\"\\0\\0\\0_authLevels\";a:2:{i:0;i:1;i:1;i:1;}s:15:\"\\0\\0\\0_authActions\";N;s:12:\"\\0\\0\\0_errorMsg\";N;s:10:\"\\0\\0\\0_errors\";a:0:{}s:3:\"aid\";i:0;}}',0,''),
 ('e96918a3ec93ad66d792869a0d905307',1,0,'1383503963','__default|a:8:{s:15:\"session.counter\";i:45;s:19:\"session.timer.start\";i:1383503435;s:18:\"session.timer.last\";i:1383503963;s:17:\"session.timer.now\";i:1383503963;s:22:\"session.client.browser\";s:81:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0\";s:8:\"registry\";O:9:\"JRegistry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":4:{s:11:\"application\";O:8:\"stdClass\":1:{s:4:\"lang\";s:0:\"\";}s:13:\"com_installer\";O:8:\"stdClass\":4:{s:7:\"message\";s:0:\"\";s:17:\"extension_message\";s:0:\"\";s:8:\"warnings\";O:8:\"stdClass\":1:{s:8:\"ordercol\";N;}s:9:\"languages\";O:8:\"stdClass\":4:{s:6:\"filter\";O:8:\"stdClass\":1:{s:6:\"search\";s:0:\"\";}s:10:\"limitstart\";s:2:\"40\";s:8:\"ordercol\";s:4:\"name\";s:9:\"orderdirn\";s:3:\"asc\";}}s:11:\"com_modules\";O:8:\"stdClass\":3:{s:7:\"modules\";O:8:\"stdClass\":4:{s:6:\"filter\";O:8:\"stdClass\":8:{s:18:\"client_id_previous\";i:0;s:6:\"search\";s:0:\"\";s:6:\"access\";i:0;s:5:\"state\";s:0:\"\";s:8:\"position\";s:0:\"\";s:6:\"module\";s:12:\"mod_chamadas\";s:9:\"client_id\";i:0;s:8:\"language\";s:0:\"\";}s:10:\"limitstart\";i:0;s:8:\"ordercol\";s:8:\"position\";s:9:\"orderdirn\";s:3:\"asc\";}s:4:\"edit\";O:8:\"stdClass\":1:{s:6:\"module\";O:8:\"stdClass\":2:{s:2:\"id\";a:0:{}s:4:\"data\";N;}}s:3:\"add\";O:8:\"stdClass\":1:{s:6:\"module\";O:8:\"stdClass\":2:{s:12:\"extension_id\";N;s:6:\"params\";N;}}}s:6:\"global\";O:8:\"stdClass\":1:{s:4:\"list\";O:8:\"stdClass\":1:{s:5:\"limit\";i:20;}}}}s:4:\"user\";O:5:\"JUser\":24:{s:9:\"\\0\\0\\0isRoot\";b:1;s:2:\"id\";s:3:\"576\";s:4:\"name\";s:10:\"Super User\";s:8:\"username\";s:5:\"admin\";s:5:\"email\";s:21:\"joomlagovbr@gmail.com\";s:8:\"password\";s:65:\"c4895b7096aa172a0f9f77a92938bcd5:cPJGYBfZT5Ixm70kR3hcqGZUHvkssOLp\";s:14:\"password_clear\";s:0:\"\";s:5:\"block\";s:1:\"0\";s:9:\"sendEmail\";s:1:\"1\";s:12:\"registerDate\";s:19:\"2013-10-14 14:26:05\";s:13:\"lastvisitDate\";s:19:\"2013-11-03 18:30:35\";s:10:\"activation\";s:1:\"0\";s:6:\"params\";s:95:\"{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"jce\",\"helpsite\":\"\",\"timezone\":\"\"}\";s:6:\"groups\";a:1:{i:8;s:1:\"8\";}s:5:\"guest\";i:0;s:13:\"lastResetTime\";s:19:\"0000-00-00 00:00:00\";s:10:\"resetCount\";s:1:\"0\";s:10:\"\\0\\0\\0_params\";O:9:\"JRegistry\":1:{s:7:\"\\0\\0\\0data\";O:8:\"stdClass\":6:{s:11:\"admin_style\";s:0:\"\";s:14:\"admin_language\";s:0:\"\";s:8:\"language\";s:0:\"\";s:6:\"editor\";s:3:\"jce\";s:8:\"helpsite\";s:0:\"\";s:8:\"timezone\";s:0:\"\";}}s:14:\"\\0\\0\\0_authGroups\";a:2:{i:0;i:1;i:1;i:8;}s:14:\"\\0\\0\\0_authLevels\";a:4:{i:0;i:1;i:1;i:1;i:2;i:2;i:3;i:3;}s:15:\"\\0\\0\\0_authActions\";N;s:12:\"\\0\\0\\0_errorMsg\";N;s:10:\"\\0\\0\\0_errors\";a:0:{}s:3:\"aid\";i:0;}s:13:\"session.token\";s:32:\"2e302c945421eaac560544f595f42a44\";}',576,'admin');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_session` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_tags`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_tags`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tag_idx` (`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_tags`
--

/*!40000 ALTER TABLE `pmgov2013_tags` DISABLE KEYS */;
LOCK TABLES `pmgov2013_tags` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_tags` VALUES  (1,0,0,1,0,'','ROOT',0x726F6F74,'','',1,0,'0000-00-00 00:00:00',1,'{}','','','',0,'2011-01-01 00:00:01','',0,'0000-00-00 00:00:00','','',0,'*',1,'0000-00-00 00:00:00','0000-00-00 00:00:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_tags` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_template_styles`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_template_styles`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` char(7) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_template_styles`
--

/*!40000 ALTER TABLE `pmgov2013_template_styles` DISABLE KEYS */;
LOCK TABLES `pmgov2013_template_styles` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_template_styles` VALUES  (2,'bluestork',1,'0','Bluestork - Default','{\"useRoundedCorners\":\"1\",\"showSiteName\":\"0\"}'),
 (3,'atomic',0,'0','Atomic - Default','{}'),
 (4,'beez_20',0,'0','Beez2 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"images\\/joomla_black.gif\",\"sitetitle\":\"Joomla!\",\"sitedescription\":\"Open Source Content Management\",\"navposition\":\"left\",\"templatecolor\":\"personal\",\"html5\":\"0\"}'),
 (5,'hathor',1,'0','Hathor - Default','{\"showSiteName\":\"0\",\"colourChoice\":\"\",\"boldText\":\"0\"}'),
 (6,'beez5',0,'0','Beez5 - Default','{\"wrapperSmall\":\"53\",\"wrapperLarge\":\"72\",\"logo\":\"images\\/sampledata\\/fruitshop\\/fruits.gif\",\"sitetitle\":\"Joomla!\",\"sitedescription\":\"Open Source Content Management\",\"navposition\":\"left\",\"html5\":\"0\"}'),
 (7,'padraogoverno01',0,'1','padraogoverno01 - Default','{\"cor\":\"verde\",\"font_style_url\":\"http:\\/\\/fonts.googleapis.com\\/css?family=Open+Sans:400italic,400,600,800,700\",\"denominacao\":\"Denomina\\u00e7\\u00e3o do \\u00f3rg\\u00e3o\",\"nome_principal\":\"Nome principal\",\"subordinacao\":\"subordina\\u00e7\\u00e3o\",\"rodape_acesso_informacao\":1,\"rodape_logo_brasil\":1,\"mensagem_final_ferramenta\":\"\",\"clear_default_javascript\":1,\"local_jquery\":\"footer\",\"anexar_js_barra2014\":\"1\",\"endereco_js_barra2014\":\"http:\\/\\/barra.brasil.gov.br\\/barra.js?cor=verde\",\"google_analytics_id\":\"\",\"google_analytics_domain_name\":\"\",\"google_analytics_allow_linker\":0}'),
 (8,'protostar',0,'0','protostar - Default','{\"templateColor\":\"\",\"logoFile\":\"\",\"googleFont\":\"1\",\"googleFontName\":\"Open+Sans\",\"fluidContainer\":\"0\"}'),
 (9,'isis',1,'1','isis - Default','{\"templateColor\":\"\",\"logoFile\":\"\"}'),
 (10,'beez3',0,'0','beez3 - Default','{\"wrapperSmall\":53,\"wrapperLarge\":72,\"logo\":\"\",\"sitetitle\":\"\",\"sitedescription\":\"\",\"navposition\":\"center\",\"bootstrap\":\"\",\"templatecolor\":\"nature\",\"headerImage\":\"\",\"backgroundcolor\":\"#eee\"}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_template_styles` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_ucm_base`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_ucm_base`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_ucm_base` (
  `ucm_id` int(10) unsigned NOT NULL,
  `ucm_item_id` int(10) NOT NULL,
  `ucm_type_id` int(11) NOT NULL,
  `ucm_language_id` int(11) NOT NULL,
  PRIMARY KEY (`ucm_id`),
  KEY `idx_ucm_item_id` (`ucm_item_id`),
  KEY `idx_ucm_type_id` (`ucm_type_id`),
  KEY `idx_ucm_language_id` (`ucm_language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_ucm_base`
--

/*!40000 ALTER TABLE `pmgov2013_ucm_base` DISABLE KEYS */;
LOCK TABLES `pmgov2013_ucm_base` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_ucm_base` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_ucm_content`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_ucm_content`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_ucm_content` (
  `core_content_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `core_type_alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'FK to the content types table',
  `core_title` varchar(255) NOT NULL,
  `core_alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `core_body` mediumtext NOT NULL,
  `core_state` tinyint(1) NOT NULL DEFAULT '0',
  `core_checked_out_time` varchar(255) NOT NULL DEFAULT '',
  `core_checked_out_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `core_access` int(10) unsigned NOT NULL DEFAULT '0',
  `core_params` text NOT NULL,
  `core_featured` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `core_metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `core_created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `core_created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `core_created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `core_modified_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Most recent user that modified',
  `core_modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `core_language` char(7) NOT NULL,
  `core_publish_up` datetime NOT NULL,
  `core_publish_down` datetime NOT NULL,
  `core_content_item_id` int(10) unsigned DEFAULT NULL COMMENT 'ID from the individual type table',
  `asset_id` int(10) unsigned DEFAULT NULL COMMENT 'FK to the #__assets table.',
  `core_images` text NOT NULL,
  `core_urls` text NOT NULL,
  `core_hits` int(10) unsigned NOT NULL DEFAULT '0',
  `core_version` int(10) unsigned NOT NULL DEFAULT '1',
  `core_ordering` int(11) NOT NULL DEFAULT '0',
  `core_metakey` text NOT NULL,
  `core_metadesc` text NOT NULL,
  `core_catid` int(10) unsigned NOT NULL DEFAULT '0',
  `core_xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `core_type_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`core_content_id`),
  KEY `tag_idx` (`core_state`,`core_access`),
  KEY `idx_access` (`core_access`),
  KEY `idx_alias` (`core_alias`),
  KEY `idx_language` (`core_language`),
  KEY `idx_title` (`core_title`),
  KEY `idx_modified_time` (`core_modified_time`),
  KEY `idx_created_time` (`core_created_time`),
  KEY `idx_content_type` (`core_type_alias`),
  KEY `idx_core_modified_user_id` (`core_modified_user_id`),
  KEY `idx_core_checked_out_user_id` (`core_checked_out_user_id`),
  KEY `idx_core_created_user_id` (`core_created_user_id`),
  KEY `idx_core_type_id` (`core_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains core content data in name spaced fields';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_ucm_content`
--

/*!40000 ALTER TABLE `pmgov2013_ucm_content` DISABLE KEYS */;
LOCK TABLES `pmgov2013_ucm_content` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_ucm_content` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_update_sites`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_update_sites`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  `last_check_timestamp` bigint(20) DEFAULT '0',
  PRIMARY KEY (`update_site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Update Sites';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_update_sites`
--

/*!40000 ALTER TABLE `pmgov2013_update_sites` DISABLE KEYS */;
LOCK TABLES `pmgov2013_update_sites` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_update_sites` VALUES  (1,'Joomla Core','collection','http://update.joomla.org/core/sts/list_sts.xml',1,1383503872),
 (2,'Joomla Extension Directory','collection','http://update.joomla.org/jed/list.xml',1,1383503872),
 (3,'Accredited Joomla! Translations','collection','http://update.joomla.org/language/translationlist_3.xml',1,1383503872),
 (4,'JCE Editor Updates','extension','https://www.joomlacontenteditor.net/index.php?option=com_updates&view=update&format=xml&id=1',1,1383503872),
 (5,'Blackdale','extension','http://updates.blackdale.com/update/modblank250/modblank250.xml',1,1383503872);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_update_sites` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_update_sites_extensions`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_update_sites_extensions`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_update_sites_extensions` (
  `update_site_id` int(11) NOT NULL DEFAULT '0',
  `extension_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`update_site_id`,`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links extensions to update sites';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_update_sites_extensions`
--

/*!40000 ALTER TABLE `pmgov2013_update_sites_extensions` DISABLE KEYS */;
LOCK TABLES `pmgov2013_update_sites_extensions` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_update_sites_extensions` VALUES  (1,700),
 (2,700),
 (3,600),
 (4,10005),
 (5,10014);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_update_sites_extensions` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_updates`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_updates`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `description` text NOT NULL,
  `element` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `folder` varchar(20) DEFAULT '',
  `client_id` tinyint(3) DEFAULT '0',
  `version` varchar(10) DEFAULT '',
  `data` text NOT NULL,
  `detailsurl` text NOT NULL,
  `infourl` text NOT NULL,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='Available Updates';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_updates`
--

/*!40000 ALTER TABLE `pmgov2013_updates` DISABLE KEYS */;
LOCK TABLES `pmgov2013_updates` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_updates` VALUES  (1,3,0,'Malay','','pkg_ms-MY','package','',0,'3.1.5.4','','http://update.joomla.org/language/details3/ms-MY_details.xml',''),
 (2,3,0,'Romanian','','pkg_ro-RO','package','',0,'3.1.1.2','','http://update.joomla.org/language/details3/ro-RO_details.xml',''),
 (3,3,0,'Flemish','','pkg_nl-BE','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/nl-BE_details.xml',''),
 (4,3,0,'Chinese Traditional','','pkg_zh-TW','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/zh-TW_details.xml',''),
 (5,3,0,'French','','pkg_fr-FR','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/fr-FR_details.xml',''),
 (6,3,0,'German','','pkg_de-DE','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/de-DE_details.xml',''),
 (7,3,0,'Greek','','pkg_el-GR','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/el-GR_details.xml',''),
 (8,3,0,'Hebrew','','pkg_he-IL','package','',0,'3.1.1.1','','http://update.joomla.org/language/details3/he-IL_details.xml',''),
 (9,3,0,'Hungarian','','pkg_hu-HU','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/hu-HU_details.xml',''),
 (10,3,0,'Afrikaans','','pkg_af-ZA','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/af-ZA_details.xml',''),
 (11,3,0,'Arabic Unitag','','pkg_ar-AA','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/ar-AA_details.xml',''),
 (12,3,0,'Bulgarian','','pkg_bg-BG','package','',0,'3.0.3.1','','http://update.joomla.org/language/details3/bg-BG_details.xml',''),
 (13,3,0,'Catalan','','pkg_ca-ES','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/ca-ES_details.xml',''),
 (14,3,0,'Chinese Simplified','','pkg_zh-CN','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/zh-CN_details.xml',''),
 (15,3,0,'Croatian','','pkg_hr-HR','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/hr-HR_details.xml',''),
 (16,3,0,'Czech','','pkg_cs-CZ','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/cs-CZ_details.xml',''),
 (17,3,0,'Danish','','pkg_da-DK','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/da-DK_details.xml',''),
 (18,3,0,'Dutch','','pkg_nl-NL','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/nl-NL_details.xml',''),
 (19,3,0,'English AU','','pkg_en-AU','package','',0,'3.1.0.1','','http://update.joomla.org/language/details3/en-AU_details.xml',''),
 (20,3,0,'English US','','pkg_en-US','package','',0,'3.1.0.1','','http://update.joomla.org/language/details3/en-US_details.xml',''),
 (21,3,0,'Estonian','','pkg_et-EE','package','',0,'3.1.2.1','','http://update.joomla.org/language/details3/et-EE_details.xml',''),
 (22,3,0,'Italian','','pkg_it-IT','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/it-IT_details.xml',''),
 (23,3,0,'Japanese','','pkg_ja-JP','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/ja-JP_details.xml',''),
 (24,3,0,'Korean','','pkg_ko-KR','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/ko-KR_details.xml',''),
 (25,3,0,'Latvian','','pkg_lv-LV','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/lv-LV_details.xml',''),
 (26,3,0,'Macedonian','','pkg_mk-MK','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/mk-MK_details.xml',''),
 (27,3,0,'Norwegian Bokmal','','pkg_nb-NO','package','',0,'3.1.1.1','','http://update.joomla.org/language/details3/nb-NO_details.xml',''),
 (28,3,0,'Persian','','pkg_fa-IR','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/fa-IR_details.xml',''),
 (29,3,0,'Polish','','pkg_pl-PL','package','',0,'3.1.4.2','','http://update.joomla.org/language/details3/pl-PL_details.xml',''),
 (30,3,0,'Russian','','pkg_ru-RU','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/ru-RU_details.xml',''),
 (31,3,0,'Slovak','','pkg_sk-SK','package','',0,'3.1.5.3','','http://update.joomla.org/language/details3/sk-SK_details.xml',''),
 (32,3,0,'Swedish','','pkg_sv-SE','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/sv-SE_details.xml',''),
 (33,3,0,'Syriac','','pkg_sy-IQ','package','',0,'3.1.2.1','','http://update.joomla.org/language/details3/sy-IQ_details.xml',''),
 (34,3,0,'Tamil','','pkg_ta-IN','package','',0,'3.1.5.2','','http://update.joomla.org/language/details3/ta-IN_details.xml',''),
 (35,3,0,'Thai','','pkg_th-TH','package','',0,'3.1.4.2','','http://update.joomla.org/language/details3/th-TH_details.xml',''),
 (36,3,0,'Turkish','','pkg_tr-TR','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/tr-TR_details.xml',''),
 (37,3,0,'Ukrainian','','pkg_uk-UA','package','',0,'3.1.4.4','','http://update.joomla.org/language/details3/uk-UA_details.xml',''),
 (38,3,0,'Uyghur','','pkg_ug-CN','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/ug-CN_details.xml',''),
 (39,3,0,'Albanian','','pkg_sq-AL','package','',0,'3.1.1.1','','http://update.joomla.org/language/details3/sq-AL_details.xml',''),
 (40,3,0,'Serbian Latin','','pkg_sr-YU','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/sr-YU_details.xml',''),
 (41,3,0,'Spanish','','pkg_es-ES','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/es-ES_details.xml',''),
 (42,3,0,'Bosnian','','pkg_bs-BA','package','',0,'3.1.1.1','','http://update.joomla.org/language/details3/bs-BA_details.xml',''),
 (43,3,0,'Serbian Cyrillic','','pkg_sr-RS','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/sr-RS_details.xml',''),
 (44,3,0,'Bahasa Indonesia','','pkg_id-ID','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/id-ID_details.xml',''),
 (45,3,0,'Finnish','','pkg_fi-FI','package','',0,'3.1.4.1','','http://update.joomla.org/language/details3/fi-FI_details.xml',''),
 (46,3,0,'Swahili','','pkg_sw-KE','package','',0,'3.1.5.1','','http://update.joomla.org/language/details3/sw-KE_details.xml','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_updates` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_user_notes`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_user_notes`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_user_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `review_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_category_id` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_user_notes`
--

/*!40000 ALTER TABLE `pmgov2013_user_notes` DISABLE KEYS */;
LOCK TABLES `pmgov2013_user_notes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_user_notes` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_user_profiles`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_user_profiles`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_user_profiles`
--

/*!40000 ALTER TABLE `pmgov2013_user_profiles` DISABLE KEYS */;
LOCK TABLES `pmgov2013_user_profiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_user_profiles` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_user_usergroup_map`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_user_usergroup_map`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_user_usergroup_map` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_user_usergroup_map`
--

/*!40000 ALTER TABLE `pmgov2013_user_usergroup_map` DISABLE KEYS */;
LOCK TABLES `pmgov2013_user_usergroup_map` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_user_usergroup_map` VALUES  (576,8);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_user_usergroup_map` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_usergroups`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_usergroups`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_parent_title_lookup` (`parent_id`,`title`),
  KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_usergroups`
--

/*!40000 ALTER TABLE `pmgov2013_usergroups` DISABLE KEYS */;
LOCK TABLES `pmgov2013_usergroups` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_usergroups` VALUES  (1,0,1,20,'Public'),
 (2,1,6,17,'Registered'),
 (3,2,7,14,'Author'),
 (4,3,8,11,'Editor'),
 (5,4,9,10,'Publisher'),
 (6,1,2,5,'Manager'),
 (7,6,3,4,'Administrator'),
 (8,1,18,19,'Super Users');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_usergroups` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_users`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_users`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `lastResetTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of last password reset',
  `resetCount` int(11) NOT NULL DEFAULT '0' COMMENT 'Count of password resets since lastResetTime',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=577 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_users`
--

/*!40000 ALTER TABLE `pmgov2013_users` DISABLE KEYS */;
LOCK TABLES `pmgov2013_users` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_users` VALUES  (576,'Super User','admin','joomlagovbr@gmail.com','c4895b7096aa172a0f9f77a92938bcd5:cPJGYBfZT5Ixm70kR3hcqGZUHvkssOLp',0,1,'2013-10-14 14:26:05','2013-11-03 18:30:41','0','{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"jce\",\"helpsite\":\"\",\"timezone\":\"\"}','0000-00-00 00:00:00',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_users` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_viewlevels`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_viewlevels`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_viewlevels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `title` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_assetgroup_title_lookup` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_viewlevels`
--

/*!40000 ALTER TABLE `pmgov2013_viewlevels` DISABLE KEYS */;
LOCK TABLES `pmgov2013_viewlevels` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_viewlevels` VALUES  (1,'Public',0,'[1]'),
 (2,'Registered',1,'[6,2,8]'),
 (3,'Special',2,'[6,3,8]');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_viewlevels` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_weblinks`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_weblinks`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_weblinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `access` int(11) NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `language` char(7) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if link is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_weblinks`
--

/*!40000 ALTER TABLE `pmgov2013_weblinks` DISABLE KEYS */;
LOCK TABLES `pmgov2013_weblinks` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_weblinks` ENABLE KEYS */;


--
-- Definition of table `portal_modelo_3x`.`pmgov2013_wf_profiles`
--

DROP TABLE IF EXISTS `portal_modelo_3x`.`pmgov2013_wf_profiles`;
CREATE TABLE  `portal_modelo_3x`.`pmgov2013_wf_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `users` text NOT NULL,
  `types` text NOT NULL,
  `components` text NOT NULL,
  `area` tinyint(3) NOT NULL,
  `device` varchar(255) NOT NULL,
  `rows` text NOT NULL,
  `plugins` text NOT NULL,
  `published` tinyint(3) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` tinyint(3) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `portal_modelo_3x`.`pmgov2013_wf_profiles`
--

/*!40000 ALTER TABLE `pmgov2013_wf_profiles` DISABLE KEYS */;
LOCK TABLES `pmgov2013_wf_profiles` WRITE;
INSERT INTO `portal_modelo_3x`.`pmgov2013_wf_profiles` VALUES  (1,'Default','Default Profile for all users','','3,4,5,6,8,7','',0,'desktop,tablet,phone','help,newdocument,undo,redo,spacer,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,blockquote,formatselect,styleselect,removeformat,cleanup;fontselect,fontsizeselect,forecolor,backcolor,spacer,clipboard,indent,outdent,lists,sub,sup,textcase,charmap,hr;directionality,fullscreen,preview,source,print,searchreplace,spacer,table;visualaid,visualchars,visualblocks,nonbreaking,style,xhtmlxtras,anchor,unlink,link,imgmanager,spellchecker,article','charmap,contextmenu,browser,inlinepopups,media,help,clipboard,searchreplace,directionality,fullscreen,preview,source,table,textcase,print,style,nonbreaking,visualchars,visualblocks,xhtmlxtras,imgmanager,anchor,link,spellchecker,article,lists',1,1,0,'0000-00-00 00:00:00',''),
 (2,'Front End','Sample Front-end Profile','','3,4,5','',1,'desktop,tablet,phone','help,newdocument,undo,redo,spacer,bold,italic,underline,strikethrough,justifyfull,justifycenter,justifyleft,justifyright,spacer,formatselect,styleselect;clipboard,searchreplace,indent,outdent,lists,cleanup,charmap,removeformat,hr,sub,sup,textcase,nonbreaking,visualchars,visualblocks;fullscreen,preview,print,visualaid,style,xhtmlxtras,anchor,unlink,link,imgmanager,spellchecker,article','charmap,contextmenu,inlinepopups,help,clipboard,searchreplace,fullscreen,preview,print,style,textcase,nonbreaking,visualchars,visualblocks,xhtmlxtras,imgmanager,anchor,link,spellchecker,article,lists',0,2,0,'0000-00-00 00:00:00',''),
 (3,'Blogger','Simple Blogging Profile','','3,4,5,6,8,7','',0,'desktop,tablet,phone','bold,italic,strikethrough,lists,blockquote,spacer,justifyleft,justifycenter,justifyright,spacer,link,unlink,imgmanager,article,spellchecker,fullscreen,kitchensink;formatselect,underline,justifyfull,forecolor,clipboard,removeformat,charmap,indent,outdent,undo,redo,help','link,imgmanager,article,spellchecker,fullscreen,kitchensink,clipboard,contextmenu,inlinepopups,lists',0,3,0,'0000-00-00 00:00:00','{\"editor\":{\"toggle\":\"0\"}}'),
 (4,'Mobile','Sample Mobile Profile','','3,4,5,6,8,7','',0,'tablet,phone','undo,redo,spacer,bold,italic,underline,formatselect,spacer,justifyleft,justifycenter,justifyfull,justifyright,spacer,fullscreen,kitchensink;styleselect,lists,spellchecker,article,link,unlink','fullscreen,kitchensink,spellchecker,article,link,inlinepopups,lists',0,4,0,'0000-00-00 00:00:00','{\"editor\":{\"toolbar_theme\":\"mobile\",\"resizing\":\"0\",\"resize_horizontal\":\"0\",\"resizing_use_cookie\":\"0\",\"toggle\":\"0\",\"links\":{\"popups\":{\"default\":\"\",\"jcemediabox\":{\"enable\":\"0\"},\"window\":{\"enable\":\"0\"}}}}}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pmgov2013_wf_profiles` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
