ALTER TABLE `#__youtubegallery` ADD COLUMN `catid` int(11) NOT NULL;
ALTER TABLE `#__youtubegallery` ADD COLUMN `controls` tinyint(1) NOT NULL default 1;
ALTER TABLE `#__youtubegallery` ADD COLUMN `youtubeparams` varchar(450) NOT NULL;
ALTER TABLE `#__youtubegallery` ADD COLUMN `playertype` smallint(6) NOT NULL;

