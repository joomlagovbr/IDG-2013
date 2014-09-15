<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Cargo Model
 */
class AgendaDirigentesModelCargo extends JModelAdmin
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
        public function getTable($type = 'Cargo', $prefix = 'AgendaDirigentesTable', $config = array()) 
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
                $form = $this->loadForm('com_agendadirigentes.cargo', 'cargo',
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
                $data = JFactory::getApplication()->getUserState('com_agendadirigentes.edit.cargo.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }

        /**
         * Method to check if it's OK to delete a message. Overwrites JModelAdmin::canDelete
         */
        protected function canDelete($item)
        {
            
            $model = $this->getInstance('dirigentes', 'AgendaDirigentesModel');            
            $app = JFactory::getApplication();
            $app->input->set('filter_cargo_id', $item->id);
            $nDirigentes = $model->getTotal();

            if($nDirigentes > 0)
            {
                $app->enqueueMessage('H&aacute; '.$nDirigentes.' dirigente(s) vinculado(s) a este cargo. Remova-o(s) ou troque seu(s) cargo(s) para apagar este item.');
                return false;
            }

            if( !empty( $item->catid ) ){
                $user = JFactory::getUser();
                return $user->authorise( "cargos.manage", "com_agendadirigentes.category." . $item->catid );
            }

            return false;
        }

        public function save($data)
        {
                $result = parent::save($data);
                if(!$result)
                        return false;

                // if (@isset($data['id'])!==false && )
                // {
                        // if(!$this->updateCompromissosDirigentes($data))
                                // return false;
                // }

                return     true;           
        }
}