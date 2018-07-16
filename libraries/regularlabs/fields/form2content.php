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

class JFormFieldRL_Form2Content extends \RegularLabs\Library\FieldGroup
{
	public $type          = 'Form2Content';
	public $default_group = 'Projects';

	protected function getInput()
	{
		if ($error = $this->missingFilesOrTables(['projects' => 'project'], '', 'f2c'))
		{
			return $error;
		}

		return $this->getSelectList();
	}

	function getProjects()
	{
		$query = $this->db->getQuery(true)
			->select('t.id, t.title as name')
			->from('#__f2c_project AS t')
			->where('t.published = 1')
			->order('t.title, t.id');
		$this->db->setQuery($query);
		$list = $this->db->loadObjectList();

		return $this->getOptionsByList($list);
	}
}
