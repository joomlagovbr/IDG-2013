<?php
/**
 * @package     
 * @subpackage  
 * @copyright   
 * @license     
 */

defined('_JEXEC') or die;
?>
<div class="listagem-chamadas-secundarias">
<?php foreach ($lista_chamadas as $k => $lista): ?>
	<div class="row-fluid">
		<?php if (@$lista->chapeu): ?>
		<p class="subtitle-container">
			<?php echo $lista->chapeu ?>
		</p>
		<?php endif; ?>
		<?php if ($params->get('exibir_imagem') && !empty($lista->image_url)): ?>
		<div class="image-container">
			<a href="<?php echo $lista->link ?>">
				<img src="<?php echo $lista->image_url ?>" width="200" height="130" class="img-rounded" alt="<?php echo $lista->image_alt ?>" />
			</a>
		</div>
		<?php endif; ?>		
		<div class="content-container">
			<h3><a href="<?php echo $lista->link ?>"><?php echo $lista->title ?></a></h3>
			<div class="description">
				<?php echo $lista->introtext; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<?php if (! empty($link_saiba_mais) ): ?>
	<div class="footer">
		<a href="<?php echo $link_saiba_mais; ?>" class="link">
			<?php echo $params->get('texto_saiba_mais', 'saiba mais')?>			
		</a>	
	</div>
<?php endif; ?>
</div>