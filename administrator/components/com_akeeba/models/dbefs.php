<?php
/**
 * @package AkeebaBackup
 *
 * @license GNU General Public License, version 2 or later
 * @author Nicholas K. Dionysopoulos
 * @copyright Copyright 2006-2014 Nicholas K. Dionysopoulos
 * @since 1.3
 */
defined('_JEXEC') or die();

/**
 * Database Table filter Model class
 *
 */
class AkeebaModelDbefs extends F0FModel
{

	/**
	 * Returns a list of the database tables, views, procedures, functions and triggers,
	 * along with their filter status in array format, for use in the GUI
	 * @return array
	 */
	public function make_listing($root)
	{
		// Get database inclusion filters
		$filters = AEFactory::getFilters();
		$database_list = $filters->getInclusions('db');

		// Load the database object for the selected database
		$config = $database_list[$root];
		$config['user'] = $config['username'];
		$db = AEFactory::getDatabase($config);

		// Load the table data
		try
		{
			$table_data = $db->getTables();
		}
		catch (Exception $e)
		{
			$table_data = array();
		}

		// Process filters
		$tables = array();
		if(!empty($table_data))
		{
			foreach($table_data as $table_name => $table_type)
			{
				$status = array();

				// Add table type
				$status['type'] = $table_type;

				// Check dbobject/all filter (exclude)
				$result = $filters->isFilteredExtended($table_name, $root, 'dbobject', 'all', $byFilter);
				$status['tables'] = (!$result) ? 0 : (( $byFilter == 'tables' ) ? 1 : 2 );

				// Check dbobject/content filter (skip table data)
				$result = $filters->isFilteredExtended($table_name, $root, 'dbobject', 'content', $byFilter);
				$status['tabledata'] = (!$result) ? 0 : (( $byFilter == 'tabledata' ) ? 1 : 2 );
				if( $table_type != 'table' ) $status['tabledata'] = 2; // We can't filter contents of views, merge tables, black holes, procedures, functions and triggers :)

				$tables[$table_name] = $status;
			}
		}

		return array(
			'tables'	=> $tables,
			'root'		=> $root
		);
	}

	/**
	 * Returns an array containing a mapping of db root names and their human-readable representation
	 * @return array Array of objects; "value" contains the root name, "text" the human-readable text
	 */
	public function get_roots()
	{
		// Get database inclusion filters
		$filters = AEFactory::getFilters();
		$database_list = $filters->getInclusions('db');

		$ret = array();

		foreach($database_list as $name => $definition)
		{
			$root = $definition['host'];
			if(!empty($definition['port'])) $root.=':'.$definition['port'];
			$root.='/'.$definition['database'];

			if($name == '[SITEDB]') $root = JText::_('DBFILTER_LABEL_SITEDB');

			$entry = new stdClass();
			$entry->value = $name;
			$entry->text = $root;
			$ret[] = $entry;
		}

		return $ret;
	}

	/**
	 * Toggle a filter
	 * @param string $root Root directory
	 * @param string $item The child item of the current directory we want to toggle the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function toggle($root, $item, $filter)
	{
		if(empty($item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		$success = $filter->toggle($root, $item, $new_status);
		// Save the data on success
		if($success) $filters->save();
		// Make a return array
		return array(
			'success'	=> $success,
			'newstate'	=> $new_status
		);
	}

	/**
	 * Set a filter
	 * @param string $root Root directory
	 * @param string $item The child item of the current directory we want to set the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function remove($root, $item, $filter)
	{
		if(empty($item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		$success = $filter->remove($root, $item);
		// Save the data on success
		if($success) $filters->save();
		// Make a return array
		return array(
			'success'	=> $success,
			'newstate'	=> !$success // The new state of the filter. It is removed if and only if the transaction succeeded
		);
	}

	/**
	 * Set a filter
	 * @param string $root Root directory
	 * @param string $item The child item of the current directory we want to set the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function setFilter($root, $item, $filter)
	{
		if(empty($item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		$success = $filter->set($root, $item);
		// Save the data on success
		if($success) $filters->save();
		// Make a return array
		return array(
			'success'	=> $success,
			'newstate'	=> $success // The new state of the filter. It is set if and only if the transaction succeeded
		);
	}

	/**
	 * Swap a filter
	 * @param string $root Root directory
	 * @param string $item The child item of the current directory we want to set the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function swap($root, $old_item, $new_item, $filter)
	{
		if(empty($new_item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		if(!empty($old_item))
		{
			$success = $filter->remove($root, $old_item);
		}
		else
		{
			$success = true;
		}
		if($success) {
			$success = $filter->set($root, $new_item);
		}
		// Save the data on success
		if($success) $filters->save();
		// Make a return array
		return array(
			'success'	=> $success,
			'newstate'	=> $success // The new state of the filter. It is set if and only if the transaction succeeded
		);
	}

	/**
	 * Retrieves the filters as an array. Used for the tabular filter editor.
	 * @param string $root The root node to search filters on
	 * @return array A collection of hash arrays containing node and type for each filtered element
	 */
	public function &get_filters($root)
	{
		// A reference to the global Akeeba Engine filter object
		$filters = AEFactory::getFilters();
		// Initialize the return array
		$ret = array();
		// Define the known filter types and loop through them
		$filter_types = array('tables', 'tabledata');
		foreach($filter_types as $type)
		{
			$rawFilterData = $filters->getFilterData($type);
			if( array_key_exists($root, $rawFilterData) )
			{
				if(!empty($rawFilterData[$root]))
				{
					foreach($rawFilterData[$root] as $node)
					{
						$ret[] = array (
							'node'	=> substr($node,0), // Make sure we get a COPY, not a reference to the original data
							'type'	=> $type
						);
					}
				}
			}
		}
		/*
		 * Return array format:
		 * [array] :
		 * 		[array] :
		 * 			'node'	=> 'somedir'
		 * 			'type'	=> 'directories'
		 * 		[array] :
		 * 			'node'	=> 'somefile'
		 * 			'type'	=> 'files'
		 * 		...
		 */
		return $ret;
	}

	/**
	 * Resets the filters
	 * @param string $root Root directory
	 * @return array
	 */
	public function resetFilters($root)
	{
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		$filter = AEFactory::getFilterObject('tables');
		$filter->reset($root);
		$filter = AEFactory::getFilterObject('tabledata');
		$filter->reset($root);
		$filters->save();
		return $this->make_listing($root);
	}

	function doAjax()
	{
		$action = $this->getState('action');
		$verb = array_key_exists('verb', get_object_vars($action)) ? $action->verb : null;

		$ret_array = array();

		switch($verb)
		{
			// Return a listing for the normal view
			case 'list':
				$ret_array = $this->make_listing($action->root, $action->node);
				break;

			// Toggle a filter's state
			case 'toggle':
				$ret_array = $this->toggle($action->root, $action->node, $action->filter);
				break;

			// Set a filter (used by the editor)
			case 'set':
				$ret_array = $this->setFilter($action->root, $action->node, $action->filter);
				break;

			// Remove a filter (used by the editor)
			case 'remove':
				$ret_array = $this->remove($action->root, $action->node, $action->filter);
				break;

			// Swap a filter (used by the editor)
			case 'swap':
				$ret_array = $this->swap($action->root, $action->old_node, $action->new_node, $action->filter);
				break;

			// Tabular view
			case 'tab':
				$ret_array = $this->get_filters($action->root);
				break;

			// Reset filters
			case 'reset':
				$ret_array = $this->resetFilters($action->root);
				break;
		}

		return $ret_array;
	}
}