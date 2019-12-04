<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Updater\Adapter;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Updater\UpdateAdapter;
use Joomla\CMS\Updater\Updater;
use Joomla\CMS\Version;

/**
 * Extension class for updater
 *
 * @since  1.7.0
 */
class ExtensionAdapter extends UpdateAdapter
{
	/**
	 * Start element parser callback.
	 *
	 * @param   object  $parser  The parser object.
	 * @param   string  $name    The name of the element.
	 * @param   array   $attrs   The attributes of the element.
	 *
	 * @return  void
	 *
	 * @since   1.7.0
	 */
	protected function _startElement($parser, $name, $attrs = array())
	{
		$this->stack[] = $name;
		$tag           = $this->_getStackLocation();

		// Reset the data
		if (isset($this->$tag))
		{
			$this->$tag->_data = '';
		}

		switch ($name)
		{
			case 'UPDATE':
				$this->currentUpdate = Table::getInstance('update');
				$this->currentUpdate->update_site_id = $this->updateSiteId;
				$this->currentUpdate->detailsurl = $this->_url;
				$this->currentUpdate->folder = '';
				$this->currentUpdate->client_id = 1;
				break;

			// Don't do anything
			case 'UPDATES':
				break;

			default:
				if (in_array($name, $this->updatecols))
				{
					$name = strtolower($name);
					$this->currentUpdate->$name = '';
				}

				if ($name == 'TARGETPLATFORM')
				{
					$this->currentUpdate->targetplatform = $attrs;
				}

				if ($name == 'PHP_MINIMUM')
				{
					$this->currentUpdate->php_minimum = '';
				}

				if ($name == 'SUPPORTED_DATABASES')
				{
					$this->currentUpdate->supported_databases = $attrs;
				}
				break;
		}
	}

	/**
	 * Character Parser Function
	 *
	 * @param   object  $parser  Parser object.
	 * @param   object  $name    The name of the element.
	 *
	 * @return  void
	 *
	 * @since   1.7.0
	 */
	protected function _endElement($parser, $name)
	{
		array_pop($this->stack);

		// @todo remove code: echo 'Closing: '. $name .'<br />';
		switch ($name)
		{
			case 'UPDATE':
				// Lower case and remove the exclamation mark
				$product = strtolower(InputFilter::getInstance()->clean(Version::PRODUCT, 'cmd'));

				// Support for the min_dev_level and max_dev_level attributes is deprecated, a regexp should be used instead
				if (isset($this->currentUpdate->targetplatform->min_dev_level) || isset($this->currentUpdate->targetplatform->max_dev_level))
				{
					Log::add(
						'Support for the min_dev_level and max_dev_level attributes of an update\'s <targetplatform> tag is deprecated and'
						. ' will be removed in 4.0. The full version should be specified in the version attribute and may optionally be a regexp.',
						Log::WARNING,
						'deprecated'
					);
				}

				/*
				 * Check that the product matches and that the version matches (optionally a regexp)
				 *
				 * Check for optional min_dev_level and max_dev_level attributes to further specify targetplatform (e.g., 3.0.1)
				 */
				$patchMinimumSupported = !isset($this->currentUpdate->targetplatform->min_dev_level)
					|| Version::PATCH_VERSION >= $this->currentUpdate->targetplatform->min_dev_level;
				$patchMaximumSupported = !isset($this->currentUpdate->targetplatform->max_dev_level)
					|| Version::PATCH_VERSION <= $this->currentUpdate->targetplatform->max_dev_level;

				if ($product == $this->currentUpdate->targetplatform['NAME']
					&& preg_match('/^' . $this->currentUpdate->targetplatform['VERSION'] . '/', JVERSION)
					&& $patchMinimumSupported
					&& $patchMaximumSupported)
				{
					// Check if PHP version supported via <php_minimum> tag, assume true if tag isn't present
					if (!isset($this->currentUpdate->php_minimum) || version_compare(PHP_VERSION, $this->currentUpdate->php_minimum, '>='))
					{
						$phpMatch = true;
					}
					else
					{
						// Notify the user of the potential update
						$msg = \JText::sprintf(
							'JLIB_INSTALLER_AVAILABLE_UPDATE_PHP_VERSION',
							$this->currentUpdate->name,
							$this->currentUpdate->version,
							$this->currentUpdate->php_minimum,
							PHP_VERSION
						);

						Factory::getApplication()->enqueueMessage($msg, 'warning');

						$phpMatch = false;
					}

					$dbMatch = false;

					// Check if DB & version is supported via <supported_databases> tag, assume supported if tag isn't present
					if (isset($this->currentUpdate->supported_databases))
					{
						$db           = Factory::getDbo();
						$dbType       = strtoupper($db->getServerType());
						$dbVersion    = $db->getVersion();
						$supportedDbs = $this->currentUpdate->supported_databases;

						// MySQL and MariaDB use the same database driver but not the same version numbers
						if ($dbType === 'mysql')
						{
							// Check whether we have a MariaDB version string and extract the proper version from it
							if (stripos($dbVersion, 'mariadb') !== false)
							{
								// MariaDB: Strip off any leading '5.5.5-', if present
								$dbVersion = preg_replace('/^5\.5\.5-/', '', $dbVersion);
								$dbType    = 'mariadb';
							}
						}

						// Do we have an entry for the database?
						if (array_key_exists($dbType, $supportedDbs))
						{
							$minumumVersion = $supportedDbs[$dbType];
							$dbMatch        = version_compare($dbVersion, $minumumVersion, '>=');

							if (!$dbMatch)
							{
								// Notify the user of the potential update
								$dbMsg = \JText::sprintf(
									'JLIB_INSTALLER_AVAILABLE_UPDATE_DB_MINIMUM',
									$this->currentUpdate->name,
									$this->currentUpdate->version,
									\JText::_($db->name),
									$dbVersion,
									$minumumVersion
								);

								Factory::getApplication()->enqueueMessage($dbMsg, 'warning');
							}
						}
						else
						{
							// Notify the user of the potential update
							$dbMsg = \JText::sprintf(
								'JLIB_INSTALLER_AVAILABLE_UPDATE_DB_TYPE',
								$this->currentUpdate->name,
								$this->currentUpdate->version,
								\JText::_($db->name)
							);

							Factory::getApplication()->enqueueMessage($dbMsg, 'warning');
						}
					}
					else
					{
						// Set to true if the <supported_databases> tag is not set
						$dbMatch = true;
					}

					// Check minimum stability
					$stabilityMatch = true;

					if (isset($this->currentUpdate->stability) && ($this->currentUpdate->stability < $this->minimum_stability))
					{
						$stabilityMatch = false;
					}

					// Some properties aren't valid fields in the update table so unset them to prevent J! from trying to store them
					unset($this->currentUpdate->targetplatform);

					if (isset($this->currentUpdate->php_minimum))
					{
						unset($this->currentUpdate->php_minimum);
					}

					if (isset($this->currentUpdate->supported_databases))
					{
						unset($this->currentUpdate->supported_databases);
					}

					if (isset($this->currentUpdate->stability))
					{
						unset($this->currentUpdate->stability);
					}

					// If the PHP version and minimum stability checks pass, consider this version as a possible update
					if ($phpMatch && $stabilityMatch && $dbMatch)
					{
						if (isset($this->latest))
						{
							// We already have a possible update. Check the version.
							if (version_compare($this->currentUpdate->version, $this->latest->version, '>') == 1)
							{
								$this->latest = $this->currentUpdate;
							}
						}
						else
						{
							// We don't have any possible updates yet, assume this is an available update.
							$this->latest = $this->currentUpdate;
						}
					}
				}
				break;

			case 'UPDATES':
				// :D
				break;
		}
	}

	/**
	 * Character Parser Function
	 *
	 * @param   object  $parser  Parser object.
	 * @param   object  $data    The data.
	 *
	 * @return  void
	 *
	 * @note    This is public because its called externally.
	 * @since   1.7.0
	 */
	protected function _characterData($parser, $data)
	{
		$tag = $this->_getLastTag();

		if (in_array($tag, $this->updatecols))
		{
			$tag = strtolower($tag);
			$this->currentUpdate->$tag .= $data;
		}

		if ($tag == 'PHP_MINIMUM')
		{
			$this->currentUpdate->php_minimum = $data;
		}

		if ($tag == 'TAG')
		{
			$this->currentUpdate->stability = $this->stabilityTagToInteger((string) $data);
		}
	}

	/**
	 * Finds an update.
	 *
	 * @param   array  $options  Update options.
	 *
	 * @return  array  Array containing the array of update sites and array of updates
	 *
	 * @since   1.7.0
	 */
	public function findUpdate($options)
	{
		$response = $this->getUpdateSiteResponse($options);

		if ($response === false)
		{
			return false;
		}

		if (array_key_exists('minimum_stability', $options))
		{
			$this->minimum_stability = $options['minimum_stability'];
		}

		$this->xmlParser = xml_parser_create('');
		xml_set_object($this->xmlParser, $this);
		xml_set_element_handler($this->xmlParser, '_startElement', '_endElement');
		xml_set_character_data_handler($this->xmlParser, '_characterData');

		if (!xml_parse($this->xmlParser, $response->body))
		{
			// If the URL is missing the .xml extension, try appending it and retry loading the update
			if (!$this->appendExtension && (substr($this->_url, -4) != '.xml'))
			{
				$options['append_extension'] = true;

				return $this->findUpdate($options);
			}

			Log::add('Error parsing url: ' . $this->_url, Log::WARNING, 'updater');

			$app = Factory::getApplication();
			$app->enqueueMessage(\JText::sprintf('JLIB_UPDATER_ERROR_EXTENSION_PARSE_URL', $this->_url), 'warning');

			return false;
		}

		xml_parser_free($this->xmlParser);

		if (isset($this->latest))
		{
			if (isset($this->latest->client) && strlen($this->latest->client))
			{
				if (is_numeric($this->latest->client))
				{
					$byName = false;

					// <client> has to be 'administrator' or 'site', numeric values are deprecated. See https://docs.joomla.org/Special:MyLanguage/Design_of_JUpdate
					Log::add(
						'Using numeric values for <client> in the updater xml is deprecated. Use \'administrator\' or \'site\' instead.',
						Log::WARNING, 'deprecated'
					);
				}
				else
				{
					$byName = true;
				}

				$this->latest->client_id = ApplicationHelper::getClientInfo($this->latest->client, $byName)->id;
				unset($this->latest->client);
			}

			$updates = array($this->latest);
		}
		else
		{
			$updates = array();
		}

		return array('update_sites' => array(), 'updates' => $updates);
	}

	/**
	 * Converts a tag to numeric stability representation. If the tag doesn't represent a known stability level (one of
	 * dev, alpha, beta, rc, stable) it is ignored.
	 *
	 * @param   string  $tag  The tag string, e.g. dev, alpha, beta, rc, stable
	 *
	 * @return  integer
	 *
	 * @since   3.4
	 */
	protected function stabilityTagToInteger($tag)
	{
		$constant = '\\Joomla\\CMS\\Updater\\Updater::STABILITY_' . strtoupper($tag);

		if (defined($constant))
		{
			return constant($constant);
		}

		return Updater::STABILITY_STABLE;
	}
}
