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
 
class JFormFieldDirigenteordering extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Dirigenteordering';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                //$this->getAttribute('teste');
                $input = JFactory::getApplication()->input;
                $id = $input->get('id', 0);
                $options = array();

                if ($id==0) {
                        $options[] = JHtml::_('select.option', 'NULL', JText::_('COM_AGENDADIRIGENTES_SALVE_PRIMEIRO'));
                        return $options;
                }

                JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_agendadirigentes'.DS.'tables');
                $table = JTable::getInstance('dirigente', 'AgendaDirigentesTable');
                $table->load($id);
                
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('dir.ordering, dir.name')
                        ->from( $db->quoteName('#__agendadirigentes_dirigentes', 'dir') )
                        ->join('INNER', $db->quoteName('#__categories', 'cat')
                                . ' ON (' . $db->quoteName('dir.catid') . ' = ' . $db->quoteName('cat.id') . ')')
                        ->where('dir.catid = '.intval($table->catid))
                        ->where('dir.id <> '.intval($id));
                $query->order( 'dir.ordering ASC, dir.name ASC' );
                $db->setQuery((string)$query);
                $dirigentes = $db->loadObjectList();
                $options = array();
                
                if ($dirigentes)
                {
                        $options[] = JHtml::_('select.option', 0, ' - Primeiro - ');
                        foreach($dirigentes as $dirigente) 
                        {
                                $options[] = JHtml::_('select.option', $dirigente->ordering, $dirigente->name);
                        }
                }
                else
                {
                        $options[] = JHtml::_('select.option', 0, JText::_('COM_AGENDADIRIGENTES_ORDERING_SOMENTE_UM'));
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}