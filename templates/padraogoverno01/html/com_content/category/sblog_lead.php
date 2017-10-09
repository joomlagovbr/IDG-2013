<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

?>
<div class="span10 tileContent">
	<?php if ($this->item->state == 0) : ?>
	<div class="system-unpublished">
	<?php endif; ?>
	
	<div class="tileHeader">
		<?php if(@isset($this->item->xreference) && @$this->item->xreference != ''): ?>
		<span class="subtitle"><?php echo trim($this->item->xreference); ?></span>
		<?php endif; ?>

		<?php if ($params->get('show_title')) : ?>
			<h2>
				<?php if ( $params->get('access-view') && $params->get('show_readmore') && $this->item->readmore) : ?>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
					<?php echo $this->escape($this->item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>
	</div>

	<?php  if (@isset($images->image_intro) && @!empty($images->image_intro) ) : ?>
	<div class="tileImage">
		<?php if(@strpos($images->image_intro, 'www.youtube') === false): ?>
			
			<?php if($params->get('access-view')): ?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
			<?php endif; ?>
			
			<img
			<?php if ($images->image_intro_caption):
				echo ' title="' .htmlspecialchars($images->image_intro_caption) .'"';
			endif; ?>
			class="tileImage" src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" height="225" width="300" />							
			
			<?php if($params->get('access-view')): ?>
			</a>
			<?php endif; ?>

		<?php else: ?>
			<object width="300" height="225"><param value="<?php echo 'http://'.htmlspecialchars($images->image_intro); ?>" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><embed width="300" height="225" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" src="<?php echo 'http://'.htmlspecialchars($images->image_intro); ?>"></object>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>

	<?php if ($params->get('show_intro')) : ?>
		<?php echo $this->item->event->beforeDisplayContent; ?>
		<div class="description">
			<?php //echo $this->item->introtext; ?>
			<?php echo TemplateContentCategoryHelper::getArticleIntro( $this->item, 400, false ); ?>
			<?php if ($canEdit) : ?>
			<ul class="actions">
				<?php if ($canEdit) : ?>
				<li class="edit-icon">
					<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
				</li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>		
		</div>
	<?php endif; ?>
	<?php if($this->item->metakey != '' || ($params->get('show_parent_category') && $this->item->parent_id != 1) || $params->get('show_category') || ($params->get('show_author') && !empty($this->item->author ))): ?>
		<div class="keywords">
			<?php if($this->item->metakey != ''): ?>
		    <p>Tags: <?php TemplateContentCategoryHelper::displayMetakeyLinks($this->item->metakey); ?></p>
			<?php endif; ?>
			<?php if ($params->get('show_category') || ($params->get('show_parent_category') && $this->item->parent_id != 1)) : ?>		    	
				<?php
				$categories = '';

				if ($params->get('show_parent_category') && $this->item->parent_id != 1): 
					if ($params->get('link_parent_category'))
						$categories .= '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>';
					else
						$categories .= $this->escape($this->item->parent_title);
				endif;
				
				if ($params->get('show_parent_category') && $this->item->parent_id != 1 && $params->get('show_category'))
					 $categories .= ', ';

				if ($params->get('show_category') ):
					if ($params->get('link_category'))
						$categories .= '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)) . '">' . $this->item->category_title . '</a>';
					else
						$categories .= $this->escape($this->item->category_title);                                                              
				endif;
				?>
				<p>Registrado em: <?php echo $categories; ?></p>
			<?php endif; ?>	
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>				
				<?php $author =  $this->item->author; ?>
				<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

				<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
					<p><?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
					 JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author)); ?></p>
				<?php else :?>
					<p><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($params->get('show_modify_date')) : ?>				
				<p><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, 'd/m/Y, H\hi')); ?></p>
			<?php endif; ?>

	  </div>
	<?php endif; ?>

	

	<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JURI($link1);
			$link->setVar('return', base64_encode(urlencode($returnURL)));
		endif;
	?>
			<div class="readmore">
				<a href="<?php echo $link; ?>">
					<?php if (!$params->get('access-view')) :
						echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
					elseif ($readmore = $this->item->alternative_readmore) :
						echo $readmore;
						if ($params->get('show_readmore_title', 0) != 0) :
						    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
						endif;
					elseif ($params->get('show_readmore_title', 0) == 0) :
						echo str_replace(':','', JText::sprintf('COM_CONTENT_READ_MORE'));
					else :
						echo str_replace(':','', JText::_('COM_CONTENT_READ_MORE'));
						// echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
					endif; ?></a>
			</div>
	<?php endif; ?>

	<?php if ($this->item->state == 0) : ?>
	</div>
	<?php endif; ?>
</div>
<div class="span2 tileInfo">
	<ul>
		<?php
		// var_dump($this->item);
		$author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
		?>
		<?php if ($params->get('show_author') && !empty($this->item->author )): ?>
		<li class="hide"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></li>
		<?php endif; ?>
		<li class="hide"><?php echo (($this->item->state == 1)? 'publicado' : 'n&atilde;o publicado' ) ?></li>

		<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'd/m/y'); ?></li>
		<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'H\hi'); ?></li>
	</ul>							            								
</div>
<?php echo $this->item->event->afterDisplayContent; ?>
