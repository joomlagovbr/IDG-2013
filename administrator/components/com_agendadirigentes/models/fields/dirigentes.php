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
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * Dirigentes Form Field class for the AgendaDirigentes component
 */
class JFormFieldDirigentes extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Dirigentes';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('dir.id, dir.name, cat.title AS category_title')
                        ->from( $db->quoteName('#__agendadirigentes_dirigentes', 'dir') )
                        ->join('INNER', $db->quoteName('#__categories', 'cat')
                                . ' ON (' . $db->quoteName('dir.catid') . ' = ' . $db->quoteName('cat.id') . ')');
                $db->setQuery((string)$query);
                $dirigentes = $db->loadObjectList();
                $options = array();
                if ($dirigentes)
                {
                        foreach($dirigentes as $dirigente) 
                        {
                                $options[] = JHtml::_('select.option', $dirigente->id,
                                        '('.$dirigente->category_title.') '.$dirigente->name);
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}