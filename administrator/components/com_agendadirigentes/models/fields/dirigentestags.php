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
require_once JPATH_ROOT . '/administrator/components/com_agendadirigentes/helpers/agendadirigentes.php';
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
		$input = JFactory::getApplication()->input;
		$componentParams = AgendaDirigentesHelper::getParams();
		$published = $this->element['published']? $this->element['published'] : array(0,1);
		$db = JFactory::getDbo();
		$attr_name = $this->getAttribute('name', '');
		
		//inicializando variavel para receber opcoes do combo
		$options = array();

		//bloqueio para restringir lista de usuarios ao dono do evento selecionado.
		if( $attr_name != 'owner' &&  $attr_name != 'autoridade' )
		{
			$compromisso_id = $input->getInt('id', 0);
			if( $compromisso_id == 0 && $componentParams->get('permitir_participantes_locais', 1) == 1)
			{
				$options[0] = new StdClass();
				$options[0]->value = "";
				$options[0]->path = "";
				$options[0]->level = 1;
				$options[0]->published = 0;
				$options[0]->text = JText::_('COM_AGENDADIRIGENTES_FIELD_DIRTAGS_CHOOSE_OWNER');
				$options = JHelperTags::convertPathsToNames($options);
				return $options;
			}
			else if($componentParams->get('permitir_participantes_locais', 1) == 1)
			{
				$ownerData = $this->getOwnerDataFromCompromissoId( $compromisso_id );
				$valid_catid_list = $this->getCategoriesScope( $ownerData );
			}
			else
			{
				$valid_catid_list = array();
			}

			if( ((count($valid_catid_list) == 1 && @$valid_catid_list[0] == 0) || is_null($valid_catid_list) )
				&& $componentParams->get('permitir_participantes_externos', 1) == 0 )			
			{
				$options[0] = new StdClass();
				$options[0]->value = "";
				$options[0]->path = "";
				$options[0]->level = 1;
				$options[0]->published = 0;
				$options[0]->text = JText::_('COM_AGENDADIRIGENTES_FIELD_DIRTAGS_NO_ENOUGH_PERMISSIONS');
				$options = JHelperTags::convertPathsToNames($options);
				return $options;
			}
		}

		//se componente configurado para inclusao de participantes cadastrados no sistema e por isso locais (do mesmo orgao)
		//ou se nome do campo equivale a owner (responsavel pelo compromisso ou dono)
		// entao busque por dirigentes para povoar as opcoes do combo...
		if($componentParams->get('permitir_participantes_locais', 1) == 1 || $attr_name == 'owner' || $attr_name == 'autoridade' )
		{
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

			//se esta permitida a inclusao de participantes locais e o campo
			//nao se refere ao campo de escolha do dono do compromisso, entao flag permitir_sobreposicao = 1
			if($attr_name != 'owner' && $attr_name != 'autoridade')
			{
				$query->where('b.permitir_sobreposicao = 1');
				
				if($valid_catid_list != '*') // != all
				{
					if(is_null($valid_catid_list))
					{
						$valid_catid_list = array(0);
						JFactory::getApplication()->enqueueMessage(JText::_('COM_AGENDADIRIGENTES_FIELD_DIRTAGS_INVALID_CATEGORY_LIST'), 'Warning');
					}
					
					$query->where('b.catid IN (' . implode(', ', $valid_catid_list ) . ')');
				}
			}

			// Get the options.
			$db->setQuery((string)$query);
			$categories = $db->loadObjectList();
		}
		else //se nao forem permitidos participantes locais e nome do campo != owner
		{
			$categories = array();
		}

		//se campo == owner, incluir primeira opcao vazia
		if ( $attr_name == 'owner' || $attr_name == 'autoridade' ) 
		{
			$options[0] = new StdClass();
			$options[0]->value = "";
			$options[0]->path = "";
			$options[0]->level = 1;
			$options[0]->published = 1;
			$options[0]->text = " - Selecione - ";

			//restringir de acordo com as permissoes de usuario, para que a escolha do owner respeite o que foi cadastrado como permissao
			//so ocorre se componente configurado para restringir ou usuario nao for superuser
			if( $componentParams->get('restricted_list_compromissos', 0) == 1 && ! AgendaDirigentesHelper::isSuperUser() && $attr_name == 'owner' )
			{
				$allowedCategories = array();
				$id = $input->getInt('id', 0);

				if(empty($id)) //novo item, verifica permissoes de criacao
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
				else //item em edicao, verifica permissoes de edicao / mudanca de estado
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
		} // fim se campo == owner
		
		try
		{
			$options = array_merge($options, $categories);
		}
		catch (RuntimeException $e)
		{
			return false;
		}

		//se incluir participantes externos = true na configuracao do field
		//e se for possivel permitir participantes externos = 1 na configuracao do componente
		//e se nome do campo for diferente de owner, entao leia campo de participantes externos
		//e os inclua
		if ( $this->getAttribute('add_participantes_externos', false) == true
			&& $componentParams->get('permitir_participantes_externos', 1) == 1
			&&  $attr_name != 'owner' &&  $attr_name != 'autoridade' )
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
		$componentParams = AgendaDirigentesHelper::getParams();

		// se campo for do tipo multipla escolha e permitir participantes locais
		// ou se campo for do tipo multipla escolha e permitir participantes externos
		// entao incluir script
		if( $this->getAttribute('multiple', false) == "true"
			&& ($componentParams->get('permitir_participantes_locais', 1) == 1
				|| $componentParams->get('permitir_participantes_externos', 1) == 1)
		)
		{
			$id    = isset($this->element['id']) ? $this->element['id'] : null;
			$cssId = '#' . $this->getId($id, $this->element['name']);			
			$this->ajaxfieldCustomTag($cssId, 5, ($componentParams->get('permitir_participantes_externos', 1)==1)? 'true' : 'false' );
		}
		
		$input = parent::getInput();

		return $input;
	} 

	/*
	inclui script para inclusao de dirigentes em formato de tags.
	*/
	protected function ajaxfieldCustomTag($selector='#jform_tags', $minTermLength = 5, $customTags = 'true')
	{
		JFactory::getDocument()->addScriptDeclaration("
			(function($){
				$(document).ready(function () {
					var allowCustomTags = " . $customTags . ";
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
							else if( allowCustomTags == true )
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

	protected function getOwnerDataFromCompromissoId( $compromisso_id = 0 )
	{
		if(empty($compromisso_id))
			return NULL;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(
				$db->quoteName('car.realizar_sobreposicao') . ', ' .
				$db->quoteName('car.catid') . ', ' .
				$db->quoteName('dir.cargo_id') . ', ' .
				$db->quoteName('dir.id') . ', ' .
				$db->quoteName('cat.lft') . ', ' .
				$db->quoteName('cat.rgt')
			)->from(
				$db->quoteName('#__agendadirigentes_cargos', 'car')
			)->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_dirigentes', 'dir')
				. ' ON ' . $db->quoteName('car.id') . ' = ' . $db->quoteName('dir.cargo_id')
			)->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_dirigentes_compromissos', 'dc')
				. ' ON ' . $db->quoteName('dc.dirigente_id') . ' = ' . $db->quoteName('dir.id')
				. ' AND ' . $db->quoteName('dc.owner') . ' =  1 '
			)
			->join(
				'INNER',
				$db->quoteName('#__agendadirigentes_compromissos', 'comp')
				. ' ON ' . $db->quoteName('dc.compromisso_id') . ' = ' . $db->quoteName('comp.id')
			)
			->join(
				'INNER',
				$db->quoteName('#__categories', 'cat')
				. ' ON ' . $db->quoteName('car.catid') . ' = ' . $db->quoteName('cat.id')
			)
			->where(
				$db->quoteName('comp.id') . ' = ' . (int) $compromisso_id
			);

		$db->setQuery( (string) $query );

		//car.realizar_sobreposicao:
		//0 = nao permtir
		//1 = somente na categoria
		//* = todos
		return $db->loadObject();
	}

	protected function getCategoriesScope( $ownerData )
	{
		if(! is_object($ownerData) )
			return NULL;

		if( $ownerData->realizar_sobreposicao == '*' ) //todas as categorias
		{
			return $ownerData->realizar_sobreposicao;
		}
		else if( $ownerData->realizar_sobreposicao == 0 ) //nenhuma categoria
		{
			return array(0);
		}
		elseif( $ownerData->realizar_sobreposicao != 1 )
		{
			return NULL;
		}

		//$ownerData->realizar_sobreposicao == 1 (somente daquela categoria e filhas dela)
		$db = JFactory::getDBO();
		$query = $db->getQuery( true );
		$query->select(
				$db->quoteName('id')
			)->from(
				$db->quoteName('#__categories')
			)->where(
				$db->quoteName('lft') . ' >= ' . (int) $ownerData->lft
				. ' AND ' .
				$db->quoteName('rgt') . ' <= ' . (int) $ownerData->rgt
			);

		$db->setQuery( (string) $query );
		$result = $db->loadObjectList('id');

		if(!is_array($result))
			return array(0);

		return array_keys($result);
	}
}