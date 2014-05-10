<?php
/**
 * @package   AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 *
 * @since     1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

$dateFormat = AEUtilComconfig::getValue('dateformat', '');
$dateFormat = trim($dateFormat);
$dateFormat = !empty($dateFormat) ? $dateFormat : JText::_('DATE_FORMAT_LC4');

// Filesize formatting function by eregon at msn dot com
// Published at: http://www.php.net/manual/en/function.number-format.php
function format_filesize($number, $decimals = 2, $force_unit = false, $dec_char = '.', $thousands_char = '')
{
	if ($number <= 0)
	{
		return '-';
	}

	$units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
	if ($force_unit === false)
	{
		$unit = floor(log($number, 2) / 10);
	}
	else
	{
		$unit = $force_unit;
	}
	if ($unit == 0)
	{
		$decimals = 0;
	}

	return number_format($number / pow(1024, $unit), $decimals, $dec_char, $thousands_char) . ' ' . $units[$unit];
}

// Load a mapping of backup types to textual representation
$scripting = AEUtilScripting::loadScripting();
$backup_types = array();
foreach ($scripting['scripts'] as $key => $data)
{
	$backup_types[$key] = JText::_($data['text']);
}

?>

<?php if (version_compare(JVERSION, '3.0', 'ge')): ?>
	<script type="text/javascript">
		Joomla.orderTable = function () {
			table = document.getElementById("sortTable");
			direction = document.getElementById("directionTable");
			order = table.options[table.selectedIndex].value;
			if (order != '<?php echo $this->escape($this->lists->order); ?>') {
				dirn = 'asc';
			} else {
				dirn = direction.options[direction.selectedIndex].value;
			}
			Joomla.tableOrdering(order, dirn, '');
		}
	</script>
<?php endif ?>

<div class="alert alert-info">
	<button class="close" data-dismiss="alert">×</button>
	<h4 class="alert-heading"><?php echo JText::_('BUADMIN_LABEL_HOWDOIRESTORE_LEGEND') ?></h4>

	<p><?php echo JText::sprintf('BUADMIN_LABEL_HOWDOIRESTORE_TEXT', 'https://www.akeebabackup.com/documentation/quick-start-guide/restoring-backups.html', 'https://www.akeebabackup.com/documentation/video-tutorials/item/1024-ab04.html') ?></p>
</div>
<div id="j-main-container">
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" id="option" value="com_akeeba"/>
<input type="hidden" name="view" id="view" value="buadmins"/>
<input type="hidden" name="boxchecked" id="boxchecked" value="0"/>
<input type="hidden" name="task" id="task" value="default"/>
<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>"/>
<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>"/>
<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken() ?>" value="1"/>

<?php if (version_compare(JVERSION, '3.0', 'ge')):

// Construct the array of sorting fields
	$sortFields = array(
		'id'          => JText::_('STATS_LABEL_ID'),
		'description' => JText::_('STATS_LABEL_DESCRIPTION'),
		'backupstart' => JText::_('STATS_LABEL_START'),
		'origin'      => JText::_('STATS_LABEL_ORIGIN'),
		'type'        => JText::_('STATS_LABEL_TYPE'),
		'profile_id'  => JText::_('STATS_LABEL_PROFILEID'),
	);
	JHtml::_('formbehavior.chosen', 'select');

	?>
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<input type="text" name="description" placeholder="<?php echo JText::_('STATS_LABEL_DESCRIPTION'); ?>"
				   id="filter_description"
				   value="<?php echo $this->escape($this->getModel()->getState('description', '')); ?>"
				   title="<?php echo JText::_('STATS_LABEL_DESCRIPTION'); ?>"/>
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i
					class="icon-search"></i></button>
			<button class="btn tip hasTooltip" type="button"
					onclick="document.id('filter_description').value='';this.form.submit();"
					title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
		</div>

		<div class="filter-search btn-group pull-left hidden-phone">
			<?php echo JHTML::_('calendar', $this->lists->fltFrom, 'from', 'from', '%Y-%m-%d', array('class' => 'input-small')); ?>
		</div>
		<div class="filter-search btn-group pull-left hidden-phone">
			<?php echo JHTML::_('calendar', $this->lists->fltTo, 'to', 'to', '%Y-%m-%d', array('class' => 'input-small')); ?>
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button class="btn tip hasTooltip" type="buttin" onclick="this.form.submit(); return false;"
					title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		</div>

		<div class="btn-group pull-right">
			<label for="limit"
				   class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="directionTable"
				   class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
			<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
				<option
					value="asc" <?php if ($this->lists->order_Dir == 'asc')
				{
					echo 'selected="selected"';
				} ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
				<option
					value="desc" <?php if ($this->lists->order_Dir == 'desc')
				{
					echo 'selected="selected"';
				} ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
			</select>
		</div>
		<div class="btn-group pull-right">
			<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
			<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
				<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
				<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $this->lists->order); ?>
			</select>
		</div>
	</div>
<?php endif; ?>

<table class="table table-striped" id="itemsList">
<thead>
<tr>
	<th width="20"><input type="checkbox" name="toggle" value=""
						  onclick="Joomla.checkAll(this);"/></th>
	<th width="20" class="hidden-phone">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_ID', 'id', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="240">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_DESCRIPTION', 'description', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="80">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_START', 'backupstart', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="80" class="hidden-phone">
		<?php echo JText::_('STATS_LABEL_DURATION'); ?>
	</th>
	<th width="80">
		<?php echo JText::_('STATS_LABEL_STATUS'); ?>
	</th>
	<th width="80" class="hidden-phone">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_ORIGIN', 'origin', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="80" class="hidden-phone">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_TYPE', 'type', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="20" class="hidden-phone">
		<?php echo JHTML::_('grid.sort', 'STATS_LABEL_PROFILEID', 'profile_id', $this->lists->order_Dir, $this->lists->order, 'default'); ?>
	</th>
	<th width="80" class="hidden-phone">
		<?php echo JText::_('STATS_LABEL_SIZE'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('STATS_LABEL_MANAGEANDDL'); ?>
	</th>
</tr>
<?php if (version_compare(JVERSION, '3.0', 'lt')): ?>
	<tr>
		<td></td>
		<td></td>
		<td class="form-inline">
			<input type="text" name="description" id="description"
				   value="<?php echo $this->escape($this->lists->fltDescription) ?>"
				   class="text_area input-medium" onchange="document.adminForm.submit();"/>
			<button class="btn btn-mini"
					onclick="this.form.submit(); return false;"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button class="btn btn-mini"
					onclick="document.adminForm.description.value='';this.form.submit(); return;"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</td>
		<td colspan="2">
			<?php echo JHTML::_('calendar', $this->lists->fltFrom, 'from', 'from', '%Y-%m-%d', array('class' => 'input-mini')); ?> &mdash;
			<?php echo JHTML::_('calendar', $this->lists->fltTo, 'to', 'to', '%Y-%m-%d', array('class' => 'input-mini')); ?>
			<button class="btn btn-mini "
					onclick="this.form.submit(); return false;"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
		</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="2"></td>
	</tr>
<?php endif; ?>
</thead>
<tfoot>
<tr>
	<td colspan="11" class="center"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>
</tfoot>
<tbody>
<?php if (!empty($this->list)): ?>
	<?php $id = 1;
	$i = 0; ?>
	<?php foreach ($this->list as $record): ?>
		<?php
		$id = 1 - $id;
		$check = JHTML::_('grid.id', ++$i, $record['id']);

		$origin_lbl = 'STATS_LABEL_ORIGIN_' . strtoupper($record['origin']);
		$origin = JText::_($origin_lbl);
		/*
		if($origin == $origin_lbl)
		{
			$origin = '&ndash;';
		}
		/**/

		if (array_key_exists($record['type'], $backup_types))
		{
			$type = $backup_types[$record['type']];
		}
		else
		{
			$type = '&ndash;';
		}

		JLoader::import('joomla.utilities.date');
		$startTime = new JDate($record['backupstart']);
		$endTime = new JDate($record['backupend']);

		$duration = $endTime->toUnix() - $startTime->toUnix();
		if ($duration > 0)
		{
			$seconds = $duration % 60;
			$duration = $duration - $seconds;

			$minutes = ($duration % 3600) / 60;
			$duration = $duration - $minutes * 60;

			$hours = $duration / 3600;
			$duration = sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes) . ':' . sprintf('%02d', $seconds);
		}
		else
		{
			$duration = '-';
		}
		/*
		$user = JFactory::getUser();
		$userTZ = $user->getParam('timezone',0);
		$startTime->setOffset($userTZ);
		*/

		$filename_col = '';

		if (!empty($record['remote_filename']) && (AKEEBA_PRO == 1))
		{
			// If we have a remote filename we allow for remote file management in the Pro release
			$remotemgmttext = JText::_('STATS_LABEL_REMOTEFILEMGMT');
			$filename_col = <<<ENDHTML
<a
	class="modal akeeba_remote_management_link btn btn-mini"
	href="index.php?option=com_akeeba&view=remotefiles&tmpl=component&task=listactions&id={$record['id']}";
	rel="{handler: 'iframe', size: {x: 450, y: 280}, onClose: function(){window.location='index.php?option=com_akeeba&view=buadmin'}}"
>&raquo; $remotemgmttext &laquo;</a>
ENDHTML;
			if ($record['meta'] != 'obsolete')
			{
				$filename_col .= '<hr/>' . JText::_('REMOTEFILES_LBL_LOCALFILEHEADER');
			}
		}
		elseif (@empty($record['remote_filename']) && ($this->enginesPerProfile[$record['profile_id']] != 'none') && ($record['meta'] != 'obsolete') && (AKEEBA_PRO == 1))
		{
			$postProcEngine = $this->enginesPerProfile[$record['profile_id']];
			if (!empty($postProcEngine))
			{
				$filename_col .= '<a '
					. 'class="modal akeeba_upload" '
					. 'href="index.php?option=com_akeeba&view=upload&tmpl=component&task=start&id=' . $record['id'] . '" '
					. 'rel="{handler: \'iframe\', size: {x: 350, y: 200}, onClose: function(){window.location=\'index.php?option=com_akeeba&view=buadmin\'}}" '
					. 'title="' . JText::sprintf('AKEEBA_TRANSFER_DESC', JText::_("ENGINE_POSTPROC_{$postProcEngine}_TITLE")) . '">' .
					JText::_('AKEEBA_TRANSFER_TITLE') . ' (<em>' . $postProcEngine . '</em>)' .
					'</a>';
				$filename_col .= '<hr/>' . JText::_('REMOTEFILES_LBL_LOCALFILEHEADER');
			}
		}

		if ($record['meta'] == 'ok')
		{
			// Get the download links for downloads for completed, valid backups
			$thisPart = '';
			$thisID = urlencode($record['id']);
			$filename_col .= '<code>' . $record['archivename'] . "</code><br/>";
			if ($record['multipart'] == 0)
			{
				// Single part file -- Create a simple link
				$filename_col .= "<a class=\"btn btn-mini\" href=\"javascript:confirmDownload('$thisID', '$thisPart');\"><i class=\"icon-download-alt\"></i>" . JText::_('STATS_LOG_DOWNLOAD') . "</a>";
			}
			else
			{
				for ($count = 0; $count < $record['multipart']; $count++)
				{
					$thisPart = urlencode($count);
					$label = JText::sprintf('STATS_LABEL_PART', $count);
					$filename_col .= ($count > 0) ? ' &bull; ' : '';
					$filename_col .= "<a class=\"btn btn-mini\" href=\"javascript:confirmDownload('$thisID', '$thisPart');\"><i class=\"icon-download-alt\"></i>$label</a>";
				}
			}
		}
		else
		{
			// If the backup is not complete, just show dashes
			if (empty($filename_col))
			{
				$filename_col .= '&mdash;';
			}
		}

		// Link for Show Comments lightbox
		$info_link = "";
		if (!empty($record['comment']))
		{
			$info_link = JHTML::_('tooltip', strip_tags($this->escape($record['comment']))) . '&ensp;';
		}

		// Label class based on status
		$status = JText::_('STATS_LABEL_STATUS_' . $record['meta']);
		$statusClass = '';
		switch ($record['meta'])
		{
			case 'ok':
				$statusClass = 'label-success';
				break;
			case 'pending':
				$statusClass = 'label-warning';
				break;
			case 'fail':
				$statusClass = 'label-important';
				break;
			case 'remote':
				$statusClass = 'label-info';
				break;
		}

		$edit_link = JURI::base() . 'index.php?option=com_akeeba&view=buadmin&task=showcomment&id=' . $record['id'];

		if (empty($record['description']))
		{
			$record['description'] = JText::_('STATS_LABEL_NODESCRIPTION');
		}
		?>
		<tr class="row<?php echo $id; ?>">
			<td><?php echo $check; ?></td>
			<td class="hidden-phone">
				<?php echo $record['id']; ?>
			</td>
			<td>
				<?php echo $info_link ?>
				<a href="<?php echo $edit_link; ?>"><?php echo $this->escape($record['description']) ?></a>
			</td>
			<td>
				<?php echo $startTime->format($dateFormat, true); ?>
			</td>
			<td class="hidden-phone">
				<?php echo $duration; ?>
			</td>
			<td>
				<span class="label <?php echo $statusClass; ?>">
					<?php echo $status ?>
				</span>
			</td>
			<td class="hidden-phone"><?php echo $origin ?></td>
			<td class="hidden-phone"><?php echo $type ?></td>
			<td class="hidden-phone"><?php echo $record['profile_id'] ?></td>
			<td class="hidden-phone"><?php echo ($record['meta'] == 'ok') ? format_filesize($record['size']) : ($record['total_size'] > 0 ? "(<i>" . format_filesize($record['total_size']) . "</i>)" : '&mdash;') ?></td>
			<td class="hidden-phone"><?php echo $filename_col; ?></td>
		</tr>
	<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</form>
</div>