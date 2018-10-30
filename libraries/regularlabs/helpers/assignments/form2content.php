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

/* @DEPRECATED */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';

class RLAssignmentsForm2Content extends RLAssignment
{
	public function passProjects()
	{
		if ($this->request->option != 'com_content' && $this->request->view == 'article')
		{
			return $this->pass(false);
		}

		$query = $this->db->getQuery(true)
			->select('c.projectid')
			->from('#__f2c_form AS c')
			->where('c.reference_id = ' . (int) $this->request->id);
		$this->db->setQuery($query);
		$type = $this->db->loadResult();

		$types = $this->makeArray($type);

		return $this->passSimple($types);
	}
}
