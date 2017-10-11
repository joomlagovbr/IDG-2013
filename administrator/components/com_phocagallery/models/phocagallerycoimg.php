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
jimport('joomla.application.component.modeladmin');

class PhocaGalleryCpModelPhocaGalleryCoImg extends JModelAdmin
{
	protected	$option 		= 'com_phocagallery';
	protected $text_prefix 		= 'com_phocagallery';
	public 		$typeAlias 		= 'com_phocagallery.phocagallerycoimg';

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if ($record->imgid) {
			return $user->authorise('core.delete', 'com_phocagallery.phocagallerycoimg.'.(int) $record->imgid);
		} else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if ($record->imgid) {
			return $user->authorise('core.edit.state', 'com_phocagallery.phocagallerycoimg.'.(int) $record->imgid);
		} else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'PhocaGallerycommentImgs', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_phocagallery.phocagallerycoimg', 'phocagallerycoimg', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('phocagallerycoimg.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocagallery.edit.phocagallerycoimg.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function getReorderConditions($table = null)
	{
		
		$condition = array();
		$condition[] = 'image_id = '.(int) $table->image_id;
		return $condition;
	}
	
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}
		if(intval($table->date) == 0) {
			$table->date = JFactory::getDate()->toSql();
		}

		if (empty($table->id)) {
			// Set the values
			//$table->created	= $date->toSql();

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__phocagallery_img_comments WHERE imgid = '.(int) $table->imgid);
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