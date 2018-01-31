<?php defined('_JEXEC') or die('Restricted access');
echo '<div id="phocagallery" class="pg-detail-view'.$this->params->get( 'pageclass_sfx' ).'">';
if ($this->tmpl['backbutton'] != '') {
	echo $this->tmpl['backbutton'];
}

echo '<center style="padding-top:10px;">'
.'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
.'<tr>'
.'<td colspan="6"  valign="middle" height="'.$this->tmpl['largeheight'].'"'
.' style="height:'.$this->tmpl['largeheight'].'px" >';

echo '<div id="phocaGallerySlideshowC" style="width:'. $this->tmpl['largewidth'].'px;height:'. $this->tmpl['largeheight'].'px;padding:0;margin: auto">';

//.'<a href="#" onclick="'.$this->tmpl['detailwindowclose'].'">'.$this->item->linkimage.'</a>';
/*.'<script type="text/javascript" style="padding:0;margin:0;">';			
if ( $this->tmpl['slideshowrandom'] == 1 ) {
	echo 'new fadeshow(fadeimages, '.$this->tmpl['largewidth'] .', '. $this->tmpl['largeheight'] .', 0, '. $this->tmpl['slideshowdelay'] .', '. $this->tmpl['slideshowpause'] .', \'R\')';		
} else {						
	echo 'new fadeshow(fadeimages, '.$this->tmpl['largewidth'] .', '. $this->tmpl['largeheight'] .', 0, '. $this->tmpl['slideshowdelay'] .', '. $this->tmpl['slideshowpause'] .')';		
}
echo '</script>';*/

echo '</div>';
echo '</td>'
.'</tr>';

echo '<tr><td colspan="6"><div style="padding:0;margin:0;height:3px;font-size:0px;">&nbsp;</div></td></tr>';

// Standard Description (to get the same height as by not slideshow
if ($this->tmpl['displaydescriptiondetail'] == 1) {
	echo '<tr><td colspan="6" align="left" valign="top"><div></div></td></tr>';
}
		
echo '<tr>'
.'<td align="left" width="30%" style="padding-left:48px">'. $this->item->prevbutton .'</td>'
.'<td align="center">'. $this->item->slideshowbutton .'</td>'
.'<td align="center">'. str_replace("%onclickreload%", $this->tmpl['detailwindowreload'], $this->item->reloadbutton).'</td>';
if ($this->tmpl['detailwindow'] == 4 || $this->tmpl['detailwindow'] == 5 || $this->tmpl['detailwindow'] == 7) {
} else {
	echo '<td align="center">'. str_replace("%onclickclose%", $this->tmpl['detailwindowclose'], $this->item->closebutton).'</td>';
}
echo '<td align="right" width="30%" style="padding-right:48px">'. $this->item->nextbutton .'</td>'
.'</tr>'
.'</table>'
.'</center>';

if ($this->tmpl['detailwindow'] == 7) {
	PhocaGalleryUtils::displayFooter();
}
echo '</div>';