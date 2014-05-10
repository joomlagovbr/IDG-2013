<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

defined('_JEXEC') or die();

/**
 * A centralized place for GUI-related helper functions
 */
class AkeebaHelperIncludes
{
	static $viewHelpMap = array(
		'backup'		=> 'backup-now.html',
		'buadmin'		=> 'adminsiter-backup-files.html',
		'config'		=> 'configuration.html',
		'cpanel'		=> 'ch03.html#control-panel',
		'dbef'			=> 'database-tables-exclusion.html',
		'fsfilter'		=> 'exclude-data-from-backup.html#files-and-directories-exclusion',
		'log'			=> 'view-log.html',
		'profiles'		=> 'using-basic-operations.html#id4812849',
		'eff'			=> 'off-site-directories-inclusion.html',
		'extfilter'		=> 'extension-filters.html',
		'multidb'		=> 'include-data-to-archive.html#multiple-db-definitions',
		'regexdbfilter'	=> 'regex-database-tables-exclusion.html',
		'regexfsfilter'	=> 'regex-files-directories-exclusion.html',
		'stw'			=> 'stw.html',
		'restore'		=> 'adminsiter-backup-files.html#integrated-restoration',
		'acl'			=> 'access-control.html',
		'restorepoint'	=> 'taking-srps.html',
		'schedule'		=> 'automating-your-backup.html',
		'discover'		=> 'ch03s02s05s03.html',
		's3import'		=> 'ch03s02s05s03.html',
	);

	static public function addHelp($view)
	{
		if( array_key_exists($view, self::$viewHelpMap) )
		{
			$page = self::$viewHelpMap[$view];
			if(empty($page)) return;
			self::addLiveHelpButton($page);
		}
	}

	static public function addLiveHelpButton( $page )
	{
		if(strpos($page, '.html') === false) $page .= '.html';
		if(strpos($page, '#') === false) {
			$page .= '?tmpl=component';
		} else {
			$parts = explode('#', $page, 2);
			$page = $parts[0].'?tmpl=component#'.$parts[1];
		}
		$bar = JToolBar::getInstance('toolbar');
		$label = 'JTOOLBAR_HELP';
		$bar->appendButton( 'Popup', 'help', $label, 'https://www.akeebabackup.com/documentation/akeeba-backup-documentation/'.$page, 960, 500 );
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$css = '#modal-help { width: 1000px; margin: 0 0 0 -500px; }';
			JFactory::getDocument()->addStyleDeclaration($css);
		}
	}
}