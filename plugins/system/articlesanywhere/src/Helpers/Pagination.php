<?php
/**
 * @package         Articles Anywhere
 * @version         9.3.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Helpers;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Pagination\Pagination as JPagination;
use Joomla\CMS\Pagination\PaginationObject;
use Joomla\CMS\Router\Route as JRoute;
use RegularLabs\Library\RegEx as RL_RegEx;

class Pagination extends JPagination
{
	private $url_params = '';

	/**
	 * Create and return the pagination data object.
	 *
	 * @return  \stdClass  Pagination data object.
	 *
	 * @since   1.5
	 */
	protected function _buildDataObject()
	{
		$this->setUrlParams();

		$data = (object) [];

		// Create global navigation objects.
		$data->all      = new PaginationObject(JText::_('JLIB_HTML_VIEW_ALL'));
		$data->start    = new PaginationObject(JText::_('JLIB_HTML_START'));
		$data->previous = new PaginationObject(JText::_('JPREV'));
		$data->next     = new PaginationObject(JText::_('JNEXT'));
		$data->end      = new PaginationObject(JText::_('JLIB_HTML_END'));

		$this->setPageNumber($data->all, 0);

		if ($this->pagesCurrent > 1)
		{
			$this->setPageNumber($data->start, 1);
			$this->setPageNumber($data->previous, $this->pagesCurrent - 1);
		}

		if ($this->pagesCurrent < $this->pagesTotal)
		{
			$this->setPageNumber($data->next, $this->pagesCurrent + 1);
			$this->setPageNumber($data->end, $this->pagesTotal);
		}

		$data->pages = [];
		$stop        = $this->pagesStop;

		for ($i = $this->pagesStart; $i <= $stop; $i++)
		{
			$data->pages[$i] = new PaginationObject($i);

			if ($i == $this->pagesCurrent)
			{
				$data->pages[$i]->active = true;
				continue;
			}

			$this->setPageNumber($data->pages[$i], $i);
		}

		return $data;
	}

	private function setUrlParams()
	{
		// Build the additional URL parameters string.
		$params = '';

		if ( ! empty($this->additionalUrlParams))
		{
			foreach ($this->additionalUrlParams as $key => $value)
			{
				$params .= '&' . $key . '=' . $value;
			}
		}

		$this->url_params = $params;
	}

	private function setPageNumber(&$page, $number)
	{
		$page->base = $number;
		$page->link = JRoute::_($this->url_params . '&' . $this->prefix . '=' . $page->base);

		// Remove page=1 from:
		// ?page=1
		// ?foo=bar&page=1
		// ?foo=bar&page=1&baz=qux
		// ?page=1&foo=bar&baz=qux
		$page->link = RL_RegEx::replace('(\?|&(amp;)?)' . RL_RegEx::quote($this->prefix) . '=1$', '', $page->link);
		$page->link = RL_RegEx::replace('(\?|&(amp;)?)' . RL_RegEx::quote($this->prefix) . '=1(?:&(amp;)?)', '\1', $page->link);
	}
}
