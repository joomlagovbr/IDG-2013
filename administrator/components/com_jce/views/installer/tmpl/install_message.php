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

$result = $this->state->get('result') ? 'success' : 'error';
?>

<?php if ($this->state->get('message')) :?>
<div class="install-message">
    <h2 class="<?php echo $result; ?>"><?php echo $this->state->get('name'); ?></h2>
    <p><?php echo $this->state->get('message'); ?></p>  
    <?php echo $this->state->get('extension.message', ''); ?>
</div>
<?php endif;?>