<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Filesystem Filter model
 *
 */
class AkeebaModelFsfilters extends F0FModel
{
	/**
	 * Returns a listing of contained directories and files, as well as their
	 * exclusion status
	 * @param string $root The root directory
	 * @param string $node The subdirectory to scan
	 * @return array
	 */
	private function &get_listing($root, $node)
	{
		// Initialize the absolute directory root
		$directory = substr($root,0);
		// Replace stock directory tags, like [SITEROOT]
		$stock_dirs = AEPlatform::getInstance()->get_stock_directories();
		if(!empty($stock_dirs))
		{
			foreach($stock_dirs as $key => $replacement)
			{
				$directory = str_replace($key, $replacement, $directory);
			}
		}
		$directory = AEUtilFilesystem::TranslateWinPath($directory);
		// Clean and add the node
		$node = AEUtilFilesystem::TranslateWinPath($node);
		if( ($node == '/') ) $node = ''; // Just a dir. sep. is treated as no dir at all
		// Trim leading and trailing slashes
		$node = trim($node, '/');
		// Add node to directory
		if( !empty($node) )	$directory .= '/'.$node;

		// Add any required trailing slash to the node to be used below
		if(!empty($node)) $node .= '/';

		// Get a filters instance
		$filters = AEFactory::getFilters();

		// Detect PHP 5.2.5or earlier, with broken json_decode implementation
		$phpversion = PHP_VERSION;
		$vparts = explode('.',$phpversion);
		if(
			(($vparts[0] == 5) && ($vparts[1] == 2) && ($vparts[2] <= 5)) ||
			(($vparts[0] == 5) && ($vparts[1] == 1) )
		)
		{
			define('AKEEBA_SAFE_JSON',false);
			require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/jsonlib.php';
		}
		else
		{
			$test = '-test-';
			$tj = json_encode($test);
			$test = json_decode($test);
			if($test == '-test-') {
				define('AKEEBA_SAFE_JSON',true);
			} else {
				define('AKEEBA_SAFE_JSON',false);
				require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/jsonlib.php';
			}
		}

		// Get a listing of folders and process it
		$folders = AEUtilScanner::getFolders($directory);
		asort($folders);
		$folders_out = array();
		if(!empty($folders))
		{
			foreach($folders as $folder)
			{
				$folder = AEUtilFilesystem::TranslateWinPath($folder);
				// Filter out files whose names result to an empty JSON representation
				if(AKEEBA_SAFE_JSON)
				{
					$json_folder = json_encode($folder);
					$folder = json_decode($json_folder);
				}
				else
				{
					$jsonobj = new Akeeba_Services_JSON(0);
					$json_folder = $jsonobj->encode($folder);
					$folder = $jsonobj->decode($json_folder);
				}
				if(empty($folder)) continue;

				$test = $node.$folder;
				$status = array();

				// Check dir/all filter (exclude)
				$result = $filters->isFilteredExtended($test, $root, 'dir', 'all', $byFilter);
				$status['directories'] = (!$result) ? 0 : (( $byFilter == 'directories' ) ? 1 : 2 );

				// Check dir/content filter (skip_files)
				$result = $filters->isFilteredExtended($test, $root, 'dir', 'content', $byFilter);
				$status['skipfiles'] = (!$result) ? 0 : (( $byFilter == 'skipfiles' ) ? 1 : 2 );

				// Check dir/children filter (skip_dirs)
				$result = $filters->isFilteredExtended($test, $root, 'dir', 'children', $byFilter);
				$status['skipdirs'] = (!$result) ? 0 : (( $byFilter == 'skipdirs' ) ? 1 : 2 );

				// Add to output array
				$folders_out[$folder] = $status;
			}
		}
		unset($folders);
		$folders = $folders_out;

		// Get a listing of files and process it
		$files = AEUtilScanner::getFiles($directory);
		asort($files);
		$files_out = array();
		if(!empty($files))
		{
			foreach($files as $file)
			{
				// Filter out files whose names result to an empty JSON representation
				if(AKEEBA_SAFE_JSON)
				{
					$json_file = json_encode($file);
					$file = json_decode($json_file);
				}
				else
				{
					$jsonobj = new Akeeba_Services_JSON(0);
					$json_file = $jsonobj->encode($file);
					$file = $jsonobj->decode($json_file);
				}
				if(empty($file)) continue;

				$test = $node.$file;
				$status = array();

				// Check file/all filter (exclude)
				$result = $filters->isFilteredExtended($test, $root, 'file', 'all', $byFilter);
				$status['files'] = (!$result) ? 0 : (( $byFilter == 'files' ) ? 1 : 2 );
				$status['size'] = @filesize($directory.'/'.$file);

				// Add to output array
				$files_out[$file] = $status;
			}
		}
		unset($files);
		$files = $files_out;

		// Return a compiled array
		$retarray = array(
			'folders'	=> $folders,
			'files'		=> $files
		);
		return $retarray;

		/* Return array format
		 * [array] :
		 * 		'folders' [array] :
		 * 			(folder_name) => [array]:
		 *				'directories'	=> 0|1|2
		 *				'skipfiles'		=> 0|1|2
		 *				'skipdirs'		=> 0|1|2
		 *		'files' [array] :
		 *			(file_name) => [array]:
		 *				'files'			=> 0|1|2
		 *
		 * Legend:
		 * 0 -> Not excluded
		 * 1 -> Excluded by the direct filter
		 * 2 -> Excluded by another filter (regex, api, an unknown plugin filter...)
		 */
	}

	/**
	 * Glues the current directory crumbs and the child directory into a node string
	 * @param string $crumbs
	 * @param string $child
	 * @return string
	 */
	private function glue_crumbs(&$crumbs, $child)
	{
		// Construct the full node
		$node = '';

		// Some servers do not decode the crumbs. I don't know why!
		if(!is_array($crumbs) && (substr($crumbs,0,1) == '['))
		{
			$crumbs = @json_decode($crumbs);
			if($crumbs === false) $crumbs = array();
		}

		if(!empty($crumbs))
		{
			$node = implode('/', $crumbs);
		}
		if(!empty($node)) $node .= '/';
		if(!empty($child)) $node .= $child;

		return $node;
	}

	/**
	 * Returns an array with the listing and filter status of a directory
	 * @param string $root Root directory
	 * @param array $crumbs Components of the current directory relative to the root
	 * @param string $child The child directory of the current directory we want to scan
	 * @return array
	 */
	public function make_listing($root, $crumbs, $child)
	{
		// Construct the full node
		$node = $this->glue_crumbs($crumbs, $child);

		// Create the new crumbs
		if(!is_array($crumbs)) $crumbs = array();
		if(!empty($child)) $crumbs[] = $child;

		// Get listing with the filter info
		$listing = $this->get_listing($root, $node);

		// Assemble the array
		$listing['root'] = $root;
		$listing['crumbs'] = $crumbs;
		return $listing;
	}

	/**
	 * Toggle a filter
	 * @param string $root Root directory
	 * @param array $crumbs Components of the current directory relative to the root
	 * @param string $item The child item of the current directory we want to toggle the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function toggle($root, $crumbs, $item, $filter)
	{
		if(empty($item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the object to toggle
		$node = $this->glue_crumbs($crumbs, $item);
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		$success = $filter->toggle($root, $node, $new_status);
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
	 * @param array $crumbs Components of the current directory relative to the root
	 * @param string $item The child item of the current directory we want to set the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function setFilter($root, $crumbs, $item, $filter)
	{
		if(empty($item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the object to toggle
		$node = $this->glue_crumbs($crumbs, $item);
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		$success = $filter->set($root, $node);
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
	 * @param array $crumbs Components of the current directory relative to the root
	 * @param string $item The child item of the current directory we want to set the filter for
	 * @param string $filter The name of the filter to apply (directories, skipfiles, skipdirs, files)
	 * @return array
	 */
	public function swap($root, $crumbs, $old_item, $new_item, $filter)
	{
		if(empty($new_item)) return array(
			'success' => false,
			'newstate'=> false
		);
		// Get a reference to the global Filters object
		$filters = AEFactory::getFilters();
		// Get the object to toggle
		$old_node = $this->glue_crumbs($crumbs, $old_item);
		$new_node = $this->glue_crumbs($crumbs, $new_item);
		// Get the specific filter object
		$filter = AEFactory::getFilterObject($filter);
		// Toggle the filter
		if(!empty($old_item))
		{
			$success = $filter->remove($root, $old_node);
		}
		else
		{
			$success = true;
		}
		if($success) {
			$success = $filter->set($root, $new_node);
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
		$filter_types = array('directories', 'skipdirs', 'skipfiles', 'files');
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
		$filter = AEFactory::getFilterObject('directories');
		$filter->reset($root);
		$filter = AEFactory::getFilterObject('files');
		$filter->reset($root);
		$filter = AEFactory::getFilterObject('skipdirs');
		$filter->reset($root);
		$filter = AEFactory::getFilterObject('skipfiles');
		$filter->reset($root);
		$filters->save();
		return $this->make_listing($root);
	}

	public function doAjax()
	{
		$action = $this->getState('action');
		$verb = array_key_exists('verb', get_object_vars($action)) ? $action->verb : null;
		if(!array_key_exists('crumbs', get_object_vars($action))) $action->crumbs = '';

		$ret_array = array();

		switch($verb)
		{
			// Return a listing for the normal view
			case 'list':
				$ret_array = $this->make_listing($action->root, $action->crumbs, $action->node);
				break;

			// Toggle a filter's state
			case 'toggle':
				$ret_array = $this->toggle($action->root, $action->crumbs, $action->node, $action->filter);
				break;

			// Set a filter (used by the editor)
			case 'set':
				$ret_array = $this->setFilter($action->root, $action->crumbs, $action->node, $action->filter);
				break;

			// Swap a filter (used by the editor)
			case 'swap':
				$ret_array = $this->swap($action->root, $action->crumbs, $action->old_node, $action->new_node, $action->filter);
				break;

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