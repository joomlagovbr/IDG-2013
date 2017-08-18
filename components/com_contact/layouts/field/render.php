<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

if (!key_exists('field', $displayData))
{
	return;
}

$field     = $displayData['field'];
$label     = $field->label;
$value     = $field->value;
$class     = $field->params->get('render_class');
$showLabel = $field->params->get('showlabel');

if ($field->context == 'com_contact.mail')
{
	// Prepare the value for the contact form mail
	$value = html_entity_decode($value);

	echo ($showLabel ? $label . ': ' : '') . $value . "\r\n";
	return;
}

if (!$value)
{
	return;
}

?>
<dt class="contact-field-entry <?php echo $class; ?>">
	<?php if ($showLabel == 1) : ?>
		<span class="field-label"><?php echo htmlentities($label, ENT_QUOTES | ENT_IGNORE, 'UTF-8'); ?>: </span>
	<?php endif; ?>
</dt>
<dd class="contact-field-entry <?php echo $class; ?>">
	<span class="field-value"><?php echo $value; ?></span>
</dd>
