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
            $app = JFactory::getApplication();
            $model = $this->getInstance('dirigentes', 'AgendaDirigentesModel');
            
            $params = $model->getState('params'); 
            $params->set('restricted_list_dirigentes', 0);
            $model->setState('params', $params);
            $model->setState('filter.cargo_id', $item->id);
            $model->setState('filter.state', '*');
            $nDirigentes = $model->getTotal();

            if($nDirigentes > 0)
            {
                $app->enqueueMessage( sprintf(JText::_('COM_AGENDADIRIGENTES_MODELS_CARGO_CANT_DELETE'), $nDirigentes) );
                return false;
            }

            if( !empty( $item->catid ) )
            {
                return AgendaDirigentesHelper::getGranularPermissions( 'cargos', $item->catid, 'delete' );
            }

            return false;
        }

        protected function canEditState($item)
        {
            list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('cargos', $item, 'manage' );

            if($canChange)
                return true;
            
            return false;
        }

       /**
         * Method to toggle the featured setting of articles.
         *
         * @param   array    The ids of the items to toggle.
         * @param   integer  The value to toggle to.
         *
         * @return  boolean  True on success.
         */
        public function featured($pks, $value = 0)
        {
            // Sanitize the ids.
            $pks = (array) $pks;
            JArrayHelper::toInteger($pks);

            if (empty($pks))
            {
                $this->setError(JText::_('COM_AGENDADIRIGENTES_NENHUM_ITEM_SELECIONADO'));
                return false;
            }

            try
            {
                $db = $this->getDbo();
                $query = $db->getQuery(true)
                            ->update($db->quoteName('#__agendadirigentes_cargos'))
                            ->set('featured = ' . (int) $value)
                            ->where('id IN (' . implode(',', $pks) . ')');
                $db->setQuery($query);
                $db->execute();

            }
            catch (Exception $e)
            {
                $this->setError($e->getMessage());
                return false;
            }

            $this->cleanCache();

            return true;
        }
}