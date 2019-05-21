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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;

/**
 * Class K2Pagetype
 * @package RegularLabs\Library\Condition
 */
class K2Pagetype
	extends K2
{
	public function pass()
	{
		// K2 messes with the task in the request, so we have to reset the task
		$this->request->task = JFactory::getApplication()->input->get('task');

		return $this->passByPageType('com_k2', $this->selection, $this->include_type, false, true);
	}
}
