<?php
/**
 * @package
 * @subpackage
 * @copyright
 * @license
 */

// No direct access.
defined('_JEXEC') or die;

/**
 *
 *
 * @since
 */
function modChrome_empty($module, &$params, &$attribs)
{
	echo $module->content;
}

function modChrome_rowfluid_section01($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;
	$content = $module->content;
	if(! empty($content)):
	?>
	<div class="row-fluid">
		<section<?php if ($params->get('tag_id', '')  != ''): ?> id="<?php echo $params->get('tag_id'); ?>"<?php endif; ?><?php if ($params->get('class_sfx', '')  != ''): ?> class="<?php echo trim($params->get('class_sfx', '')); ?>"<?php endif; ?>>
			<?php if ($module->showtitle): ?>
			 <h<?php echo $headerLevel; ?> class="span2"><span><?php echo $module->title; ?></span></h<?php echo $headerLevel; ?>>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</section>
	</div>
	<?php
	endif;
}

function modChrome_menu_sobre($module, &$params, &$attribs)
{
	$content = $module->content;
	if(! empty($content)):
	?>
	<nav<?php if ($params->get('class_sfx', '')  != ''): ?> class="<?php echo $params->get('class_sfx'); ?>"<?php endif; ?>>
        <h2 class="hide"><?php echo $module->title; ?></h2>
        <?php echo $module->content; ?>
    </nav>
	<?php
	endif;
}

function modChrome_row01($module, &$params, &$attribs)
{
	if(! empty($module->content) ):
	?>
	<div id="<?php echo $params->get('moduleclass_sfx'); ?>" class="row">
    	<h2 class="hidden"><?php echo $module->title; ?></h2>
    	<?php echo $module->content; ?>
    </div>
	<?php
	endif;
}

function modChrome_hidden_titles($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;
	if(! empty($module->content) ):
	?>
		<h<?php echo $headerLevel; ?> class="hide"><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
		<?php echo $module->content; ?>
	<?php
	endif;
}

function modChrome_nav_span($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;
	if(! empty($module->content) ):

		if(strpos($params->get('moduleclass_sfx').' '.$params->get('class_sfx'), 'show-icon')===false)
			$mobile_classes = 'visible-phone visible-tablet';
		else
			$mobile_classes = '';
	?>
	<nav class="<?php echo $params->get('moduleclass_sfx'); ?> <?php echo $params->get('class_sfx', ''); ?>">
		<h<?php echo $headerLevel; ?> <?php if($params->get('moduleclass_sfx')=='menu-de-apoio'): ?>class="hide"<?php endif; ?>><?php echo $module->title; ?> <?php if($params->get('moduleclass_sfx')!='menu-de-apoio'): ?><i class="icon-chevron-down visible-phone visible-tablet pull-right"></i><?php endif; ?></h<?php echo $headerLevel; ?>>
		<?php echo $module->content; ?>
	</nav>
	<?php
	endif;
}

function modChrome_div_nav_rodape($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;
	if(! empty($module->content) ):
	?>

		<div class="<?php echo $params->get('class_sfx', ''); ?>">
			<nav class="row <?php echo $params->get('moduleclass_sfx'); ?> nav">
				<?php if ($module->showtitle): ?>
				<h<?php echo $headerLevel; ?>><?php echo $module->title; ?></h<?php echo $headerLevel; ?>>
				<?php endif; ?>
				<?php echo $module->content; ?>
			</nav>
		</div>

	<?php
	endif;
}

function modChrome_container($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 2;

	if(! empty($module->content) ):
		if($module->module == 'mod_container') // || ($module->module == 'mod_chamadas' && $params->get('layout')=='padraogoverno01:listagem-box01'
		{
			echo $module->content;
		}
		else
		{
		$class = $params->get('moduleclass_sfx');

		$container_class = '';
		$container_class_pos = strpos($class, 'container-class-');
		if($container_class_pos !== false)
		{
			$container_class = substr($class, $container_class_pos);
			$container_class = str_replace(array('container-class-','--'), array('', ' '), $container_class);
			$class = str_replace( 'container-class-', '', $class);
		}

		$variacao = $params->get('variacao', 0);
		if( $variacao > 0 ){
			if ( $variacao < 10 ) {
				$variacao = '0'.$variacao;
			}
			$class = trim($class.' variacao-module-'.$variacao);
		}

		$title = ( $params->get('titulo_alternativo', '') != '' )? $params->get('titulo_alternativo') : $module->title;
		$layout = explode(':', $params->get('layout'));
		$module->showtitle = (@$layout[1]!='manchete-principal')? $module->showtitle : '';
		?>
		<div class="row-fluid module <?php echo $class; ?>">
			<?php if ($module->showtitle): ?>
				<?php if(strpos($params->get('moduleclass_sfx'), 'no-outstanding-title')===false): ?><div class="outstanding-header"><?php endif; ?>
			 	<h<?php echo $headerLevel; ?> <?php if(strpos($params->get('moduleclass_sfx'), 'no-outstanding-title')===false): ?>class="outstanding-title"<?php endif; ?>><span><?php echo $title; ?></span></h<?php echo $headerLevel; ?>>
			 	<?php if(strpos($params->get('moduleclass_sfx'), 'no-outstanding-title')===false): ?></div><?php endif; ?>
			<?php endif; ?>
			<?php if($container_class != ''): ?><div class="<?php echo $container_class; ?>"><?php endif; ?>
			<?php echo $module->content; ?>
			<?php if($container_class != ''): ?></div><?php endif; ?>
		</div>
		<?php
		}
	endif;
}
