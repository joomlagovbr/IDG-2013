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
//caminho alterado devido à chamada de modulo
require_once( JPATH_ROOT .'/components/com_agendadirigentes/helpers/models.php' );

/**
 * This models supports retrieving lists of compromissos
 *
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 * @since       1.6
 */
class AgendaDirigentesModelParticipantes extends JModelList
{
	protected function populateState($ordering = NULL, $direction = NULL) 
    {
    	$app = JFactory::getApplication();    	

        $compromissos = $app->input->get('compromissos', array(), 'ARRAY');
        for ($i=0, $limit = count($compromissos); $i < $limit; $i++) { 
            $compromissos[$i] = intval($compromissos[$i]);
        }
        $this->setState('compromissos', $compromissos);

        $this->setState('filter_owner', $app->input->get('participantes_filter_owner', true) );
        $this->setState('exclude_dirigentes_list', $app->input->get('exclude_dirigentes_list', array(), 'ARRAY' ) );
    	
    	parent::populateState();
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return  string    An SQL query
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $compromissos = implode(',', $this->state->get('compromissos'));

        $query->select(
             $db->quoteName('dir.name', 'dirigente_name') . ', ' .
             $db->quoteName('car.name', 'cargo_name') . ', ' .
             $db->quoteName('dc.compromisso_id') . ', ' .
        	 $db->quoteName('dc.dirigente_id')
        )->from(
        	$db->quoteName('#__agendadirigentes_dirigentes', 'dir')
        )->join(
        	'INNER',
       		$db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
        	. ' ON (' . $db->quoteName('dir.id') . ' = ' . $db->quoteName('dc.dirigente_id') . ')' 
        )->join(
        	'INNER',
       		$db->quoteName('#__agendadirigentes_cargos', 'car')
        	. ' ON (' . $db->quoteName('car.id') . ' = ' . $db->quoteName('dir.cargo_id') . ')' 
        )->where(
            array(
        	$db->quoteName('dc.compromisso_id') . ' IN (' . $compromissos . ')',
        	$db->quoteName('dir.state') . ' > ' . 0
            )
        );

        if( $this->state->get('filter_owner', true) == true )
        {
            $query->where(
                $db->quoteName('dc.owner') . ' = 0'
            );
        }

        if(! is_array( $this->state->get('exclude_dirigentes_list') ) )
                $this->state->set('exclude_dirigentes_list', array( (int) $this->state->get('exclude_dirigentes_list') ) );
            
        if( count( $this->state->get('exclude_dirigentes_list') ) > 0 )
        {
            $query->where(
                $db->quoteName('dc.dirigente_id')
                . ' NOT IN (' .
                implode(', ', $this->state->get('exclude_dirigentes_list') )
                . ')'
            );
        }

        return $query;
    }
}
?>