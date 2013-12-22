<?php defined('_JEXEC') or die('Restricted access');
// SEF problem
$isThereQMR = false;
$isThereQMR = preg_match("/\?/i", $this->tmpl['action']);
if ($isThereQMR) {$amp = '&amp;';} else {$amp = '?';}

echo '<div id="phocagallery-votes">'. "\n";
echo '<div class="ph-tabs-iefix">&nbsp;</div>';//because of IE bug
echo '<h4>'. JText::_('COM_PHOCAGALLERY_RATE_THIS_CATEGORY'). '</h4>'. "\n";
echo '<p><strong>' . JText::_('COM_PHOCAGALLERY_RATING'). '</strong>: ' . $this->tmpl['votesaverage'] .' / '.$this->tmpl['votescount'] . ' ' . JText::_($this->tmpl['votestext']). '</p>'. "\n";

if ($this->tmpl['alreay_rated']) {

	echo '<ul class="star-rating">'
		.'<li class="current-rating" style="width:'.$this->tmpl['voteswidth'].'px"></li>'
		.'<li><span class="star1"></span></li>';

	for ($i = 2;$i < 6;$i++) {
		echo '<li><span class="stars'.$i.'"></span></li>';
	}
	echo '</ul>'
		.'<p>'.JText::_('COM_PHOCAGALLERY_RATING_ALREADY_RATED').'</p>'. "\n";
		
} else if ($this->tmpl['not_registered']) {

	echo '<ul class="star-rating">'
		.'<li class="current-rating" style="width:'.$this->tmpl['voteswidth'].'px"></li>'
		.'<li><span class="star1"></span></li>';

	for ($i = 2;$i < 6;$i++) {
		echo '<li><span class="stars'.$i.'"></span></li>';
	}
	echo '</ul>'
		.'<p>'.JText::_('COM_PHOCAGALLERY_COMMENT_ONLY_REGISTERED_LOGGED_RATE_CATEGORY').'</p>'. "\n";
		
} else {
	
	echo '<ul class="star-rating">'
		.'<li class="current-rating" style="width:'.$this->tmpl['voteswidth'].'px"></li>'
		.'<li><a href="'.htmlspecialchars($this->tmpl['action']).$amp.'controller=category&task=rate&rating=1&tab='.$this->tmpl['currenttab']['rating'].$this->tmpl['limitstarturl'].'" title="'. JText::sprintf('COM_PHOCAGALLERY_STAR_OUT_OF', 1, 5). '" class="star1">1</a></li>';
	
	for ($i = 2;$i < 6;$i++) {

		echo '<li><a href="'.htmlspecialchars($this->tmpl['action']).$amp.'controller=category&task=rate&rating='.$i.'&tab='.$this->tmpl['currenttab']['rating'].$this->tmpl['limitstarturl'].'" title="'. JText::sprintf('COM_PHOCAGALLERY_STARS_OUT_OF', $i, 5) .'" class="stars'.$i.'">'.$i.'</a></li>';
	}
	echo '</ul>';
}

echo '</div>'. "\n";
?>
