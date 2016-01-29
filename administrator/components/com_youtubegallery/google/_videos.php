<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT.DS.'components'.DS.'com_youtubegallery'.DS.'includes'.DS.'misc.php';
require_once __DIR__ . '/Client.php';
require_once __DIR__ . '/Service.php';
require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Collection.php';
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/Http/REST.php';
require_once __DIR__ . '/Http/Request.php';
require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/Task/Runner.php';
require_once __DIR__ . '/Http/CacheParser.php';
require_once __DIR__ . '/Cache/Abstract.php';
require_once __DIR__ . '/Cache/File.php';
require_once __DIR__ . '/IO/Abstract.php';
require_once __DIR__ . '/IO/Stream.php';
require_once __DIR__ . '/Task/Retryable.php';
require_once __DIR__ . '/IO/Exception.php';
require_once __DIR__ . '/IO/Curl.php';
require_once __DIR__ . '/Auth/Abstract.php';
require_once __DIR__ . '/Auth/OAuth2.php';
require_once __DIR__ . '/Service/Resource.php';
require_once __DIR__ . '/Service/Logging.php';
require_once __DIR__ . '/Logger/Abstract.php';
require_once __DIR__ . '/Logger/Null.php';
require_once __DIR__ . '/Service/YouTube.php';
require_once __DIR__ . '/Service/Exception.php';

class YoutubeVideos
{
	var $client;

	function __construct()
	{
		$DEVELOPER_KEY = YouTubeGalleryMisc::getSettingValue('youtube_public_api');

		$this->client = new Google_Client();
		$this->client->setDeveloperKey($DEVELOPER_KEY);

		$this->youtube = new Google_Service_YouTube($this->client);
	}

	function getChannelId($userId)
	{

		$htmlBody = '';
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
		  	@htmlspecialchars($e->getMessage()));
			$channels = array();
		} catch (Google_Exception $e) {
			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
			@htmlspecialchars($e->getMessage()));
			$channels = array();
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

	function getVideosFromPlaylist( $playlistId = '', $maxResults = '30', $order = 'date' )
	{
		$searchResponse = '';
		$htmlBody = '';
		$videos = array();

		$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&playlistId=5298F5DAD70298FC&key=AIzaSyAMLeFkN9velU1rNODN6qF96nTQBcVn-Iw';

		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url
		));
		$result = curl_exec($curl);

		$result = json_decode($result);
// 		echo "<pre>";
// var_dump($result->items);
// die();
		if(isset($result->items))
			$videos = $result->items;

		return $videos;
		/*
// var_dump($this->youtube->playlists);
		try {

			// Call the search.list method to retrieve results matching the specified
			// query term.
			if( $maxResults == -1 )
			{
				$searchResponse = $this->youtube->playlists->listPlaylists('contentDetails', array(
				  'playlistId' => $playlistId
				));
			}
			else
			{
				$searchResponse = $this->youtube->playlists->listPlaylists('contentDetails', array(
				  'playlistId' => $playlistId
				));
			}
			echo "<pre>";
var_dump($videos);
die();
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
var_dump($videos);
die();
		return (!empty($videos))? $videos : $htmlBody;
		*/
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

	function getVideosFromSearch( $keywords, $maxResults = '30', $order = 'date'  )
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
				  'q' => $keywords,
				  'order' => $order
				));
			}
			else
			{
				$searchResponse = $this->youtube->search->listSearch('id,snippet', array(
				  'q' => $keywords,
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
}