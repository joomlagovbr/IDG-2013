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
#jform_customlayout
{
		width:100%;
}
</style>

<h4>Custom Layout</h4>

<?php echo $this->form->getInput('customlayout'); ?>


<?php
require_once('customlayoutdoc.php');
?>