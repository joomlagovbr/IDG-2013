<?php
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
function insertLink() {
	var imagecategories = document.getElementById("imagecategories").value;
	if (imagecategories != '') {
		imagecategories = "|imagecategories="+imagecategories;
	}
	var imagecategoriessize = document.getElementById("imagecategoriessize").value;
	if (imagecategoriessize != '') {
		imagecategoriessize = "|imagecategoriessize="+imagecategoriessize;
	}
	
	var hideCategoriesOutput = '';
	hidecategories = getSelectedData();

	if (hidecategories != '') {
		hideCategoriesOutput = "|hidecategories="+hidecategories;
	}

	var tag = "{phocagallery view=categories"+imagecategories+imagecategoriessize+hideCategoriesOutput+"}";

	window.parent.jInsertEditorText(tag, '<?php echo $this->tmpl['ename']; ?>');
	//window.parent.document.getElementById('sbox-window').close();
	window.parent.SqueezeBox.close();
	return false;
}

function getSelectedData(array) {
	var selected = new Array();
	var dataSelect = document.forms["adminFormLink"].elements["hidecategories"];
	
	for(j = 0; j < dataSelect.options.length; j++){
		if (dataSelect.options[j].selected) {
			selected.push(dataSelect.options[j].value); }
	}
	if (array != 'true') {
		return selected.toString();
	} else {
		return selected;
	} 
}
</script>
<div id="phocagallery-links">
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_PHOCAGALLERY_CATEGORIES' ); ?></legend>
<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	<tr>
		<td class="key" align="right" width="30%">
			<label for="imagecategories">
				<?php echo JText::_( 'COM_PHOCAGALLERY_DISPLAY_IMAGES' ); ?>
			</label>
		</td>
		<td width="70%">
			<select name="imagecategories" id="imagecategories">
			<option value="0" ><?php echo JText::_( 'COM_PHOCAGALLERY_NO' ); ?></option>
			<option value="1" selected="selected"><?php echo JText::_( 'COM_PHOCAGALLERY_YES' ); ?></option>
			</select>
		</td>
	</tr>
	<tr >
		<td class="key" align="right">
			<label for="imagecategoriessize">
				<?php echo JText::_( 'COM_PHOCAGALLERY_IMAGE_SIZE' ); ?>
			</label>
		</td>
		<td>
			<select name="imagecategoriessize" id="imagecategoriessize">
			<option value="0" selected="selected"><?php echo JText::_( 'COM_PHOCAGALLERY_SMALL' ); ?></option>
			<option value="1"><?php echo JText::_( 'COM_PHOCAGALLERY_MEDIUM' ); ?></option>
			<option value="2"><?php echo JText::_( 'COM_PHOCAGALLERY_SMALL_FOLDER_ICON' ); ?></option>
			<option value="3"><?php echo JText::_( 'COM_PHOCAGALLERY_MEDIUM_FOLDER_ICON' ); ?></option>
			<option value="4"><?php echo JText::_( 'COM_PHOCAGALLERY_SMALL_WITH_SHADOW' ); ?></option>
			<option value="5"><?php echo JText::_( 'COM_PHOCAGALLERY_MEDIUM_WITH_SHADOW' ); ?></option>
			<option value="6"><?php echo JText::_( 'COM_PHOCAGALLERY_SMALL_FOLDER_ICON_WITH_SHADOW' ); ?></option>
			<option value="7"><?php echo JText::_( 'COM_PHOCAGALLERY_MEDIUM_FOLDER_ICON_WITH_SHADOW' ); ?></option>
			</select>
		</td>
	</tr>
	
	
	<tr >
		<td class="key" align="right">
			<label for="hidecategories">
				<?php echo JText::_( 'COM_PHOCAGALLERY_HIDE_CATEGORIES' ); ?>
			</label>
		</td>
		<td>
		<?php echo $this->categoriesoutput;?>
		</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td align="right"><button class="btn btn-primary" onclick="insertLink();"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCAGALLERY_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

</fieldset>
<div style="text-align:left;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->tmpl['backlink'];?>"><?php echo JText::_('COM_PHOCAGALLERY_BACK')?></a></span></div>
</div>