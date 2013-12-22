CREATE TABLE `#__youtubegallery_backup` LIKE `#__youtubegallery`;
INSERT `#__youtubegallery_backup` SELECT * FROM `#__youtubegallery`;


CREATE TABLE `#__youtubegallery_videos_backup` LIKE `#__youtubegallery_videos`;
INSERT `#__youtubegallery_videos_backup` SELECT * FROM `#__youtubegallery_videos`;



CREATE TABLE IF NOT EXISTS `#__youtubegallery_videolists` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `listname` varchar(50) NOT NULL,
  `videolist` text,
  `catid` int(11) NOT NULL,
  `updateperiod` float NOT NULL default 7,
  `lastplaylistupdate` datetime NOT NULL,


  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


ALTER TABLE `#__youtubegallery` CHANGE `galleryname` `themename` varchar(50) NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `showgalleryname` `showlistname` tinyint(1) NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `gallerynamestyle` `listnamestyle` varchar(255) NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `showactivevideotitle` `showactivevideotitle` tinyint(1) NOT NULL;

ALTER TABLE `#__youtubegallery` CHANGE `border` `border` smallint(6) NOT NULL;

INSERT INTO `#__youtubegallery_videolists` (`id`, `listname`, `videolist`, `catid`, `updateperiod`, `lastplaylistupdate`) SELECT `id`, `themename`, `gallerylist`, `catid`, `updateperiod`, `lastplaylistupdate` FROM `#__youtubegallery`;

ALTER TABLE `#__youtubegallery` CHANGE `gallerylist` `gallerylist_old` text;
ALTER TABLE `#__youtubegallery` CHANGE `catid` `catid_old` int(11) NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `updateperiod` `updateperiod_old` float NOT NULL default 7;
ALTER TABLE `#__youtubegallery` CHANGE `lastplaylistupdate` `lastplaylistupdate_old` datetime NOT NULL;

RENAME TABLE `#__youtubegallery` TO `#__youtubegallery_themes`;


ALTER TABLE `#__youtubegallery_videos` CHANGE `galleryid` `listid` int(11) NOT NULL;



ALTER TABLE `#__youtubegallery_themes` ADD COLUMN `mediafolder` varchar(255) NOT NULL;
ALTER TABLE `#__youtubegallery_themes` ADD COLUMN `readonly` tinyint(1) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_themes` ADD COLUMN `headscript` text NOT NULL;
ALTER TABLE `#__youtubegallery_themes` ADD COLUMN `themedescription` text NOT NULL;
