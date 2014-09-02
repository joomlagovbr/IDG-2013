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
class AgendaDirigentesModelCargos extends JModelList
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
                // Select some fields from the cargos table
                $query
                    ->select('DISTINCT a.id, a.name, a.catid, b.title AS titleCategory')
                    ->from( $db->quoteName('#__agendadirigentes_cargos', 'a') )
                    ->join('INNER', $db->quoteName('#__categories', 'b')
                        . ' ON (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('a.catid') . ')'
                    );

                return $query;
        }
}