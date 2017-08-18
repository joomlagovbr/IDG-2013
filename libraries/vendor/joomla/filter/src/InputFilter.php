<?php
/**
 * Part of the Joomla Framework Filter Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filter;

use Joomla\String\StringHelper;

/**
 * InputFilter is a class for filtering input from any data source
 *
 * Forked from the php input filter library by: Daniel Morris <dan@rootcube.com>
 * Original Contributors: Gianpaolo Racca, Ghislain Picard, Marco Wandschneider, Chris Tobin and Andrew Eddie.
 *
 * @since  1.0
 */
class InputFilter
{
	/**
	 * Defines the InputFilter instance should use a whitelist method for sanitising tags.
	 *
	 * @var    integer
	 * @since  1.3.0
	 */
	const TAGS_WHITELIST = 0;

	/**
	 * Defines the InputFilter instance should use a blacklist method for sanitising tags.
	 *
	 * @var    integer
	 * @since  1.3.0
	 */
	const TAGS_BLACKLIST = 1;

	/**
	 * Defines the InputFilter instance should use a whitelist method for sanitising attributes.
	 *
	 * @var    integer
	 * @since  1.3.0
	 */
	const ATTR_WHITELIST = 0;

	/**
	 * Defines the InputFilter instance should use a blacklist method for sanitising attributes.
	 *
	 * @var    integer
	 * @since  1.3.0
	 */
	const ATTR_BLACKLIST = 1;

	/**
	 * A container for InputFilter instances.
	 *
	 * @var    InputFilter[]
	 * @since  1.0
	 * @deprecated  1.2.0
	 */
	protected static $instances = array();

	/**
	 * The array of permitted tags (whitelist).
	 *
	 * @var    array
	 * @since  1.0
	 */
	public $tagsArray;

	/**
	 * The array of permitted tag attributes (whitelist).
	 *
	 * @var    array
	 * @since  1.0
	 */
	public $attrArray;

	/**
	 * The method for sanitising tags
	 *
	 * @var    integer
	 * @since  1.0
	 */
	public $tagsMethod;

	/**
	 * The method for sanitising attributes
	 *
	 * @var    integer
	 * @since  1.0
	 */
	public $attrMethod;

	/**
	 * A special list of blacklisted chars
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $blacklistedChars = array(
		'&tab;',
		'&space;',
		'&colon;',
		'&column;',
	);

	/**
	 * A flag for XSS checks. Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
	 *
	 * @var    integer
	 * @since  1.0
	 */
	public $xssAuto;

	/**
	 * The list of the default blacklisted tags.
	 *
	 * @var    array
	 * @since  1.0
	 */
	public $tagBlacklist = array(
		'applet',
		'body',
		'bgsound',
		'base',
		'basefont',
		'canvas',
		'embed',
		'frame',
		'frameset',
		'head',
		'html',
		'id',
		'iframe',
		'ilayer',
		'layer',
		'link',
		'meta',
		'name',
		'object',
		'script',
		'style',
		'title',
		'xml',
	);

	/**
	 * The list of the default blacklisted tag attributes. All event handlers implicit.
	 *
	 * @var    array
	 * @since  1.0
	 */
	public $attrBlacklist = array(
		'action',
		'background',
		'codebase',
		'dynsrc',
		'formaction',
		'lowsrc',
	);

	/**
	 * Constructor for InputFilter class.
	 *
	 * @param   array    $tagsArray   List of user-defined tags
	 * @param   array    $attrArray   List of user-defined attributes
	 * @param   integer  $tagsMethod  WhiteList method = 0, BlackList method = 1
	 * @param   integer  $attrMethod  WhiteList method = 0, BlackList method = 1
	 * @param   integer  $xssAuto     Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
	 *
	 * @since   1.0
	 */
	public function __construct($tagsArray = array(), $attrArray = array(), $tagsMethod = self::TAGS_WHITELIST, $attrMethod = self::ATTR_WHITELIST,
		$xssAuto = 1)
	{
		// Make sure user defined arrays are in lowercase
		$tagsArray = array_map('strtolower', (array) $tagsArray);
		$attrArray = array_map('strtolower', (array) $attrArray);

		// Assign member variables
		$this->tagsArray  = $tagsArray;
		$this->attrArray  = $attrArray;
		$this->tagsMethod = $tagsMethod;
		$this->attrMethod = $attrMethod;
		$this->xssAuto    = $xssAuto;
	}

	/**
	 * Method to be called by another php script. Processes for XSS and
	 * specified bad code.
	 *
	 * @param   mixed   $source  Input string/array-of-string to be 'cleaned'
	 * @param   string  $type    The return type for the variable:
	 *                           INT:       An integer, or an array of integers,
	 *                           UINT:      An unsigned integer, or an array of unsigned integers,
	 *                           FLOAT:     A floating point number, or an array of floating point numbers,
	 *                           BOOLEAN:   A boolean value,
	 *                           WORD:      A string containing A-Z or underscores only (not case sensitive),
	 *                           ALNUM:     A string containing A-Z or 0-9 only (not case sensitive),
	 *                           CMD:       A string containing A-Z, 0-9, underscores, periods or hyphens (not case sensitive),
	 *                           BASE64:    A string containing A-Z, 0-9, forward slashes, plus or equals (not case sensitive),
	 *                           STRING:    A fully decoded and sanitised string (default),
	 *                           HTML:      A sanitised string,
	 *                           ARRAY:     An array,
	 *                           PATH:      A sanitised file path, or an array of sanitised file paths,
	 *                           TRIM:      A string trimmed from normal, non-breaking and multibyte spaces
	 *                           USERNAME:  Do not use (use an application specific filter),
	 *                           RAW:       The raw string is returned with no filtering,
	 *                           unknown:   An unknown filter will act like STRING. If the input is an array it will return an
	 *                                      array of fully decoded and sanitised strings.
	 *
	 * @return  mixed  'Cleaned' version of input parameter
	 *
	 * @since   1.0
	 */
	public function clean($source, $type = 'string')
	{
		// Handle the type constraint cases
		switch (strtoupper($type))
		{
			case 'INT':
			case 'INTEGER':
				$pattern = '/[-+]?[0-9]+/';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						preg_match($pattern, (string) $eachString, $matches);
						$result[] = isset($matches[0]) ? (int) $matches[0] : 0;
					}
				}
				else
				{
					preg_match($pattern, (string) $source, $matches);
					$result = isset($matches[0]) ? (int) $matches[0] : 0;
				}

				break;

			case 'UINT':
				$pattern = '/[-+]?[0-9]+/';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						preg_match($pattern, (string) $eachString, $matches);
						$result[] = isset($matches[0]) ? abs((int) $matches[0]) : 0;
					}
				}
				else
				{
					preg_match($pattern, (string) $source, $matches);
					$result = isset($matches[0]) ? abs((int) $matches[0]) : 0;
				}

				break;

			case 'FLOAT':
			case 'DOUBLE':
				$pattern = '/[-+]?[0-9]+(\.[0-9]+)?([eE][-+]?[0-9]+)?/';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						preg_match($pattern, (string) $eachString, $matches);
						$result[] = isset($matches[0]) ? (float) $matches[0] : 0;
					}
				}
				else
				{
					preg_match($pattern, (string) $source, $matches);
					$result = isset($matches[0]) ? (float) $matches[0] : 0;
				}

				break;

			case 'BOOL':
			case 'BOOLEAN':

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (bool) $eachString;
					}
				}
				else
				{
					$result = (bool) $source;
				}

				break;

			case 'WORD':
				$pattern = '/[^A-Z_]/i';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) preg_replace($pattern, '', $eachString);
					}
				}
				else
				{
					$result = (string) preg_replace($pattern, '', $source);
				}

				break;

			case 'ALNUM':
				$pattern = '/[^A-Z0-9]/i';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) preg_replace($pattern, '', $eachString);
					}
				}
				else
				{
					$result = (string) preg_replace($pattern, '', $source);
				}

				break;

			case 'CMD':
				$pattern = '/[^A-Z0-9_\.-]/i';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$cleaned  = (string) preg_replace($pattern, '', $eachString);
						$result[] = ltrim($cleaned, '.');
					}
				}
				else
				{
					$result = (string) preg_replace($pattern, '', $source);
					$result = ltrim($result, '.');
				}

				break;

			case 'BASE64':
				$pattern = '/[^A-Z0-9\/+=]/i';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) preg_replace($pattern, '', $eachString);
					}
				}
				else
				{
					$result = (string) preg_replace($pattern, '', $source);
				}

				break;

			case 'STRING':
				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) $this->remove($this->decode((string) $eachString));
					}
				}
				else
				{
					$result = (string) $this->remove($this->decode((string) $source));
				}

				break;

			case 'HTML':
				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) $this->remove((string) $eachString);
					}
				}
				else
				{
					$result = (string) $this->remove((string) $source);
				}

				break;

			case 'ARRAY':
				$result = (array) $source;
				break;

			case 'PATH':
				$pattern = '/^[A-Za-z0-9_\/-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						preg_match($pattern, (string) $eachString, $matches);
						$result[] = isset($matches[0]) ? (string) $matches[0] : '';
					}
				}
				else
				{
					preg_match($pattern, $source, $matches);
					$result = isset($matches[0]) ? (string) $matches[0] : '';
				}

				break;

			case 'TRIM':
				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$cleaned  = (string) trim($eachString);
						$cleaned  = StringHelper::trim($cleaned, chr(0xE3) . chr(0x80) . chr(0x80));
						$result[] = StringHelper::trim($cleaned, chr(0xC2) . chr(0xA0));
					}
				}
				else
				{
					$result = (string) trim($source);
					$result = StringHelper::trim($result, chr(0xE3) . chr(0x80) . chr(0x80));
					$result = StringHelper::trim($result, chr(0xC2) . chr(0xA0));
				}

				break;

			case 'USERNAME':
				$pattern = '/[\x00-\x1F\x7F<>"\'%&]/';

				if (is_array($source))
				{
					$result = array();

					// Iterate through the array
					foreach ($source as $eachString)
					{
						$result[] = (string) preg_replace($pattern, '', $eachString);
					}
				}
				else
				{
					$result = (string) preg_replace($pattern, '', $source);
				}

				break;

			case 'RAW':
				$result = $source;
				break;

			default:
				// Are we dealing with an array?
				if (is_array($source))
				{
					foreach ($source as $key => $value)
					{
						// Filter element for XSS and other 'bad' code etc.
						if (is_string($value))
						{
							$source[$key] = $this->remove($this->decode($value));
						}
					}

					$result = $source;
				}
				else
				{
					// Or a string?
					if (is_string($source) && !empty($source))
					{
						// Filter source for XSS and other 'bad' code etc.
						$result = $this->remove($this->decode($source));
					}
					else
					{
						// Not an array or string... return the passed parameter
						$result = $source;
					}
				}

				break;
		}

		return $result;
	}

	/**
	 * Function to determine if contents of an attribute are safe
	 *
	 * @param   array  $attrSubSet  A 2 element array for attribute's name, value
	 *
	 * @return  boolean  True if bad code is detected
	 *
	 * @since   1.0
	 */
	public static function checkAttribute($attrSubSet)
	{
		$quoteStyle = version_compare(PHP_VERSION, '5.4', '>=') ? ENT_QUOTES | ENT_HTML401 : ENT_QUOTES;

		$attrSubSet[0] = strtolower($attrSubSet[0]);
		$attrSubSet[1] = html_entity_decode(strtolower($attrSubSet[1]), $quoteStyle, 'UTF-8');

		return ((strpos($attrSubSet[1], 'expression') !== false && $attrSubSet[0] === 'style')
			|| preg_match('/(?:(?:java|vb|live)script|behaviour|mocha)(?::|&colon;|&column;)/', $attrSubSet[1]) !== 0);
	}

	/**
	 * Internal method to iteratively remove all unwanted tags and attributes
	 *
	 * @param   string  $source  Input string to be 'cleaned'
	 *
	 * @return  string  'Cleaned' version of input parameter
	 *
	 * @since   1.0
	 */
	protected function remove($source)
	{
		// Iteration provides nested tag protection
		do
		{
			$temp = $source;
			$source = $this->cleanTags($source);
		}
		while ($temp != $source);

		return $source;
	}

	/**
	 * Internal method to strip a string of certain tags
	 *
	 * @param   string  $source  Input string to be 'cleaned'
	 *
	 * @return  string  'Cleaned' version of input parameter
	 *
	 * @since   1.0
	 */
	protected function cleanTags($source)
	{
		// First, pre-process this for illegal characters inside attribute values
		$source = $this->escapeAttributeValues($source);

		// In the beginning we don't really have a tag, so everything is postTag
		$preTag = null;
		$postTag = $source;
		$currentSpace = false;

		// Setting to null to deal with undefined variables
		$attr = '';

		// Is there a tag? If so it will certainly start with a '<'.
		$tagOpen_start = StringHelper::strpos($source, '<');

		while ($tagOpen_start !== false)
		{
			// Get some information about the tag we are processing
			$preTag .= StringHelper::substr($postTag, 0, $tagOpen_start);
			$postTag = StringHelper::substr($postTag, $tagOpen_start);
			$fromTagOpen = StringHelper::substr($postTag, 1);
			$tagOpen_end = StringHelper::strpos($fromTagOpen, '>');

			// Check for mal-formed tag where we have a second '<' before the first '>'
			$nextOpenTag = (StringHelper::strlen($postTag) > $tagOpen_start) ? StringHelper::strpos($postTag, '<', $tagOpen_start + 1) : false;

			if (($nextOpenTag !== false) && ($nextOpenTag < $tagOpen_end))
			{
				// At this point we have a mal-formed tag -- remove the offending open
				$postTag = StringHelper::substr($postTag, 0, $tagOpen_start) . StringHelper::substr($postTag, $tagOpen_start + 1);
				$tagOpen_start = StringHelper::strpos($postTag, '<');
				continue;
			}

			// Let's catch any non-terminated tags and skip over them
			if ($tagOpen_end === false)
			{
				$postTag = StringHelper::substr($postTag, $tagOpen_start + 1);
				$tagOpen_start = StringHelper::strpos($postTag, '<');
				continue;
			}

			// Do we have a nested tag?
			$tagOpen_nested = StringHelper::strpos($fromTagOpen, '<');

			if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end))
			{
				$preTag .= StringHelper::substr($postTag, 0, ($tagOpen_nested + 1));
				$postTag = StringHelper::substr($postTag, ($tagOpen_nested + 1));
				$tagOpen_start = StringHelper::strpos($postTag, '<');
				continue;
			}

			// Let's get some information about our tag and setup attribute pairs
			$tagOpen_nested = (StringHelper::strpos($fromTagOpen, '<') + $tagOpen_start + 1);
			$currentTag = StringHelper::substr($fromTagOpen, 0, $tagOpen_end);
			$tagLength = StringHelper::strlen($currentTag);
			$tagLeft = $currentTag;
			$attrSet = array();
			$currentSpace = StringHelper::strpos($tagLeft, ' ');

			// Are we an open tag or a close tag?
			if (StringHelper::substr($currentTag, 0, 1) == '/')
			{
				// Close Tag
				$isCloseTag = true;
				list ($tagName) = explode(' ', $currentTag);
				$tagName = StringHelper::substr($tagName, 1);
			}
			else
			{
				// Open Tag
				$isCloseTag = false;
				list ($tagName) = explode(' ', $currentTag);
			}

			/*
			 * Exclude all "non-regular" tagnames
			 * OR no tagname
			 * OR remove if xssauto is on and tag is blacklisted
			 */
			if ((!preg_match("/^[a-z][a-z0-9]*$/i", $tagName))
				|| (!$tagName)
				|| ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto)))
			{
				$postTag = StringHelper::substr($postTag, ($tagLength + 2));
				$tagOpen_start = StringHelper::strpos($postTag, '<');

				// Strip tag
				continue;
			}

			/*
			 * Time to grab any attributes from the tag... need this section in
			 * case attributes have spaces in the values.
			 */
			while ($currentSpace !== false)
			{
				$attr = '';
				$fromSpace = StringHelper::substr($tagLeft, ($currentSpace + 1));
				$nextEqual = StringHelper::strpos($fromSpace, '=');
				$nextSpace = StringHelper::strpos($fromSpace, ' ');
				$openQuotes = StringHelper::strpos($fromSpace, '"');
				$closeQuotes = StringHelper::strpos(StringHelper::substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;

				$startAtt = '';
				$startAttPosition = 0;

				// Find position of equal and open quotes ignoring
				if (preg_match('#\s*=\s*\"#', $fromSpace, $matches, PREG_OFFSET_CAPTURE))
				{
					// We have found an attribute, convert its byte position to a UTF-8 string length, using non-multibyte substr()
					$stringBeforeAttr = substr($fromSpace, 0, $matches[0][1]);
					$startAttPosition = StringHelper::strlen($stringBeforeAttr);
					$startAtt = $matches[0][0];
					$closeQuotePos = StringHelper::strpos(
						StringHelper::substr($fromSpace, ($startAttPosition + StringHelper::strlen($startAtt))), '"'
					);
					$closeQuotes = $closeQuotePos + $startAttPosition + StringHelper::strlen($startAtt);
					$nextEqual = $startAttPosition + StringHelper::strpos($startAtt, '=');
					$openQuotes = $startAttPosition + StringHelper::strpos($startAtt, '"');
					$nextSpace = StringHelper::strpos(StringHelper::substr($fromSpace, $closeQuotes), ' ') + $closeQuotes;
				}

				// Do we have an attribute to process? [check for equal sign]
				if ($fromSpace != '/' && (($nextEqual && $nextSpace && $nextSpace < $nextEqual) || !$nextEqual))
				{
					if (!$nextEqual)
					{
						$attribEnd = StringHelper::strpos($fromSpace, '/') - 1;
					}
					else
					{
						$attribEnd = $nextSpace - 1;
					}

					// If there is an ending, use this, if not, do not worry.
					if ($attribEnd > 0)
					{
						$fromSpace = StringHelper::substr($fromSpace, $attribEnd + 1);
					}
				}

				if (StringHelper::strpos($fromSpace, '=') !== false)
				{
					/*
					 * If the attribute value is wrapped in quotes we need to grab the substring from the closing quote,
					 * otherwise grab until the next space.
					 */
					if (($openQuotes !== false)
						&& (StringHelper::strpos(StringHelper::substr($fromSpace, ($openQuotes + 1)), '"') !== false))
					{
						$attr = StringHelper::substr($fromSpace, 0, ($closeQuotes + 1));
					}
					else
					{
						$attr = StringHelper::substr($fromSpace, 0, $nextSpace);
					}
				}
				else
					// No more equal signs so add any extra text in the tag into the attribute array [eg. checked]
				{
					if ($fromSpace != '/')
					{
						$attr = StringHelper::substr($fromSpace, 0, $nextSpace);
					}
				}

				// Last Attribute Pair
				if (!$attr && $fromSpace != '/')
				{
					$attr = $fromSpace;
				}

				// Add attribute pair to the attribute array
				$attrSet[] = $attr;

				// Move search point and continue iteration
				$tagLeft = StringHelper::substr($fromSpace, StringHelper::strlen($attr));
				$currentSpace = StringHelper::strpos($tagLeft, ' ');
			}

			// Is our tag in the user input array?
			$tagFound = in_array(strtolower($tagName), $this->tagsArray);

			// If the tag is allowed let's append it to the output string.
			if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod))
			{
				// Reconstruct tag with allowed attributes
				if (!$isCloseTag)
				{
					// Open or single tag
					$attrSet = $this->cleanAttributes($attrSet);
					$preTag .= '<' . $tagName;

					for ($i = 0, $count = count($attrSet); $i < $count; $i++)
					{
						$preTag .= ' ' . $attrSet[$i];
					}

					// Reformat single tags to XHTML
					if (StringHelper::strpos($fromTagOpen, '</' . $tagName))
					{
						$preTag .= '>';
					}
					else
					{
						$preTag .= ' />';
					}
				}
				else
					// Closing tag
				{
					$preTag .= '</' . $tagName . '>';
				}
			}

			// Find next tag's start and continue iteration
			$postTag = StringHelper::substr($postTag, ($tagLength + 2));
			$tagOpen_start = StringHelper::strpos($postTag, '<');
		}

		// Append any code after the end of tags and return
		if ($postTag != '<')
		{
			$preTag .= $postTag;
		}

		return $preTag;
	}

	/**
	 * Internal method to strip a tag of certain attributes
	 *
	 * @param   array  $attrSet  Array of attribute pairs to filter
	 *
	 * @return  array  Filtered array of attribute pairs
	 *
	 * @since   1.0
	 */
	protected function cleanAttributes($attrSet)
	{
		$newSet = array();

		$count = count($attrSet);

		// Iterate through attribute pairs
		for ($i = 0; $i < $count; $i++)
		{
			// Skip blank spaces
			if (!$attrSet[$i])
			{
				continue;
			}

			// Split into name/value pairs
			$attrSubSet = explode('=', trim($attrSet[$i]), 2);

			// Take the last attribute in case there is an attribute with no value
			$attrSubSet_0  = explode(' ', trim($attrSubSet[0]));
			$attrSubSet[0] = array_pop($attrSubSet_0);

			$attrSubSet[0] = strtolower($attrSubSet[0]);
			$quoteStyle = version_compare(PHP_VERSION, '5.4', '>=') ? ENT_QUOTES | ENT_HTML401 : ENT_QUOTES;

			// Remove all spaces as valid attributes does not have spaces.
			$attrSubSet[0] = html_entity_decode($attrSubSet[0], $quoteStyle, 'UTF-8');
			$attrSubSet[0] = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $attrSubSet[0]);
			$attrSubSet[0] = preg_replace('/\s+/u', '', $attrSubSet[0]);

			// Replace special blacklisted chars here
			foreach ($this->blacklistedChars as $blacklistedChar)
			{
				$attrSubSet[0] = str_replace($blacklistedChar, '', $attrSubSet[0]);
			}

			// Remove all "non-regular" attribute names
			// AND blacklisted attributes
			if ((!preg_match('/[a-z]*$/i', $attrSubSet[0]))
				|| (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist))
						|| (substr($attrSubSet[0], 0, 2) == 'on'))))
			{
				continue;
			}

			// XSS attribute value filtering
			if (!isset($attrSubSet[1]))
			{
				continue;
			}

			// Trim leading and trailing spaces
			$attrSubSet[1] = trim($attrSubSet[1]);

			// Strips unicode, hex, etc
			$attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);

			// Strip normal newline within attr value
			$attrSubSet[1] = preg_replace('/[\n\r]/', '', $attrSubSet[1]);

			// Strip double quotes
			$attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);

			// Convert single quotes from either side to doubles (Single quotes shouldn't be used to pad attr values)
			if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'"))
			{
				$attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
			}

			// Strip slashes
			$attrSubSet[1] = stripslashes($attrSubSet[1]);

			// Autostrip script tags
			if (static::checkAttribute($attrSubSet))
			{
				continue;
			}

			// Is our attribute in the user input array?
			$attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);

			// If the tag is allowed lets keep it
			if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod))
			{
				// Does the attribute have a value?
				if (empty($attrSubSet[1]) === false)
				{
					$newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
				}
				elseif ($attrSubSet[1] === "0")
				{
					// Special Case
					// Is the value 0?
					$newSet[] = $attrSubSet[0] . '="0"';
				}
				else
				{
					// Leave empty attributes alone
					$newSet[] = $attrSubSet[0] . '=""';
				}
			}
		}

		return $newSet;
	}

	/**
	 * Try to convert to plaintext
	 *
	 * @param   string  $source  The source string.
	 *
	 * @return  string  Plaintext string
	 *
	 * @since   1.0
	 * @deprecated  This method will be removed once support for PHP 5.3 is discontinued.
	 */
	protected function decode($source)
	{
		return html_entity_decode($source, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Escape < > and " inside attribute values
	 *
	 * @param   string  $source  The source string.
	 *
	 * @return  string  Filtered string
	 *
	 * @since   1.0
	 */
	protected function escapeAttributeValues($source)
	{
		$alreadyFiltered = '';
		$remainder = $source;
		$badChars = array('<', '"', '>');
		$escapedChars = array('&lt;', '&quot;', '&gt;');

		// Process each portion based on presence of =" and "<space>, "/>, or ">
		// See if there are any more attributes to process
		while (preg_match('#<[^>]*?=\s*?(\"|\')#s', $remainder, $matches, PREG_OFFSET_CAPTURE))
		{
			// We have found a tag with an attribute, convert its byte position to a UTF-8 string length, using non-multibyte substr()
			$stringBeforeTag = substr($remainder, 0, $matches[0][1]);
			$tagPosition = StringHelper::strlen($stringBeforeTag);

			// Get the character length before the attribute value
			$nextBefore = $tagPosition + StringHelper::strlen($matches[0][0]);

			// Figure out if we have a single or double quote and look for the matching closing quote
			// Closing quote should be "/>, ">, "<space>, or " at the end of the string
			$quote = StringHelper::substr($matches[0][0], -1);
			$pregMatch = ($quote == '"') ? '#(\"\s*/\s*>|\"\s*>|\"\s+|\"$)#' : "#(\'\s*/\s*>|\'\s*>|\'\s+|\'$)#";

			// Get the portion after attribute value
			$attributeValueRemainder = StringHelper::substr($remainder, $nextBefore);

			if (preg_match($pregMatch, $attributeValueRemainder, $matches, PREG_OFFSET_CAPTURE))
			{
				// We have a closing quote, convert its byte position to a UTF-8 string length, using non-multibyte substr()
				$stringBeforeQuote = substr($attributeValueRemainder, 0, $matches[0][1]);
				$closeQuoteChars = StringHelper::strlen($stringBeforeQuote);
				$nextAfter = $nextBefore + $matches[0][1];
			}
			else
			{
				// No closing quote
				$nextAfter = StringHelper::strlen($remainder);
			}

			// Get the actual attribute value
			$attributeValue = StringHelper::substr($remainder, $nextBefore, $nextAfter - $nextBefore);

			// Escape bad chars
			$attributeValue = str_replace($badChars, $escapedChars, $attributeValue);
			$attributeValue = $this->stripCssExpressions($attributeValue);
			$alreadyFiltered .= StringHelper::substr($remainder, 0, $nextBefore) . $attributeValue . $quote;
			$remainder = StringHelper::substr($remainder, $nextAfter + 1);
		}

		// At this point, we just have to return the $alreadyFiltered and the $remainder
		return $alreadyFiltered . $remainder;
	}

	/**
	 * Remove CSS Expressions in the form of <property>:expression(...)
	 *
	 * @param   string  $source  The source string.
	 *
	 * @return  string  Filtered string
	 *
	 * @since   1.0
	 */
	protected function stripCssExpressions($source)
	{
		// Strip any comments out (in the form of /*...*/)
		$test = preg_replace('#\/\*.*\*\/#U', '', $source);

		// Test for :expression
		if (!stripos($test, ':expression'))
		{
			// Not found, so we are done
			return $source;
		}

		// At this point, we have stripped out the comments and have found :expression
		// Test stripped string for :expression followed by a '('
		if (preg_match_all('#:expression\s*\(#', $test, $matches))
		{
			// If found, remove :expression
			return str_ireplace(':expression', '', $test);
		}

		return $source;
	}
}
