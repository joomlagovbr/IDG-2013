
ALTER TABLE `#__youtubegallery` ADD COLUMN `useglass` tinyint(1) NOT NULL default 0;
ALTER TABLE `#__youtubegallery` ADD COLUMN `logocover` varchar(255) NOT NULL;

ALTER TABLE `#__youtubegallery` ADD COLUMN `cache` text NOT NULL;
ALTER TABLE `#__youtubegallery` ADD COLUMN `enablecache` tinyint(1) NOT NULL default 0;

