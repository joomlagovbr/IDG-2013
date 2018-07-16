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

use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

class PluginTags
{
	static $message = '';

	public function get($string)
	{
		$matches = $this->getMatchesFromString($string);

		return array_map(function ($match) {
			return new PluginTag($match);
		}, $matches);
	}

	public function getMatchesFromString($string)
	{
		if ($string == '' || ! RL_String::contains($string, Params::getTags(true)))
		{
			return [];
		}

		$regex = Params::getRegex();

		if ( ! RL_RegEx::match($regex, $string))
		{
			return [];
		}

		RL_RegEx::matchAll($regex, $string, $matches);

		return $matches;
	}
}
