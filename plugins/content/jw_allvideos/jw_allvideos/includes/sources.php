<?php
/**
 * @version    4.8.0
 * @package    AllVideos (plugin)
 * @author     JoomlaWorks - http://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2017 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/* -------------------------------- Clappr embed templates -------------------------------- */
$clapprEmbed = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
    var player = new Clappr.Player({
        source: '{SITEURL}/{FOLDER}/{SOURCE}.{FILE_EXT}',
        poster: '{PLAYER_POSTER_FRAME}',
        height: '{HEIGHT}',
        width: '{WIDTH}',
        autoPlay: {PLAYER_AUTOPLAY},
        loop: {PLAYER_LOOP},
        //hideMediaControl: {JWPLAYER_CONTROLS},
        parentId: '#avID_{SOURCEID}'
    });
</script>
";

$clapprEmbedRemote = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
    var player = new Clappr.Player({
        source: '{SOURCE}',
        poster: '{PLAYER_POSTER_FRAME_REMOTE}',
        height: '{HEIGHT}',
        width: '{WIDTH}',
        autoPlay: {PLAYER_AUTOPLAY},
        loop: {PLAYER_LOOP},
        //hideMediaControl: {JWPLAYER_CONTROLS},
        parentId: '#avID_{SOURCEID}'
    });
</script>
";

/* -------------------------------- JW Player embed templates -------------------------------- */
$jwPlayerEmbed = "
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

$jwPlayerEmbedRemote = "
<div id=\"avID_{SOURCEID}\" style=\"width:{WIDTH}px;height:{HEIGHT}px;\" title=\"JoomlaWorks AllVideos Player\"></div>
<script type=\"text/javascript\">
    jwplayer('avID_{SOURCEID}').setup({
        'file': '{SOURCE}',
        'image': '{PLAYER_POSTER_FRAME_REMOTE}',
        'height': '{HEIGHT}',
        'width': '{WIDTH}',
        'type': '{FILE_TYPE}',
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
"3gp"           => $jwPlayerEmbed,
"3gpremote"     => $jwPlayerEmbedRemote,
"3g2"           => $jwPlayerEmbed,
"3g2remote"     => $jwPlayerEmbedRemote,

"mp4"           => $clapprEmbed,
"mp4remote"     => $clapprEmbedRemote,
"m4v"           => $jwPlayerEmbed,
"m4vremote"     => $jwPlayerEmbedRemote,
"mkv"           => $jwPlayerEmbed,
"mkvremote"     => $jwPlayerEmbedRemote,
"webm"          => $clapprEmbed,
"webmremote"    => $clapprEmbedRemote,

"ogv"           => $jwPlayerEmbed,
"ogvremote"     => $jwPlayerEmbedRemote,

"flv"           => $jwPlayerEmbed,
"flvremote"     => $jwPlayerEmbedRemote,
"f4v"           => $jwPlayerEmbed,
"f4vremote"     => $jwPlayerEmbedRemote,

"mp3"           => $clapprEmbed,
"mp3remote"     => $clapprEmbedRemote,
"aac"           => $jwPlayerEmbed,
"aacremote"     => $jwPlayerEmbedRemote,
"m4a"           => $jwPlayerEmbed,
"m4aremote"     => $jwPlayerEmbedRemote,
"ogg"           => $jwPlayerEmbed,
"oggremote"     => $jwPlayerEmbedRemote,
"oga"           => $jwPlayerEmbed,
"ogaremote"     => $jwPlayerEmbedRemote,

/* Quicktime */
"mov"           => $qtEmbed,
"movremote"     => $qtEmbedRemote,
"mpeg"          => $qtEmbed,
"mpegremote"    => $qtEmbedRemote,
"mpg"           => $qtEmbed,
"mpgremote"     => $qtEmbedRemote,
"avi"           => $qtEmbed,
"aviremote"     => $qtEmbedRemote,

/* Windows Media */
"wmv"           => $wmEmbed,
"wma"           => $wmEmbed,
"wmvremote"     => $wmEmbedRemote,
"wmaremote"     => $wmEmbedRemote,

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

// youtube.com - https://www.youtube.com/watch?v=g5lGNkS5TE0 or https://www.youtube.com/playlist?list=PL0875C16C899A8DE6
"YouTube" => "<iframe src=\"https://www.youtube.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// vimeo.com - http://www.vimeo.com/1319796
"Vimeo" => "<iframe src=\"https://player.vimeo.com/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// dailymotion.com - http://www.dailymotion.com/featured/video/x35714_cap-nord-projet-1_creation
"Dailymotion" => "<iframe src=\"https://www.dailymotion.com/embed/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// facebook.com - https://www.facebook.com/Channel4News/videos/10155042102006939/
"Facebook" => "<iframe src=\"https://www.facebook.com/plugins/video.php?href={SOURCE}&show_text=0&width={WIDTH}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// soundcloud.com - https://soundcloud.com/sebastien-tellier/look
"SoundCloud" => "
<script type=\"text/javascript\">
    allvideos.ready(function(){
        allvideos.embed({
            'url': 'https://soundcloud.com/oembed?format=js&iframe=true&callback=soundcloud{SOURCEID}&auto_play={PLAYER_AUTOPLAY}&maxwidth={WIDTH}&url={SOURCE}',
            'callback': 'soundcloud{SOURCEID}',
            'playerID': 'avID_{SOURCEID}'
        });
    });
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",



/* --- Other 3rd party video providers --- */
// collegehumor.com - http://www.collegehumor.com/video/6961115/should-we-do-a-bitcoin-sketch
"CollegeHumor" => "
<script type=\"text/javascript\">
    allvideos.ready(function(){
        allvideos.embed({
            'url': 'https://json2jsonp.com/?callback=collegeHumor{SOURCEID}&url=http%3A%2F%2Fwww.collegehumor.com%2Foembed.json%3Fmaxwidth%3D{WIDTH}%26url%3D{SOURCE}',
            'callback': 'collegeHumor{SOURCEID}',
            'playerID': 'avID_{SOURCEID}'
        });
    });
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",

// dotsub.com - https://dotsub.com/view/6c5d7514-5656-476a-9504-07dd4e2f6509
"Dotsub" => "<iframe src=\"https://dotsub.com/media/{SOURCE}/embed/\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// flickr.com - https://www.flickr.com/photos/bswise/5930051523/in/pool-726923@N23/
"Flickr" => "
<script type=\"text/javascript\">
    allvideos.ready(function(){
        allvideos.embed({
            'url': 'https://json2jsonp.com/?callback=flickr{SOURCEID}&url=https%3A%2F%2Fwww.flickr.com%2Fservices%2Foembed%2F%3Fformat%3Djson%26maxwidth%3D{WIDTH}%26maxheight%3D{HEIGHT}%26url%3D{SOURCE}',
            'callback': 'flickr{SOURCEID}',
            'playerID': 'avID_{SOURCEID}'
        });
    });
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",

// funnyordie.com - https://www.funnyordie.com/videos/7c52bd0f81/the-pussy-patch-from-lil-jon
"FunnyOrDie" => "<iframe src=\"https://www.funnyordie.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// gloria.tv - https://gloria.tv/video/radASVdUvido2xP8bd8n6zEpS
"Gloria" => "<iframe src=\"https://gloria.tv/embed/+/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// godtube.com - http://www.godtube.com/watch/?v=FJ219MNU
"GodTube" => "<iframe src=\"https://www.godtube.com/embed/watch/{SOURCE}/?w=728&h=408&ap=true&sl=true&title=true&dp=true\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// ku6.com (China) - http://ku6.com/2017/detail.html?vid=MY8_sA92OcjFb4EezXaFZQ
"Ku6" => $clapprEmbedRemote,

// liveleak.com - https://www.liveleak.com/view?i=ea0_1506997861
"LiveLeak" => "<iframe src=\"https://www.liveleak.com/ll_embed?i={SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// metacafe.com - http://www.metacafe.com/watch/11498826/amazing-girl-pours-rainbow-shots/
"Metacafe" => "<iframe src=\"{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// myspace.com - https://myspace.com/myspace/video/the-pedicab-interviews-chris-cole/109546118
"Myspace" => "<iframe src=\"//media.myspace.com/play/video/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// rutube.ru - https://rutube.ru/video/2e311cf4495d9b69304b50a6aafe72cb/
"Rutube" => "
<script type=\"text/javascript\">
    allvideos.ready(function(){
        allvideos.embed({
            'url': 'https://json2jsonp.com/?callback=rutube{SOURCEID}&url=https%3A%2F%2Frutube.ru%2Fapi%2Foembed%2F%3Fformat%3Djson%26maxwidth%3D{WIDTH}%26maxheight%3D{HEIGHT}%26url%3D{SOURCE}',
            'callback': 'rutube{SOURCEID}',
            'playerID': 'avID_{SOURCEID}'
        });
    });
</script>
<div id=\"avID_{SOURCEID}\" title=\"JoomlaWorks AllVideos Player\">&nbsp;</div>
",

// sapo.pt - http://videos.sapo.pt/A9AWbpCMRmFKA2ZZIdNm - oEmbed API: https://rd3.videos.sapo.pt/oembed?format=json&url=http://videos.sapo.pt/A9AWbpCMRmFKA2ZZIdNm
"SAPO" => "<iframe src=\"https://rd3.videos.sapo.pt/playhtml?file=https://rd3.videos.sapo.pt/{SOURCE}/mov/1\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// screenr.com - http://www.screenr.com/LQ2s [retired but existing videos still work]
"Screenr" => "<iframe src=\"http://www.screenr.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// twitch.tv - https://go.twitch.tv/rom_bik (channel) or https://www.twitch.tv/videos/179343513 (video)
"Twitch" => "<iframe src=\"https://player.twitch.tv/?{SOURCE}&autoplay=false\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// ustream.tv - http://www.ustream.tv/recorded/108521696
"Ustream" => "<iframe src=\"https://www.ustream.tv/embed/recorded/{SOURCE}?html5ui\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// vbox7.com - https://www.vbox7.com/play:213e79223b
"VBox7" => "<iframe src=\"https://www.vbox7.com/emb/external.php?vid={SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

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

// videa.hu - http://videa.hu/videok/filmklub/film-animacio/tom-cruise-tancol-kockazatos-uzlet-pmd0e3xAJUEbJird
"Videa" => "<iframe src=\"//videa.hu/player?v={SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// vine.co - https://vine.co/v/hr9OQTHJYPj
"Vine" => "<iframe src=\"{SOURCE}/embed/simple?audio=1\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// yahoo.com - https://uk.news.yahoo.com/video/catalan-protesters-block-roads-general-114436805.html
"Yahoo" => "<iframe src=\"{SOURCE}?format=embed\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// youku.com (China) - http://v.youku.com/v_show/id_XMzAyNjcxNDkzNg==.html?spm=a2hww.20027244.ykRecommend.5~5!2~5~5~A
"Youku" => "<iframe src=\"https://player.youku.com/embed/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

// youmaker.com - https://www.youmaker.com/video/722db6ac-1ce4-4beb-7385-9621278e738a
"YouMaker" => "<iframe src=\"https://www.youmaker.com/api/{SOURCE}\" width=\"{WIDTH}\" height=\"{HEIGHT}\" allowfullscreen=\"true\" frameborder=\"0\" scrolling=\"no\" title=\"JoomlaWorks AllVideos Player\"></iframe>",

);
