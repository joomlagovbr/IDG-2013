<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_Content extends \RegularLabs\Library\FieldGroup
{
	public $type = 'Content';

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__categories')
			->where('extension = ' . $this->db->quote('com_content'))
			->where('parent_id > 0')
			->where('published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		// assemble items to the array
		$options = [];
		if ($this->get('show_ignore'))
		{
			if (in_array('-1', $this->value))
			{
				$this->value = ['-1'];
			}
			$options[] = JHtml::_('select.option', '-1', '- ' . JText::_('RL_IGNORE') . ' -');
			$options[] = JHtml::_('select.option', '-', '&nbsp;', 'value', 'text', true);
		}

		$query->clear('select')
			->select('id, title as name, level, published, language')
			->order('lft');

		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		$options = array_merge($options, $this->getOptionsByList($list, ['language'], -1));

		return $options;
	}

	function getItems()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__content AS i')
			->where('i.access > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('i.id, i.title as name, i.language, c.title as cat, i.state as published')
			->join('LEFT', '#__categories AS c ON c.id = i.catid')
			->order('i.title, i.ordering, i.id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		$options = $this->getOptionsByList($list, ['language', 'cat', 'id']);

		if ($this->get('showselect'))
		{
			array_unshift($options, JHtml::_('select.option', '-', '&nbsp;', 'value', 'text', true));
			array_unshift($options, JHtml::_('select.option', '-', '- ' . JText::_('Select Item') . ' -'));
		}

		return $options;
	}
}
