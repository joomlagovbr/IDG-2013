<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\RegularLabs;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\Document as RL_Document;

class DownloadKey
{
	public static function update()
	{
		// Save the download key from the Regular Labs Extension Manager config to the update sites
		if (
			RL_Document::isClient('site')
			|| JFactory::getApplication()->input->get('option') != 'com_config'
			|| JFactory::getApplication()->input->get('task') != 'config.save.component.apply'
			|| JFactory::getApplication()->input->get('component') != 'com_regularlabsmanager'
		)
		{
			return;
		}

		$form = JFactory::getApplication()->input->post->get('jform', [], 'array');

		if ( ! isset($form['key']))
		{
			return;
		}

		$key = $form['key'];

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->update('#__update_sites')
			->set($db->quoteName('extra_query') . ' = ' . $db->quote('k=' . $key))
			->where($db->quoteName('location') . ' LIKE ' . $db->quote('%download.regularlabs.com%'));
		$db->setQuery($query);
		$db->execute();
	}
}
