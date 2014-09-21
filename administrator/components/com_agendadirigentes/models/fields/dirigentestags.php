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
			->select('a.id AS value, CONCAT(c.title, " - ", b.name, " - " ,a.name) AS text, \'\' AS path, 1 AS level, a.state AS published')
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
		$db->setQuery((string)$query);

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
// var_dump($this->element['add_participantes_externos']);

		if ( $this->getAttribute('add_participantes_externos', false) )
		{

			$input = JFactory::getApplication()->input;
			$id = $input->get('id', 0, 'int');
			$query	= $db->getQuery(true)
						 ->select(
						 	$db->quoteName('participantes_externos')
						 )
						 ->from(
						 	$db->quoteName('#__agendadirigentes_compromissos')
						 )
						 ->where(
						 	$db->quoteName('id') . ' = ' . $id
						 );
			$db->setQuery( (string)$query );
			$participantes_externos = $db->loadResult();
			$participantes_externos = explode(';', $participantes_externos);

			$opt_externos = array();
			for ($i=0, $limit = count($participantes_externos); $i < $limit; $i++) { 
				$opt_externos[$i] = new StdClass();
				$opt_externos[$i]->value = trim($participantes_externos[$i]);
				$opt_externos[$i]->text = trim($participantes_externos[$i]);
				$opt_externos[$i]->path = "";
				$opt_externos[$i]->level = 1;
				$opt_externos[$i]->published = 1;
			}
			
			try
			{
				$options = array_merge($options, $opt_externos);
			}
			catch (RuntimeException $e)
			{
				return false;
			}
		}

		$options = JHelperTags::convertPathsToNames($options);

		return $options;
	}

	/*protected function getInput()
	{
		$input = parent::getInput();
		if ( $this->getAttribute('add_participantes_externos', false) )
		{
			$element = 'jform_' . $this->addAttribute('name');
			$script = 'jQuery(#'.$element.').append("<option value=\'teste\' selected=\'selected\'>teste</option>");
					   ';
			// $html = '<script>window.alert("teste")</script>';
			// $input = $input . $html;			
		}
		return $input;
	}*/
 
}