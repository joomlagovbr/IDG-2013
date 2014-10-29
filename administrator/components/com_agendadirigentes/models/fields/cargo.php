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
 
class JFormFieldCargo extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Cargo';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);

                $query->select(
                        $db->quoteName('car.id') . ', ' .
                        $db->quoteName('car.name')
                );
                $query->from(
                        $db->quoteName('#__agendadirigentes_cargos', 'car')
                );
                if ( $this->getAttribute('includecategory', false) == true )
                {
                    $query->select(
                            $db->quoteName('cat.title', 'category_name')
                    );
                    $query->join(
                            'INNER',
                            $db->quoteName('#__categories', 'cat')
                            . ' ON '.$db->quoteName('car.catid') . ' = ' . $db->quoteName('cat.id')
                    );
                    $query->order(
                            $db->quoteName('cat.title') . 'ASC, ' .
                            $db->quoteName('car.name') . ' ASC'
                    );
                }
                else
                {
                    $query->order(
                            $db->quoteName('car.name') . ' ASC'
                    );
                }

                $db->setQuery((string)$query);
                $cargos = $db->loadObjectList();
                $options = array();
                $options[] = JHtml::_('select.option', '', JText::_('COM_AGENDADIRIGENTES_SELECT_CARGO'));
                if ($cargos)
                {
                        foreach($cargos as $cargo) 
                        {
                                if ( $this->getAttribute('includecategory', false) )
                                {
                                        $options[] = JHtml::_('select.option', $cargo->id, $cargo->category_name . ' - ' . $cargo->name);                                        
                                }
                                else
                                {
                                        $options[] = JHtml::_('select.option', $cargo->id, $cargo->name);                                        
                                }
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}