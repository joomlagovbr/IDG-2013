<?php
/**
* @package PortalPadrao
* @subpackage com_agendadirigentes
*
* @copyright Copyright (C) 2005 - 2014 Joomla Calango. All rights reserved.
* @license GNU General Public License version 2 or later; see LICENSE.txt
*/
 
// impedir acesso direto ao arquivo
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('tag');
 
/**
 * DirigentesTags Form Field class for the AgendaDirigentes component
 */
class JFormFieldDirigentesTags extends JFormFieldTag
{
    /**
     * The field type.
     *
     * @var         string
     */
    public $type = 'DirigentesTags';

        	/**
	 * Method to get a list of tags
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.1
	 */
	protected function getOptions()
	{
		$published = $this->element['published']? $this->element['published'] : array(0,1);

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select('a.id AS value, CONCAT(c.title, " - ", b.name, " " ,a.name) AS text, \'\' AS path, 1 AS level, a.state AS published')
			->from( $db->quoteName('#__agendadirigentes_dirigentes', 'a') )
			->join('INNER', $db->quoteName('#__agendadirigentes_cargos', 'b')
				.' ON (' . $db->quoteName('a.cargo_id') . ' = ' . $db->quoteName('b.id') . ')' )
			->join('INNER', $db->quoteName('#__categories', 'c')
				.' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('c.id') . ')' );
		/*
		SELECT
		a.id AS value, a.name AS text, '' AS path, 1 AS level, a.state AS published
		FROM x3dts_agendadirigentes_dirigentes AS a
		INNER JOIN x3dts_agendadirigentes_cargos AS b
		ON a.cargo_id = b.id
		INNER JOIN x3dts_categories AS c
		ON a.catid = c.id
		*/

		// Filter on the published state
		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			JArrayHelper::toInteger($published);
			$query->where('a.state IN (' . implode(',', $published) . ')');
		}

		$query->order('c.title, b.name, a.name');

		// Get the options.
		$db->setQuery($query);

		$options = array();
		if ($this->element['emptyfirst'])
		{
			$options[0] = new StdClass();
			$options[0]->value = "";
			$options[0]->text = " - Selecione - ";
			$options[0]->path = "";
			$options[0]->level = 1;
			$options[0]->published = 1;
		}

		try
		{
			$options = array_merge($options, $db->loadObjectList());
		}
		catch (RuntimeException $e)
		{
			return false;
		}

		$options = JHelperTags::convertPathsToNames($options);

		return $options;
	}
 
}