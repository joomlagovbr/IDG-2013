<?php
/**
 * @package   Phoca Gallery
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.pagination');
class PhocaGalleryPaginationUserImage extends JPagination
{
	var $_tabId;
	
	public function setTab($tabId) {
		$this->_tabId = (string)$tabId;
	}
	
	protected function _buildDataObject()
	{
		$tabLink = '';
		if ((string)$this->_tabId != '') {
			$tabLink = '&tab='.(string)$this->_tabId;
		}
		
		// Initialize variables
		$data = new stdClass();

		$data->all	= new JPaginationObject(JText::_('COM_PHOCAGALLERY_VIEW_ALL'));
		if (!$this->viewall) {
			$data->all->base	= '0';
			$data->all->link	= JRoute::_($tabLink."&limitstartimage=");
		}

		// Set the start and previous data objects
		$data->start	= new JPaginationObject(JText::_('COM_PHOCAGALLERY_PAG_START'));
		$data->previous	= new JPaginationObject(JText::_('COM_PHOCAGALLERY_PAG_PREV'));

		if ($this->get('pages.current') > 1)
		{
			$page = ($this->get('pages.current') -2) * $this->limit;

			$page = $page == 0 ? '' : $page; //set the empty for removal from route

			$data->start->base	= '0';
			$data->start->link	= JRoute::_($tabLink."&limitstartimage=");
			$data->previous->base	= $page;
			$data->previous->link	= JRoute::_($tabLink."&limitstartimage=".$page);
		}

		// Set the next and end data objects
		$data->next	= new JPaginationObject(JText::_('COM_PHOCAGALLERY_PAG_NEXT'));
		$data->end	= new JPaginationObject(JText::_('COM_PHOCAGALLERY_PAG_END'));

		if ($this->get('pages.current') < $this->get('pages.total'))
		{
			$next = $this->get('pages.current') * $this->limit;
			$end  = ($this->get('pages.total') -1) * $this->limit;

			$data->next->base	= $next;
			$data->next->link	= JRoute::_($tabLink."&limitstartimage=".$next);
			$data->end->base	= $end;
			$data->end->link	= JRoute::_($tabLink."&limitstartimage=".$end);
		}

		$data->pages = array();
		$stop = $this->get('pages.stop');
		for ($i = $this->get('pages.start'); $i <= $stop; $i ++)
		{
			$offset = ($i -1) * $this->limit;

			$offset = $offset == 0 ? '' : $offset;  //set the empty for removal from route

			$data->pages[$i] = new JPaginationObject($i);
			if ($i != $this->get('pages.current') || $this->viewall)
			{
				$data->pages[$i]->base	= $offset;
				$data->pages[$i]->link	= JRoute::_($tabLink."&limitstartimage=".$offset);
			}
		}
		return $data;
	}
	
	public function getLimitBox()
	{
		$app	= JFactory::getApplication();

		// Initialize variables
		$limits = array ();

		// Make the option list
		for ($i = 5; $i <= 30; $i += 5) {
			$limits[] = JHTML::_('select.option', "$i");
		}
		$limits[] = JHTML::_('select.option', '50');
		$limits[] = JHTML::_('select.option', '100');
		$limits[] = JHTML::_('select.option', '0', JText::_('COM_PHOCAGALLERY_ALL'));

		$selected = $this->viewall ? 0 : $this->limit;

		// Build the select list
		if ($app->isClient('administrator')) {
			$html = JHTML::_('select.genericlist',  $limits, 'limitimage', 'class="inputbox input-mini" size="1" onchange="Joomla.submitform();"', 'value', 'text', $selected);
		} else {
			$html = JHTML::_('select.genericlist',  $limits, 'limitimage', 'class="inputbox input-mini" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
		return $html;
	}
	
	public function orderUpIcon($i, $condition = true, $task = '#', $alt = 'COM_PHOCAGALLERY_MOVE_UP', $enabled = true, $checkbox = 'cb') {
		
		
		$alt = JText::_($alt);
		

		$html = '&nbsp;';
		if (($i > 0 || ($i + $this->limitstart > 0)) && $condition)
		{
			if($enabled) {
				$html	= '<a href="'.$task.'" title="'.$alt.'">';
				$html	.= '   <img src="'.JURI::base(true).'/media/com_phocagallery/images/icon-uparrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
				$html	.= '</a>';
			} else {
				$html	= '<img src="'.JURI::base(true).'/media/com_phocagallery/images/icon-uparrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
			}
		}

		return $html;
	}


	public function orderDownIcon($i, $n, $condition = true, $task = '#', $alt = 'COM_PHOCAGALLERY_MOVE_DOWN', $enabled = true, $checkbox = 'cb'){
		$alt = JText::_($alt);

		$html = '&nbsp;';
		if (($i < $n -1 || $i + $this->limitstart < $this->total - 1) && $condition)
		{
			if($enabled) {
				$html	= '<a href="'.$task.'" title="'.$alt.'">';
				$html	.= '  <img src="'.JURI::base(true).'/media/com_phocagallery/images/icon-downarrow.png" width="16" height="16" border="0" alt="'.$alt.'" />';
				$html	.= '</a>';
			} else {
				$html	= '<img src="'.JURI::base(true).'/media/com_phocagallery/images/icon-downarrow0.png" width="16" height="16" border="0" alt="'.$alt.'" />';
			}
		}

		return $html;
	}
}
?>