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
//caminho alterado devido à chamada de modulo
require_once( JPATH_ROOT .'/components/com_agendadirigentes/helpers/models.php' );

/**
 * This models supports retrieving lists of compromissos
 *
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 * @since       1.6
 */
class AgendaDirigentesModelCompromissos extends JModelList
{
	protected function populateState($ordering = NULL, $direction = NULL) 
    {
    	$app = JFactory::getApplication();
    	AgendadirigentesModels::setParamBeforeSetState( 'dia', 'DataBanco', $this->getDate() );

		$id	= $app->input->getInt('id');
		$this->setState('autoridade.id', $id);

        $this->setState('participantes.load', true);
        $this->setState('filter.featured', '');

    	$params = $app->getParams();
        $this->setState('params', $params);
    	parent::populateState();
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return  string    An SQL query
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $params = $this->getState('params');

        if(!is_object($params))
        	return false;

        $dia = $db->Quote( $params->get('dia') );
        $nullDate = $db->Quote( '0000-00-00' );
        $autoridade_id = (int) $this->state->get('autoridade.id');
                
        $query->select(
        	 $db->quoteName('comp.id') . ', ' .
        	 $db->quoteName('comp.title') . ', ' .
        	 $db->quoteName('comp.data_inicial') . ', ' .
        	 $db->quoteName('comp.data_final') . ', ' .
        	 $db->quoteName('comp.horario_inicio') . ', ' .
        	 $db->quoteName('comp.horario_fim') . ', ' .
        	 $db->quoteName('comp.dia_todo') . ', ' .
        	 $db->quoteName('comp.local') . ', ' .
        	 $db->quoteName('comp.description') . ', ' .
        	 $db->quoteName('comp.participantes_externos') . ', ' .
        	 $db->quoteName('comp.params')
        )->from(
        	$db->quoteName('#__agendadirigentes_compromissos', 'comp')
        )->join(
        	'INNER',
       		$db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
        	. ' ON (' . $db->quoteName('comp.id') . ' = ' . $db->quoteName('dc.compromisso_id') . ')' 
        )->join(
        	'INNER',
       		$db->quoteName('#__agendadirigentes_dirigentes', 'dir')
        	. ' ON (' . $db->quoteName('dc.dirigente_id') . ' = ' . $db->quoteName('dir.id') . ')' 
        )->where(
        	array(
            $db->quoteName('dir.id') . ' = ' . $autoridade_id,
        	$db->quoteName('comp.data_inicial') . ' <= ' . $dia,
        	'('.
        		$db->quoteName('comp.data_final') . ' >= ' . $dia . ' OR ' .
        		$db->quoteName('comp.data_final') . ' = ' . $nullDate
        	.')',
        	$db->quoteName('comp.state') . ' > ' . 0,
        	$db->quoteName('dc.sobreposto') . ' = ' . 0
            )
        )->order(
        	 $db->quoteName('comp.horario_inicio') . ' ASC'
        );

        $featured = $this->getState('filter.featured', '');
        if( $featured != '' )
        {
            $query->where('comp.featured = ' . (int) $featured);
        }

        return $query;
    }

    public function getItems( $options = array() )
    {
    	$input = JFactory::getApplication()->input;
		$compromissos = parent::getItems();

        if ($this->state->get('participantes.load')):
            //formatando ids de compromissos
            //obtendo participantes
            $compromissos_id_list = array();        
            for ($i=0, $limit = count($compromissos); $i < $limit; $i++) { 
                $compromissos_id_list[] = $compromissos[$i]->id;
            }
            $input->set('compromissos', $compromissos_id_list);

            if (count($compromissos_id_list))
            {
                $participantesModel = $this->getInstance('participantes', 'AgendaDirigentesModel');
                if(array_key_exists('exclude_dirigente_id', $options))
                {                
                    $input->set('participantes_filter_owner', false);
                    $exclude_dirigentes_list = explode(',', $options['exclude_dirigente_id']); 
                    $input->set('exclude_dirigentes_list', $exclude_dirigentes_list);
                }
                $participantes = $participantesModel->getItems();
            }
            else
                $participantes = array();

    		//formatando dirigentes 
    		$arr_participantes = array();
    		for ($i=0, $limit = count($participantes); $i < $limit; $i++) {             
    			if(@isset($arr_participantes[$participantes[$i]->compromisso_id])===false)
    			{
    				$arr_participantes[$participantes[$i]->compromisso_id] = array();
    			}
    			$count = count( $arr_participantes[$participantes[$i]->compromisso_id] );
    			$arr =& $arr_participantes[$participantes[$i]->compromisso_id][$count];
    			$arr = new StdClass();
    			$arr->dirigente_name = $participantes[$i]->dirigente_name;
    			$arr->cargo_name = $participantes[$i]->cargo_name;
    		}
    		
    		//merging de participantes externos e dirigentes
    		for ($i=0, $limit = count($compromissos); $i < $limit; $i++) { 

    			//formatando participantes
    			if(!empty($compromissos[$i]->participantes_externos))
    				$arr_participantes_externos = explode(';', $compromissos[$i]->participantes_externos);
    			else
    				$arr_participantes_externos = array();

    			for ($j=0,$jlimit = count($arr_participantes_externos); $j < $jlimit; $j++) { 

    				$participante_nome = trim($arr_participantes_externos[$j]);
    				$arr_participantes_externos[$j] = new StdClass();
    				$arr_participantes_externos[$j]->dirigente_name = $participante_nome;
    				$arr_participantes_externos[$j]->cargo_name = NULL;

    			}

                if(isset($arr_participantes[$compromissos[$i]->id]))
                {
        			$arr_participantes[$compromissos[$i]->id] = array_merge( 
        															$arr_participantes[$compromissos[$i]->id],
        															$arr_participantes_externos
        														);                    
                }
                else if(count($arr_participantes_externos))
                {
                    $arr_participantes[$compromissos[$i]->id] = $arr_participantes_externos;
                }
                else
                {
                    $arr_participantes[$compromissos[$i]->id] = array();                    
                }

    			$compromissos[$i]->participantes = $arr_participantes[$compromissos[$i]->id];			

    			//formatando horas
    			$compromissos[$i]->horario_inicio = substr($compromissos[$i]->horario_inicio, 0, 5);
    			$compromissos[$i]->horario_fim = substr($compromissos[$i]->horario_fim, 0, 5);
    		}
        else:

            //formatando horas
            for ($i=0, $limit = count($compromissos); $i < $limit; $i++) {
                $compromissos[$i]->horario_inicio = substr($compromissos[$i]->horario_inicio, 0, 5);
                $compromissos[$i]->horario_fim = substr($compromissos[$i]->horario_fim, 0, 5);
            }

        endif;

		return $compromissos;
    }

    public static function getDate()
    {
        $app = JFactory::getApplication();
        $date = new JDate('now', $app->getCfg('offset'));
        return $date->format('Y-m-d', $app->getCfg('offset'));  
    }
}
?>