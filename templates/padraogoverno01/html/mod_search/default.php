<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_search
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" class="pull-right">
 	<fieldset>
        <legend class="hide">Busca</legend>
        <h2 class="hidden">Buscar no portal</h2>
        <div class="input-append">
        	<label for="portal-searchbox-field" class="hide">Busca: </label>
        	<input type="text" id="portal-searchbox-field" class="searchField" placeholder="Buscar no portal" title="Buscar no portal" name="searchword">       
            <button type="submit" class="btn searchButton"><span class="hide">Buscar</span><i class="icon-search"></i></button>
		</div>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
	</fieldset>
</form>
