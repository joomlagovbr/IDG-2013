<?php
/*
 * @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;

$task		= 'phocagallerytag';

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$r 			=  new PhocaGalleryRenderAdminView();
$app		= JFactory::getApplication();
$option 	= $app->input->get('option');
$OPT		= strtoupper($option);

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'phocagallerytag.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
		else {
			alert('<?php echo JText::_('JGLOBAL_VALIDATION_FORM_FAILED', true);?>');
		}
	}
</script><?php
echo $r->startForm($option, $task, $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($OPT.'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($OPT.'_PUBLISHING_OPTIONS'));
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n"; 
$formArray = array ('title', 'alias', 'link_ext', 'link_cat', 'ordering');
echo $r->group($this->form, $formArray);
$formArray = array('description');
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="publishing">'."\n"; 
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo '</div>';

echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span2"></div>';//end span2
echo $r->formInputs();
echo $r->endForm();
?>