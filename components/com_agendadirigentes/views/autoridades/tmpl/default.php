<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
defined('_JEXEC') or die;
$this->document->addStylesheet( JURI::root().'media/com_agendadirigentes/css/frontend.css' );

?>
<div class="item-page<?php //echo $this->pageclass_sfx?>">
	<h1 class="documentFirstHeading">
		<?php echo $this->escape($this->page_heading); ?>
	</h1>
	<?php if (!empty($this->introtext)): ?>
		<div class="description">
			<?php echo $this->introtext; ?>
		</div>
	<?php endif; ?>
	<?php if(@empty($this->items) || @count($this->items)==0): ?>
		<p>N&atilde;o foram encontradas autoridades cadastradas.</p>
	<?php endif; ?>
	<?php $opened = false; ?>
	<?php for ($i=0, $limit = @count($this->items); $i < $limit; $i++): ?>
		<?php
		$item = $this->items[$i];
		$next = (@isset($this->items[$i+1]))? $this->items[$i+1] : NULL;
		$prev = (@isset($this->items[$i-1]))? $this->items[$i-1] : NULL;
		$item->level = ($item->level > 5)? 5 : $item->level;
		if(!is_null($next))
			$next->level = ($next->level > 5)? 5 : $next->level;
		?>

		<?php if($item->cargo_featured): ?>
		<div class="autoridades-item-destaque">
			<h2><?php echo $item->cargo_name; ?><br />
			<a href="index.php?option=com_agendadirigentes&view=autoridade&id=<?php echo $item->dir_id ?>&dia=<?php echo $this->params->get('dia', ''); ?>" class="link-nome-autoridade"><?php echo $item->dir_name ?></a></h2>
		</div>
		<?php else: ?>			
			<?php if(is_null($prev) || !$opened): ?>
			<div class="container-autoridades-<?php echo $item->level ?>">
			<h<?php echo $item->level + 1 ?> class="autoridades-categoria"><?php echo $item->cat_title ?></h<?php echo $item->level + 1 ?>>
			<ul class="autoridades-lista">
			<?php $opened = true; ?>
			<?php endif; ?>

			<li class="autoridades-item">
				<a href="index.php?option=com_agendadirigentes&view=autoridade&id=<?php echo $item->dir_id ?>&dia=<?php echo $this->params->get('dia', ''); ?>"><strong><?php echo $item->cargo_name ?></strong><br />
				<?php echo $item->dir_name ?></a>
			</li>

			<?php if(is_null($next)): ?>
			</ul></div>
			<?php elseif($next->cat_title != $item->cat_title): ?>
			</ul></div>
			<div class="container-autoridades-<?php echo $next->level ?>">
			<h<?php echo $next->level + 1 ?> class="autoridades-categoria"><?php echo $next->cat_title ?></h<?php echo $next->level + 1 ?>>
			<ul>
			<?php endif; ?>

		<?php endif; ?>

	<?php endfor; ?>
</div>