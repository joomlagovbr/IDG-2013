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

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

class JFormFieldRL_AkeebaSubs extends \RegularLabs\Library\FieldGroup
{
	public $type          = 'AkeebaSubs';
	public $default_group = 'Levels';

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(['levels']))
		{
			return $error;
		}

		return $this->getSelectList();
	}

	function getLevels()
	{
		$query = $this->db->getQuery(true)
			->select('l.akeebasubs_level_id as id, l.title AS name, l.enabled as published')
			->from('#__akeebasubs_levels AS l')
			->where('l.enabled > -1')
			->order('l.title, l.akeebasubs_level_id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list, ['id']);
	}
}
