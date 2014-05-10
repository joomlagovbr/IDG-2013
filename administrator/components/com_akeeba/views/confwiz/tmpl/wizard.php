<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
?>

<!-- jQuery & jQuery UI detection. Also shows a big, fat warning if they're missing -->
<div id="nojquerywarning" style="margin: 1em; padding: 1em; background: #ffff00; border: thick solid red; color: black; font-size: 14pt;">
	<h1 style="margin: 1em 0; color: red; font-size: 22pt;"><?php echo JText::_('AKEEBA_CPANEL_WARN_ERROR') ?></h1>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L1B'); ?></p>
	<p><?php echo JText::_('AKEEBA_CPANEL_WARN_JQ_L2'); ?></p>
</div>
<script type="text/javascript" language="javascript">
	if(typeof akeeba.jQuery == 'function')
	{
		if(typeof akeeba.jQuery.ui == 'object')
		{
			akeeba.jQuery('#nojquerywarning').css('display','none');
			akeeba.jQuery('#notfixedperms').css('display','none');
		}
	}
</script>

<div id="akeeba-confwiz">

<div id="backup-progress-pane" class="ui-widget" style="x-display: none">
	<div class="alert alert-info">
			<?php echo JText::_('AKEEBA_WIZARD_INTROTEXT'); ?>
	</div>
	
	<fieldset id="backup-progress-header">
		<legend><?php echo JText::_('AKEEEBA_WIZARD_PROGRESS') ?></legend>
		<div id="backup-progress-content">
			<div id="backup-steps">
				<div id="step-ajax" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_AJAX'); ?></div>
				<div id="step-minexec" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_MINEXEC'); ?></div>
				<div id="step-directory" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_DIRECTORY'); ?></div>
				<div id="step-dbopt" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_DBOPT'); ?></div>
				<div id="step-maxexec" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_MAXEXEC'); ?></div>
				<div id="step-splitsize" class="label"><?php echo JText::_('AKEEBA_CONFWIZ_SPLITSIZE'); ?></div>
			</div>
			<div class="well">
				<div id="backup-substep"></div>
			</div>
		</div>
		<span id="ajax-worker"></span>
	</fieldset>
	
</div>

<div id="error-panel" class="alert alert-error alert-block" style="display:none">
	<h2 class="alert-heading"><?php echo JText::_('AKEEBA_WIZARD_HEADER_FAILED'); ?></h2>
	<div id="errorframe">
		<p id="backup-error-message">
		TEST ERROR MESSAGE
		</p>
	</div>
</div>

<div id="backup-complete" style="display: none">
	<div class="alert alert-success alert-block">
		<h2 class="alert-heading"><?php echo JText::_('AKEEBA_WIZARD_HEADER_FINISHED'); ?></h2>
		<div id="finishedframe">
			<p>
				<?php echo JText::_('AKEEBA_WIZARD_CONGRATS') ?>
			</p>
		</div>
		<button class="btn btn-primary btn-large" onclick="window.location='<?php echo JURI::base() ?>index.php?option=com_akeeba&view=backup'; return false;">
			<i class="icon-road icon-white"></i>
			<?php echo JText::_('BACKUP'); ?>
		</button>
		<button class="btn" onclick="window.location='<?php echo JURI::base() ?>index.php?option=com_akeeba&view=config'; return false;">
			<i class="icon-wrench"></i>
			<?php echo JText::_('CONFIGURATION'); ?>
		</button>
	</div>

</div>

</div>

<script type="text/javascript" language="javascript">
akeeba_ajax_url = 'index.php?option=com_akeeba&view=confwiz&task=ajax';
<?php
	$keys = array('tryajax','tryiframe','cantuseajax','minexectry','cantsaveminexec','saveminexec','cantdetermineminexec',
		'cantfixdirectories','cantdbopt','exectoolow','savingmaxexec','cantsavemaxexec','cantdeterminepartsize','partsize');
	foreach($keys as $key):
?>
akeeba_translations['UI-<?php echo strtoupper($key)?>']="<?php echo JText::_('AKEEBA_WIZARD_UI_'.strtoupper($key)) ?>";
<?php endforeach; ?>
akeeba_confwiz_boot();
</script>