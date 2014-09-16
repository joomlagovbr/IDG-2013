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

// importar library de views do Joomla
jimport('joomla.application.component.view');
 
/**
* HTML View class para AgendaDirigentes Component
*
* @since 0.0.1
*/
class AgendaDirigentesViewAutoridades extends JViewCategories
{
        /**
         * Language key for default page heading
         *
         * @var    string
         * @since  3.2
         */
        protected $pageHeading = 'Agenda de Dirigentes';

        /**
         * @var    string  The name of the extension for the category
         * @since  3.2
         */
        protected $extension = 'com_agendadirigentes';
}

?>