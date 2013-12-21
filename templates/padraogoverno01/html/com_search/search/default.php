<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
require __DIR__.'/_helper.php';
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
// echo '<pre>';
// var_dump($this->lists['searchphrase']);
// die();
?>

<div class="search<?php echo $this->pageclass_sfx; ?>">
<h1 class="documentFirstHeading">
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
</h1>


<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">

	<div class="row-fluid">
		<div class="span9">
			<div class="row-fluid">
				<fieldset class="word">
					<legend>
						<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
					</legend>

					<label for="search-searchword" class="hide">
						<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
					</label>
					
					<div class="input-append">
		              <input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="input-xxlarge">
		              <button type="button" onclick="this.form.submit()" class="btn"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
		            </div>
				
					<input type="hidden" name="task" value="search" />
				</fieldset>

				<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
					<?php if (!empty($this->searchword)):?>
					<p class="description"><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
					<?php endif;?>
				</div>

				<?php if ($this->error==null && count($this->results) > 0) :
					echo $this->loadTemplate('results');
				else :
					echo $this->loadTemplate('error');
				endif; ?>
		
			</div>
		</div>
		<div class="span3">
			
			<fieldset class="fieldset-ordering">
				<legend><?php echo JText::_('COM_SEARCH_ORDERING');?></legend>
				<label for="ordering" class="ordering hide">
					<?php echo JText::_('COM_SEARCH_ORDERING');?>
				</label>
				<?php echo $this->lists['ordering'];?>
			</fieldset>

			<fieldset class="phrases">
				<legend><?php echo JText::_('COM_SEARCH_FOR');?></legend>
				<div class="phrases-box">
				<?php TemplateSearchHelper::displaySearchPhrase(); ?>
				</div>
			</fieldset>	

			<?php if ($this->params->get('search_areas', 1)) : ?>
				<fieldset class="only">
				<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
				<?php TemplateSearchHelper::displaySearchOnly( $this->searchareas ); ?>				
				</fieldset>
			<?php endif; ?>	

			<br />
			<p class="pull-right">
				<button name="Search" onclick="this.form.submit()" class="btn"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>			
			</p>
			
			<?php if ($this->total > 0) : ?>
			<fieldset class="fieldset-limitbox">
				<legend><?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?></legend>
				<label for="limit" class="hide">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>			
			</fieldset>
			<?php endif; ?>

		</div>
	</div>

	<?php if ($this->total > 0) : ?>
		<br />
		<div class="row-fluid">
			<div class="span9">				
				<div class="row-fluid">
					<div class="pagination text-center">
						<?php echo $this->pagination->getPagesLinks(); ?>
					</div>
				</div>
				<div class="row-fluid text-center">
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
				</div>			
			</div>
		</div>					
	<?php endif; ?>

</form>
</div>