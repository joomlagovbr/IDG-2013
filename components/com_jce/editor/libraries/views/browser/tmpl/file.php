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

defined( '_JEXEC' ) or die('RESTRICTED');
?>
<form onsubmit="return false;" action="<?php echo $this->action;?>" target="_self" method="post" enctype="multipart/form-data">
	<div id="browser">
		<fieldset>
			<legend><span id="layout-full-toggle" role="button"></span><?php echo WFText::_('WF_LABEL_BROWSER');?></legend>
			
			<div class="layout-top">
				<div class="layout-header">
					<div id="browser-message"></div>
					<div id="browser-actions"></div>
				</div>
			</div>
			<div class="layout-bottom">
				<div class="layout-left">
					<div class="header"><?php echo WFText::_('WF_LABEL_FOLDERS');?></div>
					<div id="browser-tree">
						<div id="tree-body" class="tree"></div>
					</div>
				</div>
				<div class="layout-center">
					<div id="browser-list-actions">
						<!-- Check-All -->
						<div id="check-all"><span class="layout-icon checkbox" role="checkbox" aria-checked="false"></span></div>

						<!-- Sort Extension -->
						<div id="sort-ext" role="button" data-sort-type="extension" aria-labelledby="sort-ext-label">
							<div class="spacer"></div>
							<span class="layout-icon sort"><span id="sort-ext-label"><?php echo WFText::_('WF_LABEL_EXTENSION');?></span></span>
						</div>

						<!-- Sort Name -->
						<div id="sort-name" role="button" data-sort-type="string" aria-labelledby="sort-name-label">
							<div class="spacer"></div>
							<span class="layout-icon sort">&nbsp;<span id="sort-name-label"><?php echo WFText::_('WF_LABEL_NAME');?></span></span>
						</div>
						
						<!-- Sort Date -->
						<div id="sort-date" role="button" data-sort-type="date" aria-labelledby="sort-date-label" aria-hidden="true">
							<div class="spacer"></div>
							<span class="layout-icon sort">&nbsp;<span id="sort-data-label"><?php echo WFText::_('WF_LABEL_DATE');?></span></span>
						</div>

						<!-- Sort Size -->
						<div id="sort-size" role="button" data-sort-type="number" aria-labelledby="sort-size-label" aria-hidden="true">
							<div class="spacer"></div>
							<span class="layout-icon sort">&nbsp;<span id="sort-size-label"><?php echo WFText::_('WF_LABEL_SIZE');?></span></span>
						</div>

						<!-- Search -->
						<div id="show-search" role="button">
							<div class="spacer"></div>
							<span class="layout-icon search"></span>
						</div>
						<div id="searchbox" class="hide" role="popup"><input id="search" /><span class="search-icon"></span></div>
						
						<!-- Toggle Details -->
						<div id="show-details" role="button">
							<div class="spacer"></div>
							<span class="layout-icon details"></span>
						</div>
					</div>
					<div id="browser-list"></div>
					<div id="browser-list-limit">
						<ul class="limit-left">
							<li class="limit-left" role="button"></li>
							<li class="limit-left-end" role="button"></li>
						</ul>
		                <div class="limit-text"> 
							<label for="browser-list-limit-select"><?php echo WFText::_('WF_LABEL_SHOW');?></label>
							<select id="browser-list-limit-select">
		                    	<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="all"><?php echo WFText::_('WF_OPTION_ALL');?></option>
		                    </select>
		                </div>
						<ul class="limit-right">
							<li class="limit-right" role="button"></li>
							<li class="limit-right-end" role="button"></li>
						</ul>
					</div>
				</div>
			
				<div class="layout-right">
					<div class="header"><?php echo WFText::_('WF_LABEL_DETAILS');?></div>
					<div id="browser-details">
						<div id="browser-details-text"></div>
						<div id="browser-details-comment"></div>
					</div>
					<div class="spacer"></div>
					<div id="browser-buttons"></div>
					<div id="browser-details-nav">
						<span class="details-nav-left" role="button"></span>
						<span class="details-nav-text"></span>
						<span class="details-nav-right" role="button"></span>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	<!--input type="hidden" name="<?php echo $this->session->getName();?>" value="<?php echo $this->session->getId();?>" /--> 
	<input type="hidden" name="<?php echo WFToken::getToken();?>" value="1" />
</form>
