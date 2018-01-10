<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

class TemplateContentArticleHelper {

	static function getParentCategoriesByRoute( $parent_route, $order = 'ASC' )
	{
		if(empty($parent_route))
			return array();

		if(! is_array($parent_route) )
			$routes = TemplateContentArticleHelper::getParentRoutes( $parent_route );
		else
			$routes = $parent_route;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id, title, alias, CONCAT(id, '.$db->Quote(':').',alias) AS catslug');
		$query->from('#__categories');

		foreach($routes as &$route)
			$route = 'path = '.$db->Quote($route);

		$query->where( '('.implode(' OR ', $routes).') AND published = 1 AND alias <> '.$db->Quote('root') );
		$query->order('level '.$order );
		
		$db->setQuery( $query );
		$result = $db->loadObjectList();		
		
		if( @is_null($result) || @empty($result) )
			return array();

		return $result;
	}

	static function getParentRoutes( $parent_route )
	{
		$routes = array();
		// $parent_route = $parent_route . '/';
		while(strpos($parent_route, '/')!==false)
		{
			$routes[] = $parent_route;
			$parent_route = substr($parent_route, 0, strrpos($parent_route, '/'));
		}
		$routes[] = $parent_route;
		return $routes;	
	}

	static function displayCategoryLinks( $parent_categories, $item )
	{
		for ($i=0; $i < count($parent_categories); $i++) {
			if($parent_categories[$i]->catslug != ':') 
			{
				$url = '<span><a class="link-categoria" rel="tag" href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($parent_categories[$i]->catslug)).'">'.$parent_categories[$i]->title.'</a></span><span class="separator">,</span>';
				echo $url;
			}
			else
				echo '<span>'.$parent_categories[$i]->title.'</span><span class="separator">,</span>';
		}

		$url = '<span><a class="link-categoria" rel="tag" href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug)).'">'.$item->category_title.'</a></span>'; 
		 
		 if ($item->catslug):
			 echo $url;
		 else:
			 echo '<span>'.$item->category_title.'</span>';
		 endif;
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

	static function getAliases( $route )
	{
		return explode('/', $route);
	}

	static function getTemplateByCategoryAlias( $item )
	{
		if(is_file(__DIR__.'/'.$item->category_alias.'.php'))
			return $item->category_alias;

		$aliases = TemplateContentArticleHelper::getAliases( $item->parent_route );
		$aliases = array_reverse($aliases);
		foreach ($aliases as $alias) {
			if(is_file(__DIR__.'/'.$alias.'.php'))
				return $alias;			
		}

		return false;
	}

	static function showBelowContent($categories, $item)
	{
		$show = array();

		if(count($categories) > 0 )
			$show[] = 'categories';
		elseif($item->catid > 2)
			$show[] = 'categories';
		
		if(trim($item->metakey) != '')
			$show[] = 'metakeys';			

		return $show;		
	}

	static function displayFulltextImage( $images = NULL, $params = NULL )
	{
		
		if(!is_object($images) || !is_object($params))
			echo '';

		if (isset($images->image_fulltext) and !empty($images->image_fulltext)):
			
			$imgfloat  = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext;
			$title     = '';
			$class     = '';
			$class_box = '';

			if(strpos($images->image_fulltext, 'www.youtube') !== false):
				$type = 'youtube';
			else:

				if(@$images->image_fulltext_alt):
					$alt = 'alt="'.htmlspecialchars($images->image_fulltext_alt).'"';
				else:
					$alt = 'alt="imagem sem descrição."';
				endif;

				if(is_file(JPATH_SITE.DS.$images->image_fulltext)):
					list($width, $height, $type, $attr) = getimagesize( JPATH_SITE.DS.$images->image_fulltext );
				else:
					list($width, $height, $type, $attr) = @getimagesize( $images->image_fulltext );	
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

				if (@$images->image_fulltext_caption):
					$class = trim($class.' caption');
					$title = 'title="'.htmlspecialchars($images->image_fulltext_caption).'"';
				else:
					$title = 'title=""';
				endif;
				
				$src   = 'src="'.htmlspecialchars($images->image_fulltext).'"';

			endif;

			if($type=='direct'):
				$class = 'class="img-polaroid img-fulltext-'.$imgfloat.' '.$class.'"';
				?>
				<div class="direct-image <?php echo $class_box ?>">
					<?php if(@$images->image_fulltext_caption): ?>
					<div class="caption-top">
						<?php echo $images->image_fulltext_caption; ?>
					</div>
					<?php endif; ?>
					<div class="image-box">
					<img <?php echo $class; ?> <?php echo $title ?> <?php echo $alt; ?> <?php echo $src; ?> />
						<?php if(@$images->image_fulltext_alt): ?>
						<div class="image-alt">
							<?php echo htmlspecialchars($images->image_fulltext_alt); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			elseif($type=='lightbox'):
				$class = 'class="img-rounded img-fulltext-'.$imgfloat.' '.$class.'"';
				
				if(strpos($images->image_fulltext, '/thumb-')!==false)
					$img_modal = str_replace('thumb-', '', $images->image_fulltext);
				else if(strpos($images->image_fulltext, '-thumb.')!==false)
					$img_modal = str_replace('-thumb', '', $images->image_fulltext);

				if(@isset($img_modal))
				{
					if( !is_file(JPATH_SITE.DS.$img_modal) )
						$img_modal = false;
				}
				else
					$img_modal = false;

				?>
				<div class="lightbox-image <?php echo $class_box ?>">
					<?php if(@$images->image_fulltext_caption): ?>
					<div class="caption-top">
						<?php echo $images->image_fulltext_caption; ?>
					</div>
					<?php endif; ?>
					
					<div class="image-box">
					
						<?php if($img_modal): ?>
							<a href="#fulltext-modal" data-keyboard="true" data-toggle="modal">
						<?php endif; ?>
							<img <?php echo $class; ?> <?php echo $title ?> <?php echo $alt; ?> <?php echo $src; ?> />
						<?php if($img_modal): ?>						
							</a>
						<?php endif; ?>

						<?php if(@$images->image_fulltext_alt): ?>
						<div class="image-alt">
							<?php echo htmlspecialchars($images->image_fulltext_alt); ?>
						</div>
						<?php endif; ?>
					
					</div>

				</div>
				<?php				
				if($img_modal):					
					?>
					<!-- modal -->
					<div id="fulltext-modal" class="modal fade hide" tabindex="-1" role="dialog" aria-labelledby="fulltext-modal" aria-hidden="true">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<?php echo $images->image_fulltext_caption; ?>
						</div>
						<div class="modal-body">
						<img src="<?php echo htmlspecialchars($img_modal); ?>" <?php echo $alt ?> <?php echo $title ?> />
						</div>
						<div class="modal-footer">
							<div class="pull-left"><?php echo htmlspecialchars($images->image_fulltext_alt); ?></div>
							<button class="btn pull-right" data-dismiss="modal" aria-hidden="true">fechar</button>					
						</div>
					</div>
					<!-- end modal -->
					<?php
				endif;
			elseif($type=='youtube'):

				if(($imgfloat=='left' || $imgfloat == 'right' || $imgfloat == 'none'))
					$class_box = 'pull-'.$imgfloat.' light-image-'.$imgfloat;

				if( strpos($images->image_fulltext, 'http://')===false )
					$images->image_fulltext = 'http://'.$images->image_fulltext;

				?>
				<div class="video-image <?php echo $class_box ?>">
				<object width="490" height="368"><param value="<?php echo htmlspecialchars($images->image_fulltext); ?>" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><embed width="490" height="368" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" src="<?php echo htmlspecialchars($images->image_fulltext); ?>"></object>
				</div>
				<?php
			endif;
		endif;
	}
}