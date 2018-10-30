<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

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
		if ($this->install_type == 'install')
		{
			$this->setUseK2ForOldSetups();
		}

		$this->showCompatMessage();
	}

	public function onAfterInstall($route)
	{
		$this->disableCoreEditorPlugin();
	}

	private function showCompatMessage()
	{
		$installed_version = $this->getVersion($this->getInstalledXMLFile());

		if (version_compare($installed_version, 7, '<'))
		{
			JFactory::getApplication()->enqueueMessage(
				'Articles Anywhere no longer supports the old data tag attribute syntax, like: <code>{text:limit=100:strip}</code><br>'
				. 'You will need to use the new attribute syntax, like: <code>[text limit="100" strip="true"]</code><br><br>'
				. 'If you still need support for the old syntax, you will need to downgrade to Articles Anywhere v6.3.0.'
				, 'warning'
			);
		}
	}

	private function setUseK2ForOldSetups()
	{
		$params = $this->getPluginParams();

		if (empty($params))
		{
			return;
		}

		// Already set
		if (isset($params->use_k2))
		{
			return;
		}

		// No need to set the use_k2 to yes
		if ( ! isset($params->content_type) || $params->content_type != 'k2')
		{
			return;
		}

		// Set tag_characters_data to old (pre v4.2.0) value
		$params->use_k2 = true;

		$this->savePluginParams($plugin->extension_id, $params);
	}

	private function getPluginParams()
	{
		$query = $this->db->getQuery(true)
			->select('params')
			->from($this->db->quoteName('#__extensions'))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('articlesanywhere'))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote('system'));
		$this->db->setQuery($query, 0, 1);

		$params = $this->db->loadResult();

		if (empty($params))
		{
			return false;
		}

		return json_decode($params);
	}

	private function savePluginParams($params)
	{
		$params = json_encode($params);

		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set($this->db->quoteName('params') . ' = ' . $this->db->quote($params))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('articlesanywhere'))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote('system'));
		$this->db->setQuery($query);
		$this->db->execute();

		JFactory::getCache()->clean('_system');
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
