<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$catId = $params->get('catid');
?>
<div class="module module-box-01 <?php echo $moduleclass_sfx; ?>">
	<div class="header">
		<h2 class="title"><?php echo $module->title; ?></h2>
	</div>
	<ul class="row-fluid">
		<?php foreach ($list as $item) : ?>
			<li class="span12" itemscope itemtype="https://schema.org/Article">
				<a href="<?php echo $item->link; ?>" itemprop="url">
					<?php echo $item->title; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="footer">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($catId[0])); ?>" class="link"><?php ECHO JText::_('TPL_PADRAOGOVERNO01_ACESSE_LISTA_COMPLETA'); ?></a>
	</div>									
</div>
