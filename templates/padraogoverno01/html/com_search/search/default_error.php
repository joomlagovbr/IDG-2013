<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if($this->error): ?>
<div class="error">
	<span><?php echo $this->escape($this->error); ?></span>			
</div>
<?php endif; ?>