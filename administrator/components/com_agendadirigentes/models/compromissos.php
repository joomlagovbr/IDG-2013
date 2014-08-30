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
 * CompromissoList Model
 */
class AgendaDirigentesModelCompromissos extends JModelList
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
                // Select some fields from the compromissos table
                $query
                    ->select('DISTINCT(comp.id), comp.catid, comp.checked_out, comp.title, comp.data_inicial')
                    ->select(' comp.data_final, comp.horario_inicio, comp.horario_fim, comp.description, comp.local')
                    ->select(' comp.params, comp.ordering')
                    ->select(' comp.state, comp.dia_todo')
                    ->select(' dc.sobreposto, dc.owner')
                    ->select(' dir.name AS nameOwner, dir.id AS idOwner')
                    ->select(' car.name AS cargoOwner')
                    ->select(' cat.title AS titleCategory')
                    ->from( $db->quoteName('#__agendadirigentes_compromissos', 'comp') )
                    ->join( 'LEFT',
                        $db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                        .' ON (' . $db->quoteName('comp.id') . ' = ' . $db->quoteName('dc.compromisso_id') . ')' )
                    ->join( 'LEFT',
                        $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                        .' ON (' . $db->quoteName('dc.owner') . ' = ' . $db->quoteName('dir.id') . ')' )
                    ->join( 'LEFT',
                        $db->quoteName('#__agendadirigentes_cargos', 'car')
                        .' ON (' . $db->quoteName('dir.cargo_id') . ' = ' . $db->quoteName('car.id') . ')' )
                    ->join( 'LEFT',
                        $db->quoteName('#__categories', 'cat')
                        .' ON (' . $db->quoteName('comp.catid') . ' = ' . $db->quoteName('cat.id') . ')' );

                return $query;
        }
}

/*
SELECT

FROM `x3dts_agendadirigentes_compromissos` AS comp
LEFT JOIN `x3dts_agendadirigentes_dirigentes_compromissos` AS dc ON comp.id = dc.compromisso_id
LEFT JOIN `x3dts_agendadirigentes_dirigentes` AS dir ON dc.owner = dir.id
LEFT JOIN `x3dts_agendadirigentes_cargos` AS car ON dir.cargo_id = car.id

ORDER BY comp.id desc

//*/