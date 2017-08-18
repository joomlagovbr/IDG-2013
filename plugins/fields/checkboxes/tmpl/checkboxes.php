<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Checkboxes
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$fieldValue = $field->value;

if ($fieldValue === '' || $fieldValue === null)
{
	return;
}

$fieldValue = (array) $fieldValue;
$texts      = array();
$options    = $this->getOptionsFromField($field);

foreach ($options as $value => $name)
{
	if (in_array((string) $value, $fieldValue))
	{
		$texts[] = JText::_($name);
	}
}

echo htmlentities(implode(', ', $texts));
