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
 * This models supports retrieving lists of dirigentes .
 *
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 * @since       1.6
 */
class AgendaDirigentesModelAutoridades extends JModelList
{
    protected function populateState($ordering = NULL, $direction = NULL) 
    {
            $app = JFactory::getApplication();
            $input = $app->input;

            AgendadirigentesModels::setParamBeforeSetState( 'dia', 'DataBanco', $this->getDate() );
            $params = $app->getParams();
            $params->set('introtext', $app->input->get('introtext', '', 'HTML'));


            $this->setState('params', $params);
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
        // Create a new query object.
        $db = $this->getDBO();
        $query = $db->getQuery(true);
        $query->select(
                $db->quoteName('car.featured', 'cargo_featured') . ', ' .
                $db->quoteName('car.id', 'cargo_id') . ', ' .
                $db->quoteName('car.name', 'cargo_name') . ', ' .
                $db->quoteName('car.catid') . ', ' .
                $db->quoteName('cat.level') . ', ' .
                $db->quoteName('cat.path') . ', ' .
                $db->quoteName('cat.title', 'cat_title') . ', ' .
                $db->quoteName('cat.alias') . ', ' .
                $db->quoteName('dir.id', 'dir_id') . ', ' .
                $db->quoteName('dir.name', 'dir_name') . ', ' .
                $db->quoteName('dir.interino') . ', ' .
                $db->quoteName('dir.em_atividade')
                )->from(
                $db->quoteName('#__agendadirigentes_cargos', 'car')
                )->join(
                'INNER',
                $db->quoteName('#__categories', 'cat')
                . ' ON (' . $db->quoteName('car.catid') . ' = ' . $db->quoteName('cat.id') . ')' 
                )->join(
                'INNER',
                $db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                . ' ON (' . $db->quoteName('dir.cargo_id') . ' = ' . $db->quoteName('car.id') . ')'                         
                )->where(
                $db->quoteName('car.published') . ' = 1'
                )->where(
                $db->quoteName('cat.published') . ' = 1'
                )->where(
                $db->quoteName('dir.state') . ' IN (1,2)'
                )->order(
                $db->quoteName('car.featured') . ' DESC, ' .
                $db->quoteName('cat.lft') . ', ' .
                $db->quoteName('car.ordering') . ', ' .
                $db->quoteName('dir.name')
                );

        return $query;

    }

    public static function getDate()
    {
        $app = JFactory::getApplication();
        $date = new JDate('now', $app->getCfg('offset'));
        return $date->format('Y-m-d', $app->getCfg('offset'));  
    }
}
