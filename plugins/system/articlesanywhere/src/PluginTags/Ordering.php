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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\PluginTags;

defined('_JEXEC') or die;

use JDatabaseDriver;
use JFactory;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

class Ordering
{
	/* @var Config */
	protected $config;

	/* @var JDatabaseDriver */
	private $db;

	public function __construct(Config $config)
	{
		$this->config = $config;
		$this->db     = JFactory::getDbo();
	}

	public function get($attributes)
	{


		return false;
	}

}
