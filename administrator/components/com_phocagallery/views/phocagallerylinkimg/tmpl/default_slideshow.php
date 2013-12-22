<?php
defined('_JEXEC') or die('Restricted access');
$user 	=& JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'a.ordering');

JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
//<![CDATA[
function insertLink() {
	
	<?php
	$items = array('width', 'height', 'delay', 'image', 'pgslink', 'imageordering' );
	$itemsArrayOutput = '';
	foreach ($items as $key => $value) {
		
		echo 'var '.$value.' = document.getElementById("'.$value.'").value;'."\n"
			.'if ('.$value.' != \'\') {'. "\n"
			.''.$value.' = "|'.$value.'="+'.$value.';'."\n"
			.'}';
		$itemsArrayOutput .= '+'.$value;
	}
	?>
	
	/* Category */
	var categoryid = document.getElementById("filter_catid").value;
	var categoryIdOutput = '';
	if (categoryid != '') {
		categoryIdOutput = "id="+categoryid;
	}
	
	
	if (categoryIdOutput != '' &&  parseInt(categoryid) > 0) {
		/*return false;*/
	} else {
		alert("<?php echo JText::_( 'COM_PHOCAGALLERY_PLEASE_SELECT_CATEGORY', true ); ?>");
		return false;
	}
	
	var tag = "{pgslideshow "+categoryIdOutput<?php echo $itemsArrayOutput ?>+"}";
	window.parent.jInsertEditorText(tag, '<?php echo $this->tmpl['ename']; ?>');
	window.parent.SqueezeBox.close();
}
//]]>
</script>
<div id="phocagallery-links">
<fieldset class="adminform">
<legend><?php echo JText::_('COM_PHOCAGALLERY_IMAGE'); ?></legend>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">

<table class="admintable" width="100%">
		
		<tr>
			<td class="key" align="right" nowrap="nowrap" width="30%" >
			<label for="title" nowrap="nowrap" >
				<?php echo JText::_( 'COM_PHOCAGALLERY_CATEGORY' ); ?>
			</label>
			</td width="70%">
			<td><?php echo $this->lists['catid']; ?></td>
	</tr>
</table>


<input type="hidden" name="controller" value="phocagallerylinkimg" />
<input type="hidden" name="type" value="<?php echo (int)$this->tmpl['type']; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="e_name" value="<?php echo $this->tmpl['ename']?>" />
</form>



<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	<?php
	// Number
	$itemsNumber = array ('width' => array('COM_PHOCAGALLERY_SLIDESHOW_WIDTH', 640),'height' => array('COM_PHOCAGALLERY_SLIDESHOW_HEIGHT',480), 'delay' => array('COM_PHOCAGALLERY_SLIDESHOW_DELAY',3000));
	foreach ($itemsNumber as $key => $value) {
		echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="'.$key.'">'.JText::_($value[0]).'</label></td>'
		.'<td nowrap="nowrap"><input type="text" name="'.$key.'" id="'.$key.'" value="'.$value[1].'" class="text_area" /></td>'
		.'</tr>';
	}
	
	echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="image">'.JText::_('COM_PHOCAGALLERY_IMAGE').'</label></td>'
		.'<td nowrap><select name="image" id="image" class="inputbox">'
		.'<option value="L"  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_LARGE' ).'</option>'
		.'<option value="M" >'.JText::_( 'COM_PHOCAGALLERY_MEDIUM' ).'</option>'
		.'<option value="S" >'.JText::_( 'COM_PHOCAGALLERY_SMALL' ).'</option>'
		.'<option value="O" >'.JText::_( 'COM_PHOCAGALLERY_ORIGINAL_IMAGE' ).'</option>'
		.'</select></td></tr>';
	
	echo '<tr>'
		.'<td class="key" align="right" width="30%"><label for="pgslink">'.JText::_('COM_PHOCAGALLERY_SLIDESHOW_LINK').'</label></td>'
		.'<td nowrap><select name="pgslink" id="pgslink" class="inputbox">'
		.'<option value=""  selected="selected">'. JText::_( 'COM_PHOCAGALLERY_DEFAULT' ).'</option>'
		.'<option value="1" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_CATEGORY' ).'</option>'
		.'<option value="2" >'.JText::_( 'COM_PHOCAGALLERY_LINK_TO_CATEGORIES' ).'</option>'
		.'</select></td></tr>';
	?>	
		<tr>
		<td class="key" align="right" width="30%"><label for="imageordering"><?php echo JText::_( 'COM_PHOCAGALLERY_IMAGE_ORDERING' ); ?></label></td>
		<td><select name="imageordering" id="imageordering" class="inputbox">
			<option value="" selected="selected"><?php echo JText::_('COM_PHOCAGALLERY_DEFAULT')?></option>
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
		<td>&nbsp;</td>
		<td align="right"><button class="btn btn-primary" onclick="insertLink();return false;"><span class="icon-ok"></span> <?php echo JText::_( 'COM_PHOCAGALLERY_INSERT_CODE' ); ?></button></td>
	</tr>
</table>
</form>

</fieldset>
<div style="text-align:left;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->tmpl['backlink'];?>"><?php echo JText::_('COM_PHOCAGALLERY_BACK')?></a></span></div>
</div>