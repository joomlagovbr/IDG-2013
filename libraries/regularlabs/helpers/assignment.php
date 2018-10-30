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

if (is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';
}

class RLAssignment
	extends \RegularLabs\Library\Condition
{
	function pass($pass = true, $include_type = null)
	{
		return $this->_($pass, $include_type);
	}

	public function passByPageTypes($option, $selection = [], $assignment = 'all', $add_view = false, $get_task = false, $get_layout = true)
	{
		return $this->passByPageType($option, $selection, $assignment, $add_view, $get_task, $get_layout);
	}

	public function passContentIds()
	{
		return $this->passContentId();
	}

	public function passContentKeywords($fields = ['title', 'introtext', 'fulltext'], $text = '')
	{
		return $this->passContentKeyword($fields, $text);
	}

	public function passMetaKeywords($field = 'metakey', $keywords = '')
	{
		return $this->passMetaKeyword($field, $keywords);
	}

	public function passAuthors($field = 'created_by', $author = '')
	{
		return $this->passAuthors($field, $author);
	}
}
