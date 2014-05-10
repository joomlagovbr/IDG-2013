<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Backup finalization domain
 */
class AECoreDomainFinalization extends AEAbstractPart
{

	private $action_queue = array();
	private $action_handlers = array();

	private $current_method = '';

	private $backup_parts = array();
	private $backup_parts_index = -1;

	private $update_stats = false;

	private $remote_files_killlist = null;

	// Used for percentage reporting
	private $steps_total = 0;
	private $steps_done = 0;
	private $substeps_total = 0;
	private $substeps_done = 0;

	/**
	 * Implements the abstract method
	 *
	 * @see akeeba/abstract/AEAbstractPart#_prepare()
	 */
	protected function _prepare()
	{
		// Make sure the break flag is not set
		$configuration = AEFactory::getConfiguration();
		$configuration->get('volatile.breakflag', false);

		// Populate actions queue
		$this->action_queue = array(
			'remove_temp_files',
			'update_statistics',
			'update_filesizes',
			'run_post_processing',
			'kickstart_post_processing',
			'apply_quotas',
			'apply_remote_quotas',
			'mail_administrators',
			'update_statistics', // Run it a second time to update the backup end time after post processing, emails, etc ;)
		);

		// Remove the Kickstart post-processing if we do not have the option set
		$uploadKickstart = $configuration->get('akeeba.advanced.uploadkickstart', 0);

		if (!$uploadKickstart)
		{
			$idx = array_search('kickstart_post_processing', $this->action_queue);
			unset ($this->action_queue['kickstart_post_processing']);
		}

		// Allow adding finalization action objects using the volatile.core.finalization.action_handlers array
		$customHandlers = $configuration->get('volatile.core.finalization.action_handlers', null);

		if (is_array($customHandlers) && !empty($customHandlers))
		{
			// Pop the second update_statistics call
			$oldItem = array_pop($this->action_queue);

			foreach ($customHandlers as $handler)
			{
				$this->action_handlers[] = $handler;
			}

			// Push the second update_statistics call
			$this->action_handlers[] = $oldItem;
		}

		// Do we have a custom action queue set in volatile.core.finalization.action_queue?
		$customQueue = $configuration->get('volatile.core.finalization.action_queue', null);

		if (is_array($customQueue) && !empty($customQueue))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Overriding finalization action queue');
			$this->action_queue = array();

			foreach ($customQueue as $action)
			{
				if (method_exists($this, $action))
				{
					$this->action_queue[] = $action;
				}
				else
				{
					foreach ($this->action_handlers as $handler)
					{
						if (method_exists($handler, $action))
						{
							$this->action_queue[] = $action;
							break;
						}
					}
				}
			}
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Finalization action queue: ' . implode(', ', $this->action_queue));

		$this->steps_total = count($this->action_queue);
		$this->steps_done = 0;
		$this->substeps_total = 0;
		$this->substeps_done = 0;

		// Seed the method
		$this->current_method = array_shift($this->action_queue);

		// Set ourselves to running state
		$this->setState('running');
	}

	/**
	 * Implements the abstract method
	 *
	 * @see akeeba/abstract/AEAbstractPart#_run()
	 */
	protected function _run()
	{
		$configuration = AEFactory::getConfiguration();

		if ($this->getState() == 'postrun')
		{
			return;
		}

		$finished = (empty($this->action_queue)) && ($this->current_method == '');

		if ($finished)
		{
			$this->setState('postrun');

			return;
		}

		$this->setState('running');

		$timer = AEFactory::getTimer();

		// Continue processing while we have still enough time and stuff to do
		while (($timer->getTimeLeft() > 0) && (!$finished) && (!$configuration->get('volatile.breakflag', false)))
		{
			$method = $this->current_method;

			if (method_exists($this, $method))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . "::_run() Running built-in method $method");
				$status = $this->$method();
			}
			else
			{
				$status = true;

				if (!empty($this->action_handlers))
				{
					foreach ($this->action_handlers as $handler)
					{
						if (method_exists($handler, $method))
						{
							AEUtilLogger::WriteLog(_AE_LOG_DEBUG, __CLASS__ . "::_run() Running add-on method $method");
							$status = $handler->$method($this);
							break;
						}
					}
				}
			}

			if ($status === true)
			{
				$this->current_method = '';
				$this->steps_done++;
				$finished = (empty($this->action_queue));

				if (!$finished)
				{
					$this->current_method = array_shift($this->action_queue);
					$this->substeps_total = 0;
					$this->substeps_done = 0;
				}
			}
		}

		if ($finished)
		{
			$this->setState('postrun');
			$this->setStep('');
			$this->setSubstep('');
		}
	}

	/**
	 * Implements the abstract method
	 *
	 * @see akeeba/abstract/AEAbstractPart#_finalize()
	 */
	protected function _finalize()
	{
		$this->setState('finished');
	}

	/**
	 * Sends an email to the administrators
	 *
	 * @return bool
	 */
	private function mail_administrators()
	{
		$this->setStep('Processing emails to administrators');
		$this->setSubstep('');

		// Skip email for back-end backups
		if (AEPlatform::getInstance()->get_backup_origin() == 'backend')
		{
			return true;
		}

		$must_email = AEPlatform::getInstance()->get_platform_configuration_option('frontend_email_on_finish', 0) != 0;

		if (!$must_email)
		{
			return true;
		}

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Preparing to send e-mail to administrators");

		$email = AEPlatform::getInstance()->get_platform_configuration_option('frontend_email_address', '');
		$email = trim($email);

		if (!empty($email))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Using pre-defined list of emails");
			$emails = explode(',', $email);
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Fetching list of Super Administrator emails");
			$emails = AEPlatform::getInstance()->get_administrator_emails();
		}

		if (!empty($emails))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Creating email subject and body");
			// Fetch user's preferences
			$subject = trim(AEPlatform::getInstance()->get_platform_configuration_option('frontend_email_subject', ''));
			$body = trim(AEPlatform::getInstance()->get_platform_configuration_option('frontend_email_body', ''));

			// Get the statistics
			$statistics = AEFactory::getStatistics();
			$stat = $statistics->getRecord();
			$parts = AEUtilStatistics::get_all_filenames($stat, false);

			$profile_number = AEPlatform::getInstance()->get_active_profile();
			$profile_name = AEPlatform::getInstance()->get_profile_name($profile_number);
			$parts = AEUtilStatistics::get_all_filenames($stat, false);
			$stat = (object)$stat;
			$num_parts = $stat->multipart;

			// Non-split archives have a part count of 0
			if ($num_parts == 0)
			{
				$num_parts = 1;
			}

			$parts_list = '';

			if (!empty($parts))
			{
				foreach ($parts as $file)
				{
					$parts_list .= "\t" . basename($file) . "\n";
				}
			}

			// Get the remote storage status
			$remote_status = '';
			$post_proc_engine = AEFactory::getConfiguration()->get('akeeba.advanced.proc_engine');

			if (!empty($post_proc_engine) && ($post_proc_engine != 'none'))
			{
				if (empty($stat->remote_filename))
				{
					$remote_status = AEPlatform::getInstance()->translate('COM_AKEEBA_EMAIL_POSTPROCESSING_FAILED');
				}
				else
				{
					$remote_status = AEPlatform::getInstance()->translate('COM_AKEEBA_EMAIL_POSTPROCESSING_SUCCESS');
				}
			}

			// Do we need a default subject?
			if (empty($subject))
			{
				// Get the default subject
				$subject = AEPlatform::getInstance()->translate('EMAIL_SUBJECT_OK');
			}
			else
			{
				// Post-process the subject
				$subject = AEUtilFilesystem::replace_archive_name_variables($subject);
			}

			// Do we need a default body?
			if (empty($body))
			{
				$body = AEPlatform::getInstance()->translate('EMAIL_BODY_OK');
				$info_source = AEPlatform::getInstance()->translate('EMAIL_BODY_INFO');
				$body .= "\n\n" . sprintf($info_source, $profile_number, $num_parts) . "\n\n";
				$body .= $parts_list;
			}
			else
			{
				// Post-process the body
				$body = AEUtilFilesystem::replace_archive_name_variables($body);
				$body = str_replace('[PROFILENUMBER]', $profile_number, $body);
				$body = str_replace('[PROFILENAME]', $profile_name, $body);
				$body = str_replace('[PARTCOUNT]', $num_parts, $body);
				$body = str_replace('[FILELIST]', $parts_list, $body);
				$body = str_replace('[REMOTESTATUS]', $remote_status, $body);
			}
			// Sometimes $body contains literal \n instead of newlines
			$body = str_replace('\\n', "\n", $body);

			foreach ($emails as $email)
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Sending email to $email");
				AEPlatform::getInstance()->send_email($email, $subject, $body);
			}
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "No email recipients found! Skipping email.");
		}

		return true;
	}

	/**
	 * Removes temporary files
	 *
	 * @return bool
	 */
	private function remove_temp_files()
	{
		$this->setStep('Removing temporary files');
		$this->setSubstep('');
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Removing temporary files");
		AEUtilTempfiles::deleteTempFiles();

		return true;
	}

	/**
	 * Runs the writer's post-processing steps
	 *
	 * @return bool
	 */
	private function run_post_processing()
	{
		$this->setStep('Post-processing');
		// Do not run if the archive engine doesn't produce archives
		$configuration = AEFactory::getConfiguration();
		$this->setSubstep('');

		$engine_name = $configuration->get('akeeba.advanced.proc_engine');
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Loading post-processing engine object ($engine_name)");
		$post_proc = AEFactory::getPostprocEngine($engine_name);

		// Initialize the archive part list if required
		if (empty($this->backup_parts))
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Initializing post-processing engine');

			// Initialize the flag for multistep post-processing of parts
			$configuration->set('volatile.postproc.filename', null);
			$configuration->set('volatile.postproc.directory', null);

			// Populate array w/ absolute names of backup parts
			$statistics = AEFactory::getStatistics();
			$stat = $statistics->getRecord();
			$this->backup_parts = AEUtilStatistics::get_all_filenames($stat, false);

			if (is_null($this->backup_parts))
			{
				// No archive produced, or they are all already post-processed
				AEUtilLogger::WriteLog(_AE_LOG_INFO, 'No archive files found to post-process');

				return true;
			}

			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, count($this->backup_parts) . ' files to process found');
			$this->substeps_total = count($this->backup_parts);
			$this->substeps_done = 0;

			$this->backup_parts_index = 0;

			// If we have an empty array, do not run
			if (empty($this->backup_parts))
			{
				return true;
			}

			// Break step before processing?
			if ($post_proc->break_before && !AEFactory::getConfiguration()->get('akeeba.tuning.nobreak.finalization', 0))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Breaking step before post-processing run');
				$configuration->set('volatile.breakflag', true);

				return false;
			}
		}

		// Make sure we don't accidentally break the step when not required to do so
		$configuration->set('volatile.breakflag', false);

		// Do we have a filename from the previous run of the post-proc engine?
		$filename = $configuration->get('volatile.postproc.filename', null);

		if (empty($filename))
		{
			$filename = $this->backup_parts[$this->backup_parts_index];
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Beginning post processing file ' . $filename);
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Continuing post processing file ' . $filename);
		}

		$this->setStep('Post-processing');
		$this->setSubstep(basename($filename));
		$timer = AEFactory::getTimer();
		$startTime = $timer->getRunningTime();
		$result = $post_proc->processPart($filename);
		$this->propagateFromObject($post_proc);

		if ($result === false)
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, 'Failed to process file ' . $filename);
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, 'Error received from the post-processing engine:');
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, implode("\n", $this->getWarnings()));
			$this->setWarning('Failed to process file ' . $filename);
		}
		elseif ($result === true)
		{
			// The post-processing of this file ended successfully
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Finished post-processing file ' . $filename);
			$configuration->set('volatile.postproc.filename', null);
		}
		else
		{
			// More work required
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'More post-processing steps required for file ' . $filename);
			$configuration->set('volatile.postproc.filename', $filename);

			// Do we need to break the step?
			$endTime = $timer->getRunningTime();
			$stepTime = $endTime - $startTime;
			$timeLeft = $timer->getTimeLeft();

			if ($timeLeft < $stepTime)
			{
				// We predict that running yet another step would cause a timeout
				$configuration->set('volatile.breakflag', true);
			}
			else
			{
				// We have enough time to run yet another step
				$configuration->set('volatile.breakflag', false);
			}
		}

		// Should we delete the file afterwards?
		if (
			$configuration->get('engine.postproc.common.delete_after', false)
			&& $post_proc->allow_deletes
			&& ($result === true)
		)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Deleting already processed file ' . $filename);
			AEPlatform::getInstance()->unlink($filename);
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Not removing processed file ' . $filename);
		}

		if ($result === true)
		{
			// Move the index forward if the part finished processing
			$this->backup_parts_index++;

			// Mark substep done
			$this->substeps_done++;

			// Break step after processing?
			if ($post_proc->break_after && !AEFactory::getConfiguration()->get('akeeba.tuning.nobreak.finalization', 0))
			{
				$configuration->set('volatile.breakflag', true);
			}

			// If we just finished processing the first archive part, save its remote path in the statistics.
			if (($this->substeps_done == 1) || ($this->substeps_total == 0))
			{
				if (!empty($post_proc->remote_path))
				{
					$statistics = AEFactory::getStatistics();
					$remote_filename = $engine_name . '://';
					$remote_filename .= $post_proc->remote_path;
					$data = array(
						'remote_filename' => $remote_filename
					);
					$remove_after = $configuration->get('engine.postproc.common.delete_after', false);

					if ($remove_after)
					{
						$data['filesexist'] = 0;
					}

					$statistics->setStatistics($data);
					$this->propagateFromObject($statistics);
				}
			}

			// Are we past the end of the array (i.e. we're finished)?
			if ($this->backup_parts_index >= count($this->backup_parts))
			{
				AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Post-processing has finished for all files');

				return true;
			}
		}
		elseif ($result === false)
		{
			// If the post-processing failed, make sure we don't process anything else
			$this->backup_parts_index = count($this->backup_parts);
			$this->setWarning('Post-processing interrupted -- no more files will be transferred');

			return true;
		}

		// Indicate we're not done yet
		return false;
	}

	/**
	 * Runs the Kickstart post-processing step
	 *
	 * @return  boolean
	 */
	private function kickstart_post_processing()
	{
		$this->setStep('Post-processing Kickstart');

		$configuration = AEFactory::getConfiguration();
		$this->setSubstep('');

		// Do not run if we are not told to upload Kickstart
		$uploadKickstart = $configuration->get('akeeba.advanced.uploadkickstart', 0);

		if (!$uploadKickstart)
		{
			AEUtilLogger::WriteLog(_AE_LOG_INFO, "Getting ready to upload Kickstart");

			return true;
		}

		$engine_name = $configuration->get('akeeba.advanced.proc_engine');
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Loading post-processing engine object ($engine_name)");
		$post_proc = AEFactory::getPostprocEngine($engine_name);

		// Set $filename to kickstart's source file
		$filename = AEPlatform::getInstance()->get_installer_images_path() . '/kickstart.txt';

		// Post-process the file
		$this->setSubstep('kickstart.php');
		$result = $post_proc->processPart($filename, 'kickstart.php');
		$this->propagateFromObject($post_proc);

		if ($result === false)
		{
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, 'Failed to upload kickstart.php');
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, 'Error received from the post-processing engine:');
			AEUtilLogger::WriteLog(_AE_LOG_WARNING, implode("\n", $this->getWarnings()));

			$this->setWarning('Failed to upload kickstart.php');
		}
		elseif ($result === true)
		{
			// The post-processing of this file ended successfully
			AEUtilLogger::WriteLog(_AE_LOG_INFO, 'Finished uploading kickstart.php');
			$configuration->set('volatile.postproc.filename', null);
		}

		// Indicate we're done
		return true;
	}

	/**
	 * Updates the backup statistics record
	 *
	 * @return bool
	 */
	private function update_statistics()
	{
		$this->setStep('Updating backup record information');
		$this->setSubstep('');

		// Force a step break before updating stats (works around MySQL gone away issues)
		// 3.2.5 : Added conditional break logic after the call to setStatistics()
		/**
		 * if(!$this->update_stats)
		 * {
		 * $this->update_stats = true;
		 * $configuration = AEFactory::getConfiguration();
		 * $configuration->set('volatile.breakflag', true);
		 * return false;
		 * }
		 * /**/

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Updating statistics");
		// We finished normally. Fetch the stats record
		$statistics = AEFactory::getStatistics();
		$registry = AEFactory::getConfiguration();
		$data = array(
			'backupend' => AEPlatform::getInstance()->get_timestamp_database(),
			'status'    => 'complete',
			'multipart' => $registry->get('volatile.statistics.multipart', 0)
		);
		$result = $statistics->setStatistics($data);

		if ($result === false)
		{
			// Most likely a "MySQL has gone away" issue...
			$this->update_stats = true;
			$configuration = AEFactory::getConfiguration();
			$configuration->set('volatile.breakflag', true);

			return false;
		}

		$this->propagateFromObject($statistics);

		$stat = (object)$statistics->getRecord();
		AEPlatform::getInstance()->remove_duplicate_backup_records($stat->archivename);

		return true;
	}

	private function update_filesizes()
	{
		$this->setStep('Updating file sizes');
		$this->setSubstep('');
		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Updating statistics with file sizes");

		// Fetch the stats record
		$statistics = AEFactory::getStatistics();
		$record = $statistics->getRecord();
		$filenames = $statistics->get_all_filenames($record);
		$filesize = 0.0;

		// Calculate file sizes of files remaining on the server
		if (!empty($filenames))
		{
			foreach ($filenames as $file)
			{
				$size = @filesize($file);

				if ($size !== false)
				{
					$filesize += $size * 1.0;
				}
			}
		}

		// Get the part size in volatile storage, set from the immediate part uploading effected by the
		// "Process each part immediately" option, and add it to the total file size
		$volatileTotalSize = AEFactory::getConfiguration()->get('volatile.engine.archiver.totalsize', 0);

		if ($volatileTotalSize)
		{
			$filesize += $volatileTotalSize;
		}

		$data = array(
			'total_size' => $filesize
		);

		AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Total size of backup archive (in bytes): $filesize");

		$statistics->setStatistics($data);
		$this->propagateFromObject($statistics);

		return true;
	}

	/**
	 * Applies the size and count quotas
	 *
	 * @return bool
	 */
	private function apply_quotas()
	{
		$this->setStep('Applying quotas');
		$this->setSubstep('');

		// If no quota settings are enabled, quit
		$registry = AEFactory::getConfiguration();
		$useDayQuotas = $registry->get('akeeba.quota.maxage.enable');
		$useCountQuotas = $registry->get('akeeba.quota.enable_count_quota');
		$useSizeQuotas = $registry->get('akeeba.quota.enable_size_quota');

		if (!($useDayQuotas || $useCountQuotas || $useSizeQuotas))
		{
			$this->apply_obsolete_quotas();

			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "No quotas were defined; old backup files will be kept intact");

			return true; // No quota limits were requested
		}

		// Try to find the files to be deleted due to quota settings
		$statistics = AEFactory::getStatistics();
		$latestBackupId = $statistics->getId();

		// Get quota values
		$countQuota = $registry->get('akeeba.quota.count_quota');
		$sizeQuota = $registry->get('akeeba.quota.size_quota');
		$daysQuota = $registry->get('akeeba.quota.maxage.maxdays');
		$preserveDay = $registry->get('akeeba.quota.maxage.keepday');

		// Get valid-looking backup ID's
		$validIDs = AEPlatform::getInstance()->get_valid_backup_records(true, array('NOT', 'restorepoint'));

		// Create a list of valid files
		$allFiles = array();

		if (count($validIDs))
		{
			foreach ($validIDs as $id)
			{
				$stat = AEPlatform::getInstance()->get_statistics($id);

				try
				{
					$backupstart = new DateTime($stat['backupstart']);
					$backupTS = $backupstart->format('U');
					$backupDay = $backupstart->format('d');
				}
				catch (Exception $e)
				{
					$backupTS = 0;
					$backupDay = 0;
				}

				// Multipart processing
				$filenames = AEUtilStatistics::get_all_filenames($stat, true);

				if (!is_null($filenames))
				{
					// Only process existing files
					$filesize = 0;

					foreach ($filenames as $filename)
					{
						$filesize += @filesize($filename);
					}
					$allFiles[] = array('id' => $id, 'filenames' => $filenames, 'size' => $filesize, 'backupstart' => $backupTS, 'day' => $backupDay);
				}
			}
		}

		unset($validIDs);

		// If there are no files, exit early
		if (count($allFiles) == 0)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "There were no old backup files to apply quotas on");

			return true;
		}

		// Init arrays
		$killids = array();
		$ret = array();
		$leftover = array();

		// Do we need to apply maximum backup age quotas?
		if ($useDayQuotas)
		{
			$killDatetime = new DateTime();
			$killDatetime->modify('-' . $daysQuota . ($daysQuota == 1 ? ' day' : ' days'));
			$killTS = $killDatetime->format('U');

			foreach ($allFiles as $file)
			{
				if ($file['id'] == $latestBackupId)
				{
					continue;
				}

				// Is this on a preserve day?
				if ($preserveDay > 0)
				{
					if ($preserveDay == $file['day'])
					{
						$leftover[] = $file;
						continue;
					}
				}

				// Otherwise, check the timestamp
				if ($file['backupstart'] < $killTS)
				{
					$ret[] = $file['filenames'];
					$killids[] = $file['id'];
				}
				else
				{
					$leftover[] = $file;
				}
			}
		}

		// Do we need to apply count quotas?
		if ($useCountQuotas && is_numeric($countQuota) && !($countQuota <= 0) && !$useDayQuotas)
		{
			// Are there more files than the quota limit?
			if (!(count($allFiles) > $countQuota))
			{
				// No, effectively skip the quota checking
				$leftover = $allFiles;
			}
			else
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Processing count quotas");
				// Yes, aply the quota setting. Add to $ret all entries minus the last
				// $countQuota ones.
				$totalRecords = count($allFiles);
				$checkLimit = $totalRecords - $countQuota;

				// Only process if at least one file (current backup!) is to be left
				for ($count = 0; $count < $totalRecords; $count++)
				{
					$def = array_pop($allFiles);

					if ($def['id'] == $latestBackupId)
					{
						array_push($allFiles, $def);
						continue;
					}
					if (count($ret) < $checkLimit)
					{
						if ($latestBackupId != $def['id'])
						{
							$ret[] = $def['filenames'];
							$killids[] = $def['id'];
						}
					}
					else
					{
						$leftover[] = $def;
					}
				}
				unset($allFiles);
			}
		}
		else
		{
			// No count quotas are applied
			$leftover = $allFiles;
		}

		// Do we need to apply size quotas?
		if ($useSizeQuotas && is_numeric($sizeQuota) && !($sizeQuota <= 0) && (count($leftover) > 0) && !$useDayQuotas)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Processing size quotas");
			// OK, let's start counting bytes!
			$runningSize = 0;

			while (count($leftover) > 0)
			{
				// Each time, remove the last element of the backup array and calculate
				// running size. If it's over the limit, add the archive to the return array.
				$def = array_pop($leftover);
				$runningSize += $def['size'];

				if ($runningSize >= $sizeQuota)
				{
					if ($latestBackupId == $def['id'])
					{
						$runningSize -= $def['size'];
					}
					else
					{
						$ret[] = $def['filenames'];
						$killids[] = $def['filenames'];
					}
				}
			}
		}

		// Convert the $ret 2-dimensional array to single dimensional
		$quotaFiles = array();

		foreach ($ret as $temp)
		{
			foreach ($temp as $filename)
			{
				$quotaFiles[] = $filename;
			}
		}

		// Update the statistics record with the removed remote files
		if (!empty($killids))
		{
			foreach ($killids as $id)
			{
				$data = array('filesexist' => '0');
				AEPlatform::getInstance()->set_or_update_statistics($id, $data, $this);
			}
		}

		// Apply quotas
		if (count($quotaFiles) > 0)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Applying quotas");
			JLoader::import('joomla.filesystem.file');

			foreach ($quotaFiles as $file)
			{
				if (!@AEPlatform::getInstance()->unlink($file))
				{
					$this->setWarning("Failed to remove old backup file " . $file);
				}
			}
		}

		$this->apply_obsolete_quotas();

		return true;
	}

	private function apply_remote_quotas()
	{
		$this->setStep('Applying remote storage quotas');
		$this->setSubstep('');
		// Make sure we are enabled
		$config = AEFactory::getConfiguration();
		$enableRemote = $config->get('akeeba.quota.remote', 0);

		if (!$enableRemote)
		{
			return true;
		}

		// Get the list of files to kill
		if (empty($this->remote_files_killlist))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'Applying remote file quotas');
			$this->remote_files_killlist = $this->get_remote_quotas();

			if (empty($this->remote_files_killlist))
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, 'No remote files to apply quotas to were found');

				return true;
			}
		}

		// Remove the files
		$timer = AEFactory::getTimer();

		while ($timer->getRunningTime() && count($this->remote_files_killlist))
		{
			$filename = array_shift($this->remote_files_killlist);
			list($engineName, $path) = explode('://', $filename);
			$engine = AEFactory::getPostprocEngine($engineName);

			if (!$engine->can_delete)
			{
				continue;
			}

			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Removing $filename");
			$result = $engine->delete($path);

			if (!$result)
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Removal failed: " . $engine->getWarning());
			}
		}

		// Return false if we have more work to do or true if we're done
		if (count($this->remote_files_killlist))
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Remote file removal will continue in the next step");

			return false;
		}
		else
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Remote file quotas applied successfully");

			return true;
		}
	}

	/**
	 * Applies the size and count quotas
	 *
	 * @return bool
	 */
	private function get_remote_quotas()
	{
		// Get all records with a remote filename
		$allRecords = AEPlatform::getInstance()->get_valid_remote_records();

		// Bail out if no records found
		if (empty($allRecords))
		{
			return array();
		}

		// Try to find the files to be deleted due to quota settings
		$statistics = AEFactory::getStatistics();
		$latestBackupId = $statistics->getId();

		// Filter out the current record
		$temp = array();

		foreach ($allRecords as $item)
		{
			if ($item['id'] == $latestBackupId)
			{
				continue;
			}

			$item['files'] = $this->get_remote_files($item['remote_filename'], $item['multipart']);
			$temp[] = $item;
		}

		$allRecords = $temp;

		// Bail out if only the current backup was included in the list
		if (count($allRecords) == 0)
		{
			return array();
		}

		// Get quota values
		$registry = AEFactory::getConfiguration();
		$countQuota = $registry->get('akeeba.quota.count_quota');
		$sizeQuota = $registry->get('akeeba.quota.size_quota');
		$useCountQuotas = $registry->get('akeeba.quota.enable_count_quota');
		$useSizeQuotas = $registry->get('akeeba.quota.enable_size_quota');
		$useDayQuotas = $registry->get('akeeba.quota.maxage.enable');
		$daysQuota = $registry->get('akeeba.quota.maxage.maxdays');
		$preserveDay = $registry->get('akeeba.quota.maxage.keepday');

		$leftover = array();
		$ret = array();
		$killids = array();

		if ($useDayQuotas)
		{
			$killDatetime = new DateTime();
			$killDatetime->modify('-' . $daysQuota . ($daysQuota == 1 ? ' day' : ' days'));
			$killTS = $killDatetime->format('U');

			foreach ($allRecords as $def)
			{
				$backupstart = new DateTime($def['backupstart']);
				$backupTS = $backupstart->format('U');
				$backupDay = $backupstart->format('d');

				// Is this on a preserve day?
				if ($preserveDay > 0)
				{
					if ($preserveDay == $backupDay)
					{
						$leftover[] = $def;
						continue;
					}
				}
				// Otherwise, check the timestamp
				if ($backupTS < $killTS)
				{
					$ret[] = $def['files'];
					$killids[] = $def['id'];
				}
				else
				{
					$leftover[] = $def;
				}
			}
		}

		// Do we need to apply count quotas?
		if ($useCountQuotas && ($countQuota >= 1) && !$useDayQuotas)
		{
			$countQuota--;
			// Are there more files than the quota limit?
			if (!(count($allRecords) > $countQuota))
			{
				// No, effectively skip the quota checking
				$leftover = $allRecords;
			}
			else
			{
				AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Processing remote count quotas");
				// Yes, apply the quota setting.
				$totalRecords = count($allRecords);

				for ($count = 0; $count <= $totalRecords; $count++)
				{
					$def = array_pop($allRecords);

					if (count($leftover) >= $countQuota)
					{
						$ret[] = $def['files'];
						$killids[] = $def['id'];
					}
					else
					{
						$leftover[] = $def;
					}
				}

				unset($allRecords);
			}
		}
		else
		{
			// No count quotas are applied
			$leftover = $allRecords;
		}

		// Do we need to apply size quotas?
		if ($useSizeQuotas && ($sizeQuota > 0) && (count($leftover) > 0) && !$useDayQuotas)
		{
			AEUtilLogger::WriteLog(_AE_LOG_DEBUG, "Processing remote size quotas");
			// OK, let's start counting bytes!
			$runningSize = 0;

			while (count($leftover) > 0)
			{
				// Each time, remove the last element of the backup array and calculate
				// running size. If it's over the limit, add the archive to the $ret array.
				$def = array_pop($leftover);
				$runningSize += $def['total_size'];
				if ($runningSize >= $sizeQuota)
				{
					$ret[] = $def['files'];
					$killids[] = $def['id'];
				}
			}
		}

		// Convert the $ret 2-dimensional array to single dimensional
		$quotaFiles = array();

		foreach ($ret as $temp)
		{
			if (!is_array($temp) || empty($temp))
			{
				continue;
			}

			foreach ($temp as $filename)
			{
				$quotaFiles[] = $filename;
			}
		}

		// Update the statistics record with the removed remote files
		if (!empty($killids))
		{
			foreach ($killids as $id)
			{
				if (empty($id))
				{
					continue;
				}

				$data = array('remote_filename' => '');
				AEPlatform::getInstance()->set_or_update_statistics($id, $data, $this);
			}
		}

		return $quotaFiles;
	}

	private function get_remote_files($filename, $multipart)
	{
		$result = array();

		$extension = substr($filename, -3);
		$base = substr($filename, 0, -4);

		$result[] = $filename;

		if ($multipart > 1)
		{
			for ($i = 1; $i < $multipart; $i++)
			{
				$newExt = substr($extension, 0, 1) . sprintf('%02u', $i);
				$result[] = $base . '.' . $newExt;
			}
		}

		return $result;
	}

	/**
	 * Keeps a maximum number of "obsolete" records
	 */
	private function apply_obsolete_quotas()
	{
		$this->setStep('Applying quota limit on obsolete backup records');
		$this->setSubstep('');
		$registry = AEFactory::getConfiguration();
		$limit = $registry->get('akeeba.quota.obsolete_quota', 0);
		$limit = (int)$limit;

		if ($limit <= 0)
		{
			return;
		}

		$statsTable = AEPlatform::getInstance()->tableNameStats;
		$db = AEFactory::getDatabase(AEPlatform::getInstance()->get_platform_database_options());
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn($statsTable))
			->where($db->qn('status') . ' = ' . $db->q('complete'))
			->where($db->qn('filesexist') . '=' . $db->q('0'))
			->order($db->qn('id') . ' DESC');

		$db->setQuery($query, $limit, 100000);
		$array = $db->loadColumn();

		if (empty($array))
		{
			return;
		}

		$ids = array();

		foreach ($array as $id)
		{
			$ids[] = $db->q($id);
		}
		$ids = implode(',', $ids);

		$query = $db->getQuery(true)
			->delete($db->qn($statsTable))
			->where($db->qn('id') . " IN ($ids)");
		$db->setQuery($query);
		$db->query();
	}

	/**
	 * Get the percentage of finalization steps done
	 *
	 * @see backend/akeeba/abstract/AEAbstractPart#getProgress()
	 */
	public function getProgress()
	{
		if ($this->steps_total <= 0)
		{
			return 0;
		}

		$overall = $this->steps_done / $this->steps_total;
		$local = 0;

		if ($this->substeps_total > 0)
		{
			$local = $this->substeps_done / $this->substeps_total;
		}

		return $overall + ($local / $this->steps_total);
	}

	public function relayStep($step)
	{
		$this->setStep($step);
	}

	public function relaySubstep($substep)
	{
		$this->setSubstep($substep);
	}
}