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
 * Class AkeebasubsLevel
 * @package RegularLabs\Library\Condition
 */
class AkeebasubsLevel
	extends Akeebasubs
{
	public function pass()
	{
		if ( ! $this->request->id || $this->request->option != 'com_akeebasubs' || $this->request->view != 'level')
		{
			return $this->_(false);
		}

		return $this->passSimple($this->request->id);
	}
}
