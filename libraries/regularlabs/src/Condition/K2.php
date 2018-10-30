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

use JFile;

// If controller.php exists, assume this is K2 v3
defined('RL_K2_VERSION') or define('RL_K2_VERSION', JFile::exists(JPATH_ADMINISTRATOR . '/components/com_k2/controller.php') ? 3 : 2);

/**
 * Class K2
 * @package RegularLabs\Library\Condition
 */
abstract class K2
	extends \RegularLabs\Library\Condition
{
	use \RegularLabs\Library\ConditionContent;

	public function getItem($fields = [])
	{
		$query = $this->db->getQuery(true)
			->select($fields)
			->from('#__k2_items')
			->where('id = ' . (int) $this->request->id);
		$this->db->setQuery($query);

		return $this->db->loadObject();
	}
}
