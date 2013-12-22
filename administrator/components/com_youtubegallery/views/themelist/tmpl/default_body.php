<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 3.5.9
 * @author DesignCompass corp< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$item->id;
        $link2themelist='index.php?option=com_youtubegallery&view=themelist&themeid='.$item->id;
?>
        

        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->id; ?></a>
                </td>
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->themename; ?></a>
                </td>
                
                <td<?php echo ($item->readonly ? ' style="color:green;"' : ''); ?>>
                        <?php echo ($item->mediafolder!='' ? 'images/'.$item->mediafolder : ''); ?>
                </td>
                
                <td>
                        <?php   
                                if($item->readonly)
                                        echo '<span style="color:green;">Imported Theme</a>';
                        
                        ?>
                        
                </td>
                
                
                
                
        </tr>
<?php endforeach; ?>
