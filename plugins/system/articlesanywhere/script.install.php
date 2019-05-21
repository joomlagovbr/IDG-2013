<?php
/**
 * @package         Articles Anywhere
 * @version         9.2.0
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemArticlesAnywhereInstallerScript extends PlgSystemArticlesAnywhereInstallerScriptHelper
{
	public $name           = 'ARTICLES_ANYWHERE';
	public $alias          = 'articlesanywhere';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'editors-xtd');

		$this->enableCoreEditorPlugin();
	}

	public function onBeforeInstall($route)
	{
		$this->showCompatMessage();
	}

	public function onAfterInstall($route)
	{
		$this->disableCoreEditorPlugin();
	}

	private function showCompatMessage()
	{
		$installed_version = $this->getVersion($this->getInstalledXMLFile());

		if ($installed_version && version_compare($installed_version, 7, '<'))
		{
			JFactory::getApplication()->enqueueMessage(
				'Articles Anywhere no longer supports the old data tag attribute syntax, like: <code>{text:limit=100:strip}</code><br>'
				. 'You will need to use the new attribute syntax, like: <code>[text limit="100" strip="true"]</code><br><br>'
				. 'If you still need support for the old syntax, you will need to downgrade to Articles Anywhere v6.3.0.'
				, 'warning'
			);
		}
	}

	private function disableCoreEditorPlugin()
	{
		$query = $this->getCoreEditorPluginQuery()
			->set($this->db->quoteName('enabled') . ' = 0')
			->where($this->db->quoteName('enabled') . ' = 1');
		$this->db->setQuery($query);
		$this->db->execute();

		if ( ! $this->db->getAffectedRows())
		{
			return;
		}

		JFactory::getApplication()->enqueueMessage(JText::_('Joomla\'s own "Article" editor button has been disabled'), 'warning');
	}

	private function enableCoreEditorPlugin()
	{
		$query = $this->getCoreEditorPluginQuery()
			->set($this->db->quoteName('enabled') . ' = 1')
			->where($this->db->quoteName('enabled') . ' = 0');
		$this->db->setQuery($query);
		$this->db->execute();

		if ( ! $this->db->getAffectedRows())
		{
			return;
		}

		JFactory::getApplication()->enqueueMessage(JText::_('Joomla\'s own "Article" editor button has been re-enabled'), 'warning');
	}

	private function getCoreEditorPluginQuery()
	{
		return $this->db->getQuery(true)
			->update('#__extensions')
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('article'))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote('editors-xtd'))
			->where($this->db->quoteName('custom_data') . ' NOT LIKE ' . $this->db->quote('%articlesanywhere_ignore%'));
	}
}
