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

$fieldName = 'birthdate';
$list = BirthdaysHelper::filterByBirthdate($list, $fieldName);
?>
<ul class="birthdays<?php echo $moduleclass_sfx; ?>">

<?php foreach ($list as &$item): ?>
	<li>
	<?php if (isset($item->fields[$fieldName])): ?>
		<time datetime="<?php echo $item->fields[$fieldName]->value; ?>">
			<span>
				<?php echo JHtml::_('date', $item->fields[$fieldName]->value, 'd', null); ?>
			</span>
		</time>
	<?php endif; ?>
		<span>
			<?php echo BirthdaysHelper::prepareName($item); ?>
		</span>
	</li>
<?php endforeach; ?>
</ul>
