<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TablePhocaGalleryEf extends JTable
{
	function __construct(& $db) {
		parent::__construct('#__phocagallery_styles', 'id', $db);
	}

	function check()
	{

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = \JApplicationHelper::stringURLSafe($this->alias);
		if (trim(str_replace('-','',$this->alias)) == '') {
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		return true;
	}
}
?>
