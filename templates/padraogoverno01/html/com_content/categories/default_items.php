<?php

/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0):
?>

		<?php foreach($this->items[$this->parent->id] as $id => $item): ?>
			<div class="tileItem">
				<div class="span10 tileContent">
					<?php
					$params = json_decode($item->params);
					if(@isset($params->image)){
						if($params->image!='') {
							?>
							<div class="tileImage">
								<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));?>">
									<img class="tileImage" src="<?php echo htmlspecialchars($params->image); ?>" alt="imagem ilustrativa" height="225" width="300" />
								</a>
							</div>
							<?php
						}
					}
					?>
					<div class="tileHeader">
					<h2>
						<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));?>">
						<?php echo $this->escape($item->title); ?></a>
					</h2>
					</div>
					<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
						<div class="description">
						<?php if ($item->description) : ?>
							<?php echo JHtml::_('content.prepare', $item->description, '', 'com_content.categories'); ?>
						<?php endif; ?>
						</div>
			        <?php endif; ?>
					<div class="keywords">
						<?php if (!empty($item->metakey)) { ?>
							 <p>Tags: <?php TemplateContentCategoriesHelper::displayMetakeyLinks($item->metakey); ?></p>
						<?php } ?>
						<?php
						$parent = $item->get('_parent');
						if(is_object($parent)){ ?>
							<p>Registrado em: <a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($parent->id)); ?>">
								<?php echo $parent->title ?>
							</a></p>
						<?php
						}
						?>
						<?php if ($item->modified_time != '0000-00-00 00:00:00') : ?>
						<p><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $item->modified_time, 'd/m/Y, H\hi')); ?></p>
						<?php endif; ?>
					</div>
					<div class="tile-list-1">
					<?php if (count($item->getChildren()) > 0) :
					$this->items[$item->id] = $item->getChildren();
					$this->parent = $item;
					$this->maxLevelcat--;
					echo $this->loadTemplate('items2');
					$this->parent = $item->getParent();
					$this->maxLevelcat++;
					endif; ?>
					</div>
				</div>
				<div class="span2 tileInfo">
					<ul>
						<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $item->created_time, 'd/m/y'); ?></li>
						<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $item->created_time, 'H\hi'); ?></li>
						<li><i class="icon-fixed-width"><strong><?php echo $item->numitems; ?></strong></i> <?php if($item->numitems==1): ?>item<?php else: ?>itens<?php endif; ?></li>
					</ul>
				</div>



			</div>

		<?php endforeach; ?>

<?php endif; ?>
