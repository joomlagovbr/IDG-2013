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
    protected function canDelete( $item )
    {
        $app = JFactory::getApplication();
        $model = $this->getInstance('compromissos', 'AgendaDirigentesModel');            

        $params = $model->getState('params'); 
        $params->set('restricted_list_compromissos', 0);
        $model->setState('params', $params);
        $model->setState('filter.dirigente_id', $item->id);
        $model->setState('filter.state', '*');
        $model->setState('list.status_dono_compromisso', 1);
        $nCompromissos = $model->getTotal();

        if($nCompromissos > 0)
        {
            $app->enqueueMessage( sprintf(JText::_('COM_AGENDADIRIGENTES_MODELS_DIRIGENTE_CANT_DELETE'), $nCompromissos) );
            return false;
        }

        if( !empty( $item->catid ) )
        {
            return AgendaDirigentesHelper::getGranularPermissions( 'dirigentes', $item->catid, 'delete' );
        }

        return false;
    }

    protected function canEditState($item)
    {
        list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('dirigentes', $item, 'manage' );
        
        if($canChange)
            return true;
        
        return false;
    }

    public function delete(&$pks)
    {
        $pks = (array) $pks;

        if( parent::delete($pks) )
        {
            //apaga compromissos dos quais o dirigente era somente participante ou convidado
            $query = $this->_db->getQuery(true);
            $query->delete(
                    $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos')
                )
                ->where(
                    $this->_db->quoteName('dirigente_id')
                    . ' IN ( ' .
                    implode(', ', $pks)
                    . ' ) AND ' .    
                    $this->_db->quoteName('owner') . ' = 0 '    
                );

            $this->_db->setQuery( (string) $query );
            return $this->_db->query();

        }

        return false;
    }

    public function getItem( $pk = NULL )
    {
        $item = parent::getItem( $pk );

        if (is_null($item) || !is_object($item)) {
            return 0;
        }

        if(empty($item->cargo_id)) {
            return 0;
        }

        return $item;
    }
}