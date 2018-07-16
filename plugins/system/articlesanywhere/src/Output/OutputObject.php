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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output;

use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data\Numbers;

defined('_JEXEC') or die;

class OutputObject
{
	var $config;
	var $item;
	var $numbers;
	var $values;

	public function __construct(Config $config, Item $item, Numbers $numbers)
	{
		$this->config  = $config;
		$this->item    = $item;
		$this->numbers = $numbers;
		$this->values  = new Values($config, $item, $numbers);
	}
}
