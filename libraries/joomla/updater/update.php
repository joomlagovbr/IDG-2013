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

/**
 * Update class. It is used by JUpdater::update() to install an update. Use JUpdater::findUpdates() to find updates for
 * an extension.
 *
 * @since  11.1
 */
class JUpdate extends JObject
{
	/**
	 * Update manifest `<name>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $name;

	/**
	 * Update manifest `<description>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $description;

	/**
	 * Update manifest `<element>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $element;

	/**
	 * Update manifest `<type>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type;

	/**
	 * Update manifest `<version>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $version;

	/**
	 * Update manifest `<infourl>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $infourl;

	/**
	 * Update manifest `<client>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $client;

	/**
	 * Update manifest `<group>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $group;

	/**
	 * Update manifest `<downloads>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $downloads;

	/**
	 * Update manifest `<tags>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $tags;

	/**
	 * Update manifest `<maintainer>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $maintainer;

	/**
	 * Update manifest `<maintainerurl>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $maintainerurl;

	/**
	 * Update manifest `<category>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $category;

	/**
	 * Update manifest `<relationships>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $relationships;

	/**
	 * Update manifest `<targetplatform>` element
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $targetplatform;

	/**
	 * Extra query for download URLs
	 *
	 * @var    string
	 * @since  13.1
	 */
	protected $extra_query;

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
	 * Unused state array
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $stateStore = array();

	/**
	 * Object containing the current update data
	 *
	 * @var    stdClass
	 * @since  12.1
	 */
	protected $currentUpdate;

	/**
	 * Object containing the latest update data
	 *
	 * @var    stdClass
	 * @since  12.1
	 */
	protected $latest;

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
	 * Get the last position in stack count
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	protected function _getLastTag()
	{
		return $this->stack[count($this->stack) - 1];
	}

	/**
	 * XML Start Element callback
	 *
	 * @param   object  $parser  Parser object
	 * @param   string  $name    Name of the tag found
	 * @param   array   $attrs   Attributes of the tag
	 *
	 * @return  void
	 *
	 * @note    This is public because it is called externally
	 * @since   11.1
	 */
	public function _startElement($parser, $name, $attrs = array())
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
			// This is a new update; create a current update
			case 'UPDATE':
				$this->currentUpdate = new stdClass;
				break;

			// Don't do anything
			case 'UPDATES':
				break;

			// For everything else there's...the default!
			default:
				$name = strtolower($name);

				if (!isset($this->currentUpdate->$name))
				{
					$this->currentUpdate->$name = new stdClass;
				}

				$this->currentUpdate->$name->_data = '';

				foreach ($attrs as $key => $data)
				{
					$key = strtolower($key);
					$this->currentUpdate->$name->$key = $data;
				}
				break;
		}
	}

	/**
	 * Callback for closing the element
	 *
	 * @param   object  $parser  Parser object
	 * @param   string  $name    Name of element that was closed
	 *
	 * @return  void
	 *
	 * @note    This is public because it is called externally
	 * @since   11.1
	 */
	public function _endElement($parser, $name)
	{
		array_pop($this->stack);

		switch ($name)
		{
			// Closing update, find the latest version and check
			case 'UPDATE':
				$product = strtolower(JFilterInput::getInstance()->clean(JVersion::PRODUCT, 'cmd'));

				// Support for the min_dev_level and max_dev_level attributes is deprecated, a regexp should be used instead
				if (isset($this->currentUpdate->targetplatform->min_dev_level) || isset($this->currentUpdate->targetplatform->max_dev_level))
				{
					JLog::add(
						'Support for the min_dev_level and max_dev_level attributes of an update\'s <targetplatform> tag is deprecated and'
						. ' will be removed in 4.0. The full version should be specified in the version attribute and may optionally be a regexp.',
						JLog::WARNING,
						'deprecated'
					);
				}

				/*
				 * Check that the product matches and that the version matches (optionally a regexp)
				 *
				 * Check for optional min_dev_level and max_dev_level attributes to further specify targetplatform (e.g., 3.0.1)
				 */
				if (isset($this->currentUpdate->targetplatform->name)
					&& $product == $this->currentUpdate->targetplatform->name
					&& preg_match('/^' . $this->currentUpdate->targetplatform->version . '/', $this->get('jversion.full', JVERSION))
					&& ((!isset($this->currentUpdate->targetplatform->min_dev_level)) 
					|| $this->get('jversion.dev_level', JVersion::DEV_LEVEL) >= $this->currentUpdate->targetplatform->min_dev_level)
					&& ((!isset($this->currentUpdate->targetplatform->max_dev_level)) 
					|| $this->get('jversion.dev_level', JVersion::DEV_LEVEL) <= $this->currentUpdate->targetplatform->max_dev_level))
				{
					$phpMatch = false;

					// Check if PHP version supported via <php_minimum> tag, assume true if tag isn't present
					if (!isset($this->currentUpdate->php_minimum) || version_compare(PHP_VERSION, $this->currentUpdate->php_minimum->_data, '>='))
					{
						$phpMatch = true;
					}

					$dbMatch = false;

					// Check if DB & version is supported via <supported_databases> tag, assume supported if tag isn't present
					if (isset($this->currentUpdate->supported_databases))
					{
						$db           = JFactory::getDbo();
						$dbType       = strtolower($db->getServerType());
						$dbVersion    = $db->getVersion();
						$supportedDbs = $this->currentUpdate->supported_databases;

						// Do we have a entry for the database?
						if (isset($supportedDbs->$dbType))
						{
							$minumumVersion = $supportedDbs->$dbType;
							$dbMatch        = version_compare($dbVersion, $minumumVersion, '>=');
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

					if ($phpMatch && $stabilityMatch && $dbMatch)
					{
						if (isset($this->latest))
						{
							if (version_compare($this->currentUpdate->version->_data, $this->latest->version->_data, '>') == 1)
							{
								$this->latest = $this->currentUpdate;
							}
						}
						else
						{
							$this->latest = $this->currentUpdate;
						}
					}
				}
				break;
			case 'UPDATES':
				// If the latest item is set then we transfer it to where we want to
				if (isset($this->latest))
				{
					foreach (get_object_vars($this->latest) as $key => $val)
					{
						$this->$key = $val;
					}

					unset($this->latest);
					unset($this->currentUpdate);
				}
				elseif (isset($this->currentUpdate))
				{
					// The update might be for an older version of j!
					unset($this->currentUpdate);
				}
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
	 * @since   11.1
	 */
	public function _characterData($parser, $data)
	{
		$tag = $this->_getLastTag();

		// Throw the data for this item together
		$tag = strtolower($tag);

		if ($tag == 'tag')
		{
			$this->currentUpdate->stability = $this->stabilityTagToInteger((string) $data);

			return;
		}

		if (isset($this->currentUpdate->$tag))
		{
			$this->currentUpdate->$tag->_data .= $data;
		}
	}

	/**
	 * Loads an XML file from a URL.
	 *
	 * @param   string  $url                The URL.
	 * @param   int     $minimum_stability  The minimum stability required for updating the extension {@see JUpdater}
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function loadFromXml($url, $minimum_stability = JUpdater::STABILITY_STABLE)
	{
		$version    = new JVersion;
		$httpOption = new Registry;
		$httpOption->set('userAgent', $version->getUserAgent('Joomla', true, false));

		try
		{
			$http = JHttpFactory::getHttp($httpOption);
			$response = $http->get($url);
		}
		catch (RuntimeException $e)
		{
			$response = null;
		}

		if ($response === null || $response->code !== 200)
		{
			// TODO: Add a 'mark bad' setting here somehow
			JLog::add(JText::sprintf('JLIB_UPDATER_ERROR_EXTENSION_OPEN_URL', $url), JLog::WARNING, 'jerror');

			return false;
		}

		$this->minimum_stability = $minimum_stability;

		$this->xmlParser = xml_parser_create('');
		xml_set_object($this->xmlParser, $this);
		xml_set_element_handler($this->xmlParser, '_startElement', '_endElement');
		xml_set_character_data_handler($this->xmlParser, '_characterData');

		if (!xml_parse($this->xmlParser, $response->body))
		{
			JLog::add(
				sprintf(
					'XML error: %s at line %d', xml_error_string(xml_get_error_code($this->xmlParser)),
					xml_get_current_line_number($this->xmlParser)
				),
				JLog::WARNING, 'updater'
			);

			return false;
		}

		xml_parser_free($this->xmlParser);

		return true;
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
		$constant = 'JUpdater::STABILITY_' . strtoupper($tag);

		if (defined($constant))
		{
			return constant($constant);
		}

		return JUpdater::STABILITY_STABLE;
	}
}
