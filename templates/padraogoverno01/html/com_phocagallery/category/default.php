<?php
defined('_JEXEC') or die('Restricted access');
require JPATH_SITE .'/templates/padraogoverno01/html/com_phocagallery/_helper.php';
//removendo css,js e custom html do componente:
TmplPhocagalleryHelper::removeCss(array('phocagallery.css','modal.css'));
TmplPhocagalleryHelper::removeJs(array('mootools-core-uncompressed.js','mootools-more.js','core-uncompressed.js','modal-uncompressed.js','modal.js','$$(\'a.pg-modal-button\')'));
TmplPhocagalleryHelper::removeCustom(array('#phocagallery','#sbox-window', 'phocagalleryieall.css', '.phocagallery-box-file'));
//incluindo campos que nao vem por padrao na consulta
$this->category = TmplPhocagalleryHelper::getExtrafields($this->category, array('date'));
?>
<div id="phocagallery" class="pg-category-view<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if ($this->params->get( 'page_heading' ) != '') : ?>
	<span class="documentCategory"><?php echo $this->params->get( 'page_heading' ); ?></span>
<?php endif; ?>

<h1 class="secondaryHeading"><?php echo $this->category->title; ?></h1>

<div class="subtitle pg-category-view-desc<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php if (isset($this->category->description) && $this->category->description != '' ): ?>
		<?php echo JHTML::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
</div>
<div class="content-header-options-1 row-fluid">
	<div class="documentByLine span7">
		<ul>
			<li class="documentCreated">
				<?php echo 'Criado em: ' . JHtml::_('date', $this->category->date, JText::_('DATE_FORMAT_LC2')); ?>
			</li>

		</ul>
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
	<?php
		endif;
	 ?>
</div>
<?php

$this->checkRights = 1;


if ((int)$this->tagId > 0) {
	// Search by tags
	$this->checkRights = 1;

	// Categories View in Category View
	// if ($this->tmpl['displaycategoriescv']) {
	// 	echo $this->loadTemplate('categories');
	// }

	echo $this->loadTemplate('images');
	echo "<a href=\"javascript:history.back()\">&lt; voltar</a>";
	echo $this->loadTemplate('pagination');
	echo '</div>'. "\n";

} else {
	// Standard category displaying
	$this->checkRights = 0;


	// Categories View in Category View
	// if ($this->tmpl['displaycategoriescv']) {
		// echo $this->loadTemplate('categories');
	// }

	// Rendering images
	echo $this->loadTemplate('images');
	echo "<a href=\"javascript:history.back()\" title=\"Voltar à página anterior\">&lt; voltar</a>";
	echo $this->loadTemplate('pagination');

}
?>
</div>
