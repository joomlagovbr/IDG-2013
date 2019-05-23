<?php
/**
 *
 *
 * @package   mod_blank250
 * copyright Blackdale/Bob Galway
 * @license GPL3
 */
// no direct access
defined('_JEXEC') or die;
include('params.php');



$doc =& JFactory::getDocument();
if($graphics==1){include('css.php');}

if ($phpuse==1){

    // create temporary file    

    if (!file_exists($tmp_file)){   
    $handle = fopen($tmp_file, 'w'); }  
    $temp=modblank250Helper::phpprocessbm($phpcode,$modno_bm,$tmp_file);
    }

//add custom tags to head section

if ($scriptuse==1){$doc->addCustomTag( $script );}

// deselect unwanted content

if ($textareause==2){$codeeditor="";}

include('article.php');

// output

echo '
<!-- Blank250 Starts Here -->';
include('view1.php');
echo '
<!-- Blank250 Ends Here -->';

if ($phpuse==1){

//delete temporary file
fclose($handle);
unlink($tmp_file);}
?>

