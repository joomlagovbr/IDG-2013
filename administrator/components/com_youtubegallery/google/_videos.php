<?php
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/Client.php';
require_once __DIR__ . '/Service.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Collection.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/http/REST.php';
require_once __DIR__ . '/http/Request.php';
require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/Task/Runner.php';
require_once __DIR__ . '/http/CacheParser.php';
require_once __DIR__ . '/cache/Abstract.php';
require_once __DIR__ . '/cache/File.php';
require_once __DIR__ . '/IO/Abstract.php';
require_once __DIR__ . '/IO/Stream.php';
require_once __DIR__ . '/auth/Abstract.php';
require_once __DIR__ . '/auth/OAuth2.php';
require_once __DIR__ . '/Service/Resource.php';
require_once __DIR__ . '/Service/Logging.php';
require_once __DIR__ . '/Logger/Abstract.php';
require_once __DIR__ . '/Logger/Null.php';
require_once __DIR__ . '/Service/YouTube.php';
require_once __DIR__ . '/Task/Retryable.php';
require_once __DIR__ . '/Service/Exception.php';

class YoutubeVideos
{
	var $client;

	function __construct()
	{
		$DEVELOPER_KEY = 'AIzaSyBDPLGESeCrQC9ZqrBL6CUfPwwbtbryZoc';

		$this->client = new Google_Client();
		$this->client->setDeveloperKey($DEVELOPER_KEY);

		$this->youtube = new Google_Service_YouTube($this->client);
	}

	function getChannelId($userId)
	{
		try {
			// Call the search.list method to retrieve results matching the specified
			// query term.
			$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
			  'q' => $userId,
			  'maxResults' => '3',
			  'type' => 'channel',
			  'order' => 'relevance'
			));

			$channels = array();

			// Add each result to the appropriate list, and then display the lists of
			// matching videos, channels, and playlists.
			foreach ($searchResponse['items'] as $searchResult) {

			      $channels[] = $searchResult['id']['channelId'];
			  }
		} catch (Google_Service_Exception $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
		  htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
		$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
		  htmlspecialchars($e->getMessage()));
		}

		return $channels;
	}

	function getVideosFromChannel( $channelId = '', $maxResults = '30', $order = 'date' )
	{
		$searchResponse = '';
		$htmlBody = '';
		$videos = array();

		try {

			// Call the search.list method to retrieve results matching the specified
			// query term.
			if( $maxResults == -1 )
			{
				$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
				  'channelId' => $channelId,
				  'order' => $order
				));
			}
			else
			{
				$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
				  'channelId' => $channelId,
				  'maxResults' => $maxResults,
				  'order' => $order
				));
			}

			$videos = $searchResponse['items'];
		}
		catch (Google_Service_Exception $e)
		{
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
		  	htmlspecialchars($e->getMessage()));
		}
		catch (Google_Exception $e)
		{
			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
		  	htmlspecialchars($e->getMessage()));
		}

		return (!empty($videos))? $videos : $htmlBody;
	}

	function getVideoInfo( $videoid )
	{
		$videos = array();

		try {

			$searchResponse = $this->youtube->videos->listVideos('id,snippet,contentDetails', array(
			  'id' => $videoid
			));

			$videos = $searchResponse;

		} catch (Google_Service_Exception $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
		  htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
		$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
		  htmlspecialchars($e->getMessage()));
		}

		return $videos;		
	}
}