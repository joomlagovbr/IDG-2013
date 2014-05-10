<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Control Panel model
 *
 */
class AkeebaModelLogs extends F0FModel
{
	function getLogFiles()
	{
		$configuration = AEFactory::getConfiguration();
		$outdir = $configuration->get('akeeba.basic.output_directory');

		$files = AEUtilScanner::getFiles($outdir);
		$ret = array();
		if(!empty($files) && is_array($files))
		{
			foreach($files as $filename)
			{
				$basename = basename($filename);
				if( (substr($basename,0,7) == 'akeeba.') && (substr($basename,-4) == '.log') && ($basename != 'akeeba.log') )
				{
					$tag = str_replace('akeeba.', '', str_replace('.log', '', $basename));
					if(!empty($tag)) $ret[] = $tag;
				}
			}
		}
		return $ret;
	}

	function getLogList()
	{
		$options = array();

		$list = $this->getLogFiles();
		if(!empty($list))
		{
			$options[] = JHTML::_('select.option',null,JText::_('LOG_CHOOSE_FILE_VALUE'));
			foreach($list as $item)
			{
				$text = JText::_('STATS_LABEL_ORIGIN_'.strtoupper($item));
				$options[] = JHTML::_('select.option',$item,$text);
			}
		}
		return $options;
	}

	public function echoRawLog()
	{
		$tag = $this->getState('tag', '');

		echo "WARNING: Do not copy and paste lines from this file!\r\n";
		echo "You are supposed to ZIP and attach it in your support forum post.\r\n";
		echo "If you fail to do so, your support request will receive minimal priority.\r\n";
		echo "\r\n";
		echo "--- START OF RAW LOG --\r\n";
		@readfile(AEUtilLogger::logName($tag)); // The at sign is necessary to skip showing PHP errors if the file doesn't exist or isn't readable for some reason
		echo "--- END OF RAW LOG ---\r\n";
	}
}