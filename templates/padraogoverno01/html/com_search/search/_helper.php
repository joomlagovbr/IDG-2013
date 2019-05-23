<?php
/**
 * @package
 * @subpackage
 * @copyright
 * @license
 */

// no direct access
defined('_JEXEC') or die;

class TemplateSearchHelper {

	static function displaySearchPhrase() {  //TemplateSearchHelper::displaySearchPhrase()

		$searchphrases		= array();

		$searchphrases[]	= JHtml::_('select.option',  'all', JText::_('COM_SEARCH_ALL_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'any', JText::_('COM_SEARCH_ANY_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'exact', JText::_('COM_SEARCH_EXACT_PHRASE'));

		$input = JFactory::getApplication()->input;
		$match = $input->get('searchphrase', 'all', 'string');

		foreach($searchphrases as $k => $search)
		{
			?>
			<label class="radio">
				<input type="radio" name="searchphrase" id="searchphrase-id<?php echo $k ?>" value="<?php echo $search->value ?>" <?php if($match==$search->value): ?>checked="checked"<?php endif; ?>>
				<?php echo $search->text; ?>
			</label>
			<?php
		}
	}

	static function displaySearchOnly( $searchareas = array() ) {
		foreach ($searchareas['search'] as $val => $txt):
			$checked = is_array($searchareas['active']) && in_array($val, $searchareas['active']) ? 'checked="checked"' : '';
		?>
		<label for="area-<?php echo $val;?>" class="checkbox">
			<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
			<?php echo JText::_($txt); ?>
		</label>
		<?php endforeach;
	}

	static function displayMetakeyLinks( $metakey, $link = '', $searchword = '' )
	{
		if(empty($link))
			$link = 'index.php?ordering=newest&limit=20&areas[0]=contenttags&option=com_search&searchword=';

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
				<a href="<?php echo JRoute::_($link . $search_formated); ?>" class="link-categoria">
					<?php
					$keys[$i-1] = str_ireplace($searchword, '<span class="highlight">'.$searchword.'</span>', $keys[$i-1]);
					if(strpos($keys[$i-1], '<span class="highlight">')!==false)
						$replace = true;
					else
						$replace = false;
					?>
					<?php if(strtolower($searchword) == strtolower(trim($keys[$i-1])) && $replace == false): ?><span class="highlight"><?php endif; ?>
					<?php echo trim($keys[$i-1]); ?>
					<?php if(strtolower($searchword) == strtolower(trim($keys[$i-1])) && $replace == false): ?></span><?php endif; ?>
				</a>
				<?php echo $separator; ?>
			</span>
			<?php
			endif;
		}
	}
}
