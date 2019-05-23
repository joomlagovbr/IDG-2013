<?php defined('_JEXEC') or die('Restricted access');
require JPATH_SITE .'/templates/padraogoverno01/html/com_phocagallery/_helper.php';
TmplPhocagalleryHelper::removeCss(array('phocagallery.css'));
TmplPhocagalleryHelper::removeCustom(array('.contentpane'));

$jinput = JFactory::getApplication()->input;
$tmpl = $jinput->get('tmpl', '', 'command');

if($tmpl=='component')
	TmplPhocagalleryHelper::addCss( JURI::root() . 'templates/padraogoverno01/html/com_phocagallery/detail/default.css');
else
{
	TmplPhocagalleryHelper::removeJs(array('jquery-1.6.4.min.js'));
}
echo '<div id="phocagallery" class="pg-detail-view'.$this->params->get( 'pageclass_sfx' ).'">';

switch ($this->tmpl['detailwindow']) {
	case 4:
	case 7:
	case 9:
	case 10:
		$closeImage 	= $this->item->linkimage;
		$closeButton 	= '';
	break;
	default:
		$closeButton 	= '';
		$closeImage 	= '<a class="main-image" href="#" target="_parent">'.$this->item->linkimage.'</a>';
	break;

}

if($tmpl=='component')
{
	echo $closeImage."<br>";
	echo '<div class="prevButton">'.$this->item->prevbutton."</div>";
	echo '<div class="nextButton">'.$this->item->nextbutton."</div>";
	
	if(!empty($this->item->title)):
		echo '<div class="hidden-title hide">';
		echo $this->item->title;
		echo '</div>';
	endif;

	if(!empty($this->item->description)):
		echo '<div class="hidden-description hide">';
		echo PhocaGalleryText::wordDelete($this->item->description, 190, '...');
		// echo JHtml::_('content.prepare', $this->item->description, 'com_phocagallery.item');
		echo '</div>';
	endif;

	echo '<script type="text/javascript">';
	echo 'jQuery(document).ready(function(){
		if(window.parent)
		{
			title = jQuery(".hidden-title").text();
			description = jQuery(".hidden-description").html();
			jQuery("#fulltext-modal .modal-header strong", window.parent.document).text(title);
			jQuery("#fulltext-modal .modal-footer .text", window.parent.document).html(description);
			jQuery(".main-image").click(function(){
				url = document.URL;
				url = url.replace("?tmpl=component","");
				url = url.replace("&tmpl=component","");
				url = url.replace("&amp;tmpl=component","");
				window.parent.document.location.href = url;
				return false;
			});
		}
	});';
	echo '</script>';
}
else
{
	$this->item = TmplPhocagalleryHelper::getPhotoExtraInfo($this->item);
	TmplPhocagalleryHelper::setPhotoBreadcrumb($this->item);

	echo '<span class="documentCategory">'.$this->item->cat_title.'</span>';
	echo '<h1 class="documentFirstHeading">'.$this->item->title.'</h1>';
	echo '<div class="content-header-options-1 row-fluid">
			<div class="documentByLine span7">
				<span class="documentCreated">
				'.'Envio em: ' . JHtml::_('date', $this->item->date, JText::_('DATE_FORMAT_LC2')).'
				</span>
				<span class="separator"> | </span>
				<span>
				<a href="javascript:history.back()" title="Voltar &agrave; p&aacute;gina anterior">Voltar &agrave; p&aacute;gina anterior</a>
				</span>
			</div>		
			<div class="btns-social-like span5 hide">';
			$modules = JModuleHelper::getModules( 'com_content-article-btns-social' );
			if(count($modules)): ?>		
				<?php foreach($modules as $module): ?>
					<?php $html = JModuleHelper::renderModule($module); ?>
					<?php $html = str_replace('{SITE}', JURI::root(), $html); ?>
					<?php echo $html; ?>
				<?php endforeach; ?>
			<?php endif;
	echo 	'</div>
		</div>';

	?>	


	<div class="row-fluid">
		<p class="pull-right"></p>
	</div>

	<div class="direct-image direct-image-horz">		
		<div class="caption-top">
			<?php if(!empty($this->item->metadesc)): ?>
			<?php echo $this->item->metadesc; ?>
			<?php else: ?>
			&nbsp;
			<?php endif; ?>
			<a title="<?php echo JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD') ?>" class="pull-right" href="<?php echo JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$this->item->catslug.'&id='.$this->item->slug. '&tmpl=component&phocadownload=1' ); ?>"><i class="icon-download-alt"><span class="hide"><?php echo JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD') ?></span></i></a>
		</div>		
		<div class="image-box">
			<?php echo str_replace('class="', 'class="img-polaroid img-fulltext-left caption ', $this->item->linkimage); ?>			
		</div>
	</div>

	<?php
	if(!empty($this->item->description))
	{
	echo $this->item->description;	
	}
	?>
	<br>
	<ul>
		<li>
			<a title="<?php echo JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD') ?>" href="<?php echo JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$this->item->catslug.'&id='.$this->item->slug. '&tmpl=component&phocadownload=1' ); ?>"><?php echo JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD') ?></a>
		</li>
	</ul>
	<div class="below-content">
		<div class="line">
			registrado em:
			<span><a href="<?php echo $this->item->cat_link ?>" rel="tag" class="link-categoria"><?php echo $this->item->cat_title; ?></a></span>
		</div>
		<?php if(!empty($this->item->metakey)): ?>
		<div class="line">
		Assunto(s):
		<?php TmplPhocagalleryHelper::displayMetakeyLinks($this->item->metakey); ?>	
		</div>
		<?php endif; ?>				
	</div>
	<?php
}

echo '</div>';
?>