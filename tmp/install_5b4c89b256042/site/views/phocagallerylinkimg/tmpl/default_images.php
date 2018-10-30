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
$user 	= JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'a.ordering');

JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
//<![CDATA[
function insertLink() {
	
	<?php
	$items = array('imageshadow', 'fontcolor', 'bgcolor', 'bgcolorhover', 'imagebgcolor', 'bordercolor', 'bordercolorhover', 'detail','displayname', 'displaydetail', 'displaydownload', 'displaybuttons', 'displaydescription', 'descriptionheight' ,'namefontsize', 'namenumchar', 'enableswitch', 'overlib', 'piclens','float', 'boxspace', 'displayimgrating', 'pluginlink', 'type', 'imageordering', 'minboxwidth' );
	$itemsArrayOutput = '';
	foreach ($items as $key => $value) {
		
		echo 'var '.$value.' = document.getElementById("'.$value.'").value;'."\n"
			.'if ('.$value.' != \'\') {'. "\n"
			.''.$value.' = "|'.$value.'="+'.$value.';'."\n"
			.'}';
		$itemsArrayOutput .= '+'.$value;
	}
	?>
	
	/* LimitStart*/
	var limitStartOutput = '';
	var limitstart = document.getElementById("limitstartparam").value;
	if (limitstart != '') {
		limitStartOutput = "|limitstart="+limitstart;
	}
	/* LimitCount*/
	var limitCountOutput = '';
	var limitcount = document.getElementById("limitcountparam").value;
	if (limitcount != '') {
		limitCountOutput = "|limitcount="+limitcount;
	}
	/* Category */
	var categoryid = document.getElementById("filter_catid").value;
	var categoryIdOutput = '';
	if (categoryid != '') {
		categoryIdOutput = "|categoryid="+categoryid;
	}
	
	if (limitStartOutput != '') {
		/*return false;*/
	} else {
		alert("<?php echo JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_LIMIT_START', true ); ?>");
		return false;
	}
	
	if (limitCountOutput != '') {
		/*return false;*/
	} else {
		alert("<?php echo JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_LIMIT_COUNT', true ); ?>");
		return false;
	}
	
	
	if (categoryIdOutput != '' &&  parseInt(categoryid) > 0) {
		/*return false;*/
	} else {
		alert("<?php echo JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY', true ); ?>");
		return false;
	}
	
	var tag = "{phocagallery view=category"+categoryIdOutput+limitStartOutput+limitCountOutput<?php echo $itemsArrayOutput ?>+"}";
	window.parent.jInsertEditorText(tag, '<?php echo $this->tmpl['ename']; ?>');
	window.parent.SqueezeBox.close();
}
//]]>
</script>
<div id="phocagallery-links">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_PHOCAGALLERY_IMAGES'); ?></legend>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm"  id="adminForm">

<div id="editcell">
	<table class="admintable" width="100%">
		
		<tr>
			<td class="key" align="right"  width="30%">
			<label for="title" >
				<?php echo JText::_( 'COM_PHOCAGALLERY_CATEGORY' ); ?>
			</label>
			</td width="70%">
			<td><?php echo $this->lists['catid']; ?></td>
	</tr>

		<tr>
			<td class="key" align="right" width="30%"><label for="imagecategories"><?php echo JText::_( 'COM_PHOCAGALLERY_LIMIT_START' ); ?></label></td>
		<td width="70%">
		<?php echo $this->lists['limitstartparam'];?>
			
		</tr>
		
		<tr>
			<td class="key" align="right" width="30%"><label for="imagecategories"><?php echo JText::_( 'COM_PHOCAGALLERY_LIMIT_COUNT' ); ?></label></td>
		<td width="70%">
		<?php echo $this->lists['limitcountparam'];?>
			
		</tr>
	</table>
</div>


<input type="hidden" name="controller" value="phocagallerylinkimg" />
<input type="hidden" name="type" value="<?php echo (int)$this->tmpl['type']; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="e_name" value="<?php echo $this->tmpl['ename']?>" />
</form>



<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	<tr>
		<td class="key" align="right" width="30%"><label for="imageordering"><?php echo JText::_( 'COM_PHOCAGALLERY_FIELD_IMAGE_ORDERING_LABEL' ); ?></label></td>
		<td><select name="imageordering" id="imageordering" class="inputbox">
			<option value="" selected="selected"><?php echo JText::_('COM_PHOCAGALLERY_DEFAULT')?></option>
			<option value="1"><?php echo JText::_('COM_PHOCAGALLERY_ORDERING_ASC')?></option>
			<option value="2"><?php echo JText::_('COM_PHOCAGALLERY_ORDERING_DESC')?></option>
			<option value="3"><?php echo JText::_('COM_PHOCAGALLERY_TITLE_ASC')?></option>
			<option value="4"><?php echo JText::_('COM_PHOCAGALLERY_TITLE_DESC')?></option>
			<option value="5"><?php echo JText::_('COM_PHOCAGALLERY_DATE_ASC')?></option>
			<option value="6"><?php echo JText::_('COM_PHOCAGALLERY_DATE_DESC')?></option>
			<option value="7"><?php echo JText::_('COM_PHOCAGALLERY_ID_ASC')?></option>
			<option value="8"><?php echo JText::_('COM_PHOCAGALLERY_ID_DESC')?></option>
			<option value="9"><?php echo JText::_('COM_PHOCAGALLERY_RANDOM')?></option>
		</select></td>
</tr>
	
	
	<tr>
		<td class="key" align="right" width="30%"><label for="imagecategories"><?php echo JText::_( 'COM_PHOCAGALLERY_IMAGE_BACKGROUND_SHADOW' ); ?></label></td>
		<td width="70%">
			<select name="imageshadow" id="imageshadow">
			<option value=""  selected="selected"><?php echo JText::_( 'COM_PHOCAGALLERY_DEFAULT' )?></option>
			<option value="none" ><?php echo JText::_('COM_PHOCAGALLERY_NONE'); ?></option>
			<option value="shadow1" ><?php echo JText::_( 'COM_PHOCAGALLERY_SHADOW1' ); ?></option>
			<option value="shadow2" ><?php echo JText::_( 'COM_PHOCAGALLERY_SHADOW2' ); ?></option>
			<option value="shadow3" ><?php echo JText::_( 'COM_PHOCAGALLERY_SHADOW3' ); ?></option>
			</select>
		</td>
	</tr>

	<?php 
	// Colors
	$itemsColor = array ('fontcolor' => 'COM_PHOCAGALLERY_FIELD_FONT_COLOR_LABEL', 'bgcolor' => 'COM_PHOCAGALLERY_FIELD_BACKGROUND_COLOR_LABEL', 'bgcolorhover' => 'COM_PHOCAGALLERY_FIELD_BACKGROUND_COLOR_HOVER_LABEL', 'imagebgcolor' => 'COM_PHOCAGALLERY_FIELD_IMAGE_BACKGROUND_COLOR_LABEL', 'bordercolor' => 'COM_PHOCAGALLERY_FIELD_BORDER_COLOR_LABEL', 'bordercolorhover' => 'COM_PHOCAGALLERY_FIELD_BORDER_COLOR_HOVER_LABEL');
	
	foreach ($itemsColor as $key => $value) {
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="'.$key.'">'.JText::_($value).'</label></td>'
		.'<td nowrap="nowrap"><input type="text" name="'.$key.'" id="'.$key.'" value="" class="text_area" /><span style="margin-left:10px" onclick="openPicker(\''.$key.'\')"  class="picker_buttons">'. JText::_('COM_PHOCAGALLERY_PICK_COLOR').'</span></td>'
		.'</tr>';
	}
	?>
	
	<tr>
		<td class="key" align="right" width="30%"><label for="detail"><?php echo JText::_( 'COM_PHOCAGALLERY_DETAIL_WINDOW' ); ?></label></td>
		<td width="70%">
		<select name="detail" id="detail" class="inputbox">
		<option value=""  selected="selected"><?php echo JText::_( 'COM_PHOCAGALLERY_DEFAULT' )?></option>
		<option value="1" ><?php echo JText::_( 'COM_PHOCAGALLERY_STANDARD_POPUP_WINDOW' ); ?></option>
		<option value="0" ><?php echo JText::_( 'COM_PHOCAGALLERY_MODAL_POPUP_BOX' ); ?></option>
		<option value="2" ><?php echo JText::_( 'COM_PHOCAGALLERY_MODAL_POPUP_BOX_IMAGE_ONLY' ); ?></option>
		<option value="3" ><?php echo JText::_( 'COM_PHOCAGALLERY_SHADOWBOX' ); ?></option>
		<option value="4" ><?php echo JText::_( 'COM_PHOCAGALLERY_HIGHSLIDE' ); ?></option>
		<option value="5" ><?php echo JText::_( 'COM_PHOCAGALLERY_HIGHSLIDE_IMAGE_ONLY' ); ?></option>
		<option value="6" ><?php echo JText::_( 'COM_PHOCAGALLERY_JAK_LIGHTBOX' ); ?></option>
		<option value="8" ><?php echo JText::_( 'COM_PHOCAGALLERY_SLIMBOX' ); ?></option>
		<?php /*<option value="7" >No Popup</option>*/ ?>
		</select></td>
	</tr>
	
	<?php
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="pluginlink">'.JText::_('COM_PHOCAGALLERY_PLUGIN_LINK').'</label></td>'
		.'<td nowrap><select name="pluginlink" id="pluginlink" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_DETAIL_IMAGE' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_CATEGORY' ).'</option>'
		.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_CATEGORIES' ).'</option>';
	
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="type">'.JText::_('COM_PHOCAGALLERY_PLUGIN_TYPE').'</label></td>'
		.'<td nowrap><select name="type" id="type" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_DETAIL_IMAGE' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_MOSAIC' ).'</option>'
		.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_LARGE_IMAGE' ).'</option>';
	
	// yes/no
	$itemsYesNo = array ('displayname' => 'COM_PHOCAGALLERY_FIELD_DISPLAY_NAME_LABEL', 'displaydetail' => 'COM_PHOCAGALLERY_FIELD_DISPLAY_DETAIL_ICON_LABEL', 'displaydownload' => 'COM_PHOCAGALLERY_FIELD_DISPLAY_DOWNLOAD_ICON_LABEL', 'displaybuttons' => 'COM_PHOCAGALLERY_FIELD_DISPLAY_BUTTONS_LABEL', 'displaydescription' => 'COM_PHOCAGALLERY_FIELD_DISPLAY_DESCRIPTION_DETAIL_LABEL', 'displayimgrating' => 'COM_PHOCAGALLERY_DISPLAY_IMAGE_RATING' );
	foreach ($itemsYesNo as $key => $value) {
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="'.$key.'">'.JText::_($value).'</label></td>'
		.'<td nowrap><select name="'.$key.'" id="'.$key.'" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>';
		
		if ($key == 'displaydownload') {
			echo '<option value="1" >'. JText::_( 'COM_PHOCAGALLERY_SHOW' ).'</option>'
			.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_SHOW_DIRECT_DOWNLOAD' ).'</option>'
			.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_HIDE' ).'</option>';
		} else {
			echo '<option value="1" >'. JText::_( 'COM_PHOCAGALLERY_SHOW' ).'</option>'
			.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_HIDE' ).'</option>';
		}
		echo '</select></td>'
		.'</tr>';
	}
	
	
	// Number
	$itemsNumber = array ('descriptionheight' => 'COM_PHOCAGALLERY_FIELD_DESCRIPTION_DETAIL_HEIGHT_LABEL','namefontsize' => 'COM_PHOCAGALLERY_FIELD_FONT_SIZE_NAME_LABEL', 'namenumchar' => 'COM_PHOCAGALLERY_FIELD_CHAR_LENGTH_NAME_LABEL', 'boxspace' => 'COM_PHOCAGALLERY_FIELD_CATEGORY_BOX_SPACE_LABEL','minboxwidth' => 'COM_PHOCAGALLERY_MIN_BOX_WIDTH');
	foreach ($itemsNumber as $key => $value) {
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="'.$key.'">'.JText::_($value).'</label></td>'
		.'<td nowrap="nowrap"><input type="text" name="'.$key.'" id="'.$key.'" value="" class="text_area" /></td>'
		.'</tr>';
	}
	
	echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="enableswitch">'.JText::_('COM_PHOCAGALLERY_SWITCH_IMAGE').'</label></td>'
		.'<td nowrap><select name="enableswitch" id="enableswitch" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_ENABLE' ).'</option>'
		.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_DISABLE' ).'</option>';
	
	echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="overlib">'.JText::_('COM_PHOCAGALLERY_FIELD_OVERLIB_EFFECT_LABEL').'</label></td>'
		.'<td nowrap><select name="overlib" id="overlib" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_NONE' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_ONLY_IMAGE' ).'</option>'
		.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_ONLY_DESCRIPTION' ).'</option>'
		.'<option value="3" >'.JText::_( 'COM_PHOCAGALLERY_IMAGE_AND_DESCRIPTION' ).'</option>';
	
	echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="piclens">'.JText::_('COM_PHOCAGALLERY_ENABLE_COOLIRIS').'</label></td>'
		.'<td nowrap><select name="piclens" id="piclens" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="0" >'.JText::_( 'COM_PHOCAGALLERY_NO' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_YES' ).'</option>'
		.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_FIELD_YES_START_COOLIRIS' ).'</option>';
	?>
	
	
	<tr>
		<td class="key" align="right" width="30%"><label for="float"><?php echo JText::_( 'COM_PHOCAGALLERY_FLOAT_IMAGE' ); ?></label></td>
		<td width="70%">
			<select name="float" id="float">
			<option value=""  selected="selected"><?php echo JText::_( 'COM_PHOCAGALLERY_DEFAULT' )?></option>
			<option value="left" ><?php echo JText::_( 'COM_PHOCAGALLERY_LEFT' ); ?></option>
			<option value="right" ><?php echo JText::_( 'COM_PHOCAGALLERY_RIGHT' ); ?></option>
			</select>
		</td>
	</tr>

	
	<tr>
		<td>&nbsp;</td>
		<td align="right"><button class="btn btn-primary" onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCAGALLERY_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

</fieldset>
<div style="text-align:left;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->tmpl['backlink'];?>"><?php echo JText::_('COM_PHOCAGALLERY_BACK')?></a></span></div>
</div>