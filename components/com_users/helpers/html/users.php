<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users Html Helper
 *
 * @since  1.6
 */
abstract class JHtmlUsers
{
	/**
	 * Get the sanitized value
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function value($value)
	{
		if (is_string($value))
		{
			$value = trim($value);
		}

		if (empty($value))
		{
			return JText::_('COM_USERS_PROFILE_VALUE_NOT_FOUND');
		}

		elseif (!is_array($value))
		{
			return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
		}
	}

	/**
	 * Get the space symbol
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  string
	 *
	 * @since   1.6
	 */
	public static function spacer($value)
	{
		return '';
	}

	/**
	 * Get the sanitized helpsite link
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function helpsite($value)
	{
		if (empty($value))
		{
			return static::value($value);
		}

		$text = $value;

		if ($xml = simplexml_load_file(JPATH_ADMINISTRATOR . '/help/helpsites.xml'))
		{
			foreach ($xml->sites->site as $site)
			{
				if ((string) $site->attributes()->url == $value)
				{
					$text = (string) $site;
					break;
				}
			}
		}

		$value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');

		if (strpos($value, 'http') === 0)
		{
			return '<a href="' . $value . '">' . $text . '</a>';
		}

		return '<a href="http://' . $value . '">' . $text . '</a>';
	}

	/**
	 * Get the sanitized template style
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function templatestyle($value)
	{
		if (empty($value))
		{
			return static::value($value);
		}
		else
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('title')
				->from('#__template_styles')
				->where('id = ' . $db->quote($value));
			$db->setQuery($query);
			$title = $db->loadResult();

			if ($title)
			{
				return htmlspecialchars($title, ENT_COMPAT, 'UTF-8');
			}
			else
			{
				return static::value('');
			}
		}
	}

	/**
	 * Get the sanitized language
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function admin_language($value)
	{
		if (empty($value))
		{
			return static::value($value);
		}
		else
		{
			$file = JLanguageHelper::getLanguagePath(JPATH_ADMINISTRATOR, $value) . '/' . $value . '.xml';

			$result = null;

			if (is_file($file))
			{
				$result = JLanguageHelper::parseXMLLanguageFile($file);
			}

			if ($result)
			{
				return htmlspecialchars($result['name'], ENT_COMPAT, 'UTF-8');
			}
			else
			{
				return static::value('');
			}
		}
	}

	/**
	 * Get the sanitized language
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function language($value)
	{
		if (empty($value))
		{
			return static::value($value);
		}
		else
		{
			$file = JLanguageHelper::getLanguagePath(JPATH_SITE, $value) . '/' . $value . '.xml';

			$result = null;

			if (is_file($file))
			{
				$result = JLanguageHelper::parseXMLLanguageFile($file);
			}

			if ($result)
			{
				return htmlspecialchars($result['name'], ENT_COMPAT, 'UTF-8');
			}
			else
			{
				return static::value('');
			}
		}
	}

	/**
	 * Get the sanitized editor name
	 *
	 * @param   mixed  $value  Value of the field
	 *
	 * @return  mixed  String/void
	 *
	 * @since   1.6
	 */
	public static function editor($value)
	{
		if (empty($value))
		{
			return static::value($value);
		}
		else
		{
			$db = JFactory::getDbo();
			$lang = JFactory::getLanguage();
			$query = $db->getQuery(true)
				->select('name')
				->from('#__extensions')
				->where('element = ' . $db->quote($value))
				->where('folder = ' . $db->quote('editors'));
			$db->setQuery($query);
			$title = $db->loadResult();

			if ($title)
			{
				$lang->load("plg_editors_$value.sys", JPATH_ADMINISTRATOR, null, false, true)
					|| $lang->load("plg_editors_$value.sys", JPATH_PLUGINS . '/editors/' . $value, null, false, true);
				$lang->load($title . '.sys');

				return JText::_($title);
			}
			else
			{
				return static::value('');
			}
		}
	}
}
