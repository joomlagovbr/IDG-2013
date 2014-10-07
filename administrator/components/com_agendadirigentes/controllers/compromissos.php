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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Compromissos Controller
 */
class AgendaDirigentesControllerCompromissos extends JControllerAdmin
{
		public function __construct($config = array())
		{
			parent::__construct($config);
			$this->registerTask('unfeatured',	'featured');
		}

        /**
         * Proxy for getModel.
         * @since       2.5
         */
        public function getModel($name = 'Compromisso', $prefix = 'AgendaDirigentesModel', $config = array()) 
        {
                $model = parent::getModel($name, $prefix, array('ignore_request' => true));
                return $model;
        }

		/**
		 * Method to toggle the featured setting of a list of compromissos.
		 *
		 * @return  void
		 * @since   1.6
		 */
		public function featured()
		{
			// Check for request forgeries
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

			$user   = JFactory::getUser();
			$isSuperUser = AgendaDirigentesHelper::isSuperUser();
			
			$ids    = $this->input->get('cid', array(), 'array');
			$values = array('featured' => 1, 'unfeatured' => 0);
			$task   = $this->getTask();
			$value  = JArrayHelper::getValue($values, $task, 0, 'int');

			$model = $this->getModel();
			$fields = array('car.catid', 'comp.id', 'comp.created_by');
			$dataFromIds = $model->getDataFromIds($ids, $fields);

			$params = JComponentHelper::getParams( $this->option );
			$allowFeature = $params->get('allowFeature', 'state');

			if (empty($ids))
			{
				JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
			}

			foreach ($ids as $i => $id)
			{
				$item = $dataFromIds[$id];

				if( !isset($item->catid) )
				{
					unset($ids[$i]);
					continue;
				}

				if($allowFeature == 'superuser')
				{
					if($isSuperUser)
						continue;
					else
					{
						$ids = NULL;
						JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
						break;
					}
				}

				if($allowFeature == 'state')
				{
					list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('compromissos', $item );
					if (! $canChange )
					{
						unset($ids[$i]);
						JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
					}					
				}
				else if($allowFeature == 'edit')
				{					 
					//informacao canManage eh sobreposta por permissoes de edicao de proprios itens e a linha abaixo nao
					if (! $user->authorise( "core.edit", "com_agendadirigentes.category." . $item->catid ) )
					{
						unset($ids[$i]);
						JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
					}	
				}
			}

			if (empty($ids))
			{
				JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
			}
			else
			{
				// Get the model.
				$model = $this->getModel();

				// Publish compromissos.
				if (!$model->featured($ids, $value))
				{
					JError::raiseWarning(500, $model->getError());
				}
			}

			$this->setRedirect('index.php?option=com_agendadirigentes&view=compromissos');
		}
}