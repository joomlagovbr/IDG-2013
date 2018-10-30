<?php
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');

echo '<form action="index.php" method="post" name="adminForm" id="phocagalleryin-form">';
echo '<div id="j-sidebar-container" class="span2">'.$this->sidebar.'</div>';
echo '<div id="j-main-container" class="span9">'
	
	.'<div style="float:right;margin:10px;">'
	. JHTML::_('image', 'media/com_phocagallery/images/administrator/logo-phoca.png', 'Phoca.cz' )
	.'</div>'
	. JHTML::_('image', 'media/com_phocagallery/images/administrator/logo.png', 'Phoca.cz')
	.'<h3>'.JText::_('COM_PHOCAGALLERY_PHOCA_GALLERY').' - '. JText::_('COM_PHOCAGALLERY_INFORMATION').'</h3>'
	.'<p>'. JText::_('COM_PHOCAGALLERY_RECOMMENDED_SETTINGS').'</p>'
	.'<div style="clear:both;"></div>';
	
echo '<table cellpadding="5" cellspacing="1">'
	.'<tr><td></td>'
	.'<td align="center">'.JText::_('COM_PHOCAGALLERY_RECOMMENDED').'</td>'
	.'<td align="center">'.JText::_('COM_PHOCAGALLERY_CURRENT').'</td></tr>';

if ($this->tmpl['enablethumbcreation'] == 1) {
	$bgStyle = 'class="alert alert-error"';
} else {
	$bgStyle = 'class="alert alert-success"';
}


echo '<tr '.$bgStyle.'>'
	.'<td>'. JText::_('COM_PHOCAGALLERY_ENABLE_THUMBNAIL_GENERATION').'</td>'
	.'<td align="center">'.JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-false.png', JText::_('COM_PHOCAGALLERY_DISABLED') ) .'</td>'
	.'<td align="center">'.$this->tmpl['enablethumbcreationstatus'].'</td>'
	.'</tr>'
	.'<tr>'
	.'<td colspan="3">'.JText::_('COM_PHOCAGALLERY_ENABLE_THUMBNAIL_GENERATION_INFO_DESC').'</td></tr>';


if ($this->tmpl['paginationthumbnailcreation'] == 1) {
	$bgStyle 	= 'class="alert alert-success"';
	$icon		= 'true';
	$iconText	= JText::_('COM_PHOCAGALLERY_ENABLED');
} else {
	$bgStyle 	= 'class="alert alert-error"';
	$icon		= 'false';
	$iconText	= JText::_('COM_PHOCAGALLERY_DISABLED');
}

echo '<tr '.$bgStyle.'>'
	.'<td>'. JText::_('COM_PHOCAGALLERY_PAGINATION_THUMBNAIL_GENERATION').'</td>'
	.'<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-true.png', JText::_('COM_PHOCAGALLERY_ENABLED') ) .'</td>'
	.'<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-'.$icon.'.png', JText::_($iconText) ) .'</td>'
	.'</tr>'
	.'<tr><td colspan="3">'. JText::_('COM_PHOCAGALLERY_PAGINATION_THUMBNAIL_GENERATION_INFO_DESC').'</td></tr>';

if ($this->tmpl['cleanthumbnails'] == 0) {
	$bgStyle = 'class="alert alert-success"';
	$icon		= 'false';
	$iconText	= JText::_('COM_PHOCAGALLERY_DISABLED');
	

} else {
	$bgStyle = 'class="alert alert-error"';
	$icon		= 'true';
	$iconText	= JText::_('COM_PHOCAGALLERY_ENABLED');
}
echo '<tr '.  $bgStyle.'>'
	.'<td>'. JText::_('COM_PHOCAGALLERY_CLEAN_THUMBNAILS').'</td>'
	.'<td align="center">'. JHTML::_('image','media/com_phocagallery/images/administrator/icon-16-false.png' , JText::_('COM_PHOCAGALLERY_DISABLED') ) .'</td>'
	.'<td align="center">'. JHTML::_('image','media/com_phocagallery/images//administrator/icon-16-'.$icon.'.png', JText::_($iconText) ) .'</td>'
	.'</tr>'
	.'<tr><td colspan="3">'. JText::_('COM_PHOCAGALLERY_CLEAN_THUMBNAILS_INFO_DESC').'</td></tr>';

echo $this->foutput;
echo '</table>';

echo '<h3>'.  JText::_('COM_PHOCAGALLERY_HELP').'</h3>';

echo '<p>'
.'<a href="https://www.phoca.cz/phocagallery/" target="_blank">Phoca Gallery Main Site</a><br />'
.'<a href="https://www.phoca.cz/documentation/" target="_blank">Phoca Gallery User Manual</a><br />'
.'<a href="https://www.phoca.cz/forum/" target="_blank">Phoca Gallery Forum</a><br />'
.'</p>';

echo '<h3>'.  JText::_('COM_PHOCAGALLERY_VERSION').'</h3>'
.'<p>'.  $this->tmpl['version'] .'</p>';

echo '<h3>'.  JText::_('COM_PHOCAGALLERY_COPYRIGHT').'</h3>'
.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
.'<p><a href="https://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>';

echo '<h3>'.  JText::_('COM_PHOCAGALLERY_LICENCE').'</h3>'
.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';

echo '<h3>'.  JText::_('COM_PHOCAGALLERY_TRANSLATION').': '. JText::_('COM_PHOCAGALLERY_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2007 - '.  date("Y"). ' '. JText::_('COM_PHOCAGALLERY_TRANSLATER'). '</p>'
        .'<p>'.JText::_('COM_PHOCAGALLERY_TRANSLATION_SUPPORT_URL').'</p>';

echo '<input type="hidden" name="task" value="" />'
.'<input type="hidden" name="option" value="com_phocagallery" />'
.'<input type="hidden" name="controller" value="phocagalleryin" />';


echo '<p>&nbsp;</p>';

echo '<div style="border-top:1px solid #eee"></div><p>&nbsp;</p>'
.'<div class="btn-group">
<a class="btn btn-large btn-primary" href="https://www.phoca.cz/version/index.php?phocagallery='.  $this->tmpl['version'] .'" target="_blank"><i class="icon-loop icon-white"></i>&nbsp;&nbsp;'.  JText::_('COM_PHOCAGALLERY_CHECK_FOR_UPDATE') .'</a></div>';


echo '<div style="margin-top:30px;height:39px;background: url(\''.JURI::root(true).'/media/com_phocagallery/images/administrator/line.png\') 100% 0 no-repeat;">&nbsp;</div>';


echo '</div>';
echo '<div class="span1"></div>';
echo '</form>';