<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Compromisso Model
 */
class AgendaDirigentesModelCompromisso extends JModelAdmin
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
        public function getTable($type = 'Compromisso', $prefix = 'AgendaDirigentesTable', $config = array()) 
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
                $form = $this->loadForm('com_agendadirigentes.compromisso', 'compromisso',
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
                $data = JFactory::getApplication()->getUserState('com_agendadirigentes.edit.compromisso.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                        if(@isset($data->id))
                        {                            
                            $data->owner = $this->getOwner($data);
                            $data->dirigentes = $this->getParticipantes($data);
                        }
                        else
                        {
                            $data->owner = NULL;
                            $data->dirigentes = array();
                        }
                }
                return $data;
        }

        protected function getOwner( $data = NULL )
        {
                if (is_null($data)) {
                        $data = $this->getItem();
                }
                $query = $this->_db->getQuery(true);
                $query->select( $this->_db->quoteName('dirigente_id') );
                $query->from( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
                $query->where( $this->_db->quoteName('compromisso_id') . ' = ' . $data->id );
                $query->where( $this->_db->quoteName('owner') . ' = 1' );
                $this->_db->setQuery($query);
                return $this->_db->loadResult();
        }

        protected function getParticipantes( $data = NULL )
        {
                if (is_null($data)) {
                        $data = $this->getItem();
                }
                $query = $this->_db->getQuery(true);
                $query->select( $this->_db->quoteName('dirigente_id') );
                $query->from( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
                $query->where( $this->_db->quoteName('compromisso_id') . ' = ' . $data->id );
                $query->where( $this->_db->quoteName('owner') . ' = 0' );
                $this->_db->setQuery($query);
                
                $result = $this->_db->loadRowList();
                $array = array();
                foreach ($result as $k => $v) {
                        $array[] = $v[0];
                }
                return $array;
        }

        public function save($data)
        {


                $result = parent::save($data);
                if(!$result)
                        return false;

                // if (@isset($data['id'])!==false && )
                // {
                        if(!$this->updateCompromissosDirigentes($data))
                                return false;
                // }

                return true;                
        }

        protected function updateCompromissosDirigentes($data)
        {
                if (@isset($data['id'])===false) {
                        return false;
                }

                if(!$this->clearCompromissosDirigentes($data))
                        return false;

                if(!$this->insertCompromissosDirigentes($data))
                        return false;

                return true;
        }

        protected function clearCompromissosDirigentes($data)
        {
                $query = $this->_db->getQuery(true);
                $query->delete( $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos') );
                $query->where( $this->_db->quoteName('compromisso_id') .' = '.intval($data['id']) );
                $this->_db->setQuery($query);
                return $this->_db->query();
        }

        protected function insertCompromissosDirigentes($data)
        {
                $owner = @$data['owner'];
                if (is_array($owner)) {
                        $owner = (int) $owner[0];
                }
                $owner = (int) $owner;
                if($owner==0)
                        return false;

                $items = array();
                $items[] = array(
                                'dirigente_id' => $owner,
                                'compromisso_id' => intval($data['id']),
                                'owner' => 1,
                                'sobreposto' => 0
                        );

                $dirigentes = @$data['dirigentes'];
                if (is_array($dirigentes)) {
                        for ($i=0, $limit = count($dirigentes); $i < $limit; $i++) { 
                            if (is_numeric($dirigentes[$i])) { //grava somente os itens que possuem ID, ou seja, dirigentes cadastrados
                                $items[] = array(
                                        'dirigente_id' => $dirigentes[$i],
                                        'compromisso_id' => $data['id'],
                                        'owner' => 0,
                                        'sobreposto' => 0
                                );
                            }
                        }
                }

                for ($i=0, $limit = count($items); $i < $limit; $i++) { 
                        $columns = array_keys($items[$i]);
                        $values = array_values($items[$i]);
                        $query = $this->_db->getQuery(true);
                        $query
                            ->insert($this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos'))
                            ->columns($this->_db->quoteName($columns))
                            ->values(implode(',', $values));
                        $this->_db->setQuery($query);
                        if (!$this->_db->query()) {
                            return false;
                        }
                }

                return true;
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
                            ->update($db->quoteName('#__agendadirigentes_compromissos'))
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

        // public function getCategoriesFromIds( $pks = array() )
        // {
        //     foreach ($pks as &$pk) {
        //         $pk = intval($pk);
        //     }
        //     $items = implode(', ', $pks);
            
        //     $db = JFactory::getDBO();
        //     $query = $db->getQuery(true);
        //     $query->select('id, catid')
        //           ->from('#__agendadirigentes_compromissos')
        //           ->where('id IN ('.$items.')');
            
        //     $db->setQuery((string)$query);
        //     return $db->loadObjectList('id');
        // }
}