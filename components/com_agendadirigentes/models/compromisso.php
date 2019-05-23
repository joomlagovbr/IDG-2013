<?php
defined('_JEXEC') or die;
//caminho alterado devido à chamada de modulo
require_once( JPATH_ROOT .'/components/com_agendadirigentes/helpers/models.php' );

class AgendaDirigentesModelCompromisso extends JModelItem
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
		$this->setState('compromisso.id', $id);

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
				$id = (int) $this->getState('compromisso.id');
			}

			$db = $this->_db;
			$query = $db->getQuery(true);
			$query->select(
						$db->quoteName('id') . ', '.
						$db->quoteName('title') . ', '.
						$db->quoteName('data_inicial') . ', '.
						$db->quoteName('horario_inicio') . ', '.
						$db->quoteName('data_final') . ', '.
						$db->quoteName('horario_fim') . ', '.
						$db->quoteName('local') . ', '.
						$db->quoteName('description') . ', '.
						$db->quoteName('params') . ', '.
						$db->quoteName('created') . ', '.
						$db->quoteName('state') . ', '.
						$db->quoteName('modified') 
					)
					->from(
						$db->quoteName('#__agendadirigentes_compromissos')
					)					
					->where(
						$db->quoteName('id') . ' = ' . $id
					);

			$query->where(
				$db->quoteName('state') . ' IN (1,2)'
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

}