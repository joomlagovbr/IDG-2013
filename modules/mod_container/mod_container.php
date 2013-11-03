<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_container
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
$position               = $params->get('posicao', '');
$moduleclass_sfx        = $params->get('moduleclass_sfx', '');
$moduleclass_sfx_level2 = '';

$headerLevel            = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;
$tag1                   = $params->get('container_level1', 'div');
$tag2                   = $params->get('container_level2', 'div');
$title_outstanding      = $params->get('title_outstanding', 1);
$auto_divisor           = $params->get('auto_divisor', 1);

$alternative_title      = $params->get('alternative_title', '');
$text_link_title        = $params->get('text_link_title', '');
$url_link_title         = str_ireplace(array('{SITE}/', '{SITE}'), JURI::root(), $params->get('url_link_title', '') );

$show_footer            = $params->get('show_footer', 0);
$text_link_footer       = $params->get('text_link_footer', '');
$url_link_footer        = str_ireplace(array('{SITE}/', '{SITE}'), JURI::root(), $params->get('url_link_footer', '') );

$numero_colunas         = $params->get('numero_limite_colunas', 3);
$grid_positions         = 12;

if($title_outstanding)
	$moduleclass_sfx = $moduleclass_sfx.' module';

if (!empty($moduleclass_sfx)) {
	$class = 'class="'.$moduleclass_sfx.'"';
}
else
	$class = '';

if(!empty($alternative_title))
	$module->title = $alternative_title;

if(!empty($position))
{
	$modules = JModuleHelper::getModules( $position );
	if ($auto_divisor) {
		$divisor = intval($grid_positions / count($modules));	
		if($divisor >= ($grid_positions/$numero_colunas))
			$moduleclass_sfx_level2 = trim($moduleclass_sfx_level2.' span'.$divisor);
		else
			$moduleclass_sfx_level2 = trim($moduleclass_sfx_level2.' row');		
	}
	require JModuleHelper::getLayoutPath('mod_container', $params->get('layout', 'default') );
}