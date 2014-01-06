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

defined('_JEXEC') or die('RESTRICTED');

?>
<table class="table table-striped">
	<thead>
		<tr>
			<th width="20" align="center">&nbsp;</th>
			<th><?php echo WFText::_( 'WF_LABEL_LANGUAGE' ); ?></th>
			<th width="10%" align="center"><?php echo WFText::_( 'WF_LABEL_VERSION' ); ?></th>
			<th width="15%" align="center"><?php echo WFText::_( 'WF_LABEL_DATE' ); ?></th>
			<th width="25%" align="center"><?php echo WFText::_( 'WF_LABEL_AUTHOR' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php 
	foreach ($this->languages as $language) : ?>
		<tr<?php echo $language->style;?>>
			<td width="20" align="center"><input type="checkbox" name="lid[]" value="<?php echo $language->language;?>" <?php echo $language->cbd;?>/></td>
			<td><?php echo $language->name; ?></td>
			<td align="center"><?php echo @$language->version != '' ? $language->version : '&nbsp;'; ?></td>
			<td align="center"><?php echo @$language->creationdate != '' ? $language->creationdate : '&nbsp;'; ?></td>
			<td>
				<span class="editlinktip tooltip" title="<?php echo WFText::_( 'WF_LABEL_AUTHOR_INFO' );?>::<?php echo $language->authorUrl; ?>">
					<?php echo @$language->author != '' ? $language->author : '&nbsp;'; ?>
				</span>
			</td>
		</tr>
	<?php
	endforeach; ?>
	</tbody>
</table>