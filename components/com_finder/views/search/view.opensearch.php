<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * OpenSearch View class for Finder
 *
 * @since  2.5
 */
class FinderViewSearch extends JViewLegacy
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  JError object on failure, void on success.
	 *
	 * @since   2.5
	 */
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();

		$params = JComponentHelper::getParams('com_finder');
		$doc->setShortName($params->get('opensearch_name', $app->get('sitename')));
		$doc->setDescription($params->get('opensearch_description', $app->get('MetaDesc')));

		// Add the URL for the search
		$searchUri = JUri::base() . 'index.php?option=com_finder&q={searchTerms}';

		// Find the menu item for the search
		$menu  = $app->getMenu();
		$items = $menu->getItems('link', 'index.php?option=com_finder&view=search');

		if (isset($items[0]))
		{
			$searchUri .= '&Itemid=' . $items[0]->id;
		}

		$htmlSearch           = new JOpenSearchUrl;
		$htmlSearch->template = JRoute::_($searchUri);
		$doc->addUrl($htmlSearch);
	}
}
