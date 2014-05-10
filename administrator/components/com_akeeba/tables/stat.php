<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.5
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaTableStat extends F0FTable
{
	public function __construct( $table, $key, &$db )
	{
		parent::__construct('#__ak_stats', 'id', $db);
	}
}