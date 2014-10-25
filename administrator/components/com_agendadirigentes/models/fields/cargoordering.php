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
 
class JFormFieldCargoordering extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Cargoordering';
 
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
                $table = JTable::getInstance('cargo', 'AgendaDirigentesTable');
                $table->load($id);
                
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('car.ordering, car.name, car.id')
                        ->from( $db->quoteName('#__agendadirigentes_cargos', 'car') )
                        ->join('INNER', $db->quoteName('#__categories', 'cat')
                                . ' ON (' . $db->quoteName('car.catid') . ' = ' . $db->quoteName('cat.id') . ')')
                        ->where('car.catid = '.intval($table->catid));

                $query->order( 'car.ordering ASC, car.name ASC' );

                $db->setQuery((string)$query);
                $cargos = $db->loadObjectList();
                $options = array();
                $this->setValue( $table->ordering. ':' . $table->id );
                if ($cargos)
                {

                        $options[] = JHtml::_('select.option', 0, JText::_('COM_AGENDADIRIGENTES_FIELD_CARGOORD_FIRST'));
                        foreach($cargos as $cargo) 
                        {
                            if($cargo->id == $table->id)
                                $options[] = JHtml::_('select.option', $cargo->ordering . ':' . $cargo->id, '-> '.$cargo->name.' <-');
                            else
                                $options[] = JHtml::_('select.option', $cargo->ordering . ':' . $cargo->id, $cargo->name);
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