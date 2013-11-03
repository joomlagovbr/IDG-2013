<?php
defined('_JEXEC') or die;

class ModeloArticle_content
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
		$query->select('cont.id, cont.catid, cont.alias');
		$query->from('#__content cont');
		$query->from('#__categories cat');

		$query->where('cont.catid = cat.id');
		$query->where('cont.state=1');
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
			$query->select($params->get('chapeu').' AS chapeu');
		}
		
		//Traz o resultado do título ou não
		if($params->get('exibir_title')){
			$query->select('cont.title');
		}

		//Traz o resultado da imagem ou não
		if($params->get('exibir_imagem')){
			$query->select('cont.images');
		}

		//Traz o resultado do introtext ou não
		if($params->get('exibir_introtext')){
			$query->select('cont.introtext');
		}

		if($params->get('somente_imagem')){
			$query->where('cont.images NOT LIKE \'{"image_intro":""%\'');
		}		

		//obtem o valor de configuracao quando um unico artigo sera exibido...
		$id_unique_article = ($params->get('id_article_unico', '') != '')? $params->get('id_article_unico') : $params->get('id_item_unico', '');

		//se o valor para um unico artigo estiver vazio, executa as buscas e configura a consulta para as categorias ou tags
		if( empty($id_unique_article) )
		{
			//Implode nas categorias selecionadas
			$cat = implode(',', $params->get('catid'));

			//Verifica se irá filtrar por categoria
			if($params->get('buscar_cat_tag') != '2' && $params->get('catid')){			
				//Subquery para trazer os id's das categorias filhas			
				if($params->get('visualizar_filho')){
					$subQuery = $db->getQuery(true);
					$subQuery->select('filho.id');
					$subQuery->from('#__categories AS pai');
					$subQuery->from('#__categories AS filho');
					$subQuery->where('pai.id IN ('.$cat.')');
					$subQuery->where('filho.lft >= pai.lft');
					$subQuery->where('filho.rgt <= pai.rgt');
		
					//Define o nível máximo da categoria
					if($params->get('nivel') && count($params->get('catid')) == 1){
						$subQuery->where('filho.level <= '. $params->get('nivel'));
					}
		
					//Filtra as categorias que deverão ser listadas.
					$query->where('cont.catid IN ('.$subQuery.')');
				}else{
					$query->where('cont.catid = '.$cat);				
				}
			}

			 /* ANTENÇÃO
			 * O CÓDIGO ABAIXO FUNCIONA APENAS NA VERSÃO 3.X.X
			 * NA VERSÃO 2.5 OU INFERIOR O COMANDO ABAIXO SERÁ DESCONSIDERADO 
			 */
			
			//Pega a versão do Joomla
			jimport('cms.version');
			$versao = new JVersion;		
			$versaoint = (int)str_replace('.', '', $versao->RELEASE);
			
			//verifica se a versão é superior a 2.5
			if($versaoint > 25){
				//Verifica se irá filtrar por tag
				if($params->get('buscar_cat_tag') != '1' && $params->get('tags')){			
					//Pega os id's em array e separa por vírgulas 
					$tags = implode(',', $params->get('tags'));
					
					$query->from('#__contentitem_tag_map mtag');
					$query->from('#__tags tag');
					$query->where('cont.id = mtag.content_item_id');
					$query->where('tag.id = mtag.tag_id');
					$query->where('tag.published = 1');
					$query->where('tag.access IN ('.$groups.')');
					$query->where('tag_id IN ('.$tags.')');
					$query->group('cont.id');
				}
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
			$lista[$i]->chapeu = ($params->get('chapeu_item'.($i+1), '') != '')? $params->get('chapeu_item'.($i+1) ) : @$lista[$i]->chapeu;
			$lista[$i]->title = ($params->get('title_item'.($i+1), '') != '')? $params->get('title_item'.($i+1) ) : $lista[$i]->title;

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
				$lista[$i]->images = json_decode($lista[$i]->images);				
			}

			if( $params->get('image_item'.($i+1), '') != '') {
				$lista[$i]->image_url = $params->get('image_item'.($i+1) );
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
			elseif($params->get('exibir_imagem')) {
				$lista[$i]->image_alt = @$lista[$i]->images->image_intro_alt;
			}
			else {
				$lista[$i]->image_alt = '';				
			}

			if( $params->get('image_item'.($i+1).'_caption', '') != '') {
				$lista[$i]->image_caption = $params->get('image_item'.($i+1).'_caption' );
			}
			elseif($params->get('exibir_imagem')) {
				$lista[$i]->image_caption = @$lista[$i]->images->image_intro_caption;
			}
			else {
				$lista[$i]->image_caption = '';				
			}

			// LINK DO ARTIGO
			$fields = array();
			$fields[] = 'url_simple_item'.($i+1);
			$fields[] = 'url_menu_item'.($i+1);
			$fields[] = 'url_article_item'.($i+1);
			$lista[$i]->link = ModChamadasHelper::getLink($params, $fields, $lista[$i]);

		}

		//retorna a lista ja processada
		return $lista;
	}
}