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
?>

<script type="text/javascript">
        function SwithTabs(nameprefix, count, activeindex)
        {
                for(i=0;i<count;i++)
                {
                        var obj=document.getElementById(nameprefix+i);
                        obj.style.display="none";
                }
                
                var obj=document.getElementById(nameprefix+activeindex);
                obj.style.display="block";
        }
</script>

<style>
#jform_headscript, #jform_themedescription
{
		width:420px;
}
</style>

<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_youtubegallery'); ?>" method="post" class="form-inline">
<?php echo $this->form->getInput('id'); ?>


<div class="row-fluid">
		<!-- Begin Content -->
		<div class="span10 form-horizontal">
				<ul class="nav nav-tabs">
						<li class="active"><a href="#general" data-toggle="tab">General</a></li>
						<li><a href="#playersettings" data-toggle="tab">Player Settings</a></li>
						<li><a href="#customlayout" data-toggle="tab">Custom Layout</a></li>
						<li><a href="#customnavbarlayout" data-toggle="tab">Custom Navigation Bar</a></li>
						<li><a href="#misc" data-toggle="tab">Misc</a></li>
						<li><a href="#advanced" data-toggle="tab">Advanced</a></li>
						<li><a href="http://www.joomlaboat.com/youtube-gallery/youtube-gallery-themes?view=catalog&layout=custom" target="_blank" style="color:#51A351;">Get more Themes</a></li>

				</ul>
			
				<div class="tab-content">
						
						<!-- Begin Tabs -->
						<div class="tab-pane active" id="general">
								
								<fieldset class="adminform">
								<?php include('layoutwizard.php'); ?>
								</fieldset>
								
						</div>
						
						
						
						<div class="tab-pane" id="customlayout">
								<fieldset class="adminform">
								<?php include('customlayout.php'); ?>
								</fieldset>
						</div>
						
						<div class="tab-pane" id="customnavbarlayout">
								<fieldset class="adminform">
								<?php include('navbarcustomlayout.php'); ?>
								</fieldset>
						</div>
						
						
						
						<div class="tab-pane" id="playersettings">
								<fieldset class="adminform">
								<?php include('playersettings.php'); ?>
								</fieldset>
						</div>
						
						<div class="tab-pane" id="advanced">
								<fieldset class="adminform">
								<?php include('advanced.php'); ?>
								</fieldset>
						</div>
						
						<div class="tab-pane" id="misc">
								<fieldset class="adminform">
								<?php include('misc.php'); ?>
								</fieldset>
						</div>
						
						
				</div>
		</div>
</div>

        <input type="hidden" name="task" value="themeform.edit" />
        <?php echo JHtml::_('form.token'); ?>
        
</form>