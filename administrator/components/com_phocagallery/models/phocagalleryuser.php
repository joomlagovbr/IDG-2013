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
defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class PhocaGalleryCpModelPhocaGalleryUser extends JModelAdmin
{

	protected	$option 		= 'com_phocagallery';
	protected 	$text_prefix	= 'com_phocagallery';
	public 		$typeAlias 		= 'com_phocagallery.phocagalleryuser';

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		/*if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_phocagallery.phocagalleryuser');
		} else {*/
			return parent::canDelete($record);
		//}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		/*if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_phocagallery.phocagalleryuser');
		} else {*/
			return parent::canEditState($record);
		//}
	}

	public function getTable($type = 'PhocaGalleryUser', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) {

		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocagallery.phocagalleryuser', 'phocagalleryuser', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocagallery.edit.phocagalleryuser.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/*protected function getReorderConditions($table = null)
	{
		$condition = array();
		//$condition[] = 'catid = '. (int) $table->catid;
		//$condition[] = 'state >= 0';
		return $condition;
	}*/


	function approve(&$pks, $value = 1)
	{
		// Initialise variables.
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		$table		= $this->getTable('phocagalleryuser');
		$pks		= (array) $pks;

		// Include the content plugins for the change of state event.
		JPluginHelper::importPlugin('content');

		// Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$this->canEditState($table)) {
					// Prune items that you can't change.
					unset($pks[$i]);
					throw new Exception(JText::_('JLIB_APPLICATION_ERROR_EDIT_STATE_NOT_PERMITTED'), 403);
				}
			}
		}

		// Attempt to change the state of the records.
		if (!$table->approve($pks, $value, $user->get('id'))) {
			$this->setError($table->getError());
			return false;
		}

		$context = $this->option.'.'.$this->name;

		// Trigger the onContentChangeState event.
		/*$result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));
		if (in_array(false, $result, true)) {
			$this->setError($table->getError());
			return false;
		}*/

		return true;
	}






	/*
	var $_id = null;

	function __construct() {
		parent::__construct();
		$array = JFactory::getApplication()->input->get('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id) {
		$this->_id	= $id;
	}
*/
/*TO DO - add rules like in approve */
	function delete($cid = array()) {

		if (count( $cid )) {
			\Joomla\Utilities\ArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			//Delete it from DB
			$query = 'UPDATE #__phocagallery_user'
				. ' SET avatar = ""'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}


	/*TO DO - add rules like in approve */
	function approveall() {

		//$user 	=& JFactory::getUser();
		$query = 'UPDATE #__phocagallery'
			. ' SET approved = 1';
			//. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )';
		$this->_db->setQuery( $query );

		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$query = 'UPDATE #__phocagallery_categories'
			. ' SET approved = 1';
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= \JApplicationHelper::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = \JApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocagallery_user');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toSql();
			//$table->modified_by	= $user->get('id');
		}
	}

}
?>
