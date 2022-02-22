<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$user       = JFactory::getUser();
$input      = JFactory::getApplication()->input;
$params     = JComponentHelper::getParams('com_media');
$lang       = JFactory::getLanguage();
$onClick    = '';
$fieldInput = $this->state->get('field.id');
$isMoo      = $input->getInt('ismoo', 1);
$author     = $input->getCmd('author');
$asset      = $input->getCmd('asset');

JHtml::_('formbehavior.chosen', 'select');

// Load tooltip instance without HTML support because we have a HTML tag in the tip
JHtml::_('bootstrap.tooltip', '.noHtmlTip', array('html' => false));

// Include jQuery
JHtml::_('behavior.core');
JHtml::_('jquery.framework');
JHtml::_('script', 'media/popup-imagemanager.min.js', array('version' => 'auto', 'relative' => true));
JHtml::_('stylesheet', 'media/popup-imagemanager.css', array('version' => 'auto', 'relative' => true));

if ($lang->isRtl())
{
	JHtml::_('stylesheet', 'media/popup-imagemanager_rtl.css', array('version' => 'auto', 'relative' => true));
}

JFactory::getDocument()->addScriptOptions(
	'mediamanager', array(
		'base'   => $params->get('image_path', 'images') . '/',
		'asset'  => $asset,
		'author' => $author
	)
);

/**
 * Mootools compatibility
 *
 * There is an extra option passed in the URL for the iframe &ismoo=0 for the bootstrap fields.
 * By default the value will be 1 or defaults to mootools behaviour
 *
 * This should be removed when mootools won't be shipped by Joomla.
 */
if (!empty($fieldInput)) // Media Form Field
{
	if ($isMoo)
	{
		$onClick = "window.parent.jInsertFieldValue(document.getElementById('f_url').value, '" . $fieldInput . "');window.parent.jModalClose();window.parent.jQuery('.modal.in').modal('hide');";
	}
}
else // XTD Image plugin
{
	$onClick = 'ImageManager.onok();window.parent.jModalClose();';
}
?>
<div class="container-popup">

	<form action="index.php?option=com_media&amp;asset=<?php echo $asset; ?>&amp;author=<?php echo $author; ?>" class="form-horizontal" id="imageForm" method="post" enctype="multipart/form-data">

		<div id="messages" style="display: none;">
			<span id="message"></span><?php echo JHtml::_('image', 'media/dots.gif', '...', array('width' => 22, 'height' => 12), true); ?>
		</div>

		<div class="well">
			<div class="row-fluid">
				<div class="span8 control-group">
					<div class="control-label">
						<label for="folder"><?php echo JText::_('COM_MEDIA_DIRECTORY'); ?></label>
					</div>
					<div class="controls">
						<?php echo $this->folderList; ?>
						<button class="btn" type="button" id="upbutton" title="<?php echo JText::_('COM_MEDIA_DIRECTORY_UP'); ?>"><?php echo JText::_('COM_MEDIA_UP'); ?></button>
					</div>
				</div>
				<div class="span4 control-group">
					<div class="pull-right">
						<button class="btn btn-success button-save-selected" type="button" <?php if (!empty($onClick)) :
							// This is for Mootools compatibility ?>onclick="<?php echo $onClick; ?>"<?php endif; ?> data-dismiss="modal"><?php echo JText::_('COM_MEDIA_INSERT'); ?></button>
						<button class="btn button-cancel" type="button" onclick="window.parent.jQuery('.modal.in').modal('hide');<?php if (!empty($onClick)) :
							// This is for Mootools compatibility ?>parent.jModalClose();<?php endif ?>" data-dismiss="modal"><?php echo JText::_('JCANCEL'); ?></button>
					</div>
				</div>
			</div>
		</div>

		<iframe id="imageframe" name="imageframe" src="index.php?option=com_media&amp;view=imagesList&amp;tmpl=component&amp;folder=<?php echo rawurlencode($this->state->folder); ?>&amp;asset=<?php echo $asset; ?>&amp;author=<?php echo $author; ?>"></iframe>

		<div class="well">
			<div class="row-fluid">
				<div class="span12 control-group">
					<div class="control-label">
						<label for="f_url"><?php echo JText::_('COM_MEDIA_IMAGE_URL'); ?></label>
					</div>
					<div class="controls">
						<input type="text" id="f_url" value="" />
					</div>
				</div>
			</div>
		</div>

		<?php if (!$this->state->get('field.id')) : ?>
			<div class="well">
				<div class="row-fluid">
					<div class="span6 control-group">
						<div class="control-label">
							<label title="<?php echo JText::_('COM_MEDIA_ALIGN_DESC'); ?>" class="noHtmlTip" for="f_align"><?php echo JText::_('COM_MEDIA_ALIGN'); ?></label>
						</div>
						<div class="controls">
							<select size="1" id="f_align">
								<option value="" selected="selected"><?php echo JText::_('COM_MEDIA_NOT_SET'); ?></option>
								<option value="left"><?php echo JText::_('JGLOBAL_LEFT'); ?></option>
								<option value="center"><?php echo JText::_('JGLOBAL_CENTER'); ?></option>
								<option value="right"><?php echo JText::_('JGLOBAL_RIGHT'); ?></option>
							</select>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6 control-group">
						<div class="control-label">
							<label for="f_alt"><?php echo JText::_('COM_MEDIA_IMAGE_DESCRIPTION'); ?></label>
						</div>
						<div class="controls">
							<input type="text" id="f_alt" value="" />
						</div>
					</div>
					<div class="span6 control-group">
						<div class="control-label">
							<label for="f_title"><?php echo JText::_('COM_MEDIA_TITLE'); ?></label>
						</div>
						<div class="controls">
							<input type="text" id="f_title" value="" />
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span6 control-group">
						<div class="control-label">
							<label for="f_caption"><?php echo JText::_('COM_MEDIA_CAPTION'); ?></label>
						</div>
						<div class="controls">
							<input type="text" id="f_caption" value="" />
						</div>
					</div>
					<div class="span6 control-group">
						<div class="control-label">
							<label title="<?php echo JText::_('COM_MEDIA_CAPTION_CLASS_DESC'); ?>" class="noHtmlTip" for="f_caption_class"><?php echo JText::_('COM_MEDIA_CAPTION_CLASS_LABEL'); ?></label>
						</div>
						<div class="controls">
							<input type="text" list="d_caption_class" id="f_caption_class" value="" />
							<datalist id="d_caption_class">
								<option value="text-left">
								<option value="text-center">
								<option value="text-right">
							</datalist>
						</div>
					</div>
				</div>
			<input type="hidden" id="dirPath" name="dirPath" />
			<input type="hidden" id="f_file" name="f_file" />
			<input type="hidden" id="tmpl" name="component" />
		</div>
		<?php endif; ?>
	</form>

	<?php if ($user->authorise('core.create', 'com_media')) : ?>
		<form action="<?php echo JUri::base(); ?>index.php?option=com_media&amp;task=file.upload&amp;tmpl=component&amp;<?php echo $this->session->getName() . '=' . $this->session->getId(); ?>&amp;<?php echo JSession::getFormToken(); ?>=1&amp;asset=<?php echo $asset; ?>&amp;author=<?php echo $author; ?>&amp;view=images" id="uploadForm" class="form-horizontal" name="uploadForm" method="post" enctype="multipart/form-data">
			<div id="uploadform" class="well">
				<fieldset id="upload-noflash" class="actions">
					<div class="control-group">
						<div class="control-label">
							<label for="upload-file" class="control-label"><?php echo JText::_('COM_MEDIA_UPLOAD_FILE'); ?></label>
						</div>
						<div class="controls">
							<input required type="file" id="upload-file" name="Filedata[]" multiple /><button class="btn btn-primary" id="upload-submit"><span class="icon-upload icon-white"></span> <?php echo JText::_('COM_MEDIA_START_UPLOAD'); ?></button>
							<p class="help-block">
								<?php $cMax    = (int) $this->config->get('upload_maxsize'); ?>
								<?php $maxSize = JUtility::getMaxUploadSize($cMax . 'MB'); ?>
								<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', JHtml::_('number.bytes', $maxSize)); ?>
							</p>
						</div>
					</div>
				</fieldset>
				<?php JFactory::getSession()->set('com_media.return_url', 'index.php?option=com_media&view=images&tmpl=component&fieldid=' . $input->getCmd('fieldid', '') . '&e_name=' . $input->getCmd('e_name') . '&asset=' . $asset . '&author=' . $author); ?>
			</div>
		</form>
	<?php endif; ?>
</div>
