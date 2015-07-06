<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_videosdestaque
 *
 * @copyright   Copyright (C) 2015 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;


require_once __DIR__ . '/helper.php';
$items = ModVideosDestaqueHelper::getItems($params);
$text_link_footer       = $params->get('text_link_footer', '');
$url_link_footer        = str_ireplace(array('{SITE}/', '{SITE}'), JURI::root(), $params->get('url_link_footer', '') );
// $link_saiba_mais = ModVideosDestaqueHelper::getLink($params, array('link_saiba_mais', 'link_saiba_mais_menu', 'link_saiba_mais_article'));
require JModuleHelper::getLayoutPath('mod_videosdestaque', $params->get('layout', 'default') );