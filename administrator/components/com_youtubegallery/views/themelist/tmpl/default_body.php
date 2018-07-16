<?php
/**
 * YoutubeGallery Joomla! 3.0 Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=themeform&layout=edit&id='.$item->id;
        $link2themelist='index.php?option=com_youtubegallery&view=themelist&themeid='.$item->id;
        $link2export='index.php?option=com_youtubegallery&view=themeexport&themeid='.$item->id;
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
                
                <td>
                        <?php echo ($item->mediafolder!='' ? 'images/'.$item->mediafolder : ''); ?>
                </td>
                
                <td>
                        <?php   /*
                                if($item->themefile!='')
                                {
                                        $p=explode('/',$item->themefile);
                                        $filename_only=
                                        echo '<a href="'.$item->themefile.'">'.$item->themefile.'</a>';
                                }
                                else
                                        echo "Export Theme<br/>(Joomla 3.0.1 has a bug in zip.php file)";
                                */
                                
                                echo '<a href="'.$link2export.'">Export Theme</a>';
                        
                        ?>
                        
                </td>
                
                
                
        </tr>
<?php endforeach; ?>
