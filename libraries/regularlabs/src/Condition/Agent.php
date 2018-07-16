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

use RegularLabs\Library\MobileDetect;
use RegularLabs\Library\RegEx;

/**
 * Class Agent
 * @package RegularLabs\Library\Condition
 */
abstract class Agent
	extends \RegularLabs\Library\Condition
{
	var $agent     = null;
	var $device    = null;
	var $is_mobile = false;

	/**
	 * isPhone
	 */
	public function isPhone()
	{
		return $this->isMobile();
	}

	/**
	 * isMobile
	 */
	public function isMobile()
	{
		return $this->getDevice() == 'mobile';
	}

	/**
	 * isTablet
	 */
	public function isTablet()
	{
		return $this->getDevice() == 'tablet';
	}

	/**
	 * isDesktop
	 */
	public function isDesktop()
	{
		return $this->getDevice() == 'desktop';
	}

	/**
	 * passBrowser
	 */
	public function passBrowser($browser = '')
	{
		if ( ! $browser)
		{
			return false;
		}

		if ($browser == 'mobile')
		{
			return $this->isMobile();
		}

		// also check for _ instead of .
		$browser = RegEx::replace('\\\.([^\]])', '[\._]\1', $browser);
		$browser = str_replace('\.]', '\._]', $browser);

		return RegEx::match($browser, $this->getAgent(), $match, 'i');
	}

	/**
	 * setDevice
	 */
	private function getDevice()
	{
		if ( ! is_null($this->device))
		{
			return $this->device;
		}

		$detect = new MobileDetect;

		$this->is_mobile = $detect->isMobile();

		switch (true)
		{
			case($detect->isTablet()):
				$this->device = 'tablet';
				break;

			case ($detect->isMobile()):
				$this->device = 'mobile';
				break;

			default:
				$this->device = 'desktop';
		}

		return $this->device;
	}

	/**
	 * getAgent
	 */
	private function getAgent()
	{
		if ( ! is_null($this->agent))
		{
			return $this->agent;
		}

		$detect = new MobileDetect;
		$agent  = $detect->getUserAgent();

		switch (true)
		{
			case (stripos($agent, 'Trident') !== false):
				// Add MSIE to IE11 and others missing it
				$agent = RegEx::replace('(Trident/[0-9\.]+;.*rv[: ]([0-9\.]+))', '\1 MSIE \2', $agent);
				break;

			case (stripos($agent, 'Chrome') !== false):
				// Remove Safari from Chrome
				$agent = RegEx::replace('(Chrome/.*)Safari/[0-9\.]*', '\1', $agent);
				// Add MSIE to IE Edge and remove Chrome from IE Edge
				$agent = RegEx::replace('Chrome/.*(Edge/[0-9])', 'MSIE \1', $agent);
				break;

			case (stripos($agent, 'Opera') !== false):
				$agent = RegEx::replace('(Opera/.*)Version/', '\1Opera/', $agent);
				break;
		}

		$this->agent = $agent;

		return $this->agent;
	}
}
