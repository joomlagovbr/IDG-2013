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

use Joomla\Registry\Registry;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\RegEx as RL_RegEx;

class JFormFieldRL_MenuItems extends \RegularLabs\Library\Field
{
	public $type = 'MenuItems';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$size     = (int) $this->get('size');
		$multiple = $this->get('multiple', 0);

		return $this->selectListAjax(
			$this->type, $this->name, $this->value, $this->id,
			compact('size', 'multiple')
		);
	}

	function getAjaxRaw(Registry $attributes)
	{
		$name     = $attributes->get('name', $this->type);
		$id       = $attributes->get('id', strtolower($name));
		$value    = $attributes->get('value', []);
		$size     = $attributes->get('size');
		$multiple = $attributes->get('multiple');

		$options = $this->getMenuItems();

		return $this->selectList($options, $name, $value, $id, $size, $multiple);
	}

	/**
	 * Get a list of menu links for one or all menus.
	 */
	public static function getMenuItems()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.alias, a.level, a.menutype, a.type, a.template_style_id, a.checked_out, a.language')
			->from('#__menu AS a')
			->join('LEFT', $db->quoteName('#__menu') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->where('a.published != -2')
			->group('a.id, a.title, a.level, a.menutype, a.type, a.template_style_id, a.checked_out, a.lft')
			->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$links = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		// Group the items by menutype.
		$query->clear()
			->select('*')
			->from('#__menu_types')
			->where('menutype <> ' . $db->quote(''))
			->order('title, menutype');
		$db->setQuery($query);

		try
		{
			$menuTypes = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		RL_Language::load('com_menus', JPATH_ADMINISTRATOR);

		// Create a reverse lookup and aggregate the links.
		$rlu = [];
		foreach ($menuTypes as &$type)
		{
			$type->value      = 'type.' . $type->menutype;
			$type->text       = $type->title;
			$type->level      = 0;
			$type->class      = 'hidechildren';
			$type->labelclass = 'nav-header';

			$rlu[$type->menutype] = &$type;
			$type->links          = [];
		}

		// Loop through the list of menu links.
		foreach ($links as &$link)
		{
			if ( ! isset($rlu[$link->menutype]))
			{
				continue;
			}

			$check1 = RL_RegEx::replace('[^a-z0-9]', '', strtolower($link->text));
			$check2 = RL_RegEx::replace('[^a-z0-9]', '', $link->alias);
			if ($check1 !== $check2)
			{
				$link->text .= ' <small>[' . $link->alias . ']</small>';
			}

			if ($link->language && $link->language != '*')
			{
				$link->text .= ' <small>(' . $link->language . ')</small>';
			}

			if ($link->type == 'alias')
			{
				$link->text    .= ' <small>(' . JText::_('COM_MENUS_TYPE_ALIAS') . ')</small>';
				$link->disable = 1;
			}

			$rlu[$link->menutype]->links[] = &$link;

			// Cleanup garbage.
			unset($link->menutype);
		}

		return $menuTypes;
	}
}
