<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Updater
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

jimport('joomla.base.adapterinstance');

/**
 * UpdateAdapter class.
 *
 * @since  11.1
 */
abstract class JUpdateAdapter extends JAdapterInstance
{
	/**
	 * Resource handle for the XML Parser
	 *
	 * @var    resource
	 * @since  12.1
	 */
	protected $xmlParser;

	/**
	 * Element call stack
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $stack = array('base');

	/**
	 * ID of update site
	 *
	 * @var    string
	 * @since  12.1
	 */
	protected $updateSiteId = 0;

	/**
	 * Columns in the extensions table to be updated
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $updatecols = array('NAME', 'ELEMENT', 'TYPE', 'FOLDER', 'CLIENT', 'VERSION', 'DESCRIPTION', 'INFOURL', 'EXTRA_QUERY');

	/**
	 * Should we try appending a .xml extension to the update site's URL?
	 *
	 * @var   bool
	 */
	protected $appendExtension = false;

	/**
	 * The name of the update site (used in logging)
	 *
	 * @var   string
	 */
	protected $updateSiteName = '';

	/**
	 * The update site URL from which we will get the update information
	 *
	 * @var   string
	 */
	protected $_url = '';

	/**
	 * The minimum stability required for updates to be taken into account. The possible values are:
	 * 0	dev			Development snapshots, nightly builds, pre-release versions and so on
	 * 1	alpha		Alpha versions (work in progress, things are likely to be broken)
	 * 2	beta		Beta versions (major functionality in place, show-stopper bugs are likely to be present)
	 * 3	rc			Release Candidate versions (almost stable, minor bugs might be present)
	 * 4	stable		Stable versions (production quality code)
	 *
	 * @var    int
	 * @since  14.1
	 *
	 * @see    JUpdater
	 */
	protected $minimum_stability = JUpdater::STABILITY_STABLE;

	/**
	 * Gets the reference to the current direct parent
	 *
	 * @return  object
	 *
	 * @since   11.1
	 */
	protected function _getStackLocation()
	{
		return implode('->', $this->stack);
	}

	/**
	 * Gets the reference to the last tag
	 *
	 * @return  object
	 *
	 * @since   11.1
	 */
	protected function _getLastTag()
	{
		return $this->stack[count($this->stack) - 1];
	}

	/**
	 * Finds an update
	 *
	 * @param   array  $options  Options to use: update_site_id: the unique ID of the update site to look at
	 *
	 * @return  array  Update_sites and updates discovered
	 *
	 * @since   11.1
	 */
	abstract public function findUpdate($options);

	/**
	 * Toggles the enabled status of an update site. Update sites are disabled before getting the update information
	 * from their URL and enabled afterwards. If the URL fetch fails with a PHP fatal error (e.g. timeout) the faulty
	 * update site will remain disabled the next time we attempt to load the update information.
	 *
	 * @param   int   $update_site_id  The numeric ID of the update site to enable/disable
	 * @param   bool  $enabled         Enable the site when true, disable it when false
	 *
	 * @return  void
	 */
	protected function toggleUpdateSite($update_site_id, $enabled = true)
	{
		$update_site_id = (int) $update_site_id;
		$enabled = (bool) $enabled;

		if (empty($update_site_id))
		{
			return;
		}

		$db = $this->parent->getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__update_sites'))
			->set($db->qn('enabled') . ' = ' . $db->q($enabled ? 1 : 0))
			->where($db->qn('update_site_id') . ' = ' . $db->q($update_site_id));
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			// Do nothing
		}
	}

	/**
	 * Get the name of an update site. This is used in logging.
	 *
	 * @param   int  $updateSiteId  The numeric ID of the update site
	 *
	 * @return  string  The name of the update site or an empty string if it's not found
	 */
	protected function getUpdateSiteName($updateSiteId)
	{
		$updateSiteId = (int) $updateSiteId;

		if (empty($updateSiteId))
		{
			return '';
		}

		$db = $this->parent->getDbo();
		$query = $db->getQuery(true)
					->select($db->qn('name'))
					->from($db->qn('#__update_sites'))
					->where($db->qn('update_site_id') . ' = ' . $db->q($updateSiteId));
		$db->setQuery($query);

		$name = '';

		try
		{
			$name = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			// Do nothing
		}

		return $name;
	}

	/**
	 * Try to get the raw HTTP response from the update site, hopefully containing the update XML.
	 *
	 * @param   array  $options  The update options, see findUpdate() in children classes
	 *
	 * @return  bool|JHttpResponse  False if we can't connect to the site, JHttpResponse otherwise
	 *
	 * @throws  Exception
	 */
	protected function getUpdateSiteResponse($options = array())
	{
		$url = trim($options['location']);
		$this->_url = &$url;
		$this->updateSiteId = $options['update_site_id'];

		if (!isset($options['update_site_name']))
		{
			$options['update_site_name'] = $this->getUpdateSiteName($this->updateSiteId);
		}

		$this->updateSiteName  = $options['update_site_name'];
		$this->appendExtension = false;

		if (array_key_exists('append_extension', $options))
		{
			$this->appendExtension = $options['append_extension'];
		}

		if ($this->appendExtension && (substr($url, -4) != '.xml'))
		{
			if (substr($url, -1) != '/')
			{
				$url .= '/';
			}

			$url .= 'extension.xml';
		}

		// Disable the update site. If the get() below fails with a fatal error (e.g. timeout) the faulty update
		// site will remain disabled
		$this->toggleUpdateSite($this->updateSiteId, false);

		$startTime = microtime(true);

		$version    = new JVersion;
		$httpOption = new Registry;
		$httpOption->set('userAgent', $version->getUserAgent('Joomla', true, false));

		// JHttp transport throws an exception when there's no response.
		try
		{
			$http = JHttpFactory::getHttp($httpOption);
			$response = $http->get($url, array(), 20);
		}
		catch (RuntimeException $e)
		{
			$response = null;
		}

		// Enable the update site. Since the get() returned the update site should remain enabled
		$this->toggleUpdateSite($this->updateSiteId, true);

		// Log the time it took to load this update site's information
		$endTime    = microtime(true);
		$timeToLoad = sprintf('%0.2f', $endTime - $startTime);

		JLog::add(
			"Loading information from update site #{$this->updateSiteId} with name " .
			"\"$this->updateSiteName\" and URL $url took $timeToLoad seconds", JLog::INFO, 'updater'
		);

		if ($response === null || $response->code !== 200)
		{
			// If the URL is missing the .xml extension, try appending it and retry loading the update
			if (!$this->appendExtension && (substr($url, -4) != '.xml'))
			{
				$options['append_extension'] = true;

				return $this->getUpdateSiteResponse($options);
			}

			// Log the exact update site name and URL which could not be loaded
			JLog::add('Error opening url: ' . $url . ' for update site: ' . $this->updateSiteName, JLog::WARNING, 'updater');
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::sprintf('JLIB_UPDATER_ERROR_OPEN_UPDATE_SITE', $this->updateSiteId, $this->updateSiteName, $url), 'warning');

			return false;
		}

		return $response;
	}
}
