<?php
defined('_JEXEC') or die;

class ModeloArticle_k2
{
	public function getListaModelo($params) 
	{
		//Permissão de acesso
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		//Conexão
		$db		= JFactory::getDbo();

		//Busca data - zerada e atual
		$nullDate = $db->getNullDate();
		$date = JFactory::getDate();
		$atual = $date->toSql();

		//Consulta
		$query	= $db->getQuery(true);
		$query->clear();
		$query->select('cont.id, cont.catid, cont.video, cont.video_caption, cont.video_credits, cont.alias, cont.created AS created_date, cont.modified AS modify_date, cont.publish_up AS publish_date');
		$query->from('#__k2_items cont');
		$query->from('#__k2_categories cat');

		$query->where('cont.catid = cat.id');
		$query->where('cont.published=1');
		$query->where('cat.published = 1');
		$query->where('cont.access IN ('.$groups.')');
		$query->where('cat.access IN ('.$groups.')');
		$query->where('(cont.publish_up = '.$db->Quote($nullDate).' OR cont.publish_up <= '.$db->Quote($atual).')');
		$query->where('(cont.publish_down = '.$db->Quote($nullDate).' OR cont.publish_down >= '.$db->Quote($atual).')');

		//Valor 1 = todos que não são destaque
		if($params->get('destaque') == 1){
			$query->where('cont.featured = 0');
		}

		//Valor 2 = somente destaque
		elseif($params->get('destaque') == 2){
			$query->where('cont.featured = 1');
		}		
		
		//Traz o resultado do chapeu se existir
		if($params->get('chapeu') && $params->get('chapeu') != '0'  && $params->get('chapeu') != 'nenhum' ){
			
			if($params->get('chapeu')=='cont.xreference')
				$params->set('chapeu', 'cont.extra_fields');

			$query->select($params->get('chapeu', 'cont.extra_fields').' AS chapeu');
		}
		
		//Traz o resultado do título ou não
		if($params->get('exibir_title')){
			$query->select('cont.title');
		}

		//Traz o resultado da imagem ou não
		if($params->get('exibir_imagem')){
			$query->select($db->Quote("").' AS images');
		}

		//Traz o resultado do introtext ou não
		if($params->get('exibir_introtext')){
			$query->select('cont.introtext');
		}

		if($params->get('somente_imagem')){
			$query->where('cont.image_caption <> '.$db->Quote(""));
		}		

		//obtem o valor de configuracao quando um unico artigo sera exibido...
		$id_unique_article = ($params->get('id_article_unico', '') != '')? $params->get('id_article_unico') : $params->get('id_item_unico', '');

		//se o valor para um unico artigo estiver vazio, executa as buscas e configura a consulta para as categorias ou tags
		if( empty($id_unique_article) )
		{
			//Implode nas categorias selecionadas
			$cat = $params->get('catid_components');
			$categories = explode(',', $cat);

			//Verifica se irá filtrar por categoria
			if($params->get('buscar_cat_tag') != '2' && $params->get('catid_components')){			
				//Subquery para trazer os id's das categorias filhas			
				if($params->get('visualizar_filho')){

					//limite de 3 niveis para que o desempenho nao seja muito prejudicado.
					if($params->get('nivel')>3)
						$params->set('nivel', 3);

					$subQuery = $db->getQuery(true);
					
					for ($i=1; $i <= $params->get('nivel'); $i++) { 

						$subQuery->clear();
						$subQuery->select('filho.id');
						$subQuery->from('#__k2_categories AS pai');
						$subQuery->innerJoin('#__k2_categories AS filho ON filho.parent = pai.id');
						$subQuery->where('pai.id IN ('.$cat.')');
						$subQuery->where('filho.published = 1');
						$subQuery->where('pai.published = 1');

						$db->setQuery($subQuery);
						$result = $db->loadResultArray();

						if(count($result)==0)
							break;

						$categories = array_merge($categories, $result);
						$cat = implode(',',$result);

					}
					$categories = array_unique($categories);
					foreach($categories as &$category)
					{
						$category = intval($category);
					}
					$categories = implode(',', $categories);
					
					//Filtra as categorias que deverão ser listadas.
					$query->where('cont.catid IN ('.$categories.')');
				}else{
					$query->where('cont.catid = '.$cat);				
				}
			}

// var_dump($params->get('buscar_cat_tag'));
// var_dump($params->get('tags'));
// 			die();
			
			
			//Verifica se irá filtrar por tag
			if($params->get('buscar_cat_tag') != '1' && $params->get('tags')){			

				// $tags = explode(',', $params->get('tags'));
				$tags = $params->get('tags');

				if(count($tags)>0):
					for ($i=0, $limit=count($tags); $i < $limit; $i++) { 
						$tags[$i] = trim($tags[$i]);
						$tags[$i] = $db->Quote($tags[$i]);
					}
					$tags = implode(',',$tags);
					
					$subQuery = $db->getQuery(true);
					$subQuery->clear();
					$subQuery->select('x.itemID');
					$subQuery->from('#__k2_tags t');
					$subQuery->innerJoin('#__k2_tags_xref x ON t.id = x.tagID');
					$subQuery->where('name IN ('.$tags.')');

					$db->setQuery($subQuery);
					$result = $db->loadResultArray();

					if(count($result)>0)
					{
						$query->where('cont.id IN ('.implode(',',$result).')');
					}					
				endif;
			}
		
		}
		else //se o valor de id_unique_article nao estiver vazio
		{
			$query->where('cont.id = '.intval($id_unique_article) );
			$params->set('quantidade', 1);
		}
		
		$query->order('cont.'.$params->get('ordem'), $params->get('ordem_direction'));
		$db->setQuery($query,0,$params->get('quantidade'));


		$lista = $db->loadObjectList();

		//pre processa os itens do array para valores padrao e sobrescricao dos dados pelo modulo
		$lista_counter = count($lista);
		$layout = $params->get('layout');
		$layout = explode(':',$layout);
		$layout = $layout[1];
		$allvideosplugin_params = false;

		for ($i=0; $i < $lista_counter; $i++) { 

			//chapeu e title
			if($params->get('chapeu') && $params->get('chapeu') != '0'  && $params->get('chapeu') != 'nenhum')
			{
				$lista[$i]->chapeu = ($params->get('chapeu_item'.($i+1), '') != '')? $params->get('chapeu_item'.($i+1) ) : @$lista[$i]->chapeu;
				if($lista[$i]->chapeu ==@$lista[$i]->chapeu && !empty($lista[$i]->chapeu))
				{
					$lista[$i]->chapeu = json_decode($lista[$i]->chapeu);
					if(!is_null($lista[$i]->chapeu))
					{
						if(@isset($lista[$i]->chapeu->chapeu))
							$lista[$i]->chapeu = $lista[$i]->chapeu->chapeu;
						else
							$lista[$i]->chapeu = NULL;
					}
				}
			}			
			else
				$lista[$i]->chapeu = NULL;

			@$original_title = $lista[$i]->title;

			if($params->get('exibir_title'))
				$lista[$i]->title = ($params->get('title_item'.($i+1), '') != '')? $params->get('title_item'.($i+1) ) : $lista[$i]->title;
			else
				$lista[$i]->title = NULL;
			
			// DESCRICAO DO ARTIGO
			if($params->get('desc_item'.($i+1), '') != ''){
				$lista[$i]->introtext = $params->get('desc_item'.($i+1));								
			}
			elseif($params->get('exibir_introtext')){
				$lista[$i]->introtext = '<p class="description">'.ModChamadasHelper::getIntroLimiteCaracteres($lista[$i]->introtext, $params).'</p>'	;
			}
			else{
				$lista[$i]->introtext = '';
			}

			// OPCOES DE IMAGEM DO ARTIGO
			if ($params->get('exibir_imagem')) {
				// todo: tratamento para adquirir nomes dos arquivos de imagem. E um padrao, nao fica em banco de dados

				if(!empty($lista[$i]->images))
					$lista[$i]->images = json_decode($lista[$i]->images);

			}

			if( $params->get('image_item'.($i+1), '') != '') {
				$lista[$i]->image_url = $params->get('image_item'.($i+1) );
			}
			elseif(!empty($lista[$i]->video) && $layout=='listagem-audio' && strpos($lista[$i]->video, '{/mp3}')!==false)
			{				
				if(!$allvideosplugin_params)
				{
					$allvideosplugin = JPluginHelper::getPlugin('content','jw_allvideos');
					$allvideosplugin_params = json_decode($allvideosplugin->params);				
				}
				$file = JURI::root() . $allvideosplugin_params->afolder .'/'. str_replace(array('{mp3}', '{/mp3}'), '', $lista[$i]->video).'.mp3';
				$lista[$i]->image_url = $file;
				$lista[$i]->image_caption = $lista[$i]->video_credits;
				$lista[$i]->image_alt = $lista[$i]->video_caption;
				
			}
			elseif($params->get('exibir_imagem')) {
				$lista[$i]->image_url = @$lista[$i]->images->image_intro;
			}
			else {
				$lista[$i]->image_url = '';				
			}


			if( $params->get('image_item'.($i+1).'_align', '') != '') {
				$lista[$i]->image_align = $params->get('image_item'.($i+1).'_align' );
			}
			elseif($params->get('exibir_imagem')) {
				$lista[$i]->image_align = @$lista[$i]->images->float_intro;
			}
			else {
				$lista[$i]->image_align = '';				
			}

			if( $params->get('image_item'.($i+1).'_alt', '') != '') {
				$lista[$i]->image_alt = $params->get('image_item'.($i+1).'_alt' );
			}
			elseif($params->get('exibir_imagem') && empty($lista[$i]->image_alt)) {
				$lista[$i]->image_alt = @$lista[$i]->images->image_intro_alt;
			}
			elseif(empty($lista[$i]->image_alt)) {
				$lista[$i]->image_alt = '';				
			}

			if( $params->get('image_item'.($i+1).'_caption', '') != '') {
				$lista[$i]->image_caption = $params->get('image_item'.($i+1).'_caption' );
			}
			elseif($params->get('exibir_imagem') && empty($lista[$i]->image_caption)) {
				$lista[$i]->image_caption = @$lista[$i]->images->image_intro_caption;
			}
			elseif(empty($lista[$i]->image_caption)) {
				$lista[$i]->image_caption = '';				
			}

			// LINK DO ARTIGO
			$fields = array();
			$fields[] = 'url_simple_item'.($i+1);
			$fields[] = 'url_menu_item'.($i+1);
			$fields[] = 'url_article_item'.($i+1);
			$lista[$i]->link = ModChamadasHelper::getLink($params, $fields, $lista[$i], false);

			if(empty($lista[$i]->link))
				$lista[$i]->link = JRoute::_('index.php?option=com_k2&view=item&id='.$lista[$i]->id.':'.$original_title);

		}

		//retorna a lista ja processada
		return $lista;
	}
}