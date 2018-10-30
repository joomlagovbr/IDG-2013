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
 * Class AkeebasubsPagetype
 * @package RegularLabs\Library\Condition
 */
class AkeebasubsPagetype
	extends Akeebasubs
{
	public function pass()
	{
		return $this->passByPageType('com_akeebasubs', $this->selection, $this->include_type);
	}
}
