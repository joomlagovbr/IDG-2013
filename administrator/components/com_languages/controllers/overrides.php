<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_languages
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Languages Overrides Controller.
 *
 * @since  2.5
 */
class LanguagesControllerOverrides extends JControllerAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var		string
	 * @since	2.5
	 */
	protected $text_prefix = 'COM_LANGUAGES_VIEW_OVERRIDES';

	/**
	 * Method for deleting one or more overrides.
	 *
	 * @return  void
	 *
	 * @since		2.5
	 */
	public function delete()
	{
		// Check for request forgeries.
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to dlete from the request.
		$cid = $this->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$this->setMessage(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), 'warning');
		}
		else
		{
			// Get the model.
			$model = $this->getModel('overrides');

			// Remove the items.
			if ($model->delete($cid))
			{
				$this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}

	/**
	 * Method to purge the overrider table.
	 *
	 * @return  void
	 *
	 * @since   3.4.2
	 */
	public function purge()
	{
		$model = $this->getModel('overrides');
		$model->purge();
		$this->setRedirect(JRoute::_('index.php?option=com_languages&view=overrides', false));
	}
}
