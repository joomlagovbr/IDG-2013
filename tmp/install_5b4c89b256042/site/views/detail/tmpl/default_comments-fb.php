<?php 
/*
 * @package Joomla
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access'); 

?><div id="phocagallery-comments"><?php
	//echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';//because of IE bug 
	
	$uri 		= JFactory::getURI();
	$getParamsArray = explode(',', 'start,limitstart,template,fb_comment_id');
	if (!empty($getParamsArray) ) {
		foreach($getParamsArray as $key => $value) {
			$uri->delVar($value);
		}
	}
	
	if ($this->tmpl['fb_comment_app_id'] == '') {
		echo JText::_('COM_PHOCAGALLERY_ERROR_FB_APP_ID_EMPTY');
	} else {
	
		$cCount = '';
		if ((int)$this->tmpl['fb_comment_count'] > 0) {
			$cCount = 'numposts="'.$this->tmpl['fb_comment_count'].'"';
		}

?><fb:comments href="<?php echo $uri->toString(); ?>" simple="1" <?php echo $cCount;?> width="<?php echo (int)$this->tmpl['fb_comment_width'] ?>"></fb:comments>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
   FB.init({
     appId: '<?php echo $this->tmpl['fb_comment_app_id'] ?>',
     status: true,
	 cookie: true,
     xfbml: true
   });
 }; 
  (function() {
    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.src = document.location.protocol + '//connect.facebook.net/<?php echo $this->tmpl['fb_comment_lang']; ?>/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
   }());
</script>
<?php } ?>
</div>
