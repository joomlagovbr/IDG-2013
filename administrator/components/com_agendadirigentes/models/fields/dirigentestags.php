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
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

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
		$query	= $db->getQuery(true);
		if ($this->getAttribute('show_category', 1) == 1)
		{
			$query->select('a.id AS value, CONCAT(c.title, " - ", b.name, " - " ,a.name) AS text, \'\' AS path, 1 AS level, a.state AS published, b.catid');
			$query->order('c.title, b.name, a.name');
		}
		else
		{
			$query->select('a.id AS value, CONCAT(b.name, " - " ,a.name) AS text, \'\' AS path, 1 AS level, a.state AS published, b.catid');
			$query->order('b.name, a.name');
		}
		
		$query->from(
				$db->quoteName('#__agendadirigentes_dirigentes', 'a')
			)->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_cargos', 'b')
				.' ON (' . $db->quoteName('a.cargo_id') . ' = ' . $db->quoteName('b.id') . ')'
			)->join(
				'INNER',
				$db->quoteName('#__categories', 'c')
				.' ON (' . $db->quoteName('b.catid') . ' = ' . $db->quoteName('c.id') . ')'
			);


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


		// Get the options.
		$db->setQuery((string)$query);
		$categories = $db->loadObjectList();

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

		//restringir de acordo com as permissoes de usuario
		$componentParams = AgendaDirigentesHelper::getParams();
		if( $componentParams->get('restricted_list_compromissos', 0) == 1 && ! AgendaDirigentesHelper::isSuperUser() )
		{
			$allowedCategories = array();
			$input = JFactory::getApplication()->input;
			$id = $input->getInt('id', 0);

			if(empty($id)) //new
			{
				for ($i=0, $limit = count($categories); $i < $limit; $i++)
				{
					$canCreate = AgendaDirigentesHelper::getGranularPermissions('compromissos', $categories[$i], 'create' );
					if ( $canCreate )
					{
						$allowedCategories[] = $categories[$i];
					}
				}
			}
			else //edit
			{
				for ($i=0, $limit = count($categories); $i < $limit; $i++)
				{ 
					list($canManage, $canChange) = AgendaDirigentesHelper::getGranularPermissions('compromissos', $categories[$i], 'manage' );
					if ($canManage || $canChange)
					{
						$allowedCategories[] = $categories[$i];
					}
				}
			}
			$categories = $allowedCategories;
		}
		//fim restricao de acordo com as permissoes de usuario

		try
		{
			$options = array_merge($options, $categories);
		}
		catch (RuntimeException $e)
		{
			return false;
		}


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
	/**
	 * Method to get the field input for a tag field.
	 *
	 * @return  string  The field input.
	 *
	 * @since   3.1
	 */
	protected function getInput()
	{
		if( $this->getAttribute('multiple', false) == "true" )
		{
			$id    = isset($this->element['id']) ? $this->element['id'] : null;
			$cssId = '#' . $this->getId($id, $this->element['name']);			
			$this->ajaxfieldCustomTag($cssId);
		}
		
		$input = parent::getInput();

		return $input;
	} 

	protected function ajaxfieldCustomTag($selector='#jform_tags', $minTermLength = 5)
	{
		JFactory::getDocument()->addScriptDeclaration("
			(function($){
				$(document).ready(function () {

					var customTagPrefix = '#new#';

					// Method to add tags pressing enter
					$('" . $selector . "_chzn input').keyup(function(event) {

						// Tag is greater than 3 chars and enter pressed
						if (this.value.length >= " . $minTermLength . " && (event.which === 13 || event.which === 188)) {

							// Search an highlighted result
							var highlighted = $('" . $selector . "_chzn').find('li.active-result.highlighted').first();

							// Add the highlighted option
							if (event.which === 13 && highlighted.text() !== '')
							{
								// Extra check. If we have added a custom tag with this text remove it
								var customOptionValue = customTagPrefix + highlighted.text();
								$('" . $selector . " option').filter(function () { return $(this).val() == customOptionValue; }).remove();

								// Select the highlighted result
								var tagOption = $('" . $selector . " option').filter(function () { return $(this).html() == highlighted.text(); });
								tagOption.attr('selected', 'selected');
							}
							// Add the custom tag option
							else
							{
								var customTag = this.value;

								// Extra check. Search if the custom tag already exists (typed faster than AJAX ready)
								var tagOption = $('" . $selector . " option').filter(function () { return $(this).html() == customTag; });
								if (tagOption.text() !== '')
								{
									tagOption.attr('selected', 'selected');
								}
								else
								{
									var option = $('<option>');
									option.text(this.value).val(customTagPrefix + this.value);
									option.attr('selected','selected');

									// Append the option an repopulate the chosen field
									$('" . $selector . "').append(option);
								}
							}

							this.value = '';
							$('" . $selector . "').trigger('liszt:updated');
							event.preventDefault();

						}
					});
				});
			})(jQuery);
			"
		);

	}
}