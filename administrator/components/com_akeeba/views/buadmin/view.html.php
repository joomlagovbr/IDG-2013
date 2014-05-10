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
 * Akeeba Backup Administrator view class
 *
 */
class AkeebaViewBuadmin extends F0FViewHtml
{
	protected $lists = null;

	function  __construct($config = array()) {
		parent::__construct($config);
		$this->lists = new JObject();

        $tmpl_path = JPATH_COMPONENT_ADMINISTRATOR.'/plugins/views/buadmin/tmpl';
		$this->addTemplatePath($tmpl_path);
	}

	public function onEdit($tpl = null)
	{
		$model = $this->getModel();
		$id = $model->getId();
		$record = AEPlatform::getInstance()->get_statistics($id);
		$this->record = $record;
		$this->record_id = $id;

		$this->setLayout('comment');
	}

	public function onBrowse($tpl=null)
	{
		$session = JFactory::getSession();
		$task = $session->get('buadmin.task', 'default', 'akeeba');

		if($task != 'restorepoint') $task = 'default';

		$aeconfig = AEFactory::getConfiguration();

		// Add custom submenus
		if(AKEEBA_PRO) {
			$toolbar = F0FToolbar::getAnInstance($this->input->get('option','com_foobar','cmd'), $this->config);
			$toolbar->appendLink(
				JText::_('BUADMIN_LABEL_BACKUPS'),
				JURI::base().'index.php?option=com_akeeba&view=buadmin&task=browse',
				($task == 'default')
			);
			$toolbar->appendLink(
				JText::_('BUADMIN_LABEL_SRP'),
				JURI::base().'index.php?option=com_akeeba&view=buadmin&task=restorepoint',
				($task == 'restorepoint')
			);
		}

		if(AKEEBA_PRO && ($task == 'default'))
		{
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Link', 'restore', JText::_('DISCOVER'), 'index.php?option=com_akeeba&view=discover' );
			JToolBarHelper::publish('restore', JText::_('STATS_LABEL_RESTORE'));
		}

		if(($task == 'default')) {
			JToolBarHelper::editList('showcomment', JText::_('STATS_LOG_EDITCOMMENT'));

			$pModel = F0FModel::getTmpInstance('Profiles','AkeebaModel');
			$enginesPerPprofile = $pModel->getPostProcessingEnginePerProfile();
			$this->enginesPerProfile = $enginesPerPprofile;
		}
		JToolBarHelper::spacer();

		// "Show warning first" download button. Joomlantastic!
		$confirmationText = AkeebaHelperEscape::escapeJS( JText::_('STATS_LOG_DOWNLOAD_CONFIRM'), "'\n" );
		$baseURI = JURI::base();
		$js = <<<ENDSCRIPT
function confirmDownloadButton()
{
	var answer = confirm('$confirmationText');
	if(answer) submitbutton('download');
}

function confirmDownload(id, part)
{
	var answer = confirm('$confirmationText');
	var newURL = '$baseURI';
	if(answer) {
		newURL += 'index.php?option=com_akeeba&view=buadmin&task=download&id='+id;
		if( part != '' ) newURL += '&part=' + part
		window.location = newURL;
	}
}

ENDSCRIPT;

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js);

		$hash = 'akeebabuadmin';

		// ...ordering
		$app = JFactory::getApplication();
		$this->lists->set('order',			$app->getUserStateFromRequest($hash.'filter_order',
			'filter_order', 'backupstart'));
		$this->lists->set('order_Dir',		$app->getUserStateFromRequest($hash.'filter_order_Dir',
			'filter_order_Dir', 'DESC'));

		// ...filter state
		$this->lists->set('fltDescription',	$app->getUserStateFromRequest($hash.'filter_description',
			'description', null));
		$this->lists->set('fltFrom',		$app->getUserStateFromRequest($hash.'filter_from',
			'from', null));
		$this->lists->set('fltTo',			$app->getUserStateFromRequest($hash.'filter_to',
			'to', null));
		$this->lists->set('fltOrigin',		$app->getUserStateFromRequest($hash.'filter_origin',
			'origin', null));
		$this->lists->set('fltProfile',		$app->getUserStateFromRequest($hash.'filter_profile',
			'profile', null));

		$filters = $this->_getFilters();
		$ordering = $this->_getOrdering();

		require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/statistics.php';
		$model = new AkeebaModelStatistics();
		$list = $model->getStatisticsListWithMeta(false, $filters, $ordering);

		// Assign data to the view
		$this->lists =		$this->lists; // Filter lists
		$this->list =		$list; // Data
		$this->pagination =	$model->getPagination($filters); // Pagination object

		// Add live help
		if($task == 'restorepoint') {
			$this->setLayout('restorepoint');
			AkeebaHelperIncludes::addHelp('restorepoint');
		} else {
			AkeebaHelperIncludes::addHelp('buadmin');
		}

		return true;
	}

	private function _getFilters()
	{
		$filters = array();

		if($this->lists->fltDescription) {
			$filters[] = array(
				'field'			=> 'description',
				'operand'		=> 'LIKE',
				'value'			=> $this->lists->fltDescription
			);
		}

		if($this->lists->fltFrom && $this->lists->fltTo) {
			$filters[] = array(
				'field'			=> 'backupstart',
				'operand'		=> 'BETWEEN',
				'value'			=> $this->lists->fltFrom,
				'value2'			=> $this->lists->fltTo
			);
		} elseif ($this->lists->fltFrom) {
			$filters[] = array(
				'field'			=> 'backupstart',
				'operand'		=> '>=',
				'value'			=> $this->lists->fltFrom,
			);
		} elseif($this->lists->fltTo) {
			JLoader::import('joomla.utilities.date');
			$to = new JDate($this->lists->fltTo);
			$toUnix = $to->toUnix();
			$to = date('Y-m-d').' 23:59:59';

			$filters[] = array(
				'field'			=> 'backupstart',
				'operand'		=> '<=',
				'value'			=> $to,
			);
		}
		if($this->lists->fltOrigin) {
			$filters[] = array(
				'field'			=> 'origin',
				'operand'		=> '=',
				'value'			=> $this->lists->fltOrigin
			);
		}
		if($this->lists->fltProfile) {
			$filters[] = array(
				'field'			=> 'profile_id',
				'operand'		=> '=',
				'value'			=> (int)$this->lists->fltProfile
			);
		}

		$session = JFactory::getSession();
		$task = $session->get('buadmin.task', 'browse', 'akeeba');
		if($task == 'restorepoint') {
			$filters[] = array(
				'field'			=> 'tag',
				'operand'		=> '=',
				'value'			=> 'restorepoint'
			);
		} else {
			$filters[] = array(
				'field'			=> 'tag',
				'operand'		=> '<>',
				'value'			=> 'restorepoint'
			);
		}


		if(empty($filters)) $filters = null;
		return $filters;
	}

	private function _getOrdering()
	{
		$order = array(
			'by'		=> $this->lists->order,
			'order'		=> strtoupper($this->lists->order_Dir)
		);
		return $order;
	}
}