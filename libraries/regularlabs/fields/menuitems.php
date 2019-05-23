<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
use Joomla\Registry\Registry;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\RegEx as RL_RegEx;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_MenuItems extends \RegularLabs\Library\Field
{
	public $type = 'MenuItems';

	protected function getInput()
	{
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
		RL_Language::load('com_modules', JPATH_ADMINISTRATOR);
		JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
		$menuTypes = MenusHelper::getMenuLinks();

		foreach ($menuTypes as &$type)
		{
			$type->value      = 'type.' . $type->menutype;
			$type->text       = $type->title;
			$type->level      = 0;
			$type->class      = 'hidechildren';
			$type->labelclass = 'nav-header';

			$rlu[$type->menutype] = &$type;

			foreach ($type->links as &$link)
			{
				$check1 = RL_RegEx::replace('[^a-z0-9]', '', strtolower($link->text));
				$check2 = RL_RegEx::replace('[^a-z0-9]', '', $link->alias);

				$text   = [];
				$text[] = $link->text;

				if ($check1 !== $check2)
				{
					$text[] = '<span class="small ghosted">[' . $link->alias . ']</span>';
				}

				if (in_array($link->type, ['separator', 'heading', 'alias', 'url']))
				{
					$text[] = '<span class="label label-info">' . JText::_('COM_MODULES_MENU_ITEM_' . strtoupper($link->type)) . '</span>';
					// Don't disable, as you need to be able to select the 'Also on Child Items' option
					// $link->disable = 1;
				}

				if ($link->published == 0)
				{
					$text[] = '<span class="label">' . JText::_('JUNPUBLISHED') . '</span>';
				}

				if (JLanguageMultilang::isEnabled() && $link->language != '' && $link->language != '*')
				{
					$text[] = $link->language_image
						? JHtml::_('image', 'mod_languages/' . $link->language_image . '.gif', $link->language_title, ['title' => $link->language_title], true)
						: '<span class="label" title="' . $link->language_title . '">' . $link->language_sef . '</span>';
				}

				$link->text = implode(' ', $text);
			}
		}

		return $menuTypes;
	}
}
