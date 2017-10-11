<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();

// forçando o default para "exact"
$searchphrases         = array();
$searchphrases[]       = JHtml::_('select.option', 'all', JText::_('COM_SEARCH_ALL_WORDS'));
$searchphrases[]       = JHtml::_('select.option', 'any', JText::_('COM_SEARCH_ANY_WORDS'));
$searchphrases[]       = JHtml::_('select.option', 'exact', JText::_('COM_SEARCH_EXACT_PHRASE'));
$this->lists['searchphrase'] = JHtml::_('select.radiolist', $searchphrases, 'searchphrase', '', 'value', 'text', 'exact');

?>
<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
	<div class="row-fluid">
		<div class="span10">
			<fieldset class="filters alert alert-info">

				<?php if ($this->params->get('filter_field') != 'hide') :?>
					<div class="row-busca-f">
						<input type="text" name="searchword" placeholder="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" id="search-searchword" size="100" maxlength="" value="<?php echo $this->origkeyword; ?>" class="span8 input-busca" />

						<button name="Search" onclick="this.form.submit()" class="acao-busca button btn btn-primary hasTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_SEARCH_SEARCH');?>">
							<span class="icon-search"></span>&nbsp;<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
						</button>
						<button class="acao-busca button btn btn-warning" onclick="document.getElementById('search-searchword').value='';this.form.submit();">
							<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
						</button>
					</div>
				<?php endif; ?>

				<input type="hidden" name="task" value="search" />
				<div class="clearfix"></div>
			</fieldset>


		<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
			<?php if (!empty($this->searchword)) : ?>
				<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="badge badge-info">' . $this->total . '</span>'); ?></p>
			<?php endif; ?>
		</div>

		<?php if ($this->error == null && count($this->results) > 0) :
			echo $this->loadTemplate('results');
		else :
			echo $this->loadTemplate('error');
		endif; ?>

		</div>


		<div class="span2">
			<?php if ($this->params->get('search_phrases', 3)) : ?>
				<fieldset class="phrases">
					<legend>
						<?php echo JText::_('COM_SEARCH_FOR'); ?>
					</legend>
						<div class="phrases-box">
							<?php echo $this->lists['searchphrase']; ?>
						</div>
						<div class="ordering-box">
							<label for="ordering" class="ordering">
								<?php echo JText::_('COM_SEARCH_ORDERING'); ?>
							</label>
							<?php echo $this->lists['ordering']; ?>
						</div>
				</fieldset>
			<?php endif; ?>

			<?php if ($this->params->get('search_areas', 1)) : ?>
				<fieldset class="only">
					<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY'); ?></legend>
					<?php foreach ($this->searchareas['search'] as $val => $txt) :
						$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
					?>
					<label for="area-<?php echo $val; ?>" class="checkbox">
						<input type="checkbox" name="areas[]" value="<?php echo $val; ?>" id="area-<?php echo $val; ?>" <?php echo $checked; ?> >
						<?php echo JText::_($txt); ?>
					</label>
					<?php endforeach; ?>
				</fieldset>
			<?php endif; ?>

			<?php if ($this->total > 0) : ?>

				<div class="form-limit">
					<label for="limit">
						<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>

			<?php endif; ?>
		</div>
		<!-- fim div 2 -->
	</div>
	<!-- fim row busca -->
</form>
