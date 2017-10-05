<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
?>
<ul id="language" class="pull-right">
	<?php foreach ($list as $language) : ?>
		<?php if (!$language->active || $params->get('show_active', 0)) : ?>
			<li dir="<?php echo $language->rtl ? 'rtl' : 'ltr'; ?>">
				<a href="<?php echo $language->link; ?>">
					<?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef); ?>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>