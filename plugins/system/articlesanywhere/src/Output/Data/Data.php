<?php
/**
 * @package         Articles Anywhere
 * @version         9.3.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data;

defined('_JEXEC') or die;

use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Values;

class Data implements DataInterface
{
	var    $config;
	var    $item;
	var    $values;
	static $static_item;

	public function __construct(Config $config, Item $item, Values $values)
	{
		$this->config      = $config;
		$this->item        = $item;
		$this->values      = $values;
		self::$static_item = $item;
	}

	public function get($key, $attributes)
	{
		return null;
	}
}
