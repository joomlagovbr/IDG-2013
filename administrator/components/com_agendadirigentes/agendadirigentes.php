<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die('Restricted access');

// Access check: is this user allowed to access the backend of this component?
if (!JFactory::getUser()->authorise('core.manage', 'com_agendadirigentes')) 
{
        return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// require helper file
JLoader::register('AgendaDirigentesHelper', JPATH_COMPONENT. '/helpers/agendadirigentes.php');

// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-compromisso {background-image: url(../media/com_agendadirigentes/images/icon-calendar.png);}');
 
//verificacoes globais
AgendaDirigentesHelper::verifySearchPlugin();

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by AgendaDirigentes
$controller = JControllerLegacy::getInstance('AgendaDirigentes');
 
// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();