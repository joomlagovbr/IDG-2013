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
$list = BirthdaysHelper::filterByBirthday($list, $startDate, $endDate);

?>
<p class="period">
	<?php echo JHtml::_('date', $startDate, JText::_('DATE_FORMAT_LC3')); ?> -
	<?php echo JHtml::_('date', $endDate, JText::_('DATE_FORMAT_LC3')); ?>
</p>
<ul class="birthdays<?php echo $moduleclass_sfx; ?>">

<?php foreach ($list as &$item): ?>
	<li>
	<?php if (isset($item->fields['birthday'])): ?>
		<div class="date-block">
			<h5 class="day">
				<?php echo JHtml::_('date', $item->fields['birthday']->value, 'd'); ?>
			</h5>
			<span class="month">
				<?php echo JHtml::_('date', $item->fields['birthday']->value, 'M'); ?>
			</span>
		</div>
	<?php endif; ?>

		<p class="person">
			<?php echo BirthdaysHelper::prepareName($item); ?>
		</p>
	</li>
<?php endforeach; ?>
</ul>
