<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;
 
// adquirir instancia da controller perfixada por Agenda Dirigentes
$controller = JControllerLegacy::getInstance('AgendaDirigentes');
 
// Executar a task solicitada
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
 
// Redirecionar, se definido pela controller
$controller->redirect();