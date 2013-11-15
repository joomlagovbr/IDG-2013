<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_chamadas
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//preencher link quando categoria for unica
if (empty($link_saiba_mais) && count($params->get('catid'))==1 && $params->get('buscar_cat_tag')==1) {
	$catid = $params->get('catid');
	$link_saiba_mais = JRoute::_('index.php?option=com_content&view=category&id='.$catid[0]);
}
?>

<ul class="row-fluid lista">
	<?php
		$header_tag2 = 'h'.(intval(str_replace('h', '', $params->get('header_tag')))+1);
		foreach ($lista_chamadas as $lista): ?>
		<li class="<?php echo $params->get('subitem_class', 'span4'); ?>">
		<?php if ($params->get('exibir_title') && !empty($lista->title)): ?>			
			<<?php echo $header_tag2; ?>>
				<a href="<?php echo $lista->link ?>">
					<?php echo ModChamadasHelper::getIntroLimiteCaracteres($lista->title, $params); ?>
				</a>
			</<?php echo $header_tag2; ?>>
		<?php endif; ?>
		</li>			
	<?php endforeach; ?>		
</ul>

<?php if (! empty($link_saiba_mais) ): ?>
	<div class="footer">
		<a href="<?php echo $link_saiba_mais; ?>" class="link">
			<?php echo $params->get('texto_saiba_mais', 'saiba mais')?>			
		</a>	
	</div>
<?php endif; ?>