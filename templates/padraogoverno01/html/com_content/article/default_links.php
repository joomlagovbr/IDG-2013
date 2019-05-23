<?php
/**
 * @version
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

// Create shortcut
$urls = json_decode($this->item->urls);

// Create shortcuts to some parameters.
$params = $this->item->params;
if ($urls && (!empty($urls->urla) || !empty($urls->urlb) || !empty($urls->urlc))) :
?>
<div class="content-links">
	<ul>
		<?php
			$urlarray = array(
			array($urls->urla, $urls->urlatext, $urls->targeta, 'a'),
			array($urls->urlb, $urls->urlbtext, $urls->targetb, 'b'),
			array($urls->urlc, $urls->urlctext, $urls->targetc, 'c')
			);
			foreach($urlarray as $url) :
				$link = $url[0];
				$label = $url[1];
				$target = $url[2];
				$id = $url[3];

				if( ! $link) :
					continue;
				endif;

				// If no label is present, take the link
				$label = ($label) ? $label : $link;

				// If no target is present, use the default
				$target = $target ? $target : $params->get('target'.$id);
				?>
			<li class="content-links-<?php echo $id; ?>">
				<?php
					// Compute the correct link

					switch ($target)
					{
						case 1:
							// open in a new window
							echo '<a href="'. htmlspecialchars($link) .'" target="_blank"  rel="nofollow">'.
								htmlspecialchars($label) .'</a>';
							break;

						case 2:
							// open in a popup window
							$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
							echo "<a href=\"" . htmlspecialchars($link) . "\" onclick=\"window.open(this.href, 'targetWindow', '".$attribs."'); return false;\">".
								htmlspecialchars($label).'</a>';
							break;
						case 3: ?>
							<a id="link-modal-<?php echo $id; ?>" href="<?php echo htmlspecialchars($link); ?>">
								<!-- data-keyboard="true" data-toggle="modal" data-remote="true" href="<?php echo htmlspecialchars($link); ?>#content-links-modal" -->
								<?php echo htmlspecialchars($label) . ' </a>';
								?>
								<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery("#link-modal-<?php echo $id; ?>").click(function(){
											jQuery('#content-links-modal').modal('show');
											jQuery('#content-links-modal .modal-header span').html( jQuery(this).html() );
											jQuery('#content-links-modal .modal-body iframe').attr( 'src', jQuery(this).attr('href') );
											return false;
										});
									});
								</script><noscript>&nbsp;<!-- item para fins de acessibilidade --></noscript>
								<?php
							break;

						default:
							// open in parent window
							echo '<a href="'.  htmlspecialchars($link) . '" rel="nofollow">'.
								htmlspecialchars($label) . ' </a>';
							break;
					}
				?>
				</li>
		<?php endforeach; ?>
	</ul>
	<!-- modal -->
	<div id="content-links-modal" class="modal fade hide" tabindex="-1" role="dialog" aria-labelledby="fulltext-modal" aria-hidden="true">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>	
		<span></span>	
		</div>
		<div class="modal-body">
			<iframe src="<?php echo JURI::root().'templates/padraogoverno01/html/index.html' ?>" height="500" width="100%" frameborder="0"></iframe>
		</div>
		<div class="modal-footer">			
			<button class="btn pull-right" data-dismiss="modal" aria-hidden="true">fechar</button>					
		</div>
	</div>
	<!-- end modal -->
</div>
<?php endif; ?>