<?php
/**
 * @package		Joomla.Site
 * @subpackage	Modules.Menu
 * @copyright	Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$tag = $params->get('tag_id') == null ? '' : ' id="' . $params->get('tag_id') . '"';
?>

<ul class="menu-boxed"<?php echo $tag?>>

<?php
foreach ($list as $i => &$item) :
	$class = 'span4 item-' . $item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}

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
		$class = ' class="' . trim($class) .'"';
	}

	echo '<li' . $class . '>';

	// Render the menu item.
	switch ($item->type) :		
		case 'separator':
		case 'url':
			require JModuleHelper::getLayoutPath('mod_menu', 'boxed_' . $item->type);
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'boxed_url');
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