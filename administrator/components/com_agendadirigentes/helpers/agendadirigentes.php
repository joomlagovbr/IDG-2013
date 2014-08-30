<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * AgendaDirigentes component helper.
 */
class AgendaDirigentesHelper extends JHelperContent
{

    public static $extension = 'com_agendadirigentes';

        /**
         * Configure the Linkbar.
         */
        public static function addSubmenu($submenu)
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_COMPROMISSOS'),
                'index.php?option=com_agendadirigentes&view=compromissos',
                $submenu == 'compromissos'
            );
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_CATEGORIES'),
                'index.php?option=com_categories&extension=com_agendadirigentes',
                $submenu == 'categories');
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_DIRIGENTES'),
                'index.php?option=com_agendadirigentes&view=dirigentes',
                $submenu == 'dirigentes'
            );  
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_CARGOS'),
                'index.php?option=com_agendadirigentes&view=cargos',
                $submenu == 'cargos'
            );  
            // configurando algumas propriedades da pagina de categorias
            // $document = JFactory::getDocument();
            // $document->addStyleDeclaration('.agendadirigentes-categories ' .
            //                                '{ background-image: url(../media/com_agendadirigentes/images/icon-calendar.png);}');
            //nao eh preciso usar o titulo no j3.3
            /*
            if ($submenu == 'categories') 
            {                
                    // $document->setTitle('teste'.JText::_('COM_AGENDADIRIGENTES_ADMINISTRATION_CATEGORIES'));
                $document->setTitle('testes');
            }*/
        }
}