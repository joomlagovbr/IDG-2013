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

/**
 * Class K2Item
 * @package RegularLabs\Library\Condition
 */
class K2Item
	extends K2
{
	public function pass()
	{
		if ( ! $this->request->id || $this->request->option != 'com_k2' || $this->request->view != 'item')
		{
			return $this->_(false);
		}

		$pass = false;

		// Pass Article Id
		if ( ! $this->passItemByType($pass, 'ContentId'))
		{
			return $this->_(false);
		}

		// Pass Content Keyword
		if ( ! $this->passItemByType($pass, 'ContentKeyword'))
		{
			return $this->_(false);
		}

		// Pass Meta Keyword
		if ( ! $this->passItemByType($pass, 'MetaKeyword'))
		{
			return $this->_(false);
		}

		// Pass Author
		if ( ! $this->passItemByType($pass, 'Author'))
		{
			return $this->_(false);
		}

		return $this->_($pass);
	}
}
