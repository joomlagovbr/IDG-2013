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

use RegularLabs\Library\Form as RL_Form;

class JFormFieldRL_Zoo extends \RegularLabs\Library\FieldGroup
{
	public $type = 'Zoo';

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(['applications' => 'application', 'categories' => 'category', 'items' => 'item']))
		{
			return $error;
		}

		return $this->getSelectList();
	}

	function getCategories()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__zoo_category AS c')
			->where('c.published > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

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

		$query->clear()
			->select('a.id, a.name')
			->from('#__zoo_application AS a')
			->order('a.name, a.id');
		$this->db->setQuery($query);
		$apps = $this->db->loadObjectList();

		foreach ($apps as $i => $app)
		{
			$query->clear()
				->select('c.id, c.parent AS parent_id, c.name AS title, c.published')
				->from('#__zoo_category AS c')
				->where('c.application_id = ' . (int) $app->id)
				->where('c.published > -1')
				->order('c.ordering, c.name');
			$this->db->setQuery($query);
			$items = $this->db->loadObjectList();

			if ($i)
			{
				$options[] = JHtml::_('select.option', '-', '&nbsp;', 'value', 'text', true);
			}

			// establish the hierarchy of the menu
			// TODO: use node model
			$children = [];

			if ($items)
			{
				// first pass - collect children
				foreach ($items as $v)
				{
					$pt   = $v->parent_id;
					$list = @$children[$pt] ? $children[$pt] : [];
					array_push($list, $v);
					$children[$pt] = $list;
				}
			}

			// second pass - get an indent list of the items
			$list = JHtml::_('menu.treerecurse', 0, '', [], $children, 9999, 0, 0);

			// assemble items to the array
			$options[] = JHtml::_('select.option', 'app' . $app->id, '[' . $app->name . ']');
			foreach ($list as $item)
			{
				$item->treename = '  ' . str_replace('&#160;&#160;- ', '  ', $item->treename);
				$item->treename = RL_Form::prepareSelectItem($item->treename, $item->published);
				$option         = JHtml::_('select.option', $item->id, $item->treename);
				$option->level  = 1;
				$options[]      = $option;
			}
		}

		return $options;
	}

	function getItems()
	{
		$query = $this->db->getQuery(true)
			->select('COUNT(*)')
			->from('#__zoo_item AS i')
			->where('i.state > -1');
		$this->db->setQuery($query);
		$total = $this->db->loadResult();

		if ($total > $this->max_list_count)
		{
			return -1;
		}

		$query->clear('select')
			->select('i.id, i.name, a.name as cat, i.state as published')
			->join('LEFT', '#__zoo_application AS a ON a.id = i.application_id')
			->group('i.id')
			->order('i.name, i.priority, i.id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, ['cat', 'id']);
	}
}
