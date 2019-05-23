<?php
/**
 * YoutubeGallery Joomla! Native Component
 * @version 4.4.5
 * @author Ivan Komlev< <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

jimport('joomla.version');
$version = new JVersion();
$JoomlaVersionRelease=$version->RELEASE;

$document = JFactory::getDocument();
$document->addCustomTag('<link rel="stylesheet" href="components/com_youtubegallery/views/linksform/tmpl/wizard.css" type="text/css" />');
$document->addCustomTag('<script src="components/com_youtubegallery/views/linksform/tmpl/wizard.js"></script>');

?>

<?php if($JoomlaVersionRelease>=3.0): ?>
	<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">
<?php else: ?>
	<form action="<?php echo JRoute::_('index.php?option=com_youtubegallery&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="youtubegallery-form" class="form-validate">
<?php endif; ?>

        <fieldset class="adminform">
                <?php echo $this->form->getInput('id'); ?>
                
                
                <legend><?php echo JText::_( 'COM_YOUTUBEGALLERY_FORM_DETAILS' ); ?> (Pro Version)</legend>
                
		<table style="border:none;">
                        <tbody>
                                <tr><td style="width:200px;"><?php echo $this->form->getLabel('catid'); ?></td><td>:</td><td><?php echo $this->form->getInput('catid'); ?></td></tr>
								<tr><td style="width:200px;"><?php echo $this->form->getLabel('listname'); ?></td><td>:</td><td><?php echo $this->form->getInput('listname'); ?></td></tr>
                        </tbody>
                </table>
                
				
                <p><br/>
                </p>
		<div style="display: none;top:0;left:0;position:fixed;width:100%;height:100%;background-color: black;    filter: alpha(opacity=50);    -moz-opacity: 0.5;    -khtml-opacity: 0.5;    opacity: 0.5;position: fixed;" id="YGShade">
		</div>
		
		
		<div style="display: none;position:absolute;top:0px;left:0px;width:100%;height:100%;" id="YGDialog"></div>
		
                
                <div style="display: block;height:530px;border:none;" id="videolisttab_0" class="videolisttab_content">
			<?php //Layout Wizard ?> <h4>Video Links | <a href="javascript: SwithTabs('videolisttab_',2,1)">Source</a></h4>
			<div style="margin-top:0px;height:450px;border: 1px dotted #000000;padding:10px;margin:0px;overflow: scroll -moz-scrollbars-vertical;overflow-x: hidden;overflow-y: auto;">
                                 
				<table style="border:none;">
                                <tbody>
                                        <tr>
						<td style="vertical-align: middle;font-weight:bold;" valign="middle">Video Links</td>
						<td style="vertical-align: middle;padding-left:20px;" valign="middle">
							<div class="-wrapper" >
								<button onclick="YGAddLink()" class="btn btn-small btn-success" type="button">
								<span class="icon-new icon-white"></span><span style="margin-left:10px;">Add Link</span></button>
							</div>
							
						</td>
					</tr>
				</tbody>
				</table>
				
				
				<!-- video links - dynamic-->
				<br/>
				<div id="ygvideolinkstable"></div>
				
				
				


                        </div>
		</div>
		<div style="display: none;height:530px;border:none;" id="videolisttab_1" class="videolisttab_content">
			<?php //Layout Wizard ?> <h4><a href="javascript: SwithTabs('videolisttab_',2,0);YGUpdatelinksTable();">Video Links</a> | Source</h4>
                       <div style="margin-top:0px;height:450px;border: 1px dotted #000000;padding:10px;margin:0px;overflow: scroll -moz-scrollbars-vertical;overflow-x: hidden;overflow-y: auto;">

			<?php
			
				$textarea_box=$this->form->getInput('videolist');
				require_once('doc.php');
			
			?>
			</div>
			
		</div>
		<span style="color:#008800"><i>1. Use "Order By" option in Theme settings to set the order, custom order is also available.</i></span><br/>
		<span style="color:#008800"><i>2. If you have more than 25 videos in a single Play List (Channel etc.), please check this <a href="http://www.joomlaboat.com/youtube-gallery/f-a-q/is-there-a-limit-to-how-many-videos-can-be-shown?cbprofile=2" target="_blank">link</a>.</i></span>
		<p><br/></p>
                
                        <table style="border:none;">
                                <tbody>
					<?php if($JoomlaVersionRelease>=3.0): ?>
	                                        <tr><td style="width:200px;">
							<?php echo $this->form->getLabel('updateperiod'); ?></td><td>:</td><td><?php echo $this->form->getInput('updateperiod'); ?></td></tr>
					<?php else: ?>
						<tr><td>
							<?php echo $this->form->getLabel('updateperiod'); ?></td><td>:</td><td><?php echo $this->form->getInput('updateperiod'); ?></td></tr>
					<?php endif; ?>
					
	                                        <tr><td><?php echo $this->form->getLabel('description'); ?></td><td>:</td><td><?php echo $this->form->getInput('description'); ?></td></tr>
						<tr><td><?php echo $this->form->getLabel('author'); ?></td><td>:</td><td><?php echo $this->form->getInput('author'); ?></td></tr>
						<tr><td><?php echo $this->form->getLabel('authorurl'); ?></td><td>:</td><td><?php echo $this->form->getInput('authorurl'); ?></td></tr>
						<tr><td><?php echo $this->form->getLabel('watchusergroup'); ?></td><td>:</td><td><?php echo $this->form->getInput('watchusergroup'); ?></td></tr>
						<tr><td><?php echo $this->form->getLabel('image'); ?></td><td>:</td><td><?php echo $this->form->getInput('image'); ?></td></tr>
						<tr><td><?php echo $this->form->getLabel('note'); ?></td><td>:</td><td><?php echo $this->form->getInput('note'); ?></td></tr>
					
                                </tbody>
                        </table>
                
			<script>
				YGSetVLTA('jform_videolist');
				YGUpdatelinksTable();
			</script>

        </fieldset>
        <div>
		<?php if($JoomlaVersionRelease>=3.0): ?>
			<input type="hidden" name="jform[id]" value="<?php echo (int)$this->item->id; ?>" />				
                <?php endif; ?>
		<input type="hidden" name="task" value="linksform.edit" />
                <?php echo JHtml::_('form.token'); ?>
        </div>
</form>