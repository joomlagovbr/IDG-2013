<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// No direct access.
defined('_JEXEC') or die;

$counter = count($list);

if($counter > 0): ?>
<ul class="<?php echo $params->get('moduleclass_sfx'); ?> n<?php echo $counter; ?>">
<?php
foreach ($list as $i => &$item) :

	$length_title = strlen($item->title);
	$class = 'item-'.$item->id;
	
	if ($item->id == $active_id) {
		$class .= ' current';
	}

	if($class_sfx=='duas-linhas' && (($length_title <= 24 && $counter == 5) || ($length_title <= 31 && $counter == 4) || ($length_title <= 41 && $counter == 3) || ($length_title <= 61 && $counter == 2)) )
		$class = ' ajuste-duas-linhas';

	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	echo '<li'.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul>';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>
<?php endif; ?>