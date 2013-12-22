CREATE TABLE IF NOT EXISTS `#__youtubegallery_videos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `galleryid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `videosource` varchar(30) NOT NULL,
  `videoid` varchar(30) NOT NULL,
  `imageurl` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `custom_imageurl` varchar(255) NOT NULL,
  `custom_title` varchar(255) NOT NULL,
  `custom_description` text NOT NULL,
  `specialparams` varchar(255) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `allowupdates` tinyint(1) NOT NULL default 0,
  `status` smallint(6) NOT NULL,
  `isvideo` tinyint(1) NOT NULL default 0,
  `link` varchar(255) NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `#__youtubegallery` ADD COLUMN `updateperiod` smallint(6) NOT NULL;
ALTER TABLE `#__youtubegallery` ADD COLUMN `lastplaylistupdate` datetime NOT NULL;