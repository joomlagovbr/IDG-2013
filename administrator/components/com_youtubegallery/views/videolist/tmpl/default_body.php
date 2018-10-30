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
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_youtubegallery'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'misc.php');
$getinfomethod=YouTubeGalleryMisc::getSettingValue('getinfomethod');

$s=false;
if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on")
	$s=true;
	
?>
<?php foreach($this->items as $i => $item):

        $link2edit='index.php?option=com_youtubegallery&view=linksform&layout=edit&id='.$item->id;
        
        ?>
        

        <tr class="row<?php echo $i % 2; ?>">
                <td>
			
		<?php
			if($item->isvideo)
			{
				
			$images=explode(',',$item->imageurl);
			if(count($images)>0 and $item->imageurl!='')
			{

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
							
						$img=$images[$index];
					}	
					else
						$img=$item->custom_imageurl;
				}
				else
					$img=$images[0];
					
				if($s)
					$img=str_replace('http:','https:',$img);
					
				if(strpos($img,'://')===false and $img!='' and $img[0]!='/')
					$img='../'.$img;
					
				echo '<p style="text-align:center;"><img src="'.$img.'" style="width:100px;" /></p><p style="text-align:center;">';
				

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
			
			}else
				echo 'Playlist/Videolist';
		?>
		</td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videosource; ?></a></td>
                <td><a href="<?php echo $item->link; ?>" target="_blank"><?php echo $item->videoid; ?></a></td>
                <td><div id="video_<?php echo $item->id;?>_title"><?php echo $item->title; ?></div></td>
                <td><div id="video_<?php echo $item->id;?>_description"><?php echo $item->description; ?></div></td>
                <td><div id="video_<?php echo $item->id;?>_lastupdate"><?php echo $item->lastupdate; ?></div></td>
                <td style="text-align: center;">
		
		<?php
		
		if($getinfomethod=='js' or $getinfomethod=='jsmanual')
		{
			$pair=explode(',',$item->datalink);
			$link=$pair[0];
		
			echo '
		<script>
		function UpdateVideoData_'.$item->id.'()
		{
			UpdateVideoData("'.$link.'","'.$item->videoid.'",'.$item->id.');
		}
		</script>
		';
			echo '<div id="video_'.$item->id.'_status"><a href="javascript:UpdateVideoData_'.$item->id.'()">Update</a></div>';
		}
		else
		{
			if($item->status==200)
			        echo '<span style="color:green;">Ok</span>';
			elseif($item->status==0)
			        echo '<span style="color:black;">-</span>';
			else
			        echo '<span style="color:red;font-weight:bold;">Error: '.$item->status.'</span>';
		}
		
                ?>
		
		</td>
                <td><?php echo $item->ordering; ?></td>
        </tr>
				
	
<?php endforeach; ?>
