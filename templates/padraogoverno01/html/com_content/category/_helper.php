<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

class TemplateContentCategoryHelper {

	static function getAuthorByUserId( $id )
	{
		if(empty($id))
			return '';

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('name');
		$query->from('#__users');
		$query->where( 'id = '.intval($id) );
		
		$db->setQuery( $query );
		$result = $db->loadResult();		
		
		if( @is_null($result) || @empty($result) )
			return '';

		return $result;
	}

	static function getAuthor( $category = '' )
	{
		if(empty($category))
			return '';

		if (@isset($category->metadata)) {
			$category->metadata = json_decode($category->metadata);

			if( $category->metadata->author != '')
				return $category->metadata->author;
		}

		$author = TemplateContentCategoryHelper::getAuthorByUserId( @$category->created_user_id );
		
		return $author;
	}

	static function getPaths( $path )
	{
		return explode('/', $path);
	}

	static function getParentCategoryPathsByPath( $path, $full = false )
	{
		$separated_paths = array();
		while(strpos($path, '/')!==false)
		{
			$separated_paths[] = $path;
			$path = substr($path, 0, strrpos($path, '/'));
		}
		$separated_paths[] = $path;
		return $separated_paths;	
	}

	static function getParentCategoriesByPaths( $paths, $order = 'ASC' )
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, title, alias, CONCAT(id, '.$db->Quote(':').',alias) AS catslug, path');
		$query->from('#__categories');

		foreach($paths as &$path)
			$path = 'path = '.$db->Quote($path);

		$query->where( '('.implode(' OR ', $paths).') AND alias <> '.$db->Quote('root') );
		$query->order('level '.$order );
		
		$db->setQuery( $query );
		$result = $db->loadObjectList();		
		
		if( @is_null($result) || @empty($result) )
			return array();

		return $result;
	}

	static function getParentCategories( $category, $includeCategory = false )
	{
		$paths = TemplateContentCategoryHelper::getParentCategoryPathsByPath( $category->get('path') );
		$categories = TemplateContentCategoryHelper::getParentCategoriesByPaths( $paths );
		if(! $includeCategory)
			array_pop($categories);
		return $categories;
	}

	//solucao local, mantida para futuras comparacoes de desempenho
	/*static function getChildCategories( $categories, $maxlevel = 10 )
	{
		if(!is_array($categories))
			$categories = array($categories);

		$db = JFactory::getDBO();
		$results = array();

		foreach ($categories as $key => $category) {
			
			if($category->level == $maxlevel)
			{
				return NULL;
			}
			$query = $db->getQuery(true);
			$query->select('id, title, alias, CONCAT(id, '.$db->Quote(':').',alias) AS catslug, path, parent_id, level');
			$query->from('#__categories');
			$query->where('parent_id = '.intval($category->id).' AND published = 1 AND level = '.($category->level+1).' AND level < '.$maxlevel);
			$db->setQuery($query);
			$results = array_merge($results, $db->loadObjectList());
			$children = TemplateContentCategoryHelper::getChildCategories($results, $maxlevel);
			if(!is_null($children))
				$results = array_merge($results, $children);
		}

		return $results;
		
	}

	static function getChildCategories( $catid = 0 )
	{
		if(empty($catid))
			return array();

		JLoader::import( 'items', JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'models' );
		$category_model = JModel::getInstance( 'category', 'ContentModel' );
		$items = $category_model->getChildren($catid);
		return $items;
	}*/

	static function getChildIds( $children, $category )
	{
		$ids = array();
		if (count($children[$category->id]) > 0) :
			foreach($children[$category->id] as $id => $child) :
				$ids[] = $child->id;
			endforeach;
		endif;
		return $ids;
	}

	static function getLastArticleModifiedDate( $category, $children = false )
	{
		$category_ids = array($category->id);
		if($children !== false)
		{
			$category_ids = array_merge($category_ids, TemplateContentCategoryHelper::getChildIds($children, $category) );
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('MAX(modified) AS max_modified, MAX(created) AS max_created');
		$query->from('#__content');
		$query->where( 'catid IN ('.implode(',', $category_ids).') AND state = 1' );
		// echo $query->dump();
		$db->setQuery($query);
		$options = $db->loadObject();
		if( strtotime($options->max_modified) > strtotime($options->max_created))
			return  JHtml::_('date', $options->max_modified, JText::_('DATE_FORMAT_LC2'));
		else
			return  JHtml::_('date', $options->max_created, JText::_('DATE_FORMAT_LC2'));
	}

	static function getArticleIntro( $article, $limit = 190, $force_intro = true )
	{
		if(@ !empty($article->metadesc))
			return '<p>'.$article->metadesc.'</p>';

		if(@ !empty($article->introtext) && $force_intro)
		{
			$intro = strip_tags($article->introtext);
			if (strlen($intro) > $limit) {
				//Busca o total de caractere até a última palavra antes do limite.
				$limite_palavra = strrpos(substr($intro, 0, $limit), " ");
				$intro = trim(substr($intro, 0, $limite_palavra)).'...';
			}
			return '<p>'.$intro.'</p>';
		}
		elseif(@ !empty($article->introtext) &&  @ !empty($article->fulltext))
			return $article->introtext;
		else
		{
			$text = strip_tags($article->text);
			if (strlen($text) > $limit) {
				//Busca o total de caractere até a última palavra antes do limite.
				$limite_palavra = strrpos(substr($text, 0, $limit), " ");
				$text = trim(substr($text, 0, $limite_palavra)).'...';
			}
			return '<p>'.$text.'</p>';
		}
	}

	static function displayMetakeyLinks( $metakey, $link = '' )
	{
		if(empty($link))
			$link = 'index.php?ordering=newest&searchphrase=all&limit=20&areas[0]=contenttags&Itemid=181&option=com_search&searchword=';

		$keys = explode(',', $metakey);
		$count_keys = count($keys);
		$lang = JFactory::getLanguage();

		if(count($keys)==1)
		{				
			$keys =  explode(';', $metakey);
			$count_keys = count($keys);
		}
		for ($i=1; $i <= $count_keys; $i++) { 
			if($i!=$count_keys)
				$separator = '<span class="separator">,</span>';
			else
				$separator = '';

			if(trim($keys[$i-1]) != ''):
				$search_formated = urlencode(substr(trim($keys[$i-1]),0, $lang->getUpperLimitSearchWord()));
			?>
			<span>
				<a href="<?php echo JRoute::_($link . $search_formated); ?>" class="link-categoria"><?php echo trim($keys[$i-1]); ?></a>
				<?php echo $separator; ?>
			</span>
			<?php
			endif;
		}
	}

	static function displayCategoryImage( $image_src )
	{
		if( !is_string($image_src) )
			echo '';

		$imgfloat  = 'left';
		$title     = '';
		$class     = '';
		$class_box = '';

		$alt = 'alt="imagem sem descrição."';

		if(is_file(JPATH_SITE.DS.$image_src)):
			list($width, $height, $type, $attr) = getimagesize( JPATH_SITE.DS.$image_src );
		else:
			list($width, $height, $type, $attr) = @getimagesize( $image_src );	
		endif;

		if(@isset($width) && @isset($height)):
			if($width < 500)
				$type = 'lightbox';
			else
				$type = 'direct';				
		endif;

		if(($imgfloat=='left' || $imgfloat == 'right') && $type != 'direct'):
			$class_box = 'pull-'.$imgfloat.' light-image-'.$imgfloat;
			if($width >= $height)
				$class_box .= ' light-image-horz';
			else
				$class_box .= ' light-image-vert';
		elseif($type=='direct'):
			if($width >= $height)
				$class_box = 'direct-image-horz';
			else
				$class_box = 'direct-image-vert';					
		endif;
		
		$src   = 'src="'.htmlspecialchars( $image_src ).'"';


		if($type=='direct'):
			$class = 'class="img-polaroid img-fulltext-'.$imgfloat.' '.$class.'"';
			?>
			<div class="direct-image <?php echo $class_box ?>">
				<div class="image-box">
				<img <?php echo $class; ?> <?php echo $title ?> <?php echo $alt; ?> <?php echo $src; ?> />						
				</div>
			</div>
			<?php
		elseif($type=='lightbox'):
			$class = 'class="img-rounded img-fulltext-'.$imgfloat.' '.$class.'"';
			?>
			<div class="lightbox-image <?php echo $class_box ?>">
				<div class="image-box">
					<img <?php echo $class; ?> <?php echo $title ?> <?php echo $alt; ?> <?php echo $src; ?> />
				</div>
			</div>
			<?php			
		endif;
	}
}