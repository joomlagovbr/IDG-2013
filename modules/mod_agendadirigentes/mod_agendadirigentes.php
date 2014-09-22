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

require JModuleHelper::getLayoutPath('mod_agendadirigentes', $params->get('layout', 'default') );