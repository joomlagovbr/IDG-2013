<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.ArticlesCategory
 *
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2020 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 */

defined('_JEXEC') or die();

JLoader::register('BirthdaysHelper', __DIR__ . '/_helper.php');

$startDate = new JDate('today');
$endDate = clone $startDate;
$endDate->add(new DateInterval('P7D'));

BirthdaysHelper::addCustomFields($list);
BirthdaysHelper::filterByBirthday($list, $startDate, $endDate);

?>
<p class="period">
	<?php echo JHtml::_('date', $startDate, JText::_('DATE_FORMAT_LC3')); ?> -
	<?php echo JHtml::_('date', $endDate, JText::_('DATE_FORMAT_LC3')); ?>
</p>
<ul class="birthdays<?php echo $moduleclass_sfx; ?>">

<?php foreach ($list as &$item): ?>
	<li>
	<?php if (isset($item->fields['birthday'])): ?>
		<span class="date">
			<?php echo JHtml::_('date', $item->fields['birthday']->value, 'd M'); ?>
		</span>
	<?php endif; ?>

		<span class="person">
			<?php echo BirthdaysHelper::prepareName($item); ?>
		</span>
	</li>
<?php endforeach; ?>
</ul>
