<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * Compromisso Table class
 */
class AgendaDirigentesTableCompromisso extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__agendadirigentes_compromissos', 'id', $db);
        }
        /**
         * Overloaded bind function
         *
         * @param       array           named array
         * @return      null|string     null is operation was satisfactory, otherwise returns an error
         * @see JTable:bind
         * @since 1.5
         */
        public function bind($array, $ignore = '') 
        {
                if (isset($array['params']) && is_array($array['params'])) 
                {
                        // Convert the params field to a string.
                        $parameter = new JRegistry;
                        $parameter->loadArray($array['params']);
                        $array['params'] = (string)$parameter;
                }

                return parent::bind($array, $ignore);
        }

        /**
         * Overloaded load function
         *
         * @param       int $pk primary key
         * @param       boolean $reset reset data
         * @return      boolean
         * @see JTable:load
         */
        public function load($pk = null, $reset = true) 
        {            
            if (parent::load($pk, $reset)) 
            {                
                // Convert the params field to a registry.
                $params = new JRegistry;                 
                $params->loadString($this->params, 'JSON');
                $this->params = $params;

                if($this->horario_fim == '00:00:00')
                    $this->horario_fim = '';

                $this->horario_inicio = substr($this->horario_inicio, 0, 5);
                $this->horario_fim = substr($this->horario_fim, 0, 5);

                if(!isset($this->catid))
                {
                    $query = $this->_db->getQuery(true);
                    $query->select(
                                $this->_db->quoteName('car.catid')
                            )
                            ->from(
                                $this->_db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
                            )
                            ->join(
                                'INNER',
                                $this->_db->quoteName('#__agendadirigentes_dirigentes', 'dir')
                                . ' ON ' . 
                                $this->_db->quoteName('dc.dirigente_id')
                                . ' = ' . 
                                $this->_db->quoteName('dir.id')
                            )
                            ->join(
                                'INNER',
                                $this->_db->quoteName('#__agendadirigentes_cargos', 'car')
                                . ' ON ' . 
                                $this->_db->quoteName('dir.cargo_id')
                                . ' = ' . 
                                $this->_db->quoteName('car.id')
                            )
                            ->where(
                                $this->_db->quoteName('dc.compromisso_id') . ' = ' . intval($pk)
                                . ' AND ' .
                                $this->_db->quoteName('dc.owner') . ' = 1'
                            );

                    $this->_db->setQuery((string)$query);
                    $this->catid = $this->_db->loadResult();
                }

                return true;
            }
            else
            {
                return false;
            }
        }

        function check()
        {
            //separacao dos dados para gravar em campo participantes_externos (que nao precisa de mapeamento no form)
            $app = JFactory::getApplication();
            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');
            @$dirigentes = $jform['dirigentes'];
            $JDate = new JDate('now', $app->getCfg('offset'));

            if (isset($dirigentes) && is_array($dirigentes)) {

                $arr_dirigentes = array();
                $arr_participantes_externos = array();
                $replace_dirigentes = array('#new#', ',', ';', '=', '"', '\'', '/', '\\');

                for ($i=0, $count_dirigentes = count($dirigentes); $i < $count_dirigentes; $i++) { 
                    if (is_numeric($dirigentes[$i]))
                    {
                        $arr_dirigentes[] = $dirigentes[$i];
                    }
                    else if(!empty($dirigentes[$i]))
                    {                        
                        $arr_participantes_externos[] = str_replace( $replace_dirigentes, '', $dirigentes[$i] );
                    }
                }
                if($count_dirigentes)
                {
                    $this->participantes_externos = implode('; ', $arr_participantes_externos);
                    $input->set('dirigentes', $arr_dirigentes);
                }
            }
            else
            {
                $this->participantes_externos = '';
                $input->set('dirigentes', array() );
            }

            //verificacoes de data e horario
            if (strpos($this->data_inicial, '/')!==false)
            {
                $this->data_inicial = explode("/", $this->data_inicial);
                $this->data_inicial = $this->data_inicial[2]."-".$this->data_inicial[1]."-".$this->data_inicial[0];
            }
            if (strpos($this->data_final, '/')!==false)
            {
            	$this->data_final = explode("/", $this->data_final);
            	$this->data_final = $this->data_final[2]."-".$this->data_final[1]."-".$this->data_final[0];                
            }

            if( $this->dia_todo == 1 )
            {
                $this->horario_inicio = '08:00:00';
                $this->horario_fim = '18:00:00';
            }
            else
            {
                $length_horario_inicio = strlen($this->horario_inicio);
                if($length_horario_inicio<5)
                    $this->horario_inicio = '08:00:00';
                else if($length_horario_inicio==5)
                     $this->horario_inicio .= ':00';

                $length_horario_fim = strlen($this->horario_fim);
                if($length_horario_fim<5 && $length_horario_fim > 0)
                    $this->horario_fim = '18:00:00';
                else if($length_horario_fim==5)
                     $this->horario_fim .= ':00';
            }
        	
            //verificacao de informacoes de controle
        	if(empty($this->created) || $this->created=="0000-00-00 00:00:00")
        		$this->created = $JDate->format('Y-m-d H:i:s', $app->getCfg('offset'));
        	
        	if(empty($this->created_by) || $this->created_by==0)
        		$this->created_by = JFactory::getUser()->get("id");
        	
       		$this->modified = $JDate->format('Y-m-d H:i:s', $app->getCfg('offset'));   	
       		$this->modified_by = JFactory::getUser()->get("id");
        	$this->version += 1;

            //se alguma vez publicado, entao informar em coluna de flag
            if($this->state == 1)
                $this->published_once = 1;

            //item retirado antes de salvamento. utilizado somente para fins de controle de permissoes ao carregar um item.
            unset($this->catid);

            //check padrao
        	return parent::check();
        }


        /**
         * Method to set the publishing state for a row or list of rows in the database
         * table.  The method respects checked out rows by other users and will attempt
         * to checkin rows that it can after adjustments are made.
         *
         * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
         * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published, 2=archived, -2=trashed]
         * @param   integer  $userId  The user id of the user performing the operation.
         *
         * @return  boolean  True on success.
         *
         * @since   1.6
         */
        public function publish($pks = null, $state = 1, $userId = 0)
        {
            $k = $this->_tbl_key;

            // Sanitize input.
            JArrayHelper::toInteger($pks);
            $userId = (int) $userId;
            $state = (int) $state;

            // If there are no primary keys set check to see if the instance key is set.
            if (empty($pks))
            {
                if ($this->$k)
                {
                    $pks = array($this->$k);
                }
                // Nothing to set publishing state on, return false.
                else
                {
                    $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                    return false;
                }
            }

            // Get an instance of the table
            $table = JTable::getInstance('Compromisso', 'AgendaDirigentesTable');

            // For all keys
            foreach ($pks as $pk)
            {
                // Load the banner
                if (!$table->load($pk))
                {
                    $this->setError($table->getError());
                }

                // Change the state
                $table->state = $state;

                // Check the row
                $table->check();

                // Store the row
                if (!$table->store())
                {
                    $this->setError($table->getError());
                }

            }

            return count($this->getErrors()) == 0;
        }
}