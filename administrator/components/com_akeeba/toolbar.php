<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebaToolbar extends F0FToolbar
{
	/**
	 * Disable rendering a toolbar.
	 *
	 * @return array
	 */
	protected function getMyViews()
	{
		return array();
	}

	public function onAlices()
	{
		JToolBarHelper::title(JText::_('COM_AKEEBA_TITLE_ALICES'),'akeeba');
		JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_akeeba&view=cpanel');
	}

	public function onCpanelsAdd()
	{
		JToolBarHelper::title(JText::_('AKEEBA').' :: <small>'.JText::_('AKEEBA_CONTROLPANEL').'</small>','akeeba');
		$this->_renderDefaultSubmenus('cpanel');
	}

	public function onBackups()
	{
		// Add some buttons
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
		$this->_renderDefaultSubmenus('backup');
	}

	public function onConfwizsAdd()
	{
		// Set the toolbar title
		JToolBarHelper::title(JText::_('AKEEBA').':: <small>'.JText::_('AKEEBA_CONFWIZ').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onProfilesBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('PROFILES').'</small>','akeeba');

		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
		JToolBarHelper::addNew();
		JToolBarHelper::custom('copy', 'copy.png', 'copy_f2.png', 'JLIB_HTML_BATCH_COPY', false);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList();
		JToolBarHelper::spacer();
		AkeebaHelperIncludes::addHelp('profiles');
	}

	public function onProfilesEdit()
	{
		parent::onEdit();
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('PROFILE_PAGETITLE_EDIT').'</small>','akeeba');
	}

	public function onProfilesAdd()
	{
		parent::onAdd();
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('PROFILE_PAGETITLE_NEW').'</small>','akeeba');
	}

	public function onConfigsAdd()
	{
		// Toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').':: <small>'.JText::_('CONFIGURATION').'</small>','akeeba');
		JToolBarHelper::preferences('com_akeeba', '500', '660');
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		JToolBarHelper::spacer();

		$this->_renderDefaultSubmenus('config');
	}

	public function onStatisticsBrowse()
	{
		$this->onBuadminsBrowse();
	}

	public function onBuadminsBrowse()
	{
		$session = JFactory::getSession();
		$task = $session->get('buadmin.task', 'default', 'akeeba');

		switch($task)
		{
			case 'default':
			default:
				JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('BUADMIN').'</small>','akeeba');
				break;

			case 'restorepoint':
				JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('BUADMINSRP').'</small>','akeeba');
				break;
		}

		JToolBarHelper::spacer();
		JToolBarHelper::deleteList();
		JToolBarHelper::custom( 'deletefiles', 'delete.png', 'delete_f2.png', JText::_('STATS_LABEL_DELETEFILES'), true );
		JToolBarHelper::spacer();
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');

		//$this->_renderDefaultSubmenus('buadmin');
	}

	public function onBuadminsEdit()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('BUADMIN').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		//$this->_renderDefaultSubmenus('buadmin');
	}

	public function onPostsetupsBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('AKEEBA_POSTSETUP').'</small>','akeeba');
		// Add a spacer, a help button and show the template
		JToolBarHelper::spacer();
	}

	public function onDiscoversBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('DISCOVER').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onDiscoversDiscover()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('DISCOVER').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onS3importsBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('S3IMPORT').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onS3importsDltoserver()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('S3IMPORT').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onRemotefilesListactions()
	{
		JToolBarHelper::title(JText::_('AKEEBA_REMOTEFILES'),'akeeba');
	}

	public function onRemotefilesDltoserver()
	{
		JToolBarHelper::title(JText::_('AKEEBA_REMOTEFILES'),'akeeba');
	}

	public function onLogsBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('VIEWLOG').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();

		$this->_renderDefaultSubmenus('log');
	}

	public function onFsfiltersBrowse()
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('FSFILTERS').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onDbefsBrowse()
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('DBEF').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onMultidbsBrowse()
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('MULTIDB').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onRegexfsfiltersBrowse()
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('REGEXFSFILTERS').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onRegexdbfiltersBrowse()
	{
		// Add toolbar buttons
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('REGEXDBFILTERS').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	public function onExtfilters()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('EXTFILTER').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
	}

	public function onEffsBrowse()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('EXTRADIRS').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
	}

	public function onStws()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('SITETRANSFERWIZARD').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
	}

	public function onRestores()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('RESTORATION').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
	}

	public function onSrprestores()
	{
		JToolBarHelper::title(JText::_('AKEEBA').': <small>'.JText::_('SRPRESTORATION').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
		JToolBarHelper::spacer();
	}

	public function onSchedules()
	{
		// Set the toolbar title
		JToolBarHelper::title(JText::_('AKEEBA').':: <small>'.JText::_('AKEEBA_SCHEDULE').'</small>','akeeba');
		JToolBarHelper::back('AKEEBA_CONTROLPANEL', 'index.php?option=com_akeeba');
	}

	private function _renderDefaultSubmenus($active = '')
	{
		$submenus = array(
			'cpanel'		=>	'AKEEBA_CONTROLPANEL',
			'config'		=>	'CONFIGURATION',
			'backup'		=>	'BACKUP',
			'buadmin'		=>	'BUADMIN',
			'log'			=>	'VIEWLOG',
		);

		foreach($submenus as $view => $key) {
			$link = JURI::base().'index.php?option='.$this->component.'&view='.$view;
			$this->appendLink(JText::_($key), $link, $view == $active);
		}
	}
}