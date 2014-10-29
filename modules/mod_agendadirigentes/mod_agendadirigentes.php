<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;


require_once __DIR__ . '/helper.php';
$items = ModAgendaDirigentesHelper::getItems($params);
$dia_por_extenso = ModAgendaDirigentesHelper::getFormatedDate($params);
$document = JFactory::getDocument();

$featured_compromissos = $params->get('featured_compromissos', '');
if(!empty($featured_compromissos) && !is_numeric($featured_compromissos))
	$featured_compromissos = '';

$altura_lista = ModAgendaDirigentesHelper::getAlturaLista($params);
if(!empty($altura_lista))
	$style_altura_lista = 'style="height: '.$altura_lista.'px; overflow:auto"';
else
	$style_altura_lista = '';

$limit_char = $params->get('limit_title_compromissos', '');
if(! empty($limit_char))
{	
	for ($i=0, $limit = count($items); $i < $limit; $i++) { 
		
		if(strlen($items[$i]->title) <= $limit_char)
			continue;

		$items[$i]->title = substr($items[$i]->title, 0, $limit_char);
		
		$last_space = strrpos($items[$i]->title, ' ');
		if($last_space > $limit_char*0.6)
			$items[$i]->title = substr($items[$i]->title, 0, $last_space);

		$items[$i]->title .= $params->get('delimiter_title_compromissos', '');

	}
}

require JModuleHelper::getLayoutPath('mod_agendadirigentes', $params->get('layout', 'default') );