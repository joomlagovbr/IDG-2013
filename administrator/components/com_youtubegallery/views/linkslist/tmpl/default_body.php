<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$item->id;
        $link2videolist='index.php?option=com_youtubegallery&view=videolist&listid='.$item->id;
?>
        

        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->id; ?></a>
                </td>
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <a href="<?php echo $link2edit; ?>"><?php echo $item->listname; ?></a>
                </td>
                
                
                <td>
                        <?php echo $item->categoryname; ?>
                </td>
                
                <td>
                        
                        
                        <span style="">
                                
                                <?php
                                
                                if($item->updateperiod>=1)
                                        echo JText::sprintf(JText::_('COM_YOUTUBEGALLERY_LASTUPDATE'),$item->lastplaylistupdate,$item->updateperiod);
                                else
                                {
                                        $hours=round((24*$item->updateperiod),0);
                                        echo JText::sprintf(JText::_('COM_YOUTUBEGALLERY_LASTUPDATE_HOURS'),$item->lastplaylistupdate,$hours);
                                }
                                
                                ?>
                                
                        </span>
                </td>
                
                <td>
                        <a href="<?php echo $link2videolist; ?>"><?php echo $item->number_of_videos; ?></a>
                </td>

                
        </tr>
<?php endforeach; ?>
