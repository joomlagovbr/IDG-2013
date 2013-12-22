<?php
defined('_JEXEC') or die;

class ModeloPhoca_gallery
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
		$query->select('cont.id, cont.catid, cont.alias, cont.date AS created_date, cont.date AS modify_date, cont.date AS publish_date, cont.metadesc AS image_caption');
		$query->from('#__phocagallery cont');
		$query->from('#__phocagallery_categories cat');

		$query->where('cont.catid = cat.id');
		$query->where('cont.published=1');
		$query->where('cont.approved=1');
		$query->where('cat.published = 1');
		$query->where('cat.approved = 1');
		$query->where('cat.access IN ('.$groups.')');
		
		
		//Traz o resultado do chapeu se existir
		if($params->get('chapeu') && $params->get('chapeu') != '0'  && $params->get('chapeu') != 'nenhum' ){
			$query->select($db->Quote('').' AS chapeu');
		}
		
		//Traz o resultado do título ou não
		if($params->get('exibir_title')){
			$query->select('cont.title');
		}

		//Traz o resultado da imagem ou não
		if($params->get('exibir_imagem')){
			$query->select('cont.filename AS images');
		}

		//Traz o resultado do introtext ou não
		if($params->get('exibir_introtext')){
			$query->select('cont.description AS introtext');
		}

		$baseurl = JURI::root().'images/phocagallery/';
		
		//obtem o valor de configuracao quando um unico artigo sera exibido...
		$id_unique_article = ($params->get('id_article_unico', '') != '')? $params->get('id_article_unico') : $params->get('id_item_unico', '');

		//se o valor para um unico artigo estiver vazio, executa as buscas e configura a consulta para as categorias ou tags
		if( empty($id_unique_article) )
		{
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
						$subQuery->from('#__phocagallery_categories AS pai');
						$subQuery->innerJoin('#__phocagallery_categories AS filho ON filho.parent_id = pai.id');
						$subQuery->where('pai.id IN ('.$cat.')');
						$subQuery->where('filho.published = 1');
						$subQuery->where('pai.published = 1');
						$subQuery->where('filho.approved = 1');
						$subQuery->where('pai.approved = 1');
						
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


			//Verifica se irá filtrar por tag
			if($params->get('buscar_cat_tag') != '1' && $params->get('tags'))
			{			

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
					$subQuery->select('ref.imgid');
					$subQuery->from('#__phocagallery_tags tag');
					$subQuery->innerJoin('#__phocagallery_tags_ref ref ON tag.id = ref.tagid');
					$subQuery->where('tag.alias IN ('.$tags.')');

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
		
		for ($i=0; $i < $lista_counter; $i++) { 

			//chapeu e title
			if($params->get('chapeu') && $params->get('chapeu') != '0'  && $params->get('chapeu') != 'nenhum')			
				$lista[$i]->chapeu = ($params->get('chapeu_item'.($i+1), '') != '')? $params->get('chapeu_item'.($i+1) ) : @$lista[$i]->chapeu;
			else
				$lista[$i]->chapeu = NULL;

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

			if( $params->get('image_item'.($i+1), '') != '') {
				$lista[$i]->image_url = $params->get('image_item'.($i+1) );
			}
			elseif($params->get('exibir_imagem')) {
				$folder = substr($lista[$i]->images, 0, strrpos($lista[$i]->images, '/')+1);
				$filename = substr($lista[$i]->images, strrpos($lista[$i]->images, '/')+1);
				$prefix = 'thumbs/phoca_thumb_l_';
				$lista[$i]->images = @$lista[$i]->images;
				$lista[$i]->image_url = $baseurl . $folder . $prefix . $filename;
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

			if( $params->get('image_item'.($i+1).'_caption', '') != '') {
				$lista[$i]->image_caption = $params->get('image_item'.($i+1).'_caption' );
			}

			// LINK DO ARTIGO
			$fields = array();
			$fields[] = 'url_simple_item'.($i+1);
			$fields[] = 'url_menu_item'.($i+1);
			$fields[] = 'url_article_item'.($i+1);
			$lista[$i]->link = ModChamadasHelper::getLink($params, $fields, $lista[$i], false);

			if(@empty($lista[$i]->link))
			{
				$lista[$i]->link = JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$lista[$i]->catid.'&id='.$lista[$i]->id.':'.$lista[$i]->alias);				
			}

		}

		//retorna a lista ja processada
		return $lista;
	}
}