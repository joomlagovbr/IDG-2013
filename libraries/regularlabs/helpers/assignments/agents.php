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

/* @DEPRECATED */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/assignment.php';
require_once dirname(__DIR__) . '/text.php';
require_once dirname(__DIR__) . '/mobile_detect.php';

class RLAssignmentsAgents extends RLAssignment
{
	var $agent  = null;
	var $device = null;

	/**
	 * passBrowsers
	 */
	public function passBrowsers()
	{
		if (empty($this->selection))
		{
			return $this->pass(false);
		}

		foreach ($this->selection as $browser)
		{
			if ( ! $this->passBrowser($browser))
			{
				continue;
			}

			return $this->pass(true);
		}

		return $this->pass(false);
	}

	/**
	 * passOS
	 */
	public function passOS()
	{
		return self::passBrowsers();
	}

	/**
	 * passDevices
	 */
	public function passDevices()
	{
		$pass = (in_array('mobile', $this->selection) && $this->isMobile())
			|| (in_array('tablet', $this->selection) && $this->isTablet())
			|| (in_array('desktop', $this->selection) && $this->isDesktop());

		return $this->pass($pass);
	}

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
	 * setDevice
	 */
	private function getDevice()
	{
		if ( ! is_null($this->device))
		{
			return $this->device;
		}

		$detect = new RLMobile_Detect;

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

		$detect = new RLMobile_Detect;
		$agent  = $detect->getUserAgent();

		switch (true)
		{
			case (stripos($agent, 'Trident') !== false):
				// Add MSIE to IE11
				$agent = preg_replace('#(Trident/[0-9\.]+; rv:([0-9\.]+))#is', '\1 MSIE \2', $agent);
				break;

			case (stripos($agent, 'Chrome') !== false):
				// Remove Safari from Chrome
				$agent = preg_replace('#(Chrome/.*)Safari/[0-9\.]*#is', '\1', $agent);
				// Add MSIE to IE Edge and remove Chrome from IE Edge
				$agent = preg_replace('#Chrome/.*(Edge/[0-9])#is', 'MSIE \1', $agent);
				break;

			case (stripos($agent, 'Opera') !== false):
				$agent = preg_replace('#(Opera/.*)Version/#is', '\1Opera/', $agent);
				break;
		}

		$this->agent = $agent;

		return $this->agent;
	}

	/**
	 * passBrowser
	 */
	private function passBrowser($browser = '')
	{
		if ( ! $browser)
		{
			return false;
		}

		if ($browser == 'mobile')
		{
			return $this->isMobile();
		}

		if ( ! (strpos($browser, '#') === 0))
		{
			$browser = '#' . RLText::pregQuote($browser) . '#';
		}

		// also check for _ instead of .
		$browser = preg_replace('#\\\.([^\]])#', '[\._]\1', $browser);
		$browser = str_replace('\.]', '\._]', $browser);

		return preg_match($browser . 'i', $this->getAgent());
	}
}
