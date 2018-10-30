<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
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

<h4>Custom Layout (Available in PRO version only)</h4>
<div style="display: none;">
<?php echo $this->form->getInput('customlayout'); ?>
</div>

<?php
require_once('customlayoutdoc.php');
?>
