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

        $link2edit='index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$item->id;
        
        ?>
        

        <tr class="row<?php echo $i % 2; ?>">
                <td>
			
		<?php
			
			$images=explode(',',$item->imageurl);
			if(count($images)>0 and $item->imageurl!='')
			{
				echo '<p style="text-align:center;">';
				$index=0;
				if($item->custom_imageurl!='')
				{
					if(!(strpos($item->custom_imageurl,'#')===false))
					{
						$index=(int)(str_replace('#','',$item->custom_imageurl));
						if($index<0)
							$index=0;
						if($index>=count($images))
							$index=count($images)-1;
							
						echo '<img src="'.$images[$index].'" style="width:100px;" />';
					}	
					else
						echo '<img src="'.$item->custom_imageurl.'" style="width:100px;" />';
				}
					else
						echo '<img src="'.$images[0].'" style="width:100px;" />';
					
				
				echo '</p><p style="text-align:center;">';
				$i=0;
				foreach($images as $img)
				{
					if($i==$index)
						echo $i.'  ';
					else
						echo '<a href="'.$img.'" target="_blank" />'.$i.'  </a>';
					$i++;
				}
				echo '</p>';
			}
		?>
		</td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videosource; ?></a></td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videoid; ?></a></td>
                <td><?php echo $item->title; ?></td>
                <td><?php echo $item->description; ?></td>
                <td><?php echo $item->lastupdate; ?></td>
                <td><?php
                
                if($item->status==200)
                        echo '<span style="color:green;">Ok</span>';
                elseif($item->status==0)
                        echo '<span style="color:black;">-</span>';
                else
                        echo '<span style="color:red;font-weight:bold;">Error: '.$item->status.'</span>';
                
                ?></td>
                <td><?php echo $item->ordering; ?></td>
        </tr>
				
	
<?php endforeach; ?>
