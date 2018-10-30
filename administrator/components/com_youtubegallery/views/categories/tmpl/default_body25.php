<?php
/**
 * YoutubeGallery
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=categoryform&layout=edit&id='.$item->id;
?>

        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->id; ?></a>
                </td>
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->treename; ?></a>
                </td>
                
        </tr>
<?php endforeach; ?>
