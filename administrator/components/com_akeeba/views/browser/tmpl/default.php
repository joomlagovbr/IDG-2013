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

$rootDirWarning = AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_ROOTDIR'));
JFactory::getDocument()->addScriptDeclaration(<<<ENDJS
	function akeeba_browser_useThis()
	{
		var rawFolder = document.forms.adminForm.folderraw.value;
		if( rawFolder == '[SITEROOT]' )
		{
			alert('$rootDirWarning');
			rawFolder = '[SITETMP]';
		}
		window.parent.akeeba_browser_callback( rawFolder );
	}
ENDJS
, 'text/javascript');

?>
<?php if(empty($this->folder)): ?>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		<input type="hidden" name="option" value="com_akeeba" />
		<input type="hidden" name="view" value="browser" />
		<input type="hidden" name="format" value="html" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="folder" id="folder" value="" />
		<input type="hidden" name="processfolder" id="processfolder" value="0" />
		<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />
	</form>
	<?php else: ?>

	<div class="row-fluid">
		<div class="span12">
			<form action="index.php" method="get" name="adminForm" id="adminForm" class="form-inline">
				<input type="hidden" name="option" value="com_akeeba" />
				<input type="hidden" name="view" value="browser" />
				<input type="hidden" name="tmpl" value="component" />
				<div class="input-prepend">
					<span class="add-on"
						alt="<?php echo $this->writable ? JText::_('WRITABLE') : JText::_('UNWRITABLE'); ?>"
						title="<?php echo $this->writable ? JText::_('WRITABLE') : JText::_('UNWRITABLE'); ?>"
					>
						<i class="icon-<?php echo $this->writable ? 'ok' : 'ban-circle' ?>"></i>
					</span>
					<input class="input-xlarge" type="text" name="folder" id="folder" value="<?php echo $this->folder; ?>" />
				</div>
				<input type="hidden" name="folderraw" id="folderraw" value="<?php echo $this->folder_raw ?>"/>
				<button class="btn btn-primary" onclick="document.form.adminForm.submit(); return false;">
					<i class="icon-share icon-white"></i>
					<?php echo JText::_('BROWSER_LBL_GO'); ?>
				</button>
				<button class="btn btn-success" onclick="akeeba_browser_useThis(); return false;">
					<i class="icon-check icon-white"></i>
					<?php echo JText::_('BROWSER_LBL_USE'); ?>
				</button>
				<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken()?>" value="1" />
			</form>
		</div>
	</div>
	<?php if(count($this->breadcrumbs) > 0): ?>
	<div class="row-fluid">
		<div class="span12">
			<ul class="breadcrumb">
				<?php $i = 0 ?>
				<?php foreach($this->breadcrumbs as $crumb):
					$link = JURI::base()."index.php?option=com_akeeba&view=browser&tmpl=component&folder=".urlencode($crumb['folder']);
					$label = htmlentities($crumb['label']);
					$i++;
					$bull = $i < count($this->breadcrumbs) ? '&bull;' : '';
				?>
				<li class="<?php echo $bull ? '' : 'active' ?>">
					<?php if($bull): ?>
					<a href="<?php echo $link ?>">
						<?php echo $label ?>
					</a>
					<span class="divider">&bull;</span>
					<?php else: ?>
					<?php echo $label ?>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>	
	
	<div class="row-fluid">
		<div class="span12">
		<?php if(count($this->subfolders) > 0): ?>
			<table class="table table-striped">
				<tr>
					<td>
						<?php $linkbase = JURI::base()."index.php?option=com_akeeba&view=browser&tmpl=component&folder="; ?>
						<a class="btn btn-mini btn-inverse" href="<?php echo $linkbase.urlencode($this->parent); ?>">
							<i class="icon-arrow-up icon-white"></i>
							<?php echo JText::_('BROWSER_LBL_GOPARENT') ?>
						</a>
					</td>
				</tr>
				<?php foreach($this->subfolders as $subfolder): ?>
				<tr>
					<td>
						<a href="<?php echo $linkbase.urlencode($this->folder.'/'.$subfolder); ?>"><?php echo htmlentities($subfolder) ?></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			<?php if(!$this->exists): ?>
			<div class="alert alert-error">
				<?php echo JText::_('BROWSER_ERR_NOTEXISTS'); ?>
			</div>
			<?php elseif(!$this->inRoot): ?>
			<div class="alert">
				<?php echo JText::_('BROWSER_ERR_NONROOT'); ?>
			</div>
			<?php elseif($this->openbasedirRestricted): ?>
			<div class="alert alert-error">
				<?php echo JText::_('BROWSER_ERR_BASEDIR'); ?>
			</div>
			<?php else: ?>
			<table class="table table-striped">
				<tr>
					<td>
						<?php $linkbase = JURI::base()."index.php?option=com_akeeba&view=browser&tmpl=component&folder="; ?>
						<a class="btn btn-mini btn-inverse" href="<?php echo $linkbase.urlencode($this->parent); ?>">
							<i class="icon-arrow-up icon-white"></i>
							<?php echo JText::_('BROWSER_LBL_GOPARENT') ?>
						</a>
					</td>
				</tr>
			</table>
			<?php endif;?>
		<?php endif; ?>
		</div>
	</div>
<?php endif; ?>