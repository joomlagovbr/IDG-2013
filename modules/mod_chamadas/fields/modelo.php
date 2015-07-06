<?php
/*
 * Modelo básico de fields, caso seja necessário criar algum campo não contemplado no modelo padrão
 */

defined('_JEXEC') or die;
 
jimport('joomla.form.helper');
jimport( 'joomla.filesystem.folder' );
JFormHelper::loadFieldClass('list');
 
class JFormFieldModelo extends JFormFieldList
{
	protected $type = 'Modelo';

	protected function getOptions() 
	{		
		//Pega os diretórios modelos
		$modelos_dir = JFolder::listFolderTree(JPATH_SITE . '/modules/mod_chamadas/modelos/', '', 1);			

		//Busca diretórios na pasta "mod_chamadas/modelos" e cria opções de seleção para configuração do módulo
		foreach($modelos_dir as $modelo){
			$options[] = JHtml::_('select.option', $modelo['name'], ucwords(str_replace('_', ' ', $modelo['name'])));
		}

		//Lista que será incorporada nos fields do módulo
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}