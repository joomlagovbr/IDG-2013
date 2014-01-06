<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined( '_WF_EXT' ) or die('RESTRICTED');
?>
<table border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td>
			<label for="vimeo_color" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_COLOR_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_COLOR')?></label>
			<input type="text" id="vimeo_color" class="color" />
		</td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" id="vimeo_autoplay" />
			<label for="vimeo_autoplay" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_AUTOPLAY_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_AUTOPLAY')?></label>
		</td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" id="vimeo_loop" />
			<label for="vimeo_loop" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_LOOP_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_LOOP')?></label>
		</td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" id="vimeo_fullscreen" checked="checked" />
			<label for="vimeo_fullscreen" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_FULLSCREEN_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_FULLSCREEN')?></label>
		</td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" id="vimeo_embed" />
			<label for="vimeo_embed" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_EMBED_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_EMBED')?></label>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo WFText::_('WF_AGGREGATOR_VIMEO_INTRO')?></strong></td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" id="vimeo_portrait" checked="checked" />
			<label for="vimeo_portrait" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_PORTRAIT_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_PORTRAIT')?></label>

			<input type="checkbox" id="vimeo_title" checked="checked" />
			<label for="vimeo_title" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_INTROTITLE_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_INTROTITLE')?></label>

			<input type="checkbox" id="vimeo_byline" checked="checked" />
			<label for="vimeo_byline" title="<?php echo WFText::_('WF_AGGREGATOR_VIMEO_BYLINE_DESC')?>" class="tooltip"><?php echo WFText::_('WF_AGGREGATOR_VIMEO_BYLINE')?></label>
		</td>
	</tr>
</table>