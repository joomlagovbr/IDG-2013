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
$app = JFactory::getApplication();
$amp = PhocaGalleryUtils::setQuestionmarkOrAmp($this->tmpl['action']);

if ((int)$this->tmpl['display_rating_img'] == 1 || $this->tmpl['mb_rating']) {
	// Leave message for already voted images
	$vote = $app->input->get('vote', 0, 'int');;
	if ($vote == 1) {
		$voteMsg = JText::_('COM_PHOCAGALLERY_ALREADY_RATED_IMG_THANKS');
	} else {
		$voteMsg = JText::_('COM_PHOCAGALLERY_ALREADY_RATE_IMG');
	}

	echo '<table style="text-align:left" border="0">'
		.'<tr>'
		.'<td><strong>' . JText::_('COM_PHOCAGALLERY_RATING'). '</strong>: ' . $this->tmpl['votesaverageimg'] .' / '.$this->tmpl['votescountimg'] . ' ' . JText::_($this->tmpl['votestextimg']). '&nbsp;&nbsp;</td>';
		
	if ($this->tmpl['alreay_ratedimg']) {
		echo '<td style="text-align:left"><ul class="star-rating">'
			.'<li class="current-rating" style="width:'.$this->tmpl['voteswidthimg'].'px"></li>'
			.'<li><span class="star1"></span></li>';

		for ($i = 2;$i < 6;$i++) {
			echo '<li><span class="stars'.$i.'"></span></li>';
		}
		echo '</ul></td>';
		
		if ($this->tmpl['enable_multibox'] == 1) {
			echo '<td></td></tr>';
			echo '<tr><td style="text-align:left" colspan="4" class="pg-rating-msg">'.$voteMsg.'</td></tr>';
		} else {
			echo '<td style="text-align:left" colspan="4" class="pg-rating-msg">&nbsp;&nbsp;'.$voteMsg.'</td></tr>';
		}

			
	} else if ($this->tmpl['not_registered_img']) {

		echo '<td style="text-align:left"><ul class="star-rating">'
			.'<li class="current-rating" style="width:'.$this->tmpl['voteswidthimg'].'px"></li>'
			.'<li><span class="star1"></span></li>';

		for ($i = 2;$i < 6;$i++) {
			echo '<li><span class="stars'.$i.'"></span></li>';
		}
		echo '</ul></td>';
		
		if ($this->tmpl['enable_multibox'] == 1) {
			echo '<td></td></tr>';
			echo '<tr><td style="text-align:left" colspan="4" class="pg-rating-msg">'.JText::_('COM_PHOCAGALLERY_COMMENT_ONLY_REGISTERED_LOGGED_RATE_IMAGE').'</td></tr>';
		} else {
			echo '<td style="text-align:left" colspan="4" class="pg-rating-msg">&nbsp;&nbsp;' . JText::_('COM_PHOCAGALLERY_COMMENT_ONLY_REGISTERED_LOGGED_RATE_IMAGE').'</td></tr>';
		}
		
			
	} else {
		
		echo '<td style="text-align:left"><ul class="star-rating">'
			.'<li class="current-rating" style="width:'.$this->tmpl['voteswidthimg'].'px"></li>'
			//.'<li><a href="'.$this->tmpl['action'].$amp.'controller=detail&task=rate&rating=1" title="1 '. JText::_('COM_PHOCAGALLERY_STAR_OUT_OF').' 5" class="star1">1</a></li>';
		
			.'<li><a href="'.htmlspecialchars($this->tmpl['action']).$amp.'controller=detail&task=rate&rating=1" title="'. JText::sprintf('COM_PHOCAGALLERY_STAR_OUT_OF', 1, 5). '" class="star1">1</a></li>'; 
		
		for ($i = 2;$i < 6;$i++) {
			//echo '<li><a href="'.$this->tmpl['action'].$amp.'controller=detail&task=rate&rating='.$i.'" title="'.$i.' '. JText::_('COM_PHOCAGALLERY_STARS_OUT_OF').' 5" class="stars'.$i.'">'.$i.'</a></li>';
			
			echo '<li><a href="'.htmlspecialchars($this->tmpl['action']).$amp.'controller=detail&task=rate&rating='.$i.'" title="'.JText::sprintf('COM_PHOCAGALLERY_STARS_OUT_OF', $i, 5). '" class="stars'.$i.'">'.$i.'</a></li>';
		}
		echo '</ul></td></tr>';
	}
	echo '</table>';
}
?>
