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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * DirigenteList Model
 */
class AgendaDirigentesModelDirigentes extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return      string  An SQL query
         */
        protected function getListQuery()
        {
                // Create a new query object.           
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields from the hello table
                $query
                    ->select('DISTINCT a.*, c.name as cargo, d.title as categoria') //, e.name as proprietario
                    ->from( $db->quoteName('#__agendadedirigentes_dirigentes', 'a') )
                    ->join('LEFT', $db->quoteName('#__agendadedirigentes_cargos', 'c') . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.cargo_id') . ')')
                    ->join('INNER', $db->quoteName('#__categories', 'd') . ' ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('a.catid') . ')')
                    ;

                return $query;
        }
}