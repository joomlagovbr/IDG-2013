<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Cargo Table class
 */
class AgendaDirigentesTableCargo extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__agendadirigentes_cargos', 'id', $db);
        }
 
        /**
         * Overridden bind function
         *
         * @param       array           named array
         * @return      null|string     null if operation was satisfactory, otherwise returns an error
         * @see JTable:bind
         * @since 1.5
         */
        public function bind($array, $ignore = '') 
        {
                // Bind the rules.
                if (isset($array['rules']) && is_array($array['rules']))
                {
                        $rules = new JAccessRules($array['rules']);
                        $this->setRules($rules);
                }
 
                return parent::bind($array, $ignore);
        }
 
        /**
         * Method to compute the default name of the asset.
         * The default name is in the form `table_name.id`
         * where id is the value of the primary key of the table.
         *
         * @return      string
         * @since       2.5
         */
        protected function _getAssetName()
        {
                $k = $this->_tbl_key;
                return 'com_agendadirigentes.cargo.'.(int) $this->$k;
        }
 
        /**
         * Method to return the title to use for the asset table.
         *
         * @return      string
         * @since       2.5
         */
        protected function _getAssetTitle()
        {
                return $this->name;
        }
 
        /**
         * Method to get the asset-parent-id of the item
         *
         * @return      int
         */
        protected function _getAssetParentId(JTable $table = NULL, $id = NULL)
        {
                // We will retrieve the parent-asset from the Asset-table
                $assetParent = JTable::getInstance('Asset');
                // Default: if no asset-parent can be found we take the global asset
                $assetParentId = $assetParent->getRootId();
 
                // Find the parent-asset
                if (($this->catid)&& !empty($this->catid))
                {
                        // The item has a category as asset-parent
                        $assetParent->loadByName('com_agendadirigentes.category.' . (int) $this->catid);
                }
                else
                {
                        // The item has the component as asset-parent
                        $assetParent->loadByName('com_agendadirigentes');
                }
 
                // Return the found asset-parent-id
                if ($assetParent->id)
                {
                        $assetParentId=$assetParent->id;
                }
                return $assetParentId;
        }

        function check()
        {
                if(empty($this->id))
                {
                        $query = $this->_db->getQuery(true);
                        $query->select('MAX(ordering)+1');
                        $query->from( $this->_db->quoteName('#__agendadirigentes_cargos') );
                        $query->where( $this->_db->quoteName('catid') . '=' .$this->catid );
                        $this->_db->setQuery((string)$query);

                        $this->ordering = intval( $this->_db->loadResult() );
                }
                else
                {
                        if($this->ordering == 0)
                        {
                               $query = $this->_db->getQuery(true);
                               $fields = array();
                               $fields[] =  $this->_db->quoteName('ordering') . ' = ' . $this->_db->quoteName('ordering') . ' + 1';
                                $conditions = array();
                               $conditions[] = $this->_db->quoteName('catid') . ' = ' . (int) $this->catid;
                               $conditions[] = $this->_db->quoteName('id') . ' <> ' . (int) $this->id;
                               
                               $query
                                        ->update($this->_db->quoteName('#__agendadirigentes_cargos'))
                                        ->set($fields)
                                        ->where($conditions);

                                $this->_db->setQuery((string) $query);
                                $this->_db->query();
                        }
                        else
                        {
                                @list($new_ordering, $id_item) = explode(':', $this->ordering);
                                if($id_item == $this->id || empty($id_item))
                                        $this->ordering = (int) $new_ordering;
                                else
                                        $this->ordering = intval($new_ordering) + 1;
                        }
                        
                }

                return parent::check();
        }
}