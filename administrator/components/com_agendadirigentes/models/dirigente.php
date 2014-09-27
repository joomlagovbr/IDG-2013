<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Dirigente Model
 */
class AgendaDirigentesModelDirigente extends JModelAdmin
{
        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         * @since       2.5
         */
        public function getTable($type = 'Dirigente', $prefix = 'AgendaDirigentesTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
        /**
         * Method to get the record form.
         *
         * @param       array   $data           Data for the form.
         * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
         * @return      mixed   A JForm object on success, false on failure
         * @since       2.5
         */
        public function getForm($data = array(), $loadData = true) 
        {
                // Get the form.
                $form = $this->loadForm('com_agendadirigentes.dirigente', 'dirigente',
                                        array('control' => 'jform', 'load_data' => $loadData));
                if (empty($form)) 
                {
                        return false;
                }
                return $form;
        }
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         * @since       2.5
         */
        protected function loadFormData() 
        {
                // Check the session for previously entered form data.
                $data = JFactory::getApplication()->getUserState('com_agendadirigentes.edit.dirigente.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }

        /**
         * Method to check if it's OK to delete a message. Overwrites JModelAdmin::canDelete
         */
        protected function canDelete($dirigente)
        {
            if( !empty( $dirigente->id ) ){
                $user = JFactory::getUser();
                return $user->authorise( "dirigentes.delete", "com_agendadirigentes.cargo." . $dirigente->cargo_id );
            }
        }

        public function getItem()
        {
            $item = parent::getItem();

            if (is_null($item) || !is_object($item)) {
                return 0;
            }

            if(empty($item->cargo_id)) {
                return 0;
            }

            $db = $this->_db;
            $query = $db->getQuery(true);

            $query->select(
                    $db->quoteName('cat.id')
                )->from(
                    $db->quoteName('#__categories', 'cat')
                )->join(
                    'INNER',
                    $db->quoteName('#__agendadirigentes_cargos', 'car')
                    . ' ON ' . $db->quoteName('car.catid') . ' = ' . $db->quoteName('cat.id')
                )->where(
                    $db->quoteName('car.id') . ' = ' . (int) $item->cargo_id
                );

            $db->setQuery((string)$query);
            $item->catid = $db->loadResult();

            if(empty($item->sexo))
                $item->sexo = 'M';

            return $item;
        }
}