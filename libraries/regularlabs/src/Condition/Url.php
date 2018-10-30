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

use JUri;
use RegularLabs\Library\RegEx;
use RegularLabs\Library\StringHelper;

/**
 * Class Url
 * @package RegularLabs\Library\Condition
 */
class Url
	extends \RegularLabs\Library\Condition
{
	public function pass()
	{
		$regex = isset($this->params->regex) ? $this->params->regex : false;

		if ( ! is_array($this->selection))
		{
			$this->selection = explode("\n", $this->selection);
		}

		if (count($this->selection) == 1)
		{
			$this->selection = explode("\n", $this->selection[0]);
		}

		$url = JUri::getInstance();
		$url = $url->toString();

		$urls = [
			StringHelper::html_entity_decoder(urldecode($url)),
			urldecode($url),
			StringHelper::html_entity_decoder($url),
			$url,
		];
		$urls = array_unique($urls);

		$pass = false;
		foreach ($urls as $url)
		{
			foreach ($this->selection as $s)
			{
				$s = trim($s);
				if ($s == '')
				{
					continue;
				}

				if ($regex)
				{
					$url_part = str_replace(['#', '&amp;'], ['\#', '(&amp;|&)'], $s);
					if (@RegEx::match($url_part, $url))
					{
						$pass = true;
						break;
					}

					continue;
				}

				if (strpos($url, $s) !== false)
				{
					$pass = true;
					break;
				}
			}

			if ($pass)
			{
				break;
			}
		}

		return $this->_($pass);
	}
}
