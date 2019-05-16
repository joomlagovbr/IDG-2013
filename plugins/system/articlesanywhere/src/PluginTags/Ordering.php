<?php
/**
 * @package         Articles Anywhere
 * @version         9.2.0
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\PluginTags;

defined('_JEXEC') or die;

use JDatabaseDriver;
use Joomla\CMS\Factory as JFactory;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields\CustomFields;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

class Ordering
{
	/* @var Config */
	protected $config;

	/* @var JDatabaseDriver */
	private $db;

	public function __construct(Config $config, CustomFields $custom_fields)
	{
		$this->config        = $config;
		$this->db            = JFactory::getDbo();
		$this->custom_fields = $custom_fields->getAvailableFields();
	}

	public function get($attributes)
	{


		return false;
	}

}
