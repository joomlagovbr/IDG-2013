<?php
/**
 * @package Akeeba
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

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
		}
	}
</script>

<div class="akeeba-bootstrap" id="ftpdialog" title="<?php echo JText::_('CONFIG_UI_FTPBROWSER_TITLE') ?>" style="display:none;">
	<p class="instructions alert alert-info">
		<button class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('FTPBROWSER_LBL_INSTRUCTIONS'); ?>
	</p>
	<div class="error alert alert-error" id="ftpBrowserErrorContainer">
		<button class="close" data-dismiss="alert">×</button>
		<h2><?php echo JText::_('FTPBROWSER_LBL_ERROR'); ?></h2>
		<p id="ftpBrowserError"></p>
	</div>
	<ul id="ak_crumbs" class="breadcrumb"></ul>
	<div class="row-fluid">
		<div class="span12">
			<table id="ftpBrowserFolderList" class="table table-striped">
			</table>
		</div>
	</div>
</div>

<div class="akeeba-bootstrap" id="sftpdialog" title="<?php echo JText::_('CONFIG_UI_SFTPBROWSER_TITLE') ?>" style="display:none;">
	<p class="instructions alert alert-info">
		<button class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('SFTPBROWSER_LBL_INSTRUCTIONS'); ?>
	</p>
	<div class="error alert alert-error" id="sftpBrowserErrorContainer">
		<button class="close" data-dismiss="alert">×</button>
		<h2><?php echo JText::_('SFTPBROWSER_LBL_ERROR'); ?></h2>
		<p id="sftpBrowserError"></p>
	</div>
	<ul id="ak_scrumbs" class="breadcrumb"></ul>
	<div class="row-fluid">
		<div class="span12">
			<table id="sftpBrowserFolderList" class="table table-striped">
			</table>
		</div>
	</div>
</div>

<form name="adminForm" id="adminForm" method="post" action="index.php" class="form-horizontal form-horizontal-wide">

<div id="dialog" title="<?php echo JText::_('CONFIG_UI_BROWSER_TITLE') ?>">
</div>

<div >
	<?php if($this->securesettings == 1): ?>
	<div class="alert alert-success">
		<button class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('CONFIG_UI_SETTINGS_SECURED'); ?>
	</div>
	<div class="ak_clr"></div>
	<?php elseif($this->securesettings == 0): ?>
	<div class="alert alert-error">
		<button class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('CONFIG_UI_SETTINGS_NOTSECURED'); ?>
	</div>
	<div class="ak_clr"></div>
	<?php endif; ?>
	
	<div class="alert alert-info">
		<button class="close" data-dismiss="alert">×</button>
		<strong><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?></strong>:
		#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
	</div>
	
	<div class="alert">
		<button class="close" data-dismiss="alert">×</button>
		<?php echo JText::_('CONFIG_WHERE_ARE_THE_FILTERS'); ?>
	</div>
	
</div>
	
<input type="hidden" name="option" value="com_akeeba" />
<input type="hidden" name="view" value="config" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />

<!-- This div contains dynamically generated user interface elements -->
<div id="akeebagui">
</div>

</form>
<script type="text/javascript" language="javascript">
	// Callback routine to close the browser dialog
	var akeeba_browser_callback = null;

	// Hook for DirectFTP connection test
	var directftp_test_connection = null;
	
	var directsftp_test_connection = null;

	<?php if(defined('AKEEBA_PRO')): ?>
	// Hook for Upload to Remote FTP connection test
	var postprocftp_test_connection = null;
	<?php endif; ?>

	// Some stuff for the FTP browser...
	var akeeba_directftp_init_browser = null;
	var akeeba_postprocftp_init_browser = null;

	var akeeba_ftpbrowser_hook = null;
	var akeeba_sftpbrowser_hook = null;

	var akeeba_ftpbrowser_host = null;
	var akeeba_ftpbrowser_port = 21;
	var akeeba_ftpbrowser_username = null;
	var akeeba_ftpbrowser_password = null;
	var akeeba_ftpbrowser_passive = 1;
	var akeeba_ftpbrowser_ssl = 0;
	var akeeba_ftpbrowser_directory = '';

	var akeeba_sftpbrowser_host = null;
	var akeeba_sftpbrowser_port = 21;
	var akeeba_sftpbrowser_username = null;
	var akeeba_sftpbrowser_password = null;
	var akeeba_sftpbrowser_pubkey = null;
	var akeeba_sftpbrowser_privkey = null;
	var akeeba_sftpbrowser_directory = '';

	akeeba.jQuery(document).ready(function($){
		// Push some translations
		akeeba_translations['UI-BROWSE'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_BROWSE')) ?>';
		akeeba_translations['UI-CONFIG'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_CONFIG')) ?>';
		akeeba_translations['UI-REFRESH'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_REFRESH')) ?>';

		// Load the configuration UI data in a way that doesn't let Safari screw up password fields
		akeeba_ui_theme_root = '<?php echo $this->mediadir ?>';
		var data = JSON.parse("<?php echo $this->json; ?>");

		setTimeout(function(){
			parse_config_data(data);

			// Enable popovers
			akeeba.jQuery('[rel="popover"]').popover({
				trigger: 'manual',
				animate: false,
				html: true,
				placement: 'bottom',
				template: '<div class="popover akeeba-bootstrap-popover" onmouseover="akeeba.jQuery(this).mouseleave(function() {akeeba.jQuery(this).hide(); });"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
			})
				.click(function(e) {
					e.preventDefault();
				})
				.mouseenter(function(e) {
					akeeba.jQuery('div.popover').remove();
					akeeba.jQuery(this).popover('show');
				});
		}, 10);

		// Create the dialog
		$("#dialog").dialog({
			autoOpen: false,
			closeOnEscape: false,
			height: 400,
			width: 640,
			hide: 'slide',
			modal: true,
			position: 'center',
			show: 'slide'
		});

		// Create an AJAX error trap
		akeeba_error_callback = function( message ) {
			var dialog_element = new Element('div');
			var dlgHead = new Element('h3');
			dlgHead.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
			dlgHead.inject(dialog_element);
			var dlgPara = new Element('p');
			dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?>');
			dlgPara.inject(dialog_element);
			var dlgPre = new Element('pre');
			dlgPre.set('html', message);
			dlgPre.inject(dialog_element);
			SqueezeBox.open(new Element(dialog_element), {
				handler:	'adopt',
				size:		{x: 600, y: 400}
			});
		};

		// Create the DirectFTP connection test hook
		directftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.archiver.directftp.ftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.archiver.directftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.archiver.directftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.archiver.directftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.archiver.directftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val();
			data['usessl'] = $(document.getElementById('var[engine.archiver.directftp.ftps]')).is(':checked');
			data['passive'] = $(document.getElementById('var[engine.archiver.directftp.passive_mode]')).is(':checked');

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testftp') ?>';
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.archiver.directftp.ftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				var dialog_element = new Element('div');
				
				var dlgHead = new Element('h3');
				dlgHead.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_DIALOG_TITLE')) ?>');
				dlgHead.inject(dialog_element);

				if( res === true )
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_OK')) ?>');
					dlgPara.inject(dialog_element);
				}
				else
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTFTP_TEST_FAIL')) ?>');
					dlgPara.inject(dialog_element);
					var dlgPara2 = new Element('p');
					dlgPara2.set('html', res);
					dlgPara2.inject(dialog_element);
				}
				SqueezeBox.open(new Element(dialog_element), {
					handler:	'adopt',
					size:		{x: 400, y: 200}
				});
			});
		}
		
		// Create the DirectSFTP connection test hook
		directsftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.archiver.directsftp.sftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.archiver.directsftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.archiver.directsftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.archiver.directsftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.archiver.directsftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.archiver.directsftp.initial_directory]')).val();

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testsftp') ?>';
			
			var dialog_element = new Element('div');
				
			var dlgHead = new Element('h3');
			dlgHead.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_DIALOG_TITLE')) ?>');
			dlgHead.inject(dialog_element);
			
			var dlgPara = new Element('p');
			dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_PLEASE_WAIT')) ?>');
			dlgPara.inject(dialog_element);
			
			SqueezeBox.open(new Element(dialog_element), {
				handler:	'adopt',
				size:		{x: 400, y: 200}
			});
			
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.archiver.directsftp.sftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				SqueezeBox.close();
				dialog_element.set('html', ''); // Clear the dialog's contents
				if( res === true )
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_OK')) ?>');
					dlgPara.inject(dialog_element);
				}
				else
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_DIRECTSFTP_TEST_FAIL')) ?>');
					dlgPara.inject(dialog_element);
					var dlgPara2 = new Element('p');
					dlgPara2.set('html', res);
					dlgPara2.inject(dialog_element);
				}
				SqueezeBox.open(new Element(dialog_element), {
					handler:	'adopt',
					size:		{x: 400, y: 200}
				});
			});
		}

<?php if(defined('AKEEBA_PRO')): ?>
		// Create the FTP upload post-proc engine test hook
		postprocftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.postproc.ftp.ftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.postproc.ftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.postproc.ftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.postproc.ftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.postproc.ftp.pass]')).val();
			data['initdir'] = $(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val();
			data['usessl'] = $(document.getElementById('var[engine.postproc.ftp.ftps]')).is(':checked');
			data['passive'] = $(document.getElementById('var[engine.postproc.ftp.passive_mode]')).is(':checked');

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testftp') ?>';
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.postproc.ftp.ftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				var dialog_element = new Element('div');
				
				var dlgHead = new Element('h3');
				dlgHead.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_DIALOG_TITLE')) ?>');
				dlgHead.inject(dialog_element);

				if( res === true )
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_OK')) ?>');
					dlgPara.inject(dialog_element);
				}
				else
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCFTP_TEST_FAIL')) ?>');
					dlgPara.inject(dialog_element);
					var dlgPara2 = new Element('p');
					dlgPara2.set('html', res);
					dlgPara2.inject(dialog_element);
				}
				SqueezeBox.open(new Element(dialog_element), {
					handler:	'adopt',
					size:		{x: 400, y: 200}
				});
			});
		}

		// Create the SFTP upload post-proc engine test hook
		postprocsftp_test_connection = function()
		{
			var button = $(document.getElementById('engine.postproc.sftp.sftp_test'));
			button.addClass('ui-state-disabled');
			button.removeClass('ui-state-default');

			// Get the values the user has entered
			var data = new Object();
			data['host'] = $(document.getElementById('var[engine.postproc.sftp.host]')).val();
			data['port'] = $(document.getElementById('var[engine.postproc.sftp.port]')).val();
			data['user'] = $(document.getElementById('var[engine.postproc.sftp.user]')).val();
			data['pass'] = $(document.getElementById('var[engine.postproc.sftp.pass]')).val();
			data['privkey'] = $(document.getElementById('var[engine.postproc.sftp.privkey]')).val();
			data['pubkey'] = $(document.getElementById('var[engine.postproc.sftp.pubkey]')).val();
			data['initdir'] = $(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val();

			// Construct the query
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS('index.php?option=com_akeeba&view=config&task=testsftp') ?>';
			doAjax(data, function(res){
				var button = $(document.getElementById('engine.postproc.sftp.sftp_test'));
				button.removeClass('ui-state-disabled');
				button.addClass('ui-state-default');

				var dialog_element = new Element('div');

				var dlgHead = new Element('h3');
				dlgHead.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCSFTP_TEST_DIALOG_TITLE')) ?>');
				dlgHead.inject(dialog_element);

				if( res === true )
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCSFTP_TEST_OK')) ?>');
					dlgPara.inject(dialog_element);
				}
				else
				{
					var dlgPara = new Element('p');
					dlgPara.set('html','<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_POSTPROCSFTP_TEST_FAIL')) ?>');
					dlgPara.inject(dialog_element);
					var dlgPara2 = new Element('p');
					dlgPara2.set('html', res);
					dlgPara2.inject(dialog_element);
				}
				SqueezeBox.open(new Element(dialog_element), {
					handler:	'adopt',
					size:		{x: 400, y: 200}
				});
			});
		}

		// Create the FTP Post-Processing browser directory loader hook
		akeeba_postprocftp_init_browser = function( )
		{
			akeeba_ftpbrowser_host = $(document.getElementById('var[engine.postproc.ftp.host]')).val();
			akeeba_ftpbrowser_port = $(document.getElementById('var[engine.postproc.ftp.port]')).val();
			akeeba_ftpbrowser_username = $(document.getElementById('var[engine.postproc.ftp.user]')).val();
			akeeba_ftpbrowser_password = $(document.getElementById('var[engine.postproc.ftp.pass]')).val();
			akeeba_ftpbrowser_passive = $(document.getElementById('var[engine.postproc.ftp.passive_mode]')).is(':checked');
			akeeba_ftpbrowser_ssl = $(document.getElementById('var[engine.postproc.ftp.ftps]')).is(':checked');
			akeeba_ftpbrowser_directory = $(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val();

			var akeeba_postprocftp_callback = function(path) {
				var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
			    var re = new RegExp('^[' + charlist + ']+', 'g');
			    path = '/' + (path+'').replace(re, '');
				$(document.getElementById('var[engine.postproc.ftp.initial_directory]')).val(path);
			}
			
			akeeba_ftpbrowser_hook( akeeba_postprocftp_callback );
		}

		// Create the SFTP Post-Processing browser directory loader hook
		akeeba_postprocsftp_init_browser = function( )
		{
			akeeba_sftpbrowser_host = $(document.getElementById('var[engine.postproc.sftp.host]')).val();
			akeeba_sftpbrowser_port = $(document.getElementById('var[engine.postproc.sftp.port]')).val();
			akeeba_sftpbrowser_username = $(document.getElementById('var[engine.postproc.sftp.user]')).val();
			akeeba_sftpbrowser_password = $(document.getElementById('var[engine.postproc.sftp.pass]')).val();
			akeeba_sftpbrowser_directory = $(document.getElementById('var[engine.postproc.sftp.initial_directory]')).val();
			akeeba_sftpbrowser_privkey = $(document.getElementById('var[engine.postproc.sftp.privkey]')).val();
			akeeba_sftpbrowser_pubkey = $(document.getElementById('var[engine.postproc.sftp.pubkey]')).val();

			var akeeba_postprocsftp_callback = function(path) {
				var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
			    var re = new RegExp('^[' + charlist + ']+', 'g');
			    path = '/' + (path+'').replace(re, '');
				$(document.getElementById('var[engine.postproc.sftp.initial_directory]')).val(path);
			}

			akeeba_sftpbrowser_hook( akeeba_postprocsftp_callback );
		}

<?php endif; ?>

		akeeba_browser_hook = function( folder, element )
		{
			// Close dialog callback (user confirmed the new folder)
			akeeba_browser_callback = function( myFolder ) {
				$(element).val( myFolder );
				SqueezeBox.close();
			};
			
			// URL to load the browser
			var browserSrc = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=browser&tmpl=component&processfolder=1&folder=') ?>';
			browserSrc = browserSrc + encodeURIComponent(folder);

			SqueezeBox.open(browserSrc, {
				handler:	'iframe',
				size:		{x: 600, y: 400}
			});
		};

		// Create the DirectFTP browser directory loader hook
		akeeba_directftp_init_browser = function( )
		{
			akeeba_ftpbrowser_host = $(document.getElementById('var[engine.archiver.directftp.host]')).val();
			akeeba_ftpbrowser_port = $(document.getElementById('var[engine.archiver.directftp.port]')).val();
			akeeba_ftpbrowser_username = $(document.getElementById('var[engine.archiver.directftp.user]')).val();
			akeeba_ftpbrowser_password = $(document.getElementById('var[engine.archiver.directftp.pass]')).val();
			akeeba_ftpbrowser_passive = $(document.getElementById('var[engine.archiver.directftp.passive_mode]')).is(':checked');
			akeeba_ftpbrowser_ssl = $(document.getElementById('var[engine.archiver.directftp.ftps]')).is(':checked');
			akeeba_ftpbrowser_directory = $(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val();

			var akeeba_directftp_callback = function(path) {
				var charlist = ('/').replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
			    var re = new RegExp('^[' + charlist + ']+', 'g');
			    path = '/' + (path+'').replace(re, '');
				$(document.getElementById('var[engine.archiver.directftp.initial_directory]')).val(path);
			}
			
			akeeba_ftpbrowser_hook( akeeba_directftp_callback );
		}

		// FTP browser function
		akeeba_ftpbrowser_hook = function( callback )
		{
			var ftp_dialog_element = $("#ftpdialog");
			var ftp_callback = function() {
				callback(akeeba_ftpbrowser_directory);
				ftp_dialog_element.dialog("close");
			};
			
			ftp_dialog_element.css('display','block');
			ftp_dialog_element.removeClass('ui-state-error');
			ftp_dialog_element.dialog({
				autoOpen	: false,
				'title'		: '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_FTPBROWSER_TITLE')) ?>',
				draggable	: false,
				height		: 500,
				width		: 500,
				modal		: true,
				resizable	: false,
				buttons		: {
					"OK": ftp_callback,
					"Cancel": function() {
						ftp_dialog_element.dialog("close");
					}
				}
			});

			$('#ftpBrowserErrorContainer').css('display','none');
			$('#ftpBrowserFolderList').html('');
			$('#ftpBrowserCrumbs').html('');

			ftp_dialog_element.dialog('open');
			
			// URL to load the browser
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=ftpbrowser' ) ?>';

			if(empty(akeeba_ftpbrowser_directory)) akeeba_ftpbrowser_directory = '';
			
			var data = {
				'host'		: akeeba_ftpbrowser_host,
				'username'	: akeeba_ftpbrowser_username,
				'password'	: akeeba_ftpbrowser_password,
				'passive'	: (akeeba_ftpbrowser_passive ? 1 : 0),
				'ssl'		: (akeeba_ftpbrowser_ssl ? 1 : 0),
				'directory'	: akeeba_ftpbrowser_directory
			};

			// Ugly, ugly, UGLY hack...
			//$.data($('#ftpdialog'), 'directory', akeeba_ftpbrowser_directory);

			// Do AJAX call & Render results
			doAjax(
				data,
				function(data) {
					if(data.error != false) {
						// An error occured
						$('#ftpBrowserError').html(data.error);
						$('#ftpBrowserErrorContainer').css('display','block');
						$('#ftpBrowserFolderList').css('display','none');
						$('#ak_crumbs').css('display','none');
					} else {
						// Create the interface
						$('#ftpBrowserErrorContainer').css('display','none');

						// Display the crumbs
						if(!empty(data.breadcrumbs)) {
							$('#ak_crumbs').css('display','block');
							$('#ak_crumbs').html('');
							var relativePath = '/';

							akeeba_ftpbrowser_addcrumb(akeeba_translations['UI-ROOT'], '/', callback);
														
							$.each(data.breadcrumbs, function(i, crumb) {
								relativePath += '/'+crumb;

								akeeba_ftpbrowser_addcrumb(crumb, relativePath, callback);
							});
						} else {
							$('#ftpBrowserCrumbs').css('display','none');
						}

						// Display the list of directories
						if(!empty(data.list)) {
							$('#ftpBrowserFolderList').css('display','block');
							//akeeba_ftpbrowser_directory = $.data($('#ftpdialog'), 'directory');
							//if(empty(akeeba_ftpbrowser_directory)) akeeba_ftpbrowser_directory = '';
							
							$.each(data.list, function(i, item) {
								akeeba_ftpbrowser_create_link(akeeba_ftpbrowser_directory+'/'+item, item, $('#ftpBrowserFolderList'), callback );
							});							
						} else {
							$('#ftpBrowserFolderList').css('display','none');
						}
					}
				},
				function(message) {
					$('#ftpBrowserError').html(message);
					$('#ftpBrowserErrorContainer').css('display','block');
					$('#ftpBrowserFolderList').css('display','none');
					$('#ftpBrowserCrumbs').css('display','none');
				},
				false
			);
		}

		/**
		 * Creates a directory link for the FTP browser UI
		 */
		function akeeba_ftpbrowser_create_link(path, label, container, callback)
		{
			var row = $(document.createElement('tr'));
			var cell = $(document.createElement('td')).appendTo(row);

			var myElement = $(document.createElement('a'))
				.text(label)
				.click(function(){
					akeeba_ftpbrowser_directory = path;
					akeeba_ftpbrowser_hook(callback);
				})
				.appendTo(cell);
			row.appendTo($(container));
		}

		/**
		 * Adds a breadcrumb to the FTP browser
		 */
		function akeeba_ftpbrowser_addcrumb(crumb, relativePath, callback, last)
		{
			if(empty(last)) last = false;
			var li = $(document.createElement('li'));
			
			$(document.createElement('a'))
				.html(crumb)
				.click(function(e){
					akeeba_ftpbrowser_directory = relativePath;
					akeeba_ftpbrowser_hook(callback);
					e.preventDefault();
				})
				.appendTo(li);
				
			if(!last) {
				$(document.createElement('span'))
					.text('/')
					.addClass('divider')
					.appendTo(li);
			}
				
			li.appendTo('#ak_crumbs');
		}
		
		// FTP browser function
		akeeba_sftpbrowser_hook = function( callback )
		{
			var sftp_dialog_element = $("#sftpdialog");
			var sftp_callback = function() {
				callback(akeeba_sftpbrowser_directory);
				sftp_dialog_element.dialog("close");
			};

			sftp_dialog_element.css('display','block');
			sftp_dialog_element.removeClass('ui-state-error');
			sftp_dialog_element.dialog({
				autoOpen	: false,
				'title'		: '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_SFTPBROWSER_TITLE')) ?>',
				draggable	: false,
				height		: 500,
				width		: 500,
				modal		: true,
				resizable	: false,
				buttons		: {
					"OK": sftp_callback,
					"Cancel": function() {
						sftp_dialog_element.dialog("close");
					}
				}
			});

			$('#sftpBrowserErrorContainer').css('display','none');
			$('#sftpBrowserFolderList').html('');
			$('#sftpBrowserCrumbs').html('');

			sftp_dialog_element.dialog('open');

			// URL to load the browser
			akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=sftpbrowser' ) ?>';

			if(empty(akeeba_sftpbrowser_directory)) akeeba_sftpbrowser_directory = '';

			var data = {
				'host'		: akeeba_sftpbrowser_host,
				'port'		: akeeba_sftpbrowser_port,
				'username'	: akeeba_sftpbrowser_username,
				'password'	: akeeba_sftpbrowser_password,
				'pubkey'	: akeeba_sftpbrowser_pubkey,
				'privkey'	: akeeba_sftpbrowser_privkey,
				'directory'	: akeeba_sftpbrowser_directory
			};

			// Ugly, ugly, UGLY hack...
			//$.data($('#sftpdialog'), 'directory', akeeba_sftpbrowser_directory);

			// Do AJAX call & Render results
			doAjax(
				data,
				function(data) {
					if(data.error != false) {
						// An error occured
						$('#sftpBrowserError').html(data.error);
						$('#sftpBrowserErrorContainer').css('display','block');
						$('#sftpBrowserFolderList').css('display','none');
						$('#ak_scrumbs').css('display','none');
					} else {
						// Create the interface
						$('#sftpBrowserErrorContainer').css('display','none');

						// Display the crumbs
						if(!empty(data.breadcrumbs)) {
							$('#ak_scrumbs').css('display','block');
							$('#ak_scrumbs').html('');
							var relativePath = '/';

							akeeba_sftpbrowser_addcrumb(akeeba_translations['UI-ROOT'], '/', callback);

							$.each(data.breadcrumbs, function(i, crumb) {
								relativePath += '/'+crumb;

								akeeba_sftpbrowser_addcrumb(crumb, relativePath, callback);
							});
						} else {
							$('#sftpBrowserCrumbs').css('display','none');
						}

						// Display the list of directories
						if(!empty(data.list)) {
							$('#sftpBrowserFolderList').css('display','block');
							//akeeba_sftpbrowser_directory = $.data($('#sftpdialog'), 'directory');
							//if(empty(akeeba_sftpbrowser_directory)) akeeba_sftpbrowser_directory = '';

							$.each(data.list, function(i, item) {
								akeeba_sftpbrowser_create_link(akeeba_sftpbrowser_directory+'/'+item, item, $('#sftpBrowserFolderList'), callback );
							});
						} else {
							$('#sftpBrowserFolderList').css('display','none');
						}
					}
				},
				function(message) {
					$('#sftpBrowserError').html(message);
					$('#sftpBrowserErrorContainer').css('display','block');
					$('#sftpBrowserFolderList').css('display','none');
					$('#sftpBrowserCrumbs').css('display','none');
				},
				false
			);
		}

		/**
		 * Creates a directory link for the SFTP browser UI
		 */
		function akeeba_sftpbrowser_create_link(path, label, container, callback)
		{
			var row = $(document.createElement('tr'));
			var cell = $(document.createElement('td')).appendTo(row);

			var myElement = $(document.createElement('a'))
				.text(label)
				.click(function(){
					akeeba_sftpbrowser_directory = path;
					akeeba_sftpbrowser_hook(callback);
				})
				.appendTo(cell);
			row.appendTo($(container));
		}

		/**
		 * Adds a breadcrumb to the SFTP browser
		 */
		function akeeba_sftpbrowser_addcrumb(crumb, relativePath, callback, last)
		{
			if(empty(last)) last = false;
			var li = $(document.createElement('li'));

			$(document.createElement('a'))
				.html(crumb)
				.click(function(e){
					akeeba_sftpbrowser_directory = relativePath;
					akeeba_sftpbrowser_hook(callback);
					e.preventDefault();
				})
				.appendTo(li);

			if(!last) {
				$(document.createElement('span'))
					.text('/')
					.addClass('divider')
					.appendTo(li);
			}

			li.appendTo('#ak_scrumbs');
		}

		// Enable popovers
		akeeba.jQuery('[rel="popover"]').popover({
			trigger: 'manual',
			animate: false,
			html: true,
			placement: 'bottom',
			template: '<div class="popover akeeba-bootstrap-popover" onmouseover="akeeba.jQuery(this).mouseleave(function() {akeeba.jQuery(this).hide(); });"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'
		})
		.click(function(e) {
			e.preventDefault();
		})
		.mouseenter(function(e) {
			akeeba.jQuery('div.popover').remove();
			akeeba.jQuery(this).popover('show');
		});

	});
</script>