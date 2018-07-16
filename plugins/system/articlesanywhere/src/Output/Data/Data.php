<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data;

use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Values;

defined('_JEXEC') or die;

class Data implements DataInterface
{
	var $config;
	var $item;
	var $values;

	public function __construct(Config $config, Item $item, Values $values)
	{
		$this->config = $config;
		$this->item   = $item;
		$this->values = $values;
	}

	public function get($key, $attributes)
	{
		return null;
	}
}
