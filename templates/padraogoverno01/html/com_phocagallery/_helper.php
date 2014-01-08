<?php
/**
 * @package     Joomlagovbr
 * @subpackage  tmpl_padraogoverno01
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;

class TmplPhocagalleryHelper
{
	public static function removeCss($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_styleSheets as $k => $sheet)
		{
			foreach ($wanted as $word) {
				if(strpos($k, $word)!==false)
				{
					unset($doc->_styleSheets[$k]);					
				}
			}
		}
	}

	public static function addCss($path)
	{
		$doc = JFactory::getDocument();
		$doc->_styleSheets[$path] = array('mime'=>'text/css','media'=> NULL, 'attribs'=>array());		
	}

	public static function removeJs($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_scripts as $k => $script)
		{
			foreach ($wanted as $word) {
				if(strpos($k, $word)!==false)
				{
					unset($doc->_scripts[$k]);					
				}
			}
		}
		reset($wanted);
		foreach ($doc->_script as $k => $script) {
			foreach ($wanted as $word) {
				if(strpos($script, $word)!==false)
				{
					unset($doc->_script[$k]);
				}
			}
		}
	}
	public static function removeCustom($wanted = array())
	{
		if (count($wanted)==0) {
			return;
		}
		$doc = JFactory::getDocument();
		foreach($doc->_custom as $k => $v)
		{
			foreach ($wanted as $word) {
				if(strpos($v, $word)!==false)
				{
					unset($doc->_custom[$k]);					
				}
			}
		}
	}

	public static function getFormatedDescription( $intro, $limit = 190, $force_intro = true )
	{
		
		if(@ !empty($intro) && $force_intro)
		{
			$intro = strip_tags($intro);
			if (strlen($intro) > $limit) {
				//Busca o total de caractere até a última palavra antes do limite.
				$limite_palavra = strrpos(substr($intro, 0, $limit), " ");
				$intro = trim(substr($intro, 0, $limite_palavra)).'...';
				return '<p>'.$intro.'</p>';
			}
			else
				return $intro;
		}
		elseif(empty($intro))
			return 'Item sem descri&ccedil;&atilde;o. Clique para mais detalhes.';		
	}

	public static function displayMetakeyLinks( $metakey, $link = '' )
	{
		if(empty($link))
			$link = 'index.php?ordering=newest&searchphrase=all&limit=20&areas[0]=contenttags&areas[1]=phocagallery&Itemid=181&option=com_search&searchword=';

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

	public static function getExtrafields($categories, $fields = array(), $key = 'id')
	{
		if(count($fields)==0)
			return $categories;

		if(empty($categories))
			return $categories;

		if(!is_array($categories))
		{
			$array = false;
			$categories = array($categories);
		}
		else
			$array = true;

		$fields[] = 'id';

		$db = JFactory::getDBO();
		$ids = array();
		for ($i=0, $limit = count($categories); $i < $limit; $i++) { 
			$ids[] = $categories[$i]->{$key};
		}

		if (count($ids)>0) {
			$query = $db->getQuery(true);
			$query->select(implode(',', $fields));
			$query->from('#__phocagallery_categories');
			$query->where('id IN ('.implode(',',$ids).')');			
			$db->setQuery($query);
			$result = $db->loadAssocList('id');

			for ($i=0, $limit = count($categories); $i < $limit; $i++) { 
				for ($k=0,$klimit=count($fields); $k < $klimit; $k++) { 
					if($fields[$k]=='id')
						continue;
					$categories[$i]->{$fields[$k]} = $result[$categories[$i]->id][$fields[$k]];
				}
			}
		}
		
		if(!$array)
			$categories = $categories[0];

		return $categories;		
	}

	public static function getPhotoExtraInfo($photo)
	{
		if(!is_object($photo))
			return $photo;

		$jinput = JFactory::getApplication()->input;
		$Itemid = $jinput->get('Itemid', 0, 'integer');

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('title AS cat_title');
		$query->select('alias AS cat_alias');
		$query->from('#__phocagallery_categories');
		$query->where('id = '.$photo->catid);			
		$db->setQuery($query);
		$result = $db->loadAssocList();
		for ($i=0, $limit = count($result); $i < $limit; $i++) { 
			$photo->cat_title = $result[$i]['cat_title'];
			$photo->cat_alias = $result[$i]['cat_alias'];
			$photo->cat_link = JRoute::_( 'index.php?option=com_phocagallery&view=category&id='.$photo->catid.':'.$result[$i]['cat_alias'].'&Itemid='.$Itemid );
		}
		return $photo;
	}

	public static function setPhotoBreadcrumb($photo)
	{
		if(@!isset($photo->cat_link))
			return false;

		if(@!isset($photo->cat_title))
			return false;
		
		if(@!isset($photo->title))
			return false;

		$version = TmplPhocagalleryHelper::getjVersion();

		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem($photo->cat_title, $photo->cat_link);	
		$pathway->addItem($photo->title);

		return true;
	}

	public static function getjVersion()
	{
		$versao = new JVersion;
		$versaoint = intval($versao->RELEASE);
		return $versaoint;
	}
}
