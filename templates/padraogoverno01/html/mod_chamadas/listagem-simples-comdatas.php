<?php
/**
 * @package     
 * @subpackage  
 * @copyright   
 * @license     
 */

defined('_JEXEC') or die;
?>
<div class="tile-collection">
	<?php foreach ($lista_chamadas as $lista): ?>
		<?php 
			if(@!isset($lista->link))
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($lista->id, $lista->catid));
			else
				$link = $lista->link;
		?>
		<div class="tileItem">
			<<?php echo $params->get('header_tag')?>>
				<a href="<?php echo $link ?>">
					<?php echo $lista->title ?>
				</a>
			</<?php echo $params->get('header_tag')?>>			
			<?php if(@isset($lista->publish_date)): ?>			
				<p><?php echo JHtml::_('date', $lista->publish_date, 'd/m/Y H\hi'); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<?php if (! empty($link_saiba_mais) ): ?>
	<div class="outstanding-footer">
		<a href="<?php echo $link_saiba_mais; ?>" class="outstanding-link">
			<?php if ($params->get('texto_saiba_mais')): ?>
				<span class="text"><?php echo $params->get('texto_saiba_mais')?></span>
			<?php else: ?>
				<span class="text">saiba mais</span>
			<?php endif;?>
			<span class="icon-box">                                          
		      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
		    </span>
		</a>	
	</div>
<?php endif; ?>