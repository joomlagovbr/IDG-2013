<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

defined('_JEXEC') or die();

JHtml::_('behavior.framework');
?>
<div id="dialog" title="<?php echo JText::_('FSFILTER_ERROR_TITLE') ?>">
</div>

<div class="alert alert-info">
	<strong><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?></strong>
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<div class="form-inline well">
	<div>
		<label><?php echo JText::_('FSFILTER_LABEL_ROOTDIR') ?></label>
		<span><?php echo $this->root_select; ?></span>
		<button class="btn btn-danger" onclick="fsfilter_nuke(); return false;">
			<i class="icon-fire icon-white"></i>
			<?php echo JText::_('FSFILTER_LABEL_NUKEFILTERS'); ?>
		</button>
		<a class="btn btn-small" href="index.php?option=com_akeeba&view=fsfilter&task=tabular">
			<i class="icon-list"></i>
			<?php echo JText::_('FILTERS_LABEL_VIEWALL')?>
		</a>
	</div>
</div>
	
<div id="ak_crumbs_container" class="row-fluid">
	<ul id="ak_crumbs" class="breadcrumb"></ul>
</div>


<div id="ak_main_container">
	<fieldset id="ak_folder_container">
		<legend><?php echo JText::_('FSFILTER_LABEL_DIRS'); ?></legend>
		<div id="folders"></div>
	</fieldset>

	<fieldset id="ak_files_container">
		<legend><?php echo JText::_('FSFILTER_LABEL_FILES'); ?></legend>
		<div id="files"></div>
	</fieldset>
</div>

<script type="text/javascript" language="javascript">
/**
 * Callback function for changing the active root in Filesystem Filters
 */
function akeeba_active_root_changed()
{
	(function($){
		var data = new Object;
		data.root = $('#active_root').val();
		data.crumbs = new Array();
		data.node = '';
		fsfilter_load(data);
	})(akeeba.jQuery);
}

akeeba.jQuery(document).ready(function($){
	// Set the AJAX proxy URL
	akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=fsfilter&task=ajax') ?>';
	// Set the media root
	akeeba_ui_theme_root = '<?php echo $this->mediadir ?>';
	// Create the dialog
	$("#dialog").dialog({
		autoOpen: false,
		closeOnEscape: false,
		height: 200,
		width: 300,
		hide: 'slide',
		modal: true,
		position: 'center',
		show: 'slide'
	});
	// Create an AJAX error trap
	akeeba_error_callback = function( message ) {
		var dialog_element = $("#dialog");
		dialog_element.html(''); // Clear the dialog's contents
		dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
		$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?>').appendTo(dialog_element);
		$(document.createElement('pre')).html( message ).appendTo(dialog_element);
		dialog_element.dialog('open');
	};
	// Push translations
	akeeba_translations['UI-ROOT'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('FILTERS_LABEL_UIROOT')) ?>';
	akeeba_translations['UI-ERROR-FILTER'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('FILTERS_LABEL_UIERRORFILTER')) ?>';
<?php
	$filters = array('directories', 'skipfiles', 'skipdirs', 'files');
	foreach($filters as $type)
	{
		echo "\takeeba_translations['UI-FILTERTYPE-".strtoupper($type)."'] = '".
			AkeebaHelperEscape::escapeJS(JText::_('FSFILTER_TYPE_'.strtoupper($type))).
			"';\n";
	}
?>
	// Bootstrap the page display
	var data = eval(<?php echo AkeebaHelperEscape::escapeJS($this->json,"'"); ?>);
	fsfilter_render(data);
});
</script>