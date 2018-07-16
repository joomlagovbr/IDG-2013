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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use JHtml;
use JText;

class Form
{
	/**
	 * Render a full select list
	 *
	 * @param array  $options
	 * @param string $name
	 * @param string $value
	 * @param string $id
	 * @param int    $size
	 * @param bool   $multiple
	 * @param bool   $simple
	 *
	 * @return string
	 */
	public static function selectList(&$options, $name, $value, $id, $size = 0, $multiple = false, $simple = false)
	{
		if (empty($options))
		{
			return '<fieldset class="radio">' . JText::_('RL_NO_ITEMS_FOUND') . '</fieldset>';
		}

		if ( ! $multiple)
		{
			$simple = true;
		}

		$parameters = Parameters::getInstance();
		$params     = $parameters->getPluginParams('regularlabs');

		if ( ! is_array($value))
		{
			$value = explode(',', $value);
		}

		if (count($value) === 1 && strpos($value[0], ',') !== false)
		{
			$value = explode(',', $value[0]);
		}

		$count = 0;
		if ($options != -1)
		{
			foreach ($options as $option)
			{
				$count++;
				if (isset($option->links))
				{
					$count += count($option->links);
				}
				if ($count > $params->max_list_count)
				{
					break;
				}
			}
		}

		if ($options == -1 || $count > $params->max_list_count)
		{
			if (is_array($value))
			{
				$value = implode(',', $value);
			}
			if ( ! $value)
			{
				$input = '<textarea name="' . $name . '" id="' . $id . '" cols="40" rows="5">' . $value . '</textarea>';
			}
			else
			{
				$input = '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" size="60">';
			}

			return '<fieldset class="radio"><label for="' . $id . '">' . JText::_('RL_ITEM_IDS') . ':</label>' . $input . '</fieldset>';
		}

		if ($simple)
		{
			$first_level = isset($options[0]->level) ? $options[0]->level : 0;
			foreach ($options as &$option)
			{
				if ( ! isset($option->level))
				{
					continue;
				}
				$repeat       = ($option->level - $first_level > 0) ? $option->level - $first_level : 0;
				$option->text = str_repeat(' - ', $repeat) . $option->text;
			}
		}

		if ( ! $multiple)
		{
			$html = JHtml::_('select.genericlist', $options, $name, 'class="inputbox"', 'value', 'text', $value);

			return self::handlePreparedStyles($html);
		}

		$size = (int) $size ?: 300;

		if ($simple)
		{
			$attr = 'style="width: ' . $size . 'px"';
			$attr .= $multiple ? ' multiple="multiple"' : '';

			$html = JHtml::_('select.genericlist', $options, $name, trim($attr), 'value', 'text', $value, $id);

			return self::handlePreparedStyles($html);
		}

		Language::load('com_modules', JPATH_ADMINISTRATOR);

		Document::script('regularlabs/multiselect.min.js');
		Document::stylesheet('regularlabs/multiselect.min.css');

		$html = [];

		$html[] = '<div class="well well-small rl_multiselect" id="' . $id . '">';
		$html[] = '
			<div class="form-inline rl_multiselect-controls">
				<span class="small">' . JText::_('JSELECT') . ':
					<a class="rl_multiselect-checkall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="rl_multiselect-uncheckall" href="javascript:;">' . JText::_('JNONE') . '</a>,
					<a class="rl_multiselect-toggleall" href="javascript:;">' . JText::_('RL_TOGGLE') . '</a>
				</span>
				<span class="width-20">|</span>
				<span class="small">' . JText::_('RL_EXPAND') . ':
					<a class="rl_multiselect-expandall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="rl_multiselect-collapseall" href="javascript:;">' . JText::_('JNONE') . '</a>
				</span>
				<span class="width-20">|</span>
				<span class="small">' . JText::_('JSHOW') . ':
					<a class="rl_multiselect-showall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="rl_multiselect-showselected" href="javascript:;">' . JText::_('RL_SELECTED') . '</a>
				</span>
				<span class="rl_multiselect-maxmin">
				<span class="width-20">|</span>
				<span class="small">
					<a class="rl_multiselect-maximize" href="javascript:;">' . JText::_('RL_MAXIMIZE') . '</a>
					<a class="rl_multiselect-minimize" style="display:none;" href="javascript:;">' . JText::_('RL_MINIMIZE') . '</a>
				</span>
				</span>
				<input type="text" name="rl_multiselect-filter" class="rl_multiselect-filter input-medium search-query pull-right" size="16"
					autocomplete="off" placeholder="' . JText::_('JSEARCH_FILTER') . '" aria-invalid="false" tabindex="-1">
			</div>

			<div class="clearfix"></div>

			<hr class="hr-condensed">';

		$o = [];
		foreach ($options as $option)
		{
			$option->level = isset($option->level) ? $option->level : 0;
			$o[]           = $option;
			if (isset($option->links))
			{
				foreach ($option->links as $link)
				{
					$link->level = $option->level + (isset($link->level) ? $link->level : 1);
					$o[]         = $link;
				}
			}
		}

		$html[]    = '<ul class="rl_multiselect-ul" style="max-height:300px;min-width:' . $size . 'px;overflow-x: hidden;">';
		$prevlevel = 0;

		foreach ($o as $i => $option)
		{
			if ($prevlevel < $option->level)
			{
				// correct wrong level indentations
				$option->level = $prevlevel + 1;

				$html[] = '<ul class="rl_multiselect-sub">';
			}
			else if ($prevlevel > $option->level)
			{
				$html[] = str_repeat('</li></ul>', $prevlevel - $option->level);
			}
			else if ($i)
			{
				$html[] = '</li>';
			}

			$labelclass = trim('pull-left ' . (isset($option->labelclass) ? $option->labelclass : ''));

			$html[] = '<li>';

			$item = '<div class="' . trim('rl_multiselect-item pull-left ' . (isset($option->class) ? $option->class : '')) . '">';
			if (isset($option->title))
			{
				$labelclass .= ' nav-header';
			}

			if (isset($option->title) && ( ! isset($option->value) || ! $option->value))
			{
				$item .= '<label class="' . $labelclass . '">' . $option->title . '</label>';
			}
			else
			{
				$selected = in_array($option->value, $value) ? ' checked="checked"' : '';
				$disabled = (isset($option->disable) && $option->disable) ? ' readonly="readonly" style="visibility:hidden"' : '';

				$item .= '<input type="checkbox" class="pull-left" name="' . $name . '" id="' . $id . $option->value . '" value="' . $option->value . '"' . $selected . $disabled . '>
					<label for="' . $id . $option->value . '" class="' . $labelclass . '">' . $option->text . '</label>';
			}
			$item   .= '</div>';
			$html[] = $item;

			if ( ! isset($o[$i + 1]) && $option->level > 0)
			{
				$html[] = str_repeat('</li></ul>', (int) $option->level);
			}
			$prevlevel = $option->level;
		}
		$html[] = '</ul>';
		$html[] = '
			<div style="display:none;" class="rl_multiselect-menu-block">
				<div class="pull-left nav-hover rl_multiselect-menu">
					<div class="btn-group">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-micro">
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li class="nav-header">' . JText::_('COM_MODULES_SUBITEMS') . '</li>
							<li class="divider"></li>
							<li class=""><a class="checkall" href="javascript:;"><span class="icon-checkbox"></span> ' . JText::_('JSELECT') . '</a>
							</li>
							<li><a class="uncheckall" href="javascript:;"><span class="icon-checkbox-unchecked"></span> ' . JText::_('COM_MODULES_DESELECT') . '</a>
							</li>
							<div class="rl_multiselect-menu-expand">
								<li class="divider"></li>
								<li><a class="expandall" href="javascript:;"><span class="icon-plus"></span> ' . JText::_('RL_EXPAND') . '</a></li>
								<li><a class="collapseall" href="javascript:;"><span class="icon-minus"></span> ' . JText::_('RL_COLLAPSE') . '</a></li>
							</div>
						</ul>
					</div>
				</div>
			</div>';
		$html[] = '</div>';

		$html = implode('', $html);

		return self::handlePreparedStyles($html);
	}

	/**
	 * Render a simple select list
	 *
	 * @param array  $options
	 * @param        $string $name
	 * @param string $value
	 * @param string $id
	 * @param int    $size
	 * @param bool   $multiple
	 *
	 * @return string
	 */
	public static function selectListSimple(&$options, $name, $value, $id, $size = 0, $multiple = false)
	{
		return self::selectlist($options, $name, $value, $id, $size, $multiple, true);
	}

	/**
	 * Render a select list loaded via Ajax
	 *
	 * @param string $field
	 * @param string $name
	 * @param string $value
	 * @param string $id
	 * @param array  $attributes
	 * @param bool   $simple
	 *
	 * @return string
	 */
	public static function selectListAjax($field, $name, $value, $id, $attributes = [], $simple = false)
	{
		JHtml::_('jquery.framework');

		$attributes['field'] = $field;
		$attributes['name']  = $name;
		$attributes['value'] = $value;
		$attributes['id']    = $id;

		$url = 'index.php?option=com_ajax&plugin=regularlabs&format=raw'
			. '&' . Uri::createCompressedAttributes(json_encode($attributes));

		$remove_spinner = "$('#" . $id . "_spinner').remove();";
		$replace_field  = "$('#" . $id . "').replaceWith(data);";

		$error   = $remove_spinner;
		$success = "if(data)\{" . $replace_field . "\}" . $remove_spinner;

		//	$success .= "console.log('#" . $id . "');";

		if ($simple)
		{
			$success .= "if(data.indexOf('</select>') > -1)\{$('#" . $id . "').chosen();\}";
		}
		else
		{
			Document::script('regularlabs/multiselect.min.js');
			Document::stylesheet('regularlabs/multiselect.min.css');

			$success .= "if(data.indexOf('rl_multiselect') > -1)\{RegularLabsMultiSelect.init($('#" . $id . "'));\}";
		}

		$script = "jQuery(document).ready(function() {RegularLabsScripts.addToLoadAjaxList("
			. "'" . addslashes($url) . "',"
			. "'" . addslashes($success) . "',"
			. "'" . addslashes($error) . "'"
			. ")});";

		if (is_array($value))
		{
			$value = implode(',', $value);
		}

		Document::script('regularlabs/script.min.js');
		Document::stylesheet('regularlabs/style.min.css');

		$input = '<textarea name="' . $name . '" id="' . $id . '" cols="40" rows="5">' . $value . '</textarea>'
			. '<div id="' . $id . '_spinner" class="rl_spinner"></div>';

		return $input . '<script>' . $script . '</script>';
	}

	/**
	 * Render a simple select list loaded via Ajax
	 *
	 * @param string $field
	 * @param string $name
	 * @param string $value
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return string
	 */
	public static function selectListSimpleAjax($field, $name, $value, $id, $attributes = [])
	{
		return self::selectListAjax($field, $name, $value, $id, $attributes, true);
	}

	/**
	 * Prepare the string for a select form field item
	 *
	 * @param string $string
	 * @param int    $published
	 * @param string $type
	 * @param int    $remove_first
	 *
	 * @return string
	 */
	public static function prepareSelectItem($string, $published = 1, $type = '', $remove_first = 0)
	{
		if (empty($string))
		{
			return '';
		}

		$string = str_replace(['&nbsp;', '&#160;'], ' ', $string);
		$string = RegEx::replace('- ', '  ', $string);

		for ($i = 0; $remove_first > $i; $i++)
		{
			$string = RegEx::replace('^  ', '', $string, '');
		}

		if (RegEx::match('^( *)(.*)$', $string, $match, ''))
		{
			list($string, $pre, $name) = $match;

			$pre = str_replace('  ', ' ·  ', $pre);
			$pre = RegEx::replace('(( ·  )*) ·  ', '\1 »  ', $pre);
			$pre = str_replace('  ', ' &nbsp; ', $pre);

			$string = $pre . $name;
		}

		switch (true)
		{
			case ($type == 'separator'):
				$string = '[[:font-weight:normal;font-style:italic;color:grey;:]]' . $string;
				break;

			case ($published == -2):
				$string = '[[:font-style:italic;color:grey;:]]' . $string . ' [' . JText::_('JTRASHED') . ']';
				break;

			case ($published == 0):
				$string = '[[:font-style:italic;color:grey;:]]' . $string . ' [' . JText::_('JUNPUBLISHED') . ']';
				break;

			case ($published == 2):
				$string = '[[:font-style:italic;:]]' . $string . ' [' . JText::_('JARCHIVED') . ']';
				break;
		}

		return $string;
	}

	/**
	 * Replace style placeholders with actual style attributes
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private static function handlePreparedStyles($string)
	{
		// No placeholders found
		if (strpos($string, '[[:') === false)
		{
			return $string;
		}

		// Doing following replacement in 3 steps to prevent the Regular Expressions engine from exploding

		// Replace style tags right after the html tags
		$string = RegEx::replace(
			'>\s*\[\[\:(.*?)\:\]\]',
			' style="\1">',
			$string
		);

		// No more placeholders found
		if (strpos($string, '[[:') === false)
		{
			return $string;
		}

		// Replace style tags prepended with a minus and any amount of whitespace: '- '
		$string = RegEx::replace(
			'>((?:-\s*)+)\[\[\:(.*?)\:\]\]',
			' style="\2">\1',
			$string
		);

		// No more placeholders found
		if (strpos($string, '[[:') === false)
		{
			return $string;
		}

		// Replace style tags prepended with whitespace, a minus and any amount of whitespace: ' - '
		$string = RegEx::replace(
			'>((?:\s+-\s*)+)\[\[\:(.*?)\:\]\]',
			' style="\2">\1',
			$string
		);

		return $string;
	}
}
