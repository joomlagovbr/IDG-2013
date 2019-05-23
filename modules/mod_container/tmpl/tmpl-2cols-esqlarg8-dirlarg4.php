<?php
defined('_JEXEC') or die;

//CARREGAMENTO DOS MÓDULOS
$modules_col_esquerda = array_merge( JModuleHelper::getModules( $position ), JModuleHelper::getModules( $position . '-esq' ) );
$modules_col_direita = JModuleHelper::getModules( $position . '-dir' );
$moduleclass_sfx_level2 = trim($moduleclass_sfx_level2);		

$cols = array($modules_col_esquerda, $modules_col_direita);
$span_cols = array('span8', 'span4');

if($tag1 != 'none')
	echo '<'.$tag1.' '.$class.'>'."\n";
?>
	<?php //titulo do modulo ?>
	<?php if ($module->showtitle): ?> 
		<?php if ($title_outstanding): ?><div class="outstanding-header"><?php endif; ?>
        	<h<?php echo $headerLevel; ?> <?php if ($title_outstanding): ?>class="outstanding-title"<?php endif; ?>><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
        	<?php if( !empty($text_link_title) && !empty($url_link_title) ): ?>
        		<a href="<?php echo $url_link_title ?>" class="outstanding-link"><span class="text"><?php echo $text_link_title; ?></span>
                    <span class="icon-box">                                          
                      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
                    </span>
                </a>
        	<?php endif; ?>
        <?php if ($title_outstanding): ?></div><?php endif; ?>
        <?php $headerLevel = intval($headerLevel)+1; ?>
	<?php endif; ?>

	<?php
	foreach ($cols as $col => $modules):

		if(count($modules)==0)
			continue;

		echo '<div class="'.$span_cols[ $col ].'">';
		$counter = 1; 		

		foreach ($modules as $k => $mod):
			$mod_params = json_decode($mod->params);

			$class = @$mod_params->moduleclass_sfx;
			$class .= ' module '.$moduleclass_sfx_level2;
			if(@$mod_params->variacao != '')
				$class .= ' variacao-module-'.((intval($mod_params->variacao)<10)? '0' : '').intval($mod_params->variacao);

			$mod->title = (@$mod_params->titulo_alternativo!='')? $mod_params->titulo_alternativo : $mod->title;
			$mod->title = (@$mod_params->alternative_title!='')? $mod_params->alternative_title : $mod->title;
			$class = 'class="'.trim($class).'"';
			
			if($tag2 != 'none')
				echo '<'.$tag2.' '.$class.'>'."\n";
			?>
			<?php //titulo do modulo 2 ?>
			<?php if ($mod->showtitle && $counter <= $numero_colunas): ?>
				<?php if ($params->get('title_outstanding_column'.$counter)): ?><div class="outstanding-header"><?php else: ?><div class="header"><?php endif; ?>
				<h<?php echo $headerLevel; ?> <?php if ($params->get('title_outstanding_column'.$counter)): ?>class="outstanding-title"<?php else: ?>class="title"<?php endif; ?>><?php echo $mod->title; ?></h<?php echo $headerLevel; ?>>
	        	<?php if( $params->get('text_link_title_column'.$counter) != '' && $params->get('url_link_title_column'.$counter) != '' ): ?>
	        		<a href="<?php echo $params->get('url_link_title_column'.$counter) ?>" class="outstanding-link"><span class="text"><?php echo $params->get('text_link_title_column'.$counter); ?></span>
	                    <span class="icon-box">                                          
	                      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
	                    </span>
	                </a>
	        	<?php endif; ?>				
				</div>
			<?php elseif($mod->showtitle): ?>
				<div class="header">
				<h<?php echo $headerLevel; ?> class="title"><?php echo $mod->title; ?></h<?php echo $headerLevel; ?>>
				</div>
			<?php endif; ?>

			<?php echo JModuleHelper::renderModule($mod); ?>			
	
			<?php if($counter<=$numero_colunas): ?>
				<?php if ( $params->get('footer_outstanding_column'.$counter) ): ?>
    			<div class="outstanding-footer">
		        	<?php if( $params->get('text_link_footer_column'.$counter) != '' && $params->get('url_link_footer_column'.$counter) != '' ): ?>
	        		<a href="<?php echo $params->get('url_link_footer_column'.$counter) ?>" class="outstanding-link"><span class="text"><?php echo $params->get('text_link_footer_column'.$counter); ?></span>
	                    <span class="icon-box">                                          
	                      <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
	                    </span>
	                </a>
		        	<?php endif; ?>			
	        	</div>
	        	<?php endif; ?>			
			<?php endif; ?>

			<?php
			if($tag2 != 'none')
				echo '</'.$tag2.'>'."\n";
			$counter++;	
		endforeach;

		echo '</div>';

	endforeach;
	?>

	<?php if ($show_footer): ?>
    <div class="outstanding-footer">
    	<?php if( !empty($text_link_footer) && !empty($url_link_footer) ): ?>
        <a href="<?php echo $url_link_footer ?>" class="outstanding-link">
            <span class="text"><?php echo $text_link_footer; ?></span>
            <span class="icon-box">                                          
              <i class="icon-angle-right icon-light"><span class="hide">&nbsp;</span></i>
            </span>
        </a>
    	<?php endif; ?>
    </div>  
	<?php endif; ?>
<?php
if($tag1 != 'none')
	echo '</'.$tag1.'>'."\n";
?>