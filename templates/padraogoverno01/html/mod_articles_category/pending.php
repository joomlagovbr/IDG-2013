<?php
/**
 * @package     Joomla.Site
 * @subpackage  Modules.ArticlesCategory
 *
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2021 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 */

defined('_JEXEC') or die();

JLoader::register('PendingHelper', __DIR__ . '/helpers/_pending_helper.php');

PendingHelper::addCustomFields($list);
PendingHelper::filterPending($list);
?>

<?php if (count($list) == 0) : ?>
	<p><strong>Sem documentos pendentes</p>
<?php else : ?>
	<ul class="media-list pending<?php echo $moduleclass_sfx ? ' ' . preg_replace('/span\d/', '', $moduleclass_sfx) : ''; ?>">

	<?php foreach ($list as &$item): ?>
		<li class="media">
		<?php if (isset($item->fields['limitat'])): ?>
			<span class="date pull-left">
				<?php echo JHtml::_('date', $item->fields['limitat']->rawvalue, 'd M'); ?>
			</span>
		<?php endif; ?>
			<div class="media-body">
				<p><?php echo $item->title; ?></p>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
