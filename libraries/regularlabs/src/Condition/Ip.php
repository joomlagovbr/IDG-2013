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
 * Class Ip
 * @package RegularLabs\Library\Condition
 */
class Ip
	extends \RegularLabs\Library\Condition
{
	public function pass()
	{
		if (is_array($this->selection))
		{
			$this->selection = implode(',', $this->selection);
		}

		$this->selection = explode(',', str_replace([' ', "\r", "\n"], ['', '', ','], $this->selection));

		$pass = $this->checkIPList();

		return $this->_($pass);
	}

	private function checkIPList()
	{
		foreach ($this->selection as $range)
		{
			// Check next range if this one doesn't match
			if ( ! $this->checkIP($range))
			{
				continue;
			}

			// Match found, so return true!
			return true;
		}

		// No matches found, so return false
		return false;
	}

	private function checkIP($range)
	{
		if (empty($range))
		{
			return false;
		}

		if (strpos($range, '-') !== false)
		{
			// Selection is an IP range
			return $this->checkIPRange($range);
		}

		// Selection is a single IP (part)
		return $this->checkIPPart($range);
	}

	private function checkIPRange($range)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		// Return if no IP address can be found (shouldn't happen, but who knows)
		if (empty($ip))
		{
			return false;
		}

		// check if IP is between or equal to the from and to IP range
		list($min, $max) = explode('-', trim($range), 2);

		// Return false if IP is smaller than the range start
		if ($ip < trim($min))
		{
			return false;
		}

		$max = $this->fillMaxRange($max, $min);

		// Return false if IP is larger than the range end
		if ($ip > trim($max))
		{
			return false;
		}

		return true;
	}

	/* Fill the max range by prefixing it with the missing parts from the min range
	 * So 101.102.103.104-201.202 becomes:
	 * max: 101.102.201.202
	 */
	private function fillMaxRange($max, $min)
	{
		$max_parts = explode('.', $max);

		if (count($max_parts) == 4)
		{
			return $max;
		}

		$min_parts = explode('.', $min);

		$prefix = array_slice($min_parts, 0, count($min_parts) - count($max_parts));

		return implode('.', $prefix) . '.' . implode('.', $max_parts);
	}

	private function checkIPPart($range)
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		// Return if no IP address can be found (shouldn't happen, but who knows)
		if (empty($ip))
		{
			return false;
		}

		$ip_parts    = explode('.', $ip);
		$range_parts = explode('.', trim($range));

		// Trim the IP to the part length of the range
		$ip = implode('.', array_slice($ip_parts, 0, count($range_parts)));

		// Return false if ip does not match the range
		if ($range != $ip)
		{
			return false;
		}

		return true;
	}
}
