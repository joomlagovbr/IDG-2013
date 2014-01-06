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
 * Renders a password element
 */
class WFElementPassword extends WFElement
{
	/**
	 * Element name
	 *
	 * @var    string
	 */
	protected $_name = 'Password';

	/**
	 * Fetch a html for a password element
	 *
	 * @param   string       $name          Element name
	 * @param   string       $value         Element value
	 * @param   WFXMLElement  &$node        WFXMLElement node object containing the settings for the element
	 * @param   string       $control_name  Control name
	 *
	 * @return  string
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{

		$size   = ((string) $node->attributes()->size     ? 'size="' . (string) $node->attributes()->size . '"' : '');
		$class  = ((string) $node->attributes()->class    ? 'class="' . (string) $node->attributes()->class . '"' : 'class="text_area"');

		return '<input type="password" name="' . $control_name . '[' . $name . ']" id="' . $control_name . $name . '" value="' . $value . '" '
			. $class . ' ' . $size . ' />';
	}
}
