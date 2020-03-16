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

$periodToAdd = new DateInterval('P7D');
$startDate = new JDate('today');
$endDate = clone $startDate;
$endDate->add($periodToAdd);

BirthdaysHelper::addCustomFields($list);
BirthdaysHelper::filterByBirthday($list, $startDate, $endDate);

?>
<p class="period">
	<?php echo JHtml::_('date', $startDate, JText::_('DATE_FORMAT_LC3')); ?> -
	<?php echo JHtml::_('date', $endDate, JText::_('DATE_FORMAT_LC3')); ?>
</p>

<?php if (count($list) == 0) : ?>
	<p>Sem aniversariantes no período</p>
<?php else : ?>
	<ul class="birthdays<?php echo preg_replace('/span\d/', '', $moduleclass_sfx); ?>">

	<?php foreach ($list as &$item): ?>
		<li>
		<?php if (isset($item->fields['birthday'])): ?>
			<span class="date">
				<?php echo JHtml::_('date', $item->fields['birthday']->rawvalue, 'd M'); ?>
			</span>
		<?php endif; ?>
			<span class="person">
			<?php if (isset($item->fields['rank'])) : ?>
				<?php echo $item->fields['rank']->rawvalue[0] . ' '; ?>
			<?php endif; ?>
				<?php echo BirthdaysHelper::prepareName($item); ?>
			</span>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif;
