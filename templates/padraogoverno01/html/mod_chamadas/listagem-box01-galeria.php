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
<div class="gallery-pane">
	<div id="gallery-carousel-<?php echo $module->id ?>" class="carousel slide span11">
		<div class="carousel-inner">
			<?php foreach ($lista_chamadas as $k => $lista):
				if($lista->image_alt==''){
					$lista->image_alt = 'Foto da galeria de imagens: sem descrição informada.';
				}?>
			<div class="item<?php if($k==0): ?> active<?php endif; ?>">
				<a href="<?php echo $lista->link;?>">
					<img alt="<?php echo $lista->image_alt ?>" src="<?php echo $lista->image_url ?>" style="width:<?php echo ($params->get('largura_px')) ? $params->get('largura_px').'px;' : '705px;'; ?> height:<?php echo ($params->get('largura_px')) ? $params->get('altura_px').'px;' : '150px;'; ?>">
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="galeria-thumbs span1 no-margin">
		<ul>
			<?php reset($lista_chamadas); ?>
			<?php foreach ($lista_chamadas as $k => $lista): ?>
			<li class="galeria-image">
				<a href="#0<?php echo $k ?>"><?php echo ++$k ?>
					<!--<img src="<?php echo $lista->image_url; ?>" alt="Miniatura para navegação, foto <?php echo $k+1 ?>"> -->
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>