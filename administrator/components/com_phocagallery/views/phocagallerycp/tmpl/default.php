<?php defined('_JEXEC') or die('Restricted access');?>

<form action="index.php" method="post" name="adminForm">
<div id="j-sidebar-container" class="span2">
<?php echo JHtmlSidebar::render(); ?>
</div>
<div id="j-main-container" class="span10">

<div class="adminform">
<div class="pga-cpanel-left">
	<div id="cpanel">
		<?php

		$class	= $this->t['n'] . 'RenderAdmin';
		$link	= 'index.php?option='.$this->t['o'].'&view=';
		foreach ($this->views as $k => $v) {
			$linkV	= $link . $this->t['c'] . $k;
			echo $class::quickIconButton( $linkV, 'icon-48-pg-'.$k.'.png', JText::_($v), $this->t['i']);
		}
				?><div style="clear:both">&nbsp;</div>
		<p>&nbsp;</p>
		<?php /*
		<div style="text-align:center;padding:0;margin:0;border:0">
			<iframe style="padding:0;margin:0;border:0" src="http://www.phoca.cz/adv/phocagallery" noresize="noresize" frameborder="0" border="0" cellspacing="0" scrolling="no" width="500" marginwidth="0" marginheight="0" height="125">
			<a href="http://www.phoca.cz/adv/phocagallery" target="_blank">Phoca Gallery</a>
			</iframe> 
		</div> */ ?>
		<div class="alert alert-block alert-info ph-w80">
		<button type="button" class="close" data-dismiss="alert">×</button>
			<?php echo PhocaGalleryRenderAdmin::getLinks(); ?>
		</div>
	</div>
</div>
		
<div class="pga-cpanel-right">
	<div class="well">
		<div style="float:right;margin:10px;">
			<?php echo JHTML::_('image', 'media/com_phocagallery/images/administrator/logo-phoca.png', 'Phoca.cz' );?>
		</div>
			
		<?php
		echo '<h3>'.  JText::_('COM_PHOCAGALLERY_VERSION').'</h3>'
		.'<p>'.  $this->t['version'] .'</p>';

		echo '<h3>'.  JText::_('COM_PHOCAGALLERY_COPYRIGHT').'</h3>'
		.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
		.'<p><a href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>';

		echo '<h3>'.  JText::_('COM_PHOCAGALLERY_LICENCE').'</h3>'
		.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';
		
		echo '<h3>'.  JText::_('COM_PHOCAGALLERY_TRANSLATION').': '. JText::_('COM_PHOCAGALLERY_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2007 - '.  date("Y"). ' '. JText::_('COM_PHOCAGALLERY_TRANSLATER'). '</p>'
        .'<p>'.JText::_('COM_PHOCAGALLERY_TRANSLATION_SUPPORT_URL').'</p>';
		
		?>
		<p>&nbsp;</p>
		<p><strong><?php echo JText::_('COM_PHOCAGALLERY_SHADOWBOX_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_PHOCAGALLERY_SHADOWBOX_LICENCE');?></p>
		<p><a href="http://www.shadowbox-js.com/" target="_blank">Shadowbox.js</a> by <a target="_blank" href="http://www.shadowbox-js.com/">Michael J. I. Jackson</a><br />
		<a target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-Noncommercial-Share Alike</a></p>
		
		<p><strong><?php echo JText::_('COM_PHOCAGALLERY_HIGHSLIDE_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_PHOCAGALLERY_HIGHSLIDE_LICENCE');?></p>
		<p><a href="http://highslide.com/" target="_blank">Highslide JS</a> by <a target="_blank" href="http://highslide.com/">Torstein Hønsi</a><br />
		<a target="_blank" href="http://creativecommons.org/licenses/by-nc/2.5/">Creative Commons Attribution-NonCommercial 2.5  License</a></p>
		
		<p><strong><?php echo JText::_('COM_PHOCAGALLERY_BOXPLUS_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_PHOCAGALLERY_BOXPLUS_LICENCE');?></p>
		<p><a href="http://hunyadi.info.hu/en/projects/boxplus" target="_blank">boxplus</a> by <a target="_blank" href="http://hunyadi.info.hu/">Levente Hunyadi</a><br />
		<a target="_blank" href="http://www.gnu.org/licenses/gpl.html">GPL</a></p>
		
		<p>Google™, Google Maps™, Google Picasa™ and YouTube Broadcast Yourself™ are registered trademarks of Google Inc.</p>
		
		<?php
		echo '<div style="border-top:1px solid #c2c2c2"></div><p>&nbsp;</p>'
.'<div class="btn-group"><a class="btn btn-large btn-primary" href="http://www.phoca.cz/version/index.php?phocagallery='.  $this->t['version'] .'" target="_blank"><i class="icon-loop icon-white"></i>&nbsp;&nbsp;'.  JText::_('COM_PHOCAGALLERY_CHECK_FOR_UPDATE') .'</a></div>';
		?>
		
	</div>
</div>

</div>

<input type="hidden" name="option" value="com_phocagallery" />
<input type="hidden" name="view" value="phocagallerycp" />
<?php echo JHtml::_('form.token'); ?>

</div>
</form>