<?php
/**
 * @package
 * @subpackage
 * @copyright
 * @license
 */

// no direct access
defined('_JEXEC') or die;
require __DIR__.'/../category/_helper.php';

class TemplateContentCategoriesHelper extends TemplateContentCategoryHelper {

	static function getAuthor( $view_object = '' )
	{
		if(!is_object($view_object))
			return '';


		if (!@is_array($view_object->get('items'))) {
			return '';
		}

		$items = $view_object->get('items');

		if (count($items)==0) {
			return '';
		}

		if(!is_object(@$items[0]->get('_parent')))
			return '';

		$parent_category = $items[0]->get('_parent');
		@$metadata = json_decode($parent_category->metadata);

		if(is_null($metadata))
			return '';

		if($metadata->author != '')
			return $metadata->author;

		return '';
	}

	static function getLastArticleModifiedDate( $view_object, $children = false )
	{
		if(!is_object($view_object))
			return '';


		if (!@is_array($view_object->get('items'))) {
			return '';
		}

		$items = $view_object->get('items');

		if (count($items)==0) {
			return '';
		}

		$category_ids = array();
		for ($i=0, $limit = count($items); $i < $limit; $i++) {
			$category_ids[] = $items[$i]->id;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('MAX(modified) AS max_modified, MAX(created) AS max_created');
		$query->from('#__content');
		$query->where( 'catid IN ('.implode(',', $category_ids).') AND state = 1' );
		$db->setQuery($query);
		$options = $db->loadObject();
		if( strtotime($options->max_modified) > strtotime($options->max_created))
			return  JHtml::_('date', $options->max_modified, JText::_('DATE_FORMAT_LC2'));
		else
			return  JHtml::_('date', $options->max_created, JText::_('DATE_FORMAT_LC2'));
	}

	static function displayMetakeyLinks( $metakey, $link = '' )
	{
		return parent::displayMetakeyLinks($metakey, $link);
	}
}
