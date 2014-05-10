<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 *
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * The Backup Administrator class
 *
 */
class AkeebaControllerDefault extends F0FController
{
	/**
	 * Akeeba Backup-specific ACL checks. All views not listed here are
	 * limited by the akeeba.configure privilege.
	 *
	 * @var array
	 */
	private static $viewACLMap = array(
		'backup'	=> 'akeeba.backup',
		'upload'	=> 'akeeba.backup',
		'stw'		=> 'akeeba.backup',
		'buadmin'	=> 'akeeba.download',
		'log'		=> 'akeeba.download',
		'srprestore'=> 'akeeba.download',
		's3import'	=> 'akeeba.download',
		'restore'	=> 'akeeba.download',
		'remotefile'=> 'akeeba.download',
		'discover'	=> 'akeeba.download',
	);

	/**
	 * Do our custom ACL checks for the back-end views
	 *
	 * @return boolean
	 */
	private function akeebaACLCheck()
	{
		// Get the view
		$view = $this->input->getCmd('view', '');

		// Fetch the privilege to check, or use the default (akeeba.configure)
		// privilege.
		if (array_key_exists($view, self::$viewACLMap))
		{
			$privilege = self::$viewACLMap[$view];
		}
		else
		{
			$privilege = 'akeeba.configure';
		}

		// If an empty privileve is defined do not do any ACL check
		if (empty($privilege))
		{
			return true;
		}

		// Throw an error if we are not allowed access to the view
		if (!JFactory::getUser()->authorise($privilege, 'com_akeeba'))
		{
			$this->setRedirect('index.php?option=com_akeeba&view=cpanel');
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			$this->redirect();
			return false;
		}
		else
		{
			return true;
		}
	}

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->akeebaACLCheck();
	}

	/**
	 * ACL check before changing the access level; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeAccesspublic()
	{
		return true;
	}

	/**
	 * ACL check before changing the access level; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeAccessregistered()
	{
		return true;
	}

	/**
	 * ACL check before changing the access level; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeAccessspecial()
	{
		return true;
	}

	/**
	 * ACL check before adding a new record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeAdd()
	{
		return true;
	}

	/**
	 * ACL check before saving a new/modified record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeApply()
	{
		return true;
	}

	/**
	 * ACL check before allowing someone to browse
	 *
	 * @return bool
	 */
	protected function onBeforeBrowse()
	{
		return true;
	}

	/**
	 * ACL check before cancelling an edit
	 *
	 * @return bool
	 */
	protected function onBeforeCancel()
	{
		return true;
	}

	/**
	 * ACL check before editing a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeEdit()
	{
		return true;
	}

	/**
	 * ACL check before changing the ordering of a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeOrderdown()
	{
		return true;
	}

	/**
	 * ACL check before changing the ordering of a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeOrderup()
	{
		return true;
	}

	/**
	 * ACL check before changing the publish status of a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforePublish()
	{
		return true;
	}

	/**
	 * ACL check before removing a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeRemove()
	{
		return true;
	}

	/**
	 * ACL check before saving a new/modified record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeSave()
	{
		return true;
	}

	/**
	 * ACL check before saving a new/modified record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeSavenew()
	{
		return true;
	}

	/**
	 * ACL check before changing the ordering of a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeSaveorder()
	{
		return true;
	}

	/**
	 * ACL check before changing the publish status of a record; override to customise
	 *
	 * @return bool
	 */
	protected function onBeforeUnpublish()
	{
		return true;
	}
}