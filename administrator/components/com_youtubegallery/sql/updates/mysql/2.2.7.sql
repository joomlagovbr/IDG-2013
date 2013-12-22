ALTER TABLE `#__youtubegallery` ADD COLUMN `orderby` varchar(50) NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `openinnewwindow` `openinnewwindow` smallint(6) NOT NULL;
ALTER TABLE `#__youtubegallery` ADD COLUMN `customnavlayout` text NOT NULL;
ALTER TABLE `#__youtubegallery` CHANGE `updateperiod` `updateperiod` float NOT NULL default 7;

ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `publisheddate` datetime NOT NULL;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `duration` int(11) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `rating_average` float NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `rating_max` smallint(6) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `rating_min` smallint(6) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `rating_numRaters` int(11) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `statistics_favoriteCount` smallint(6) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `statistics_viewCount` smallint(6) NOT NULL default 0;
ALTER TABLE `#__youtubegallery_videos` ADD COLUMN `keywords` text NOT NULL;
