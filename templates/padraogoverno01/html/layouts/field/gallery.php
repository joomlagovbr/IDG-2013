<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layouts.Field
 *
 * @author      JoomlaGovBR
 * @copyright   Copyright (C) 2013-2019 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 */

defined('_JEXEC') or die('Restricted access!');

$field = $displayData['field'];
$item  = $displayData['item'];

if ($field->rawvalue === '') {
	return;
}

$values        = json_decode($field->rawvalue, true);
$valuesCounter = 0;

if (empty($values)) {
	return;
}

?>

<div id="gallery-carousel-<?php echo $item->id; ?>" class="carousel gallery slide">
	<div class="carousel-inner">
		<?php foreach ($values as $value) : ?>
			<div class="item<?php echo $valuesCounter == 0 ? ' active' : ''; ?>">
				<?php $altText = $value['Texto Alternativo'] == '' ? 'Foto da galeria de imagens: sem descrição informada.' : $value['Texto Alternativo']; ?>
				<img src="<?php echo $value['Foto/Imagem']; ?>" alt="<?php echo $altText; ?>" />
				<div class="carousel-caption">
					<?php if ($value['Legenda']) : ?>
						<p><?php echo $value['Legenda']; ?></p>
					<?php endif; ?>

					<?php if ($value['Créditos/Autor']) : ?>
						<p class="rights">Foto: <?php echo $value['Créditos/Autor']; ?></p>
					<?php endif; ?>

					<span class="options">
						<a href="<?php echo JUri::root() . $value['Foto/Imagem']; ?>" download title="Salvar imagem">
							<i class="icon-long-arrow-down"></i>
						</a>
					</span>
				</div>
			</div>
			<?php $valuesCounter++; ?>
		<?php endforeach; ?>
	</div>
	<?php if ($valuesCounter > 1) : ?>
		<a class="carousel-control left" href="#gallery-carousel-<?php echo $item->id; ?>" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#gallery-carousel-<?php echo $item->id; ?>" data-slide="next">&rsaquo;</a>
	<?php endif; ?>
</div>
