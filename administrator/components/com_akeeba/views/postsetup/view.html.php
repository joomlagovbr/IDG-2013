<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.3.b1
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * MVC View for Profiles management
 *
 */
class AkeebaViewPostsetup extends F0FViewHtml
{
	public function onBrowse($tpl = null)
	{
		$this->_setSRPStatus();
		$this->_setBackuponupdateStatus();
		$this->_setConfWizStatus();
		$this->showsrp = $this->isMySQL();

		return true;
	}

	private function _setBackuponupdateStatus()
	{
		$db = JFactory::getDBO();

		$query = $db->getQuery(true)
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('element').' = '.$db->q('backuponupdate'))
			->where($db->qn('folder').' = '.$db->q('system'));
		$db->setQuery($query);
		$enabledBOU = $db->loadResult();

		if(!AKEEBA_PRO) {
			$enabledBOU = false;
		}

		$this->enablebackuponupdate = $enabledBOU;
	}

	private function _setSRPStatus()
	{
		if($this->_setConfWizStatus()) {
			$this->enablesrp = $this->isMySQL();
			return;
		}

		$db = JFactory::getDBO();

		$query = $db->getQuery(true)
			->select($db->qn('enabled'))
			->from($db->qn('#__extensions'))
			->where($db->qn('element').' = '.$db->q('srp'))
			->where($db->qn('folder').' = '.$db->q('system'));
		$db->setQuery($query);
		$enableSRP = $db->loadResult();

		if(!AKEEBA_PRO) {
			$enableSRP = false;
		}
		if(!$this->isMySQL()) {
			$enableSRP = false;
			return;
		}

		$this->enablesrp = $enableSRP ? true : false;
	}

	private function _setConfWizStatus()
	{
		static $enableconfwiz;

		$component = JComponentHelper::getComponent( 'com_akeeba' );
		if(is_object($component->params) && ($component->params instanceof JRegistry)) {
			$params = $component->params;
		} else {
			$params = new JParameter($component->params);
		}

		if(empty($enableconfwiz)) {
			$lv = $params->get( 'lastversion', '' );

			$enableconfwiz = empty($lv);
		}

		$minStability = $params->get( 'minstability', 'stable' );
		$acceptlicense = $params->get( 'acceptlicense', '0' );
		$acceptsupport = $params->get( 'acceptsupport', '0' );
		$acceptbackuptest = $params->get( 'acceptbackuptest', '0' );
		$angieupgrade = $params->get( 'angieupgrade', '0' );

		$this->enableconfwiz = $enableconfwiz;
		$this->minstability = $minStability;
		$this->acceptlicense = $acceptlicense;
		$this->acceptsupport = $acceptsupport;
		$this->acceptbackuptest = $acceptbackuptest;
		$this->showangieupgrade = ($angieupgrade == 0);

		return $enableconfwiz;
	}

	private function isMySQL()
	{
		$db = JFactory::getDbo();
		return strtolower(substr($db->name, 0, 5)) == 'mysql';
	}
}