<?php
/**
 * @version		4.5.0
 * @package		AllVideos (plugin)
 * @author    JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div class="avPlayerWrapper<?php echo $output->mediaTypeClass; ?>">
	<div class="avPlayerContainer">
		<div id="<?php echo $output->playerID; ?>" class="avPlayerBlock">
			<?php echo $output->player; ?>
			<?php if($allowAudioDownloading && $output->mediaType=='audio'): ?>
			<div class="avDownloadLink">
				<a target="_blank" href="<?php echo $output->source; ?>">
					<span><?php echo JText::_('JW_PLG_AV_DOWNLOAD'); ?></span>
				</a>
				<span class="hint">(<?php echo JText::_('JW_PLG_AV_DOWNLOAD_HINT'); ?>)</span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
