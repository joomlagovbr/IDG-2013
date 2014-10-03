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
                $canDo  = self::getActions('com_agendadirigentes');

            $can_categories_list = $canDo->get('categories.list');
            $can_dirigentes_list = $canDo->get('dirigentes.list');
            $can_cargos_list = $canDo->get('cargos.list');

            if ($can_categories_list || $can_dirigentes_list || $can_cargos_list)
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_COMPROMISSOS'),
                    'index.php?option=com_agendadirigentes&view=compromissos',
                    $submenu == 'compromissos'
                );
            }

            if ($can_categories_list) 
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_CATEGORIES'),
                    'index.php?option=com_categories&extension=com_agendadirigentes',
                    $submenu == 'categories');
            }

            if ($can_dirigentes_list) 
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_DIRIGENTES'),
                    'index.php?option=com_agendadirigentes&view=dirigentes',
                    $submenu == 'dirigentes'
                );  
            }

            if ($can_cargos_list) 
            {
                JHtmlSidebar::addEntry(
                    JText::_('COM_AGENDADIRIGENTES_SUBMENU_CARGOS'),
                    'index.php?option=com_agendadirigentes&view=cargos',
                    $submenu == 'cargos'
                );                  
            }
            
        }

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