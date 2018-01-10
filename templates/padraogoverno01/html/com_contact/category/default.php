<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

?>
<div class="contact-category<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<span class="documentCategory"><?php echo $this->escape($this->params->get('page_heading')); ?></span>
<?php endif; ?>
<?php if($this->params->get('show_category_title', 1)) : ?>
<h1 class="documentFirstHeading">
	<?php echo JHtml::_('content.prepare', $this->category->title, '', 'com_contact.category'); ?>
</h1>
<?php endif; ?>


	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
<div class="lightbox-image pull-left light-image-left light-image-horz">							
	<div class="image-box">
		<img class="img-rounded" src="<?php echo $this->category->getParams()->get('image'); ?>" alt="Imagem decorativa."/>				
	</div>
</div>
	<?php endif; ?>

<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="subtitle">
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_contact.category'); ?>
	<?php endif; ?>
	</div>
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
<div class="cat-children">
	<h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES') ; ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>
</div>
