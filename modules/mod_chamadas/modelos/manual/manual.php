<?php
defined('_JEXEC') or die;

class ModeloManual
{
	public function getListaModelo($params) 
	{
		//Permissão de acesso
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$lista = array();
		$default = 3;

		$limite = (intval($params->get('limite_campos_preenchimento_manual', $default)) <= intval($params->get('quantidade', $default)))? intval($params->get('limite_campos_preenchimento_manual', $default)) : intval($params->get('quantidade', $default));

		for ($i=0; $i < $limite; $i++) { 

			$lista[$i] = new StdClass;

			//chapeu e title
			$lista[$i]->chapeu = $params->get('chapeu_item'.($i+1), '' );
			$lista[$i]->title = $params->get('title_item'.($i+1), '' );

			// DESCRICAO DO ARTIGO
			if($params->get('desc_item'.($i+1), '') != ''){
				$lista[$i]->introtext = $params->get('desc_item'.($i+1));								
			}
			else{
				$lista[$i]->introtext = '';
			}

			// OPCOES DE IMAGEM DO ARTIGO
			if( $params->get('image_item'.($i+1), '') != '') {
				$lista[$i]->image_url = $params->get('image_item'.($i+1) );
			}
			else {
				$lista[$i]->image_url = '';				
			}

			if( $params->get('image_item'.($i+1).'_align', '') != '') {
				$lista[$i]->image_align = $params->get('image_item'.($i+1).'_align' );
			}
			else {
				$lista[$i]->image_align = '';				
			}

			if( $params->get('image_item'.($i+1).'_alt', '') != '') {
				$lista[$i]->image_alt = $params->get('image_item'.($i+1).'_alt' );
			}
			else {
				$lista[$i]->image_alt = '';				
			}

			// LINK DO ARTIGO
			$fields = array();
			$fields[] = 'url_simple_item'.($i+1);
			$fields[] = 'url_menu_item'.($i+1);
			$fields[] = 'url_article_item'.($i+1);
			$lista[$i]->link = ModChamadasHelper::getLink($params, $fields);

		}

		//retorna a lista ja processada
		return $lista;
	}
}