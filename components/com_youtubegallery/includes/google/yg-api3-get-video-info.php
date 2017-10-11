<?php

$data = array(
    'code' => $_GET['code'],
    'client_id' => '588466768517-h92ivg63akeam237pedcft82suhm0ruf.apps.googleusercontent.com',
    'client_secret' => 'Eh9vWrcP3yO8H_jz4BG9XAYK',
    'redirect_uri' => 'http://j30a.joomlaboat.com/components/com_youtubegallery/includes/my_uploads.php',
    'grant_type' => 'authorization_code');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$return = curl_exec($ch);
curl_close($ch);

echo $return;

/*
$option = array(
        'part' => 'statistics', 
        'mine' => 'true',
        'key' => 'AIzaSyBXIwzqH-wOvZwe6F415X7cdFoQjnY1u6U'
    );
$url = "https://www.googleapis.com/youtube/v3/channels?".http_build_query($option, 'a', '&');

echo 'url='.$url.'<br/>';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
$curlheader[0] = "Authorization: Bearer " . $accessToken;
curl_setopt($curl, CURLOPT_HTTPHEADER, $curlheader);

$json_response = curl_exec($curl);

curl_close($curl);

$responseObj = json_decode($json_response);
*/


$videoid='CfuET_D4V1c';
$url = 'http://gdata.youtube.com/feeds/api/videos/'.$videoid.'?v=2'; //v=2to get likes and dislikes

echo getURLData($url);



function getURLData($url)
    {
			$htmlcode='';
		
			if (function_exists('curl_init'))
			{
				$ch = curl_init();
				$timeout = 180;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$htmlcode = curl_exec($ch);
				curl_close($ch);
			}
			elseif (ini_get('allow_url_fopen') == true)
			{
				$htmlcode = file_get_contents($url);
			}
			else
			{
				echo '<p style="color:red;">Cannot load data, enable "allow_url_fopen" or install cURL<br/>
				<a href="http://joomlaboat.com/youtube-gallery/f-a-q/why-i-see-allow-url-fopen-message" target="_blank">Here</a> is what to do.
				</p>
				';
			}

			return $htmlcode;

    }
	

?>