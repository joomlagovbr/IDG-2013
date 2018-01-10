<?php
/**
 * @version		
 * @package		
 * @author		
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
require_once __DIR__.'/../_helper.php';
TmplK2Helper::removeCss(array('com_k2/css/k2.css'));
TmplK2Helper::removeJs(array('js/mootools-core.js', 'js/core.js', 'js/mootools-core-uncompressed.js', 'js/core-uncompressed.js', 'js/mootools-more.js', 'js/modal.js', 'SqueezeBox.initialize({});'));
?>
<!-- Start K2 Item Layout -->
<span id="startOfPageId<?php echo JRequest::getInt('id'); ?>"></span>
<div id="k2Container" class="itemView<?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">

	<!-- Plugins: BeforeDisplay -->
	<?php echo $this->item->event->BeforeDisplay; ?>

	<!-- K2 Plugins: K2BeforeDisplay -->
	<?php echo $this->item->event->K2BeforeDisplay; ?>

	<?php if($this->item->params->get('itemCategory')): ?>
	<!-- Item category -->
	<span class="documentCategory"><?php echo $this->item->category->name; ?></span>
	<?php endif; ?>
	
	<?php if($this->item->params->get('itemTitle')): ?>
	<h1 class="documentFirstHeading"><?php echo $this->item->title; ?></h1>
	<?php endif; ?>

	<div class="content-header-options-1 row-fluid">
		<div class="documentByLine span7">
			<span class="documentCreated">
			Publicado em <?php echo JHTML::_('date', $this->item->created , JText::_('DATE_FORMAT_LC2')); ?>
			</span>
			<span class="separator"> | </span>
			<?php if($this->item->params->get('itemAuthor')): ?>
			<span class="documentAuthor">
				por <?php echo $this->item->author->name; ?>				
			</span>
			<span class="separator"> | </span>
			<?php endif; ?>
			<span>
			<a title="Voltar à página anterior" href="javascript:history.back()">Voltar à página anterior</a>
			</span>
		</div>
		<?php $modules = JModuleHelper::getModules( 'com_content-article-btns-social' );
			if(count($modules)): ?>
			<div class="btns-social-like span5 hide">
				<?php foreach($modules as $module): ?>
					<?php $html = JModuleHelper::renderModule($module); ?>
					<?php $html = str_replace('{SITE}', JURI::root(), $html); ?>
					<?php echo $html; ?>
				<?php endforeach; ?>
			</div>		
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

  <?php if($this->item->params->get('itemIntroText')): ?>
  <!-- Item introtext -->
  <div class="description">
  	<?php echo $this->item->introtext; ?>
  </div>
  <?php endif; ?>

 <?php if($this->item->params->get('itemVideo') && !empty($this->item->video)): ?>
 
	<div class="itemVideoBlock">		
		<?php if($this->item->videoType=='embedded'): ?>		
		<div class="itemVideoEmbedded">
			<?php echo $this->item->video; ?>
		</div>
		<?php else: ?>
		<div class="itemVideo"><?php echo $this->item->video; ?></div>
		<?php endif; ?>
	</div>

  <?php endif; ?>

  <?php if($this->item->params->get('itemFullText')): ?>
  	<?php if(!empty($this->item->fulltext)): ?>
		<br><br>
		<?php echo $this->item->fulltext; ?>
	 <?php endif; ?> 
  <?php endif; ?> 

<div class="below-content">
	<?php if($this->item->params->get('itemCategory')): ?>
	<!-- Item category -->
	<div class="line">
		registrado em:
		<span><a class="link-categoria" rel="tag" href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a></span>
	</div>
	<?php endif; ?>

	<?php if($this->item->params->get('itemTags') && count($this->item->tags)): ?>
	<!-- Item tags -->
	<div class="line">
	 Assunto(s):
	 	<?php $tag_array = array(); ?>
	    <?php foreach ($this->item->tags as $tag): ?>
	    <?php $tag_array[] = '<span><a class="link-categoria" rel="tag" href="'.$tag->link.'">'.$tag->name.'</a></span>'; ?>
	    <?php endforeach; ?>
	    <?php echo implode('<span class="separator">,</span>',$tag_array); ?>
	</div>
	<?php endif; ?>					
</div>

<!-- End K2 Item Layout -->
