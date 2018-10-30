<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use JFactory;

/**
 * Class Akeebasubs
 * @package RegularLabs\Library\Condition
 */
abstract class Akeebasubs
	extends \RegularLabs\Library\Condition
{
	var $agent  = null;
	var $device = null;

	public function initRequest(&$request)
	{
		if ($request->id || $request->view != 'level')
		{
			return;
		}

		$slug = JFactory::getApplication()->input->getString('slug', '');

		if ( ! $slug)
		{
			return;
		}

		$query = $this->db->getQuery(true)
			->select('l.akeebasubs_level_id')
			->from('#__akeebasubs_levels AS l')
			->where('l.slug = ' . $this->db->quote($slug));
		$this->db->setQuery($query);
		$request->id = $this->db->loadResult();
	}
}
