<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined('_JEXEC') or die;

/**
 * Renders a spacer element
 */
class WFElementSpacer extends WFElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'Spacer';

	/**
	 * Fetch tooltip for a radio button
	 *
	 * @param   string       $label         Element label
	 * @param   string       $description   Element description for tool tip
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 * @param   string       $name          The name.
	 *
	 * @return  string
	 */
	public function fetchTooltip($label, $description, &$node, $control_name = '', $name = '')
	{
		return '&#160;';
	}

	/**
	 * Fetch HTML for a radio button
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   JXMLElement  &$node         JXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value)
		{
			return JText::_($value);
		}
		else
		{
			return ' ';
		}
	}
}
