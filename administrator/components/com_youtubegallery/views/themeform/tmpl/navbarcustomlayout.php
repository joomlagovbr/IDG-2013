<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.0
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


?>
<style>
#jform_customnavlayout
{
		width:100%;
}
</style>

<h4>Navigation Bar - Custom Layout</h4>



<?php echo $this->form->getInput('customnavlayout'); ?>

<?php
require_once('navbarcustomlayoutdoc.php');
?>