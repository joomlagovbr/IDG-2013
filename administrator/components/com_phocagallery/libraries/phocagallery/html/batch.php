<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;
defined('JPATH_PLATFORM') or die;

abstract class PhocaGalleryBatch
{
	
	public static function item($published, $category = 0)
	{
		// Create the copy/move options.
		$options = array(
			JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
		);
		
		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
		// TO DO. ' WHERE a.published = '.(int)$published
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		$tree = array();
		$text = '';
		$catId= -1;
		$tree = PhocaGalleryCategory::CategoryTreeOption($data, $tree, 0, $text, $catId);
		
		if ($category == 1) {
			array_unshift($tree, JHTML::_('select.option', 0, JText::_('JLIB_HTML_ADD_TO_ROOT'), 'value', 'text'));
		}

		
		// Create the batch selector to change select the category by which to move or copy.
		$lines = array(
			'<label id="batch-choose-action-lbl" for="batch-choose-action">',
			JText::_('JLIB_HTML_BATCH_MENU_LABEL'),
			'</label>',
			'<fieldset id="batch-choose-action" class="combo">',
				'<select name="batch[category_id]" class="inputbox" id="batch-category-id">',
					'<option value="">'.JText::_('JSELECT').'</option>',
					/*JHtml::_('select.options',	JHtml::_('category.options', $extension, array('published' => (int) $published))),*/
					JHTML::_('select.options',  $tree ),
				'</select>',
				JHTML::_( 'select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'),
			'</fieldset>'
		);

		return implode("\n", $lines);
	}
}
