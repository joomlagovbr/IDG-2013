<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="below-content">
	<div class="line">
	Links:		
	<?php
		$links = array();
	    foreach(range('a', 'e') as $k => $char) :// letters 'a' to 'e'
		    $link = $this->contact->params->get('link'.$char);
		    $label = $this->contact->params->get('link'.$char.'_name');

		    if( ! $link) :
		        continue;
		    endif;

		    // Add 'http://' if not present
		    $link = (0 === strpos($link, 'http')) ? $link : 'http://'.$link;

		    // If no label is present, take the link
		    $label = ($label) ? $label : $link;

		    $links[] = '<span><a href="'.$link.'" class="link-categoria">'.$label.'</a></span>';
		    ?>
	<?php endforeach;

	$links = implode('<span class="separator">,</span>', $links);
	echo $links;
	?>
	</div>			
</div>

