
<?php
/**
 * @GNU General Public License
 *
 */
defined('_JEXEC') or die('Restricted access');

if (!function_exists('json_decode'))
    JFactory::getApplication()->enqueueMessage('json_decode not found', 'error');

class Vimeo

{
	const ROOT_ENDPOINT = 'https://api.vimeo.com';
	const AUTH_ENDPOINT = 'https://api.vimeo.com/oauth/authorize';
	const ACCESS_TOKEN_ENDPOINT = '/oauth/access_token';
	const CLIENT_CREDENTIALS_TOKEN_ENDPOINT = '/oauth/authorize/client';
	const REPLACE_ENDPOINT = '/files';
	const VERSION_STRING = 'application/vnd.vimeo.*+json; version=3.2';
	const USER_AGENT = 'vimeo.php 1.0; (http://developer.vimeo.com/api/docs)';
	const CERTIFICATE_PATH = '/includes/Vimeo/certificates/vimeo-api.pem';
	private $_client_id = null;
	private $_client_secret = null;
	private $_access_token = null;
	protected $_curl_opts = array();
	protected $CURL_DEFAULTS = array();
	public	function __construct($client_id, $client_secret, $access_token = null)
	{
		$this->_client_id = $client_id;
		$this->_client_secret = $client_secret;
		$this->_access_token = $access_token;
		$this->CURL_DEFAULTS = array(
			CURLOPT_HEADER => 1,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYPEER => true,

			// Certificate must indicate that the server is the server to which you meant to connect.

			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_CAINFO => realpath(__DIR__ . '/../..') . self::CERTIFICATE_PATH
		);
	}

	public	function request($url, $params = array() , $method = 'GET', $json_body = true)
	{

		// add accept header hardcoded to version 3.0

		$headers[] = 'Accept: ' . self::VERSION_STRING;
		$headers[] = 'User-Agent: ' . self::USER_AGENT;
		$method = strtoupper($method);

		// add bearer token, or client information

		if (!empty($this->_access_token))
		{
			$headers[] = 'Authorization: Bearer ' . $this->_access_token;
		}
		else
		{

			//  this may be a call to get the tokens, so we add the client info.

			$headers[] = 'Authorization: Basic ' . $this->_authHeader();
		}

		//  Set the methods, determine the URL that we should actually request and prep the body.

		$curl_opts = array();
		switch ($method)
		{
		case 'GET':
			if (!empty($params))
			{
				$query_component = '?' . http_build_query($params, '', '&');
			}
			else
			{
				$query_component = '';
			}

			$curl_url = self::ROOT_ENDPOINT . $url . $query_component;
			break;

		case 'POST':
		case 'PATCH':
		case 'PUT':
		case 'DELETE':
			if ($json_body && !empty($params))
			{
				$headers[] = 'Content-Type: application/json';
				$body = json_encode($params);
			}
			else
			{
				$body = http_build_query($params, '', '&');
			}

			$curl_url = self::ROOT_ENDPOINT . $url;
			$curl_opts = array(
				CURLOPT_POST => true,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_POSTFIELDS => $body
			);
			break;
		}

		// Set the headers

		$curl_opts[CURLOPT_HTTPHEADER] = $headers;
		$response = $this->_request($curl_url, $curl_opts);
		$response['body'] = json_decode($response['body'], true);
		return $response;
	}

	private function _request($url, $curl_opts = array())
	{

		// Merge the options (custom options take precedence).

		$curl_opts = $this->_curl_opts + $curl_opts + $this->CURL_DEFAULTS;

		// Call the API.

		$curl = curl_init($url);
		curl_setopt_array($curl, $curl_opts);
		$response = curl_exec($curl);
		$curl_info = curl_getinfo($curl);
		if (isset($curl_info['http_code']) && $curl_info['http_code'] === 0)
		{
			$curl_error = curl_error($curl);
			$curl_error = !empty($curl_error) ? '[' . $curl_error . ']' : '';
			echo 'Unable to complete request.';

			// throw new VimeoRequestException('Unable to complete request.' . $curl_error);

		}

		curl_close($curl);

		// Retrieve the info

		$header_size = $curl_info['header_size'];
		$headers = substr($response, 0, $header_size);
		$body = substr($response, $header_size);

		// Return it raw.

		return array(
			'body' => $body,
			'status' => $curl_info['http_code'],
			'headers' => self::parse_headers($headers)
		);
	}

	public function getToken()
	{
		return $this->_access_token;
	}

	public function setToken($access_token)
	{
		$this->_access_token = $access_token;
	}

	public function setCURLOptions($curl_opts = array())
	{
		$this->_curl_opts = $curl_opts;
	}

	public static function parse_headers($headers)
	{
		$final_headers = array();
		$list = explode("\n", trim($headers));
		$http = array_shift($list);
		foreach($list as $header)
		{
			$parts = explode(':', $header, 2);
			$final_headers[trim($parts[0]) ] = isset($parts[1]) ? trim($parts[1]) : '';
		}

		return $final_headers;
	}

	public function accessToken($code, $redirect_uri)
	{
		return $this->request(self::ACCESS_TOKEN_ENDPOINT, array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $redirect_uri
		) , "POST", false);
	}

	public function clientCredentials($scope = 'public')
	{
		if (is_array($scope))
		{
			$scope = implode(' ', $scope);
		}

		$token_response = $this->request(self::CLIENT_CREDENTIALS_TOKEN_ENDPOINT, array(
			'grant_type' => 'client_credentials',
			'scope' => $scope
		) , "POST", false);
		return $token_response;
	}

	private function _authHeader()
	{
		return base64_encode($this->_client_id . ':' . $this->_client_secret);
	}

	public function buildAuthorizationEndpoint($redirect_uri, $scope = 'public', $state = null)
	{
		$query = array(
			"response_type" => 'code',
			"client_id" => $this->_client_id,
			"redirect_uri" => $redirect_uri
		);
		$query['scope'] = $scope;
		if (empty($scope))
		{
			$query['scope'] = 'public';
		}
		elseif (is_array($scope))
		{
			$query['scope'] = implode(' ', $scope);
		}

		if (!empty($state))
		{
			$query['state'] = $state;
		}

		return self::AUTH_ENDPOINT . '?' . http_build_query($query);
	}

}
