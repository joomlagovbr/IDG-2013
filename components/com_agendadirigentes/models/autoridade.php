<?php
defined('_JEXEC') or die;
//caminho alterado devido à chamada de modulo
require_once( JPATH_ROOT .'/components/com_agendadirigentes/helpers/models.php' );

class AgendaDirigentesModelAutoridade extends JModelItem
{
	
	/**
	 * Model context string.
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $_context = 'com_agendadirigentes.autoridade';


	protected function populateState()
	{
		$app = JFactory::getApplication();

		$id	= $app->input->getInt('id');
		$this->setState('autoridade.id', $id);

		AgendadirigentesModels::setParamBeforeSetState( 'dia', 'DataBanco', $this->getDate() );
		$params	= $app->getParams();

		// Load the parameters.
		$this->setState('params', $params);
	}

	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('autoridade.id');
			}

			$db = $this->_db;
			$query = $db->getQuery(true);
			$dia = $this->state->get('params')->get('dia');

			$query->select(
						$db->quoteName('dir.id') . ', '.
						$db->quoteName('dir.name', 'dir_name') . ', '.
						$db->quoteName('dir.interino') . ', '.
						$db->quoteName('dir.state') . ', '.
						$db->quoteName('dir.sexo') . ', '.
						$db->quoteName('car.name', 'car_name') . ', '.
						$db->quoteName('car.name_f', 'car_name_f') . ', '.
						$db->quoteName('alt.qtd_alteracoes', 'qtd_alteracoes_agenda')
					)
					->from(
						$db->quoteName('#__agendadirigentes_dirigentes', 'dir')
					)
					->join('INNER',
						$db->quoteName('#__agendadirigentes_cargos', 'car')
						. ' ON dir.cargo_id = car.id'
					)
					->join('LEFT',
						$db->quoteName('#__agendadirigentes_agendaalterada', 'alt')
						. ' ON ( dir.id = alt.id_dirigente '
						. ' AND alt.data = '. $db->Quote($dia) .' )'
					)
					->where(
						$db->quoteName('dir.id') . ' = ' . $id
					);

			$query->where(
				$db->quoteName('dir.state') . ' IN (1,2)'
			);

			$db->setQuery( (string)$query );
			$this->_item = $db->loadObject();

			if ($error = $db->getError())
			{
				$this->setError($error);
			}			
			
		}
		return $this->_item;
	}

	public function getCompromissos()
	{
		$compromissosModel = $this->getInstance('compromissos', 'AgendaDirigentesModel');
		$options = array( 'exclude_dirigente_id' => $this->getState('autoridade.id') );
		return $compromissosModel->getItems( $options );
	}

	public static function getDate()
	{
		$app = JFactory::getApplication();
		$date = new JDate('now', $app->getCfg('offset'));
		return $date->format('Y-m-d', $app->getCfg('offset'));	
	}

}