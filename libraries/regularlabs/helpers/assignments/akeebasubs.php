<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/* @DEPRECATED */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

class RLAssignmentsAkeebaSubs extends RLAssignment
{
	public function init()
	{
		if ( ! $this->request->id && $this->request->view == 'level')
		{
			$slug = JFactory::getApplication()->input->getString('slug', '');
			if ($slug)
			{
				$query = $this->db->getQuery(true)
					->select('l.akeebasubs_level_id')
					->from('#__akeebasubs_levels AS l')
					->where('l.slug = ' . $this->db->quote($slug));
				$this->db->setQuery($query);
				$this->request->id = $this->db->loadResult();
			}
		}
	}

	public function passPageTypes()
	{
		return $this->passByPageTypes('com_akeebasubs', $this->selection, $this->assignment);
	}

	public function passLevels()
	{
		if ( ! $this->request->id || $this->request->option != 'com_akeebasubs' || $this->request->view != 'level')
		{
			return $this->pass(false);
		}

		return $this->passSimple($this->request->id);
	}
}
