<?php
/**
 * @version		4.7.0
 * @package		AllVideos (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/* -------------------------------- Embed templates for VIDEO -------------------------------- */
$mediaplayerEmbed = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	jwplayer('avID_{SOURCEID}').setup({
		'file': '{SITEURL}/{FOLDER}/{SOURCE}.{FILE_EXT}',
		'image': '{PLAYER_POSTER_FRAME}',
		'height': '{HEIGHT}',
		'width': '{WIDTH}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}',
		'controls': '{JWPLAYER_CONTROLS}'
	});
</script>
";

$mediaplayerEmbedRemote = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	jwplayer('avID_{SOURCEID}').setup({
		'file': '{SOURCE}',
		'image': '{PLAYER_POSTER_FRAME_REMOTE}',
		'height': '{HEIGHT}',
		'width': '{WIDTH}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}',
		'controls': '{JWPLAYER_CONTROLS}'
	});
</script>
";

/* -------------------------------- Embed templates for AUDIO -------------------------------- */
$audioPlayerEmbed = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	jwplayer('avID_{SOURCEID}').setup({
		'file': '{SITEURL}/{FOLDER}/{SOURCE}.{FILE_EXT}',
		'image': '{PLAYER_POSTER_FRAME}',
		'height': '{HEIGHT}',
		'width': '{WIDTH}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}',
		'controls': '{JWPLAYER_CONTROLS}'
	});
</script>
";

$audioPlayerEmbedRemote = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	jwplayer('avID_{SOURCEID}').setup({
		'file': '{SOURCE}',
		'image': '{PLAYER_POSTER_FRAME_REMOTE}',
		'height': '{HEIGHT}',
		'width': '{WIDTH}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}',
		'controls': '{JWPLAYER_CONTROLS}'
	});
</script>
";

/* -------------------------------- Embed templates for Quicktime Media -------------------------------- */
$qtEmbed = "
<script type=\"text/javascript\">
	QT_WriteOBJECT_XHTML('{SITEURL}/{FOLDER}/{SOURCE}.{FILE_EXT}', '{WIDTH}', '{HEIGHT}', '', 'autoplay', '{PLAYER_AUTOPLAY}', 'loop', '{PLAYER_LOOP}', 'bgcolor', '{PLAYER_BACKGROUNDQT}', 'scale', 'aspect', 'wmode', 'transparent', 'title', 'JoomlaWorks AllVideos Player');
</script>
";

$qtEmbedRemote = "
<script type=\"text/javascript\">
	QT_WriteOBJECT_XHTML('{SOURCE}', '{WIDTH}', '{HEIGHT}', '', 'autoplay', '{PLAYER_AUTOPLAY}', 'loop', '{PLAYER_LOOP}', 'bgcolor', '{PLAYER_BACKGROUNDQT}', 'scale', 'aspect', 'wmode', 'transparent', 'title', 'JoomlaWorks AllVideos Player');
</script>
";

/* -------------------------------- Embed templates for Windows Media -------------------------------- */
$wmEmbed = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	var cnt = document.getElementById('avID_{SOURCEID}');
	var src = '{PLUGIN_PATH}/includes/js/wmvplayer/wmvplayer.xaml';
	var cfg = {
		'file': '{SITEURL}/{FOLDER}/{SOURCE}.{FILE_EXT}',
		'image': '{PLAYER_POSTER_FRAME}',
		'width': '{WIDTH}',
		'height': '{HEIGHT}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}'
	};
	var ply = new jeroenwijering.Player(cnt,src,cfg);
</script>
";

$wmEmbedRemote = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
	var cnt = document.getElementById('avID_{SOURCEID}');
	var src = '{PLUGIN_PATH}/includes/js/wmvplayer/wmvplayer.xaml';
	var cfg = {
		'file': '{SOURCE}',
		'width': '{WIDTH}',
		'height': '{HEIGHT}',
		'autostart': '{PLAYER_AUTOPLAY}',
		'repeat': '{PLAYER_LOOP}'
	};
	var ply = new jeroenwijering.Player(cnt,src,cfg);
</script>
";



/* -------------------------------- Tags & formats -------------------------------- */
$tagReplace = array(

/* --- Audio/Video formats --- */

/* JW Player compatible media */
"flv" 			=> $mediaplayerEmbed,
"flvremote" 	=> $mediaplayerEmbedRemote,
"mp4" 			=> $mediaplayerEmbed,
"mp4remote" 	=> $mediaplayerEmbedRemote,
"ogv" 			=> $mediaplayerEmbed,
"ogvremote" 	=> $mediaplayerEmbedRemote,
"webm" 			=> $mediaplayerEmbed,
"webmremote" 	=> $mediaplayerEmbedRemote,
"f4v" 			=> $mediaplayerEmbed,
"f4vremote" 	=> $mediaplayerEmbedRemote,
"m4v" 			=> $mediaplayerEmbed,
"m4vremote" 	=> $mediaplayerEmbedRemote,
"3gp" 			=> $mediaplayerEmbed,
"3gpremote" 	=> $mediaplayerEmbedRemote,
"3g2" 			=> $mediaplayerEmbed,
"3g2remote" 	=> $mediaplayerEmbedRemote,

"mp3" 			=> $audioPlayerEmbed,
"mp3remote" 	=> $audioPlayerEmbedRemote,
"aac" 			=> $audioPlayerEmbed,
"aacremote" 	=> $audioPlayerEmbedRemote,
"m4a" 			=> $audioPlayerEmbed,
"m4aremote" 	=> $audioPlayerEmbedRemote,
"ogg" 			=> $audioPlayerEmbed,
"oggremote" 	=> $audioPlayerEmbedRemote,

/* Quicktime */
"mov" 			=> $qtEmbed,
"movremote"		=> $qtEmbedRemote,
"mpeg" 			=> $qtEmbed,
"mpegremote" 	=> $qtEmbedRemote,
"mpg" 			=> $qtEmbed,
"mpgremote" 	=> $qtEmbedRemote,
"avi" 			=> $qtEmbed,
"aviremote" 	=> $qtEmbedRemote,

/* Windows Media */
"wmv" 			=> $wmEmbed,
"wma" 			=> $wmEmbed,
"wmvremote" 	=> $wmEmbedRemote,
"wmaremote" 	=> $wmEmbedRemote,

/* DivX */
"divx" => "
<object type=\"video/divx\" data=\"{SITEURL}/{FOLDER}/{SOURCE}.divx\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"type\" value=\"video/divx\" />
	<param name=\"src\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.divx\" />
	<param name=\"data\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.divx\" />
	<param name=\"codebase\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.divx\" />
	<param name=\"url\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.divx\" />
	<param name=\"mode\" value=\"full\" />
	<param name=\"pluginspage\" value=\"http://go.divx.com/plugin/download/\" />
	<param name=\"allowContextMenu\" value=\"true\" />
	<param name=\"previewImage\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.jpg\" />
	<param name=\"autoPlay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"minVersion\" value=\"1.0.0\" />
	<param name=\"custommode\" value=\"none\" />
	<p>No video? Get the DivX browser plug-in for <a href=\"http://download.divx.com/player/DivXWebPlayerInstaller.exe\">Windows</a> or <a href=\"http://download.divx.com/player/DivXWebPlayer.dmg\">Mac</a></p>
</object>
",

"divxremote" => "
<object type=\"video/divx\" data=\"{SOURCE}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"type\" value=\"video/divx\" />
	<param name=\"src\" value=\"{SOURCE}\" />
	<param name=\"data\" value=\"{SOURCE}\" />
	<param name=\"codebase\" value=\"{SOURCE}\" />
	<param name=\"url\" value=\"{SOURCE}\" />
	<param name=\"mode\" value=\"full\" />
	<param name=\"pluginspage\" value=\"http://go.divx.com/plugin/download/\" />
	<param name=\"allowContextMenu\" value=\"true\" />
	<param name=\"previewImage\" value=\"\" />
	<param name=\"autoPlay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"minVersion\" value=\"1.0.0\" />
	<param name=\"custommode\" value=\"none\" />
	<p>No video? Get the DivX browser plug-in for <a href=\"http://download.divx.com/player/DivXWebPlayerInstaller.exe\">Windows</a> or <a href=\"http://download.divx.com/player/DivXWebPlayer.dmg\">Mac</a></p>
</object>
",

/* SWF */
"swf" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"{SITEURL}/{FOLDER}/{SOURCE}.swf\">
	<param name=\"movie\" value=\"{SITEURL}/{FOLDER}/{SOURCE}.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
</object>
",

"swfremote" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"{SOURCE}\">
	<param name=\"movie\" value=\"{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
</object>
",



/* --- Major 3rd party video providers --- */
// youtube.com - http://www.youtube.com/watch?v=g5lGNkS5TE0 or https://www.youtube.com/playlist?list=PL0875C16C899A8DE6
"YouTube" => "<iframe src=\"//www.youtube.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// vimeo.com - http://www.vimeo.com/1319796
"Vimeo" => "<iframe src=\"//player.vimeo.com/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// dailymotion.com - http://www.dailymotion.com/featured/video/x35714_cap-nord-projet-1_creation
"Dailymotion" => "<iframe src=\"//www.dailymotion.com/embed/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// vine.co - https://vine.co/v/hr9OQTHJYPj
"Vine" => "<iframe src=\"{SOURCE}/embed/simple?audio=1\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// soundcloud.com - http://soundcloud.com/sebastien-tellier/look
"SoundCloud" => "
<script type=\"text/javascript\">
	allvideos.ready(function(){
		allvideos.embed({
			'url': 'http://soundcloud.com/oembed?format=js&iframe=true&callback=soundcloud{SOURCEID}&auto_play={PLAYER_AUTOPLAY}&maxwidth={WIDTH}&url={SOURCE}',
			'callback': 'soundcloud{SOURCEID}',
			'playerID': 'avID_{SOURCEID}'
		});
	});
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",



/* --- Other 3rd party video providers --- */
// 123video.nl - http://www.123video.nl/playvideos.asp?MovieID=248020
"123video" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.123video.nl/123video_emb.swf?mediaSrc={SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.123video.nl/123video_emb.swf?mediaSrc={SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
</object>
",

// aniboom.com - http://www.aniboom.com/video/28604/Kashe-Li-Its-Hard/
"Aniboom" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://api.aniboom.com/e/{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://api.aniboom.com/e/{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowscriptaccess\" value=\"sameDomain\" />
</object>
",

// blip.tv - http://blip.tv/joomlaworks/k2-for-joomla-dec-2010-4565453
"Blip" => "
<script type=\"text/javascript\">
	allvideos.ready(function(){
		allvideos.embed({
			'url': 'https://blip.tv/oembed/?callback=bliptv{SOURCEID}&maxwidth={WIDTH}&maxheight={HEIGHT}&url={SOURCE}',
			'callback': 'bliptv{SOURCEID}',
			'playerID': 'avID_{SOURCEID}'
		});
	});
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",

// collegehumor.com - http://www.collegehumor.com/video/6961115/should-we-do-a-bitcoin-sketch
"CollegeHumor" => "<iframe src=\"http://www.collegehumor.com/e/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// dotsub.com - http://dotsub.com/view/9518104c-aa15-4646-9a39-a789e5586cdb
"Dotsub" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://dotsub.com/static/players/portalplayer.swf?v=3407\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://dotsub.com/static/players/portalplayer.swf?v=3407\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashvars\" value=\"type=flv&plugins=dotsub&debug=none&tid=UA-3684979-1&uuid={SOURCE}&lang=eng\" />
</object>
",

// flickr.com - https://www.flickr.com/photos/bswise/5930051523/in/pool-726923@N23/
"Flickr" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"https://www.flickr.com/apps/video/stewart.swf?v=109786\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"https://www.flickr.com/apps/video/stewart.swf?v=109786\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashvars\" value=\"intl_lang=en-us&amp;div_id=stewart_swf{SOURCE}_div&amp;flickr_notracking=true&amp;flickr_target=_self&amp;flickr_h={HEIGHT}&amp;flickr_w={WIDTH}&amp;flickr_no_logo=true&amp;onsite=true&amp;flickr_noAutoPlay=false&amp;in_photo_gne=true&amp;photo_secret=&amp;photo_id={SOURCE}&amp;flickr_doSmall=true\" />
</object>
",

// funnyordie.com - http://www.funnyordie.com/videos/7c52bd0f81/the-pussy-patch-from-lil-jon
"FunnyOrDie" => "<iframe src=\"http://www.funnyordie.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// gametrailers.com - http://www.gametrailers.com/video/downloadable-content-soul-calibur/41925
"GameTrailers" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://media.mtvnservices.com/mgid:moses:video:gametrailers.com:{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://media.mtvnservices.com/mgid:moses:video:gametrailers.com:{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"sameDomain\" />
	<param name=\"base\" value=\".\" />
	<param name=\"flashvars\" value=\"\" />
</object>
",

// gloria.tv - http://gloria.tv/?media=509392
"Gloria" => "<iframe src=\"http://gloria.tv/?embed=frame&amp;media={SOURCE}&amp;width={WIDTH}&amp;height={HEIGHT}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" scrolling=\"no\" allowfullscreen title=\"JoomlaWorks AllVideos Player\"></iframe>",

// goal4replay.net - http://www.goal4replay.net/VideoWatchF.asp?ID=56215&Ln=En
"goal4replay" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{VWIDTH}px;height:{VHEIGHT}px;\" data=\"http://www.goal4replay.net/videoEmbedLa.swf?ID={AVSOURCE}&amp;MediaID=1\">
	<param name=\"movie\" value=\"http://www.goal4replay.net/videoEmbedLa.swf?ID={AVSOURCE}&amp;MediaID=1\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// godtube.com - http://www.godtube.com/watch/?v=FJ219MNU
"GodTube" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://media.salemwebnetwork.com/godtube/resource/mediaplayer/5.6/player.swf\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://media.salemwebnetwork.com/godtube/resource/mediaplayer/5.6/player.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashvars\" value=\"file=http://www.godtube.com/resource/mediaplayer/{SOURCE}.file&image=http://www.godtube.com/resource/mediaplayer/{SOURCE}.jpg&screencolor=000000&type=video&autostart={PLAYER_AUTOPLAY}&playonce=true&skin=http://media.salemwebnetwork.com/godtube/resource/mediaplayer/skin/default/videoskin.swf&logo.file=undefinedtheme/default/media/embed-logo.png&logo.link=http://www.godtube.com/watch/%3Fv%3D{SOURCE}&logo.position=top-left&logo.hide=false&controlbar.position=over\" />
</object>
",

// grindtv.com - http://www.grindtv.com/outdoor/video/snowmobile_riding_in_labrador/#60513
"GrindTV" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://static.grindtv.com/player/optics.swf?sa=1&si=1&i={SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://static.grindtv.com/player/optics.swf?sa=1&si=1&i={SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// kewego.com - http://www.kewego.com/video/iLyROoafYcaT.html
"Kewego" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://sll.kewego.com/swf/kp.swf\" title=\"JoomlaWorks AllVideos Player\" name=\"kplayer_{SOURCE}\" id=\"kplayer_{SOURCE}\">
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashVars\" value=\"language_code=en&playerKey=902e0deec887&configKey=&suffix=&sig={SOURCE}&autostart={PLAYER_AUTOPLAY}\" />
	<param name=\"movie\" value=\"http://sll.kewego.com/swf/kp.swf\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<video id=\"kewego_HTML5_{SOURCE}\" poster=\"http://api.kewego.com/video/getHTML5Thumbnail/?playerKey=902e0deec887&amp;sig={SOURCE}\" controls=\"true\" height=\"{HEIGHT}\" width=\"{WIDTH}\" preload=\"none\">
		<source src=\"http://api.kewego.com/video/getHTML5Stream/?playerKey=902e0deec887&amp;sig={SOURCE}\" type=\"video/mp4\" width=\"{WIDTH}\" height=\"{HEIGHT}\" />
	</video>
</object>
",

// ku6.com (China) - http://v.ku6.com/special/show_4416694/SaBUoSwhqBgcuTd1.html
"Ku6" => "<script data-vid=\"{SOURCE}\" src=\"//player.ku6.com/out/v.js\" data-width=\"{WIDTH}\" data-height=\"{HEIGHT}\"></script>",

// liveleak.com - http://www.liveleak.com/view?i=2eb_1217374911
"LiveLeak" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.liveleak.com/e/{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.liveleak.com/e/{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// livevideo.com - http://www.livevideo.com/video/EuroChild/5EEFC251BB0C43229FB0C9C70A30AF69/speakswedishstupid-a-furry-s.aspx
"LiveVideo" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.livevideo.com/flvplayer/embed/{SOURCE}&autoStart={PLAYER_AUTOPLAY}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.livevideo.com/flvplayer/embed/{SOURCE}&autoStart={PLAYER_AUTOPLAY}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// metacafe.com - http://www.metacafe.com/watch/6758278/senna_movie_trailer/
"Metacafe" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.metacafe.com/fplayer/{SOURCE}.swf\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.metacafe.com/fplayer/{SOURCE}.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// myspace.com - http://www.myspace.com/video/vid/37910278
"myspace" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://mediaservices.myspace.com/services/media/embed.aspx/m={SOURCE},t=1,mt=video\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://mediaservices.myspace.com/services/media/embed.aspx/m={SOURCE},t=1,mt=video\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"sameDomain\" />
</object>
",

// myvideo.de - http://www.myvideo.de/watch/8200801/Call_out_Video_Zeig_mir_wie_du_tanzt
"MyVideo" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.myvideo.de/movie/{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.myvideo.de/movie/{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// sapo.pt - http://videos.sapo.pt/34NipYH7bWgUzc3pZgwo
"SAPO" => "<iframe src=\"http://videos.sapo.pt/playhtml?file=http://rd3.videos.sapo.pt/{SOURCE}/mov/1\" frameborder=\"0\" scrolling=\"no\" width=\"{WIDTH}\" height=\"{HEIGHT}\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// screenr.com - http://www.screenr.com/LQ2s
"Screenr" => "<iframe src=\"http://www.screenr.com/embed/{SOURCE}\" frameborder=\"0\" width=\"{WIDTH}\" height=\"{HEIGHT}\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// sevenload.com - http://www.sevenload.com/videos/mausepingel-par-excellence-512b6a3e32b0c28c5500035b
"Sevenload" => "<iframe src=\"http://embed.sevenload.com/widgets/singlePlayer/{SOURCE}/?autoplay={PLAYER_AUTOPLAY}&env=slcom-ext\" width=\"{WIDTH}\" height=\"{HEIGHT}\" title=\"JoomlaWorks AllVideos Player\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>",

// sohu.com (China) - http://my.tv.sohu.com/us/16361148/6854750.shtml
"Sohu" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://share.vrs.sohu.com/my/v.swf&id={SOURCE}&topBar=1&autoplay={PLAYER_AUTOPLAY}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://share.vrs.sohu.com/my/v.swf&id={SOURCE}&topBar=1&autoplay={PLAYER_AUTOPLAY}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"Always\" />
</object>
",

// southparkstudios.com (clips only) - http://www.southparkstudios.com/clips/388728/it-sounds-like-poo
"SouthPark" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://media.mtvnservices.com/mgid:cms:item:southparkstudios.com:{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://media.mtvnservices.com/mgid:cms:item:southparkstudios.com:{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// stupidvideos.com - http://www.stupidvideos.com/video/animals/Cat_Practices_Jumping_Before_Jump_1/#334088
"stupidvideos" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://images.stupidvideos.com/2.0.2/swf/video.swf?sa=1&sk=7&si=2&i={SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://images.stupidvideos.com/2.0.2/swf/video.swf?sa=1&sk=7&si=2&i={SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// tudou.com - http://www.tudou.com/programs/view/vHRA5NP3ge8/
"tudou" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.tudou.com/v/{SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.tudou.com/v/{SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// ustream.tv - http://www.ustream.tv/recorded/15746278
"ustream" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.ustream.tv/flash/viewer.swf\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.ustream.tv/flash/viewer.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashVars\" value=\"loc=%2F&amp;autoplay={PLAYER_AUTOPLAY}&amp;vid={SOURCE}&amp;locale=en_US&amp;hasticket=false&amp;v3=1\" />
</object>
",

// vbox7.com - http://vbox7.com/play:1cbe43e895
"vbox7" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://i47.vbox7.com/player/ext.swf?vid={SOURCE}\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://i47.vbox7.com/player/ext.swf?vid={SOURCE}\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// veevr.com - http://veevr.com/videos/T5THb081f
"veevr" => "<iframe src=\"http://veevr.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// veoh.com - http://www.veoh.com/watch/v21091373cQe4FGa9
"veoh" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=&permalinkId={SOURCE}&player=videodetailsembedded&videoAutoPlay={PLAYER_AUTOPLAY}&id=anonymous\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=&permalinkId={SOURCE}&player=videodetailsembedded&videoAutoPlay={PLAYER_AUTOPLAY}&id=anonymous\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
</object>
",

// vidiac.com - http://www.vidiac.com/video/Dating-Stupid-Girls;Funny-Stuff
"vidiac" => "<iframe src=\"http://www.vidiac.com/video/{SOURCE}/player?layout=&read_more=1\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// screen.yahoo.com - https://screen.yahoo.com/comedy/sad-dog-does-not-baths-103338320.html
"yahoo" => "<iframe width=\"{WIDTH}\" height=\"{HEIGHT}\" scrolling=\"no\" frameborder=\"0\" src=\"{SOURCE}?format=embed\" allowfullscreen=\"true\" mozallowfullscreen=\"true\" webkitallowfullscreen=\"true\" allowtransparency=\"true\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// yfrog.com - http://yfrog.com/0ia9mcz
"yfrog" => "<iframe src=\"http://yfrog.com/{SOURCE}:embed\" width=\"{WIDTH}\" height=\"{HEIGHT}\" frameborder=\"0\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// youku.com (China) - http://v.youku.com/v_show/id_XNzAxNDM3Ng==.html
"youku" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://player.youku.com/player.php/sid/{SOURCE}/v.swf\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://player.youku.com/player.php/sid/{SOURCE}/v.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"sameDomain\" />
</object>
",

// youmaker.com - http://www.youmaker.com/video/svb5-ac21c98f0eb1467faa423c52fe90ee9f0020.html
"youmaker" => "
<object type=\"application/x-shockwave-flash\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" data=\"http://www.youmaker.com/v.swf\" title=\"JoomlaWorks AllVideos Player\">
	<param name=\"movie\" value=\"http://www.youmaker.com/v.swf\" />
	<param name=\"quality\" value=\"high\" />
	<param name=\"wmode\" value=\"{PLAYER_TRANSPARENCY}\" />
	<param name=\"bgcolor\" value=\"{PLAYER_BACKGROUND}\" />
	<param name=\"autoplay\" value=\"{PLAYER_AUTOPLAY}\" />
	<param name=\"loop\" value=\"{PLAYER_LOOP}\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"flashvars\" value=\"file=http://www.youmaker.com/video/v/nu/{SOURCE}.xml&showdigits=true&overstretch=fit&autostart={PLAYER_AUTOPLAY}&linkfromdisplay=false&rotatetime=12&repeat=list&shuffle=false&showfsbutton=false&fsreturnpage=&fullscreenpage=\" />
</object>
",

);
