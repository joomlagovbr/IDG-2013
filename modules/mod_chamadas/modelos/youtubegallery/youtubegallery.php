<?php
defined('_JEXEC') or die;

class ModeloYoutubegallery
{
	public function getListaModelo($params) 
	{
		// var_dump($params);
		// die();
		//Permissão de acesso
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());

		//Conexão
		$db		= JFactory::getDBO();

		//Busca data - zerada e atual
		$nullDate = $db->getNullDate();
		$date = JFactory::getDate();
		$atual = $date->toSql();

		//Consulta
		$query	= $db->getQuery(true);
		$query->clear();
		$query->select('cont.id, cont.listid AS catid, cont.videoid AS alias, cont.publisheddate AS created_date, cont.lastupdate AS modify_date, cont.publisheddate AS publish_date, cont.link');
		$query->from('#__youtubegallery_videos cont');
		$query->from('#__youtubegallery_videolists cat');

		$query->where('cont.listid = cat.id');
		$query->where('(cont.publisheddate = '.$db->Quote($nullDate).' OR cont.publisheddate <= '.$db->Quote($atual).')');
		$query->where('cont.isvideo = 1');	
		
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
			$query->select('cont.imageurl AS images');
		}

		//Traz o resultado do introtext ou não
		if($params->get('exibir_introtext')){
			$query->select('cont.description AS introtext');
		}

		if($params->get('somente_imagem')){
			$query->where('cont.imageurl <> '.$db->Quote(''));
		}		

		//obtem o valor de configuracao quando um unico artigo sera exibido...
		$id_unique_article = ($params->get('id_article_unico', '') != '')? $params->get('id_article_unico') : $params->get('id_item_unico', '');

		//se o valor para um unico artigo estiver vazio, executa as buscas e configura a consulta para as categorias ou tags
		if( empty($id_unique_article) )
		{
			$cat = $params->get('catid_components');			
			$cat = str_replace(array(' ',';'), array('',','),$cat);

			if($cat=='' || $cat==0)
				$cat = 1;

			$query->where('cont.listid IN ('.$cat.')');				
				
		}
		else //se o valor de id_unique_article nao estiver vazio
		{
			$query->where('cont.id = '.intval($id_unique_article) );
			$params->set('quantidade', 1);
		}

		switch($params->get('ordem'))
		{
			case 'title':
				$params->set('ordem', 'title');
			break;
			case 'publish_up':
				$params->set('ordem', 'publisheddate');
			break;
			case 'modified':
				$params->set('ordem', 'lastupdate');
			break;
			case 'ordering':
				$params->set('ordem', 'ordering');
			break;
			case 'hits':
				$params->set('ordem', 'statistics_viewCount');
			break;
			case 'created': default:
				$params->set('ordem', 'id');
			break;
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
				$lista[$i]->introtext = strip_tags($params->get('desc_item'.($i+1)));
			}
			elseif($params->get('exibir_introtext') && $lista[$i]->introtext != ''){
				$lista[$i]->introtext = ModChamadasHelper::getIntroLimiteCaracteres($lista[$i]->introtext, $params);
			}
			else{
				$lista[$i]->introtext = '';
			}

			// OPCOES DE IMAGEM DO ARTIGO
			if ($params->get('exibir_imagem')) {
				$images = explode(',', $lista[$i]->images);				
				$lista[$i]->images = new StdClass();
				$lista[$i]->images->image_intro = $images[0];
				$lista[$i]->images->float_intro = '';
				$lista[$i]->images->image_intro_alt = $lista[$i]->link;
				$lista[$i]->images->image_intro_caption = $lista[$i]->title;
				$lista[$i]->images->image_mq = $images[1];
				$lista[$i]->images->image_hq = $images[2];
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
			$lista[$i]->link = ModChamadasHelper::getLink($params, $fields, $lista[$i], false);

		}

		//retorna a lista ja processada
		return $lista;
	}
}