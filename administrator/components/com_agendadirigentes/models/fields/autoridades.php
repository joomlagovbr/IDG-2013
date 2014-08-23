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
 * HelloWorld Form Field class for the HelloWorld component
 */
class JFormFieldAutoridades extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'Autoridades';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,name');
                $query->from('#__agendadirigentes_autoridades');
                $db->setQuery((string)$query);
                $autoridades = $db->loadObjectList();
                $options = array();
                if ($autoridades)
                {
                        foreach($autoridades as $autoridade) 
                        {
                                $options[] = JHtml::_('select.option', $autoridade->id, $autoridade->name);
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}