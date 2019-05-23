<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.controlleradmin');

class PhocaGalleryCpControllerPhocaGalleryImgs extends JControllerAdmin
{
	protected	$option 		= 'com_phocagallery';

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('disapprove',	'approve');

	}

	public function &getModel($name = 'PhocaGalleryImg', $prefix = 'PhocaGalleryCpModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	function approve()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid	= JFactory::getApplication()->input->get('cid', array(), '', 'array');
		$data	= array('approve' => 1, 'disapprove' => 0);
		$task 	= $this->getTask();
		$value	= \Joomla\Utilities\ArrayHelper::getValue($data, $task, 0, 'int');

		if (empty($cid)) {
			throw new Exception(JText::_($this->text_prefix.'_NO_ITEM_SELECTED'), 500);
		} else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			\Joomla\Utilities\ArrayHelper::toInteger($cid);

			// Publish the items.

			if (!$model->approve($cid, $value)) {
				throw new Exception($model->getError(), 500);
			} else {
				if ($value == 1) {
					$ntext = $this->text_prefix.'_N_ITEMS_APPROVED';
				} else if ($value == 0) {
					$ntext = $this->text_prefix.'_N_ITEMS_DISAPPROVED';
				}
				$this->setMessage(JText::plural($ntext, count($cid)));
			}
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));
	}

	public function saveOrderAjax() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');

		\Joomla\Utilities\ArrayHelper::toInteger($pks);
		\Joomla\Utilities\ArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return) { echo "1";}
		JFactory::getApplication()->close();
	}

}
