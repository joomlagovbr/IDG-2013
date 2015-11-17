<?php
/**
 * @package     Joomlagovbr
 * @subpackage  mod_agendadirigentes
 *
 * @copyright   Copyright (C) 2013 Comunidade Joomla Calango e Grupo de Trabalho de Ministérios
 * @license     GNU General Public License version 2
 */

defined('_JEXEC') or die;
?>
<div class="row-fluid chamadas-secundarias">
<?php
$span_unit = 12 / count($items->name);
for ($i=0, $limit = count($items->name); $i < $limit; $i++):
	$class = 'module span' . $span_unit;
?>
	<div class="<?php echo $class ?>">
		<div class="video">
			<?php ModVideosDestaqueHelper::showPlayer( $items->url[$i], count($items->url) ); ?>
		</div>
		<h2><strong><?php echo $items->name[$i]; ?></strong></h2>
		<p class="description"><?php echo $items->description[$i]; ?></p>
	</div>
<?php 
endfor;
?>
	
    <?php if( !empty($text_link_footer) && !empty($url_link_footer) ): ?>
    <div class="outstanding-footer">
        <a href="<?php echo $url_link_footer ?>" class="outstanding-link">
            <span class="text"><?php echo $text_link_footer; ?></span>
            <span class="icon-box">                                          
              <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
            </span>
        </a>
    </div>  
	<?php endif; ?>
</div>