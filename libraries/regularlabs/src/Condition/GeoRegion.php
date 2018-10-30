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
 * Class GeoRegion
 * @package RegularLabs\Library\Condition
 */
class GeoRegion
	extends Geo
{
	public function pass()
	{
		if ( ! $this->getGeo() || empty($this->geo->countryCode) || empty($this->geo->regionCodes))
		{
			return $this->_(false);
		}

		$country = $this->geo->countryCode;
		$regions = $this->geo->regionCodes;

		array_walk($regions, function (&$region, $key, $country) {

			$region = $this->getCountryRegionCode($region, $country);
		}, $country);

		return $this->passSimple($regions);
	}

	private function getCountryRegionCode(&$region, $country)
	{
		switch ($country . '-' . $region)
		{
			case 'MX-CMX':
				return 'MX-DIF';

			default:
				return $country . '-' . $region;
		}
	}
}
