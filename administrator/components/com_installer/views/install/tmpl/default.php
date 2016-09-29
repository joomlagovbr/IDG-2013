<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// MooTools is loaded for B/C for extensions generating JavaScript in their install scripts, this call will be removed at 4.0
JHtml::_('behavior.framework', true);
JHtml::_('bootstrap.tooltip');

JFactory::getDocument()->addScriptDeclaration(
	'
	Joomla.submitbutton4 = function() {
		var form = document.getElementById("adminForm");

		// do field validation
		if (form.install_url.value == "" || form.install_url.value == "http://" || form.install_url.value == "https://") {
			alert("' . JText::_('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL', true) . '");
		}
		else
		{
			jQuery("#loading").css("display", "block");
			
			form.installtype.value = "url";
			form.submit();
		}
	};

	Joomla.submitbuttonInstallWebInstaller = function() {
		var form = document.getElementById("adminForm");
		
		form.install_url.value = "https://appscdn.joomla.org/webapps/jedapps/webinstaller.xml";
		
		Joomla.submitbutton4();
	};

	// Add spindle-wheel for installations:
	jQuery(document).ready(function($) {
		var outerDiv = $("#installer-install");
		
		$("#loading")
		.css("top", outerDiv.position().top - $(window).scrollTop())
		.css("left", "0")
		.css("width", "100%")
		.css("height", "100%")
		.css("display", "none")
		.css("margin-top", "-10px");
	});
	'
);

JFactory::getDocument()->addStyleDeclaration(
	'
	#loading {
		background: rgba(255, 255, 255, .8) url(\'' . JHtml::_('image', 'jui/ajax-loader.gif', '', null, true, true) . '\') 50% 15% no-repeat;
		position: fixed;
		opacity: 0.8;
		-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity = 80);
		filter: alpha(opacity = 80);
		overflow: hidden;
	}
	
	.j-jed-message {
		margin-bottom: 40px;
		line-height: 2em;
		color:#333333;
	}
	'
);

?>

<script type="text/javascript">
	// Set the first tab to active if there is no other active tab
	jQuery(document).ready(function($) {
		var hasTab = function(href){
			return $('a[data-toggle="tab"]a[href*="' + href + '"]').length;
		};
		if (!hasTab(localStorage.getItem('tab-href')))
		{
			var tabAnchor = $("#myTabTabs li:first a");
			window.localStorage.setItem('tab-href', tabAnchor.attr('href'));
			tabAnchor.click();
		}
	});
</script>

<div id="installer-install" class="clearfix">
	<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_installer&view=install'); ?>"
		method="post" name="adminForm" id="adminForm" class="form-horizontal">
		<?php if (!empty($this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
			<?php else : ?>
			<div id="j-main-container">
				<?php endif; ?>
				<!-- Render messages set by extension install scripts here -->
				<?php if ($this->showMessage) : ?>
					<?php echo $this->loadTemplate('message'); ?>
				<?php elseif ($this->showJedAndWebInstaller) : ?>
					<div class="alert alert-info j-jed-message"
						style="margin-bottom: 40px; line-height: 2em; color:#333333;">
						<?php echo JHtml::_(
							'link',
							JRoute::_('index.php?option=com_config&view=component&component=com_installer&path=&return=' . urlencode(base64_encode(JUri::getInstance()))),
							'',
							'class="alert-options hasTooltip icon-options" data-dismiss="alert" title="' . str_replace('"', '&quot;', JText::_('COM_INSTALLER_SHOW_JED_INFORMATION_TOOLTIP')) . '"'
						);
						?>
						<p><?php echo JText::_('COM_INSTALLER_INSTALL_FROM_WEB_INFO'); ?>
							<?php echo JText::_('COM_INSTALLER_INSTALL_FROM_WEB_TOS'); ?></p>
						<input class="btn" type="button"
							value="<?php echo JText::_('COM_INSTALLER_INSTALL_FROM_WEB_ADD_TAB'); ?>"
							onclick="Joomla.submitbuttonInstallWebInstaller()"/>
					</div>
				<?php endif; ?>
				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab'); ?>
				<?php // Show installation tabs at the start ?>
				<?php $firstTab = JEventDispatcher::getInstance()->trigger('onInstallerViewBeforeFirstTab', array()); ?>
				<?php // Show installation tabs ?>
				<?php $tabs = JEventDispatcher::getInstance()->trigger('onInstallerAddInstallationTab', array()); ?>
				<?php foreach ($tabs as $tab) : ?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', $tab['name'], $tab['label']); ?>
					<fieldset class="uploadform">
						<?php echo $tab['content']; ?>
					</fieldset>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
				<?php endforeach; ?>
				<?php // Show installation tabs at the end ?>
				<?php $lastTab = JEventDispatcher::getInstance()->trigger('onInstallerViewAfterLastTab', array()); ?>
				<?php $tabs = array_merge($firstTab, $tabs, $lastTab); ?>
				<?php if (!$tabs) : ?>
					<?php JFactory::getApplication()->enqueueMessage(JText::_('COM_INSTALLER_NO_INSTALLATION_PLUGINS_FOUND'), 'warning'); ?>
				<?php endif; ?>

				<?php if ($this->ftp) : ?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'ftp', JText::_('COM_INSTALLER_MSG_DESCFTPTITLE')); ?>
					<?php echo $this->loadTemplate('ftp'); ?>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
				<?php endif; ?>

				<input type="hidden" name="installtype" value=""/>
				<input type="hidden" name="task" value="install.install"/>
				<?php echo JHtml::_('form.token'); ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			</div>
	</form>
</div>
<div id="loading"></div>
