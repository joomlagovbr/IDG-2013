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
        public static function addSubmenu($submenu = NULL, $canDo = NULL)
        {
            if(is_null($canDo))
                $canDo  = JHelperContent::getActions('com_agendadirigentes');

            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_COMPROMISSOS'),
                'index.php?option=com_agendadirigentes&view=compromissos',
                $submenu == 'compromissos'
            );
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_CATEGORIES'),
                'index.php?option=com_categories&extension=com_agendadirigentes',
                $submenu == 'categories');

            if ($canDo->get('dirigentes.list')) 
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_DIRIGENTES'),
                    'index.php?option=com_agendadirigentes&view=dirigentes',
                    $submenu == 'dirigentes'
                );  
            }

            if ($canDo->get('cargos.list')) 
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_CARGOS'),
                    'index.php?option=com_agendadirigentes&view=cargos',
                    $submenu == 'cargos'
                );                  
            }
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

        /*deprecated in joomla 3... remover na documentacao depois (mas ha um erro na funcao original, que ainda precisarah ser comitado)
        public static function getActions($compromissoId = 0)
        {
            jimport('joomla.access.access');
            $user   = JFactory::getUser();
            $result = new JObject;
            if (empty($compromissoId))
            { 
                $assetName = 'com_agendadirigentes';
            }
            else
            {
                $assetName = 'com_agendadirigentes.compromisso.'.(int) $compromissoId;
            }
            $actions = JAccess::getActions('com_agendadirigentes', 'component');
            foreach ($actions as $action) { 
                $result->set($action->name, $user->authorise($action->name, $assetName));
            }
            return $result;
        }
        */

        // nova getAtions, necessaria mesmo no joomla 3. Codigo se baseia na funcao original JHelperContent::getActions
        public static function getActions($component = '', $section = '', $id = 0)
        {
            if ( (is_int($component) || is_null($component)) || (empty($section) || $section=='component') ) //linha modificada em relacao a funcao original
            {
                $result = JHelperContent::getActions($component, $section, $id);                        
                return $result;
            }

            $user   = JFactory::getUser();
            $result = new JObject;

            $path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';

            if ($section && $id)
            {
                $assetName = $component . '.' . $section . '.' . (int) $id;
            }
            else
            {
                $assetName = $component;
            }

            $actions = JAccess::getActionsFromFile($path, "/access/section[@name='" . $section . "']/"); //linha modificada em relacao a funcao original

            foreach ($actions as $action)
            {
                $result->set($action->name, $user->authorise($action->name, $assetName));
            }

            return $result;
        }

}