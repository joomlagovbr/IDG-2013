<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_barradogoverno
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

$require_layout = str_replace('_:', '', $params->get('layout', 2012));
$anexar_head = $params->get('anexar_head', '');
if($anexar_head != '') {
	$document =& JFactory::getDocument();
	$document->addCustomTag($anexar_head);
}
require JPATH_SITE . '/modules/mod_barradogoverno/assets/'.$require_layout.'/init.php';
require JModuleHelper::getLayoutPath('mod_barradogoverno', $params->get('layout', 2012) );