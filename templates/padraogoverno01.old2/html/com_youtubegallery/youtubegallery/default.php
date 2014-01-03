<?php
/**
 * @version 
 * @author 
 * @link 
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$jinput = $app->input;
$itemid = $jinput->get('Itemid', 0, 'integer');
$menu = $app->getMenu();
$itemmenu = $menu->getItem($itemid);
// echo "<pre>";
// var_dump($teste);
?>
<div class="youtubegallery-list<?php echo $itemmenu->pageclass_sfx;?>">
<?php if($jinput->get('videoid', '', 'string')== ''): ?>
<h1 class="borderHeading">
		<?php echo $itemmenu->title; ?>
</h1>
<?php else: ?>
<span class="documentCategory"><?php echo $itemmenu->title; ?></span>
<?php endif; ?>


<?php
echo $this->youtubegallerycode;
?>

</div>
