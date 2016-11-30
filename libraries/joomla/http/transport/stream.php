<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTTP
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

use Joomla\Registry\Registry;

/**
 * HTTP transport class for using PHP streams.
 *
 * @since  11.3
 */
class JHttpTransportStream implements JHttpTransport
{
	/**
	 * @var    Registry  The client options.
	 * @since  11.3
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  Client options object.
	 *
	 * @since   11.3
	 * @throws  RuntimeException
	 */
	public function __construct(Registry $options)
	{
		// Verify that URLs can be used with fopen();
		if (!ini_get('allow_url_fopen'))
		{
			throw new RuntimeException('Cannot use a stream transport when "allow_url_fopen" is disabled.');
		}

		// Verify that fopen() is available.
		if (!self::isSupported())
		{
			throw new RuntimeException('Cannot use a stream transport when fopen() is not available or "allow_url_fopen" is disabled.');
		}

		$this->options = $options;
	}

	/**
	 * Send a request to the server and return a JHttpResponse object with the response.
	 *
	 * @param   string   $method     The HTTP method for sending the request.
	 * @param   JUri     $uri        The URI to the resource to request.
	 * @param   mixed    $data       Either an associative array or a string to be sent with the request.
	 * @param   array    $headers    An array of request headers to send with the request.
	 * @param   integer  $timeout    Read timeout in seconds.
	 * @param   string   $userAgent  The optional user agent string to send with the request.
	 *
	 * @return  JHttpResponse
	 *
	 * @since   11.3
	 * @throws  RuntimeException
	 */
	public function request($method, JUri $uri, $data = null, array $headers = null, $timeout = null, $userAgent = null)
	{
		// Create the stream context options array with the required method offset.
		$options = array('method' => strtoupper($method));

		// If data exists let's encode it and make sure our Content-Type header is set.
		if (isset($data))
		{
			// If the data is a scalar value simply add it to the stream context options.
			if (is_scalar($data))
			{
				$options['content'] = $data;
			}
			// Otherwise we need to encode the value first.
			else
			{
				$options['content'] = http_build_query($data);
			}

			if (!isset($headers['Content-Type']))
			{
				$headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
			}

			// Add the relevant headers.
			$headers['Content-Length'] = strlen($options['content']);
		}

		// If an explicit timeout is given user it.
		if (isset($timeout))
		{
			$options['timeout'] = (int) $timeout;
		}

		// If an explicit user agent is given use it.
		if (isset($userAgent))
		{
			$options['user_agent'] = $userAgent;
		}

		// Ignore HTTP errors so that we can capture them.
		$options['ignore_errors'] = 1;

		// Follow redirects.
		$options['follow_location'] = (int) $this->options->get('follow_location', 1);

		// Set any custom transport options
		foreach ($this->options->get('transport.stream', array()) as $key => $value)
		{
			$options[$key] = $value;
		}

		// Add the proxy configuration, if any.
		$config = JFactory::getConfig();

		if ($config->get('proxy_enable'))
		{
			$options['proxy'] = $config->get('proxy_host') . ':' . $config->get('proxy_port');
			$options['request_fulluri'] = true;

			// Put any required authorization into the headers array to be handled later
			// TODO: do we need to support any auth type other than Basic?
			if ($user = $config->get('proxy_user'))
			{
				$auth = base64_encode($config->get('proxy_user') . ':' . $config->get('proxy_pass'));

				$headers['Proxy-Authorization'] = 'Basic ' . $auth;
			}
		}

		// Build the headers string for the request.
		$headerEntries = null;

		if (isset($headers))
		{
			foreach ($headers as $key => $value)
			{
				$headerEntries[] = $key . ': ' . $value;
			}

			// Add the headers string into the stream context options array.
			$options['header'] = implode("\r\n", $headerEntries);
		}

		// Get the current context options.
		$contextOptions = stream_context_get_options(stream_context_get_default());

		// Add our options to the current ones, if any.
		$contextOptions['http'] = isset($contextOptions['http']) ? array_merge($contextOptions['http'], $options) : $options;

		// Create the stream context for the request.
		$context = stream_context_create(
			array(
				'http' => $options,
				'ssl' => array(
					'verify_peer'   => true,
					'cafile'        => $this->options->get('stream.certpath', __DIR__ . '/cacert.pem'),
					'verify_depth'  => 5,
				)
			)
		);

		// Authentification, if needed
		if ($this->options->get('userauth') && $this->options->get('passwordauth'))
		{
			$uri->setUser($this->options->get('userauth'));
			$uri->setPass($this->options->get('passwordauth'));
		}

		// Capture PHP errors
		$php_errormsg = '';
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);

		// Open the stream for reading.
		$stream = @fopen((string) $uri, 'r', false, $context);

		if (!$stream)
		{
			if (!$php_errormsg)
			{
				// Error but nothing from php? Create our own
				$php_errormsg = sprintf('Could not connect to resource: %s', $uri, $err, $errno);
			}

			// Restore error tracking to give control to the exception handler
			ini_set('track_errors', $track_errors);

			throw new RuntimeException($php_errormsg);
		}

		// Restore error tracking to what it was before.
		ini_set('track_errors', $track_errors);

		// Get the metadata for the stream, including response headers.
		$metadata = stream_get_meta_data($stream);

		// Get the contents from the stream.
		$content = stream_get_contents($stream);

		// Close the stream.
		fclose($stream);

		if (isset($metadata['wrapper_data']['headers']))
		{
			$headers = $metadata['wrapper_data']['headers'];
		}
		elseif (isset($metadata['wrapper_data']))
		{
			$headers = $metadata['wrapper_data'];
		}
		else
		{
			$headers = array();
		}

		return $this->getResponse($headers, $content);
	}

	/**
	 * Method to get a response object from a server response.
	 *
	 * @param   array   $headers  The response headers as an array.
	 * @param   string  $body     The response body as a string.
	 *
	 * @return  JHttpResponse
	 *
	 * @since   11.3
	 * @throws  UnexpectedValueException
	 */
	protected function getResponse(array $headers, $body)
	{
		// Create the response object.
		$return = new JHttpResponse;

		// Set the body for the response.
		$return->body = $body;

		// Get the response code from the first offset of the response headers.
		preg_match('/[0-9]{3}/', array_shift($headers), $matches);
		$code = $matches[0];

		if (is_numeric($code))
		{
			$return->code = (int) $code;
		}

		// No valid response code was detected.
		else
		{
			throw new UnexpectedValueException('No HTTP response code found.');
		}

		// Add the response headers to the response object.
		foreach ($headers as $header)
		{
			$pos = strpos($header, ':');
			$return->headers[trim(substr($header, 0, $pos))] = trim(substr($header, ($pos + 1)));
		}

		return $return;
	}

	/**
	 * Method to check if http transport stream available for use
	 *
	 * @return bool true if available else false
	 *
	 * @since   12.1
	 */
	public static function isSupported()
	{
		return function_exists('fopen') && is_callable('fopen') && ini_get('allow_url_fopen');
	}
}
