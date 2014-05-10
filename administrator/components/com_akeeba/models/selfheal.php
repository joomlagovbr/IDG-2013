<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Self-healing database schema features
 *
 */
class AkeebaModelSelfheal extends F0FModel
{
	private $schemata = array();

	public function __construct($config = array()) {
		parent::__construct($config);

		$schemata['#__ak_profiles'] = <<<ENDSQL
CREATE TABLE IF NOT EXISTS `#__ak_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `configuration` longtext,
  `filters` longtext,
  PRIMARY KEY  (`id`)
) DEFAULT CHARACTER SET utf8;
ENDSQL;
		$schemata['default_profile'] = <<<ENDSQL
INSERT IGNORE INTO `#__ak_profiles` (`id`,`description`, `configuration`, `filters`) VALUES (1,'Default Backup Profile','','');
ENDSQL;
		$schemata['#__ak_stats'] = <<<ENDSQL
CREATE TABLE IF NOT EXISTS `#__ak_stats` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `description` varchar(255) NOT NULL,
  `comment` longtext,
  `backupstart` timestamp NOT NULL default '0000-00-00 00:00:00',
  `backupend` timestamp NOT NULL default '0000-00-00 00:00:00',
  `status` enum('run','fail','complete') NOT NULL default 'run',
  `origin` VARCHAR(30) NOT NULL DEFAULT 'backend',
  `type` VARCHAR(30) NOT NULL DEFAULT 'full',
  `profile_id` bigint(20) NOT NULL default '1',
  `archivename` longtext,
  `absolute_path` longtext,
  `multipart` INT NOT NULL DEFAULT 0,
  `tag` VARCHAR(255) NULL,
  `filesexist` TINYINT(3) NOT NULL DEFAULT '1',
  `remote_filename` varchar(1000) DEFAULT NULL,
  `total_size` bigint(20) NOT NULL DEFAULT '0',
  INDEX `idx_fullstatus`(`filesexist`, `status`),
  INDEX `idx_stale`(`status`, `origin`),
  PRIMARY KEY  (`id`)
) DEFAULT CHARACTER SET utf8;
ENDSQL;
		$schemata['#__ak_storage'] = <<<ENDSQL
CREATE TABLE IF NOT EXISTS `#__ak_storage` (
	`tag` VARCHAR(255) NOT NULL,
	`lastupdate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`data` LONGTEXT,
	PRIMARY KEY (`tag`)
) DEFAULT CHARACTER SET utf8;
ENDSQL;
	}

	/**
	 * Heals the database schema.
	 *
	 * @return bool False if self-healing failed
	 */
	public function healSchema()
	{
		// Only run when this component runs under the MySQL database engine
		//if(!$this->isMySQL()) return true;

		$db = JFactory::getDBO();

		// Fix missing tables
		if(!$this->tableExists('#__ak_profiles')) {
			if(!$this->runSQL($this->schemata['#__ak_profiles'])) return false;
			if(!$this->runSQL($this->schemata['default_profile'])) return false;
		}
		foreach(array('#__ak_stats','#__ak_storage') as $table) {
			if(!$this->tableExists($table)) {
				if(!$this->runSQL($this->schemata[$table])) return false;
			}
		}

		// Fix the #__ak_stats table (req. for upgrades from 3.0/.a1 to 3.1./3.1 releases)
		if(!$this->columnExists('#__ak_stats', 'total_size')) {
			$hasTagColumn = !$this->columnExists('#__ak_stats', 'tag');

			// Drop any existing #__ak_stats_bak table
			if(!$this->runSQL('DROP TABLE IF EXISTS `#__ak_stats_bak`')) {
				if($db->getErrorNum() != 1060) return false;
			}

			// Create a new #__ak_stats_bak table
			$sql = $this->schemata['#__ak_stats'];
			$sql = str_replace('#__ak_stats', '#__ak_stats_bak', $sql);
			if(!$this->runSQL($sql)) {
				if($db->getErrorNum() != 1060) return false;
			}

			// Copy existing data from #__ak_stats to #__ak_stats_bak
			if($hasTagColumn) {
				// Upgrade from 3.1.3 or later (has tag and filesexist columns)
						$sql = <<<ENDSQL
INSERT IGNORE INTO `#__ak_stats_bak`
	(`id`,`description`,`comment`,`backupstart`,`backupend`,`status`,`origin`,`type`,`profile_id`,`archivename`,`absolute_path`,`multipart`,`tag`,`filesexist`)
SELECT
  `id`,`description`,`comment`,`backupstart`,`backupend`,`status`,`origin`,`type`,`profile_id`,`archivename`,`absolute_path`,`multipart`,`tag`,`filesexist`
FROM
  `#__ak_stats`;
ENDSQL;
			} else {
				// Upgrade from 3.1.2 or earlier
				$sql = <<<ENDSQL
INSERT IGNORE INTO `#__ak_stats_bak`
	(`id`,`description`,`comment`,`backupstart`,`backupend`,`status`,`origin`,`type`,`profile_id`,`archivename`,`absolute_path`,`multipart`)
SELECT
  `id`,`description`,`comment`,`backupstart`,`backupend`,`status`,`origin`,`type`,`profile_id`,`archivename`,`absolute_path`,`multipart`
FROM
  `#__ak_stats`;
ENDSQL;
			}

			if(!$this->runSQL($sql)) {
				if($db->getErrorNum() != 1060) return false;
			}

			// Drop the broken #__ak_stats table
			if(!$this->runSQL('DROP TABLE IF EXISTS `#__ak_stats`')) return false;

			// Create the #__ak_stats table afresh
			if(!$this->runSQL($this->schemata['#__ak_stats'])) return false;

			// Move data from the #__ak_stats_bak to the new #__ak_stats table
			if(!$this->runSQL('INSERT IGNORE INTO `#__ak_stats` SELECT * FROM `#__ak_stats_bak`')) return false;

			// Drop the #__ak_stats_bak table
			if(!$this->runSQL('DROP TABLE IF EXISTS `#__ak_stats_bak`')) return false;
		}

		// If we're still here, our schema is up-to-date!
		return true;
	}

	/**
	 * Runs a SQL statement and return false if the query failed
	 * @param string $sql The SQL command to run
	 * @return bool
	 */
	private function runSQL($sql)
	{
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		try {
			$db->execute();
		} catch(DatabaseException $e) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if a particular column exists in a table
	 * @param string $table The table name to check, e.g. #__ak_stats
	 * @param string $column The column to check, e.g. tag
	 *
	 * @return bool True if the column exists
	 */
	private function columnExists($table, $column)
	{
		$db = JFactory::getDBO();

		// First, try using DESCRIBE (preferred method)
		$db->setQuery('DESCRIBE '.$db->qn($table));
		try {
			$columns = $db->loadColumn(0);
		} catch(DatabaseException $e) {
			$columns = null;
		}

		if(!is_null($columns)) {
			return in_array($column, $columns);
		}

		// DESCRIBE failed. Try the hard way...
		$db->setQuery('SHOW CREATE TABLE '.$db->qn($table));
		try {
			$creates = $db->loadColumn(1);
		} catch(DatabaseException $e) {
			return false;
		}
		$create = $creates[0];
		$search = $db->qn($column);

		return strpos($search, $create) !== false;
	}

	/**
	 * Checks if a specific table exists in the database
	 * @param string $table Table name, e.g. #__ak_stats
	 *
	 * @return bool
	 */
	private function tableExists($table)
	{
		$db = JFactory::getDBO();
		return $this->runSQL('SELECT COUNT(*) FROM '.$db->qn($table));
	}

	private function isMySQL()
	{
		$db = JFactory::getDbo();
		return strtolower(substr($db->name, 0, 5)) == 'mysql';
	}
}