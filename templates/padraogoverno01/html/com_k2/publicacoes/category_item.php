<?php
/**
 * @version		$Id: category_item.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

// Define default image size (do not change)
K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

?>
<div class="span10 tileContent">

	<!-- Plugins: BeforeDisplay -->
	<?php echo $this->item->event->BeforeDisplay; ?>

	<!-- K2 Plugins: K2BeforeDisplay -->
	<?php echo $this->item->event->K2BeforeDisplay; ?>
	
	<div class="tileHeader">

	  <?php if($this->item->params->get('catItemTitle')): ?>
	  <!-- Item title -->
	  <h2>
	  	<?php if ($this->item->params->get('catItemTitleLinked')): ?>
			<a href="<?php echo $this->item->link; ?>">
		  		<?php echo $this->item->title; ?>
		  	</a>
	  	<?php else: ?>
		  	<?php echo $this->item->title; ?>
	  	<?php endif; ?>
	  </h2>
	  <?php endif; ?>

	</div>
	<!-- Plugins: AfterDisplayTitle -->
	<?php echo $this->item->event->AfterDisplayTitle; ?>

	<!-- K2 Plugins: K2AfterDisplayTitle -->
	<?php echo $this->item->event->K2AfterDisplayTitle; ?>

	<!-- Plugins: BeforeDisplayContent -->
	<?php echo $this->item->event->BeforeDisplayContent; ?>

	<!-- K2 Plugins: K2BeforeDisplayContent -->
	<?php echo $this->item->event->K2BeforeDisplayContent; ?>

	<?php if($this->item->params->get('catItemIntroText')): ?>
	<!-- Item introtext -->
	<br>
	<div class="description">
		<?php echo $this->item->introtext; ?>
	</div>
	<?php endif; ?>
	
	<div class="keywords">
		<?php if($this->item->params->get('catItemTags') && count($this->item->tags)): ?>
		<p>Tags: 	
		<!-- Item tags -->		
		<?php $tags = array(); ?>
	    <?php foreach ($this->item->tags as $tag): ?>
	    <?php $tags[] = '<span><a href="'.TmplK2Helper::getSearchTagLink($tag->name).'">'. $tag->name.'</a></span>'; ?>	    
	    <?php //$tags[] = '<span><a href="'.$tag->link.'">'. $tag->name.'</a></span>'; ?>	    
	    <?php endforeach; ?>
		<?php echo implode('<span class="separator">,</span>', $tags); ?>
		</p>
		<?php endif; ?>
		<?php if($this->item->params->get('catItemAuthor')): ?>
		<!-- Item Author -->
		<p>
			<?php echo K2HelperUtilities::writtenBy($this->item->author->profile->gender); ?> 
			<?php echo $this->item->author->name; ?>
		</p>
		<?php endif; ?>
		<?php if($this->item->params->get('catItemCategory')): ?>
		<!-- Item category name -->
		<p>
			<?php echo JText::_('K2_PUBLISHED_IN'); ?>
			<a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
		</p>
		<?php endif; ?>
		<?php if($this->item->modified != '0000-00-00 00:00:00'): ?>
		<!-- Date created -->
		<p>			
			&Uacute;ltima modifica&ccedil;&atilde;o em <?php echo JHTML::_('date', $this->item->modified , JText::_('DATE_FORMAT_LC2')); ?>
		</p>
		<?php endif; ?>					
	</div>
	<br>
	<div class="row-fluid">
		<?php if($this->item->params->get('catItemAttachments') && count($this->item->attachments)): ?>
		<!-- Item attachments -->
		<div class="catItemAttachmentsBlock pull-right">	  
		  <ul class="catItemAttachments">
		    <?php foreach ($this->item->attachments as $attachment): ?>
				<li>
			    <a class="btn" title="<?php echo K2HelperUtilities::cleanHtml($attachment->titleAttribute); ?>" href="<?php echo $attachment->link; ?>">
			    	<i class="icon-download-alt"></i>
			    	<?php echo $attachment->title ; ?>
			    	<?php if($this->item->params->get('catItemAttachmentsCounter')): ?>
			    <span>(<?php echo $attachment->hits; ?> <?php echo ($attachment->hits==1) ? JText::_('K2_DOWNLOAD') : JText::_('K2_DOWNLOADS'); ?>)</span>
			    <?php endif; ?>
			    </a>
			    </li>	
		    <?php endforeach; ?>
		  </ul>
		</div>
		<?php endif; ?>
	</div>
	<!-- Plugins: AfterDisplayContent -->
	<?php echo $this->item->event->AfterDisplayContent; ?>

	<!-- K2 Plugins: K2AfterDisplayContent -->
	<?php echo $this->item->event->K2AfterDisplayContent; ?>

  <!-- Plugins: AfterDisplay -->
  <?php echo $this->item->event->AfterDisplay; ?>

  <!-- K2 Plugins: K2AfterDisplay -->
  <?php echo $this->item->event->K2AfterDisplay; ?>

</div>
<div class="tileInfo span2">
	<ul>
		<li><i class="icon-fixed-width icon-calendar"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'd/m/y'); ?></li>
		<li><i class="icon-fixed-width icon-time"></i> <?php echo JHtml::_('date', $this->item->publish_up, 'H\hi'); ?></li>
	</ul>
</div>
<!-- End K2 Item Layout -->
