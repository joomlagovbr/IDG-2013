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
$doc = JFactory::getDocument();
include('params.php');
include('css.php');


if ($phpuse == 1) {

    // create temporary file

    if (!file_exists($tmp_file)) {
        $handle = fopen($tmp_file, 'w');
        fclose($handle);
    }
    $modblank250Helper = new modblank250Helper();
    $temp = $modblank250Helper->phpprocessbm($phpcode, $tmp_file);
}

//add custom tags to head section

if ($scriptuse == 1) {
    $doc->addCustomTag($script);
}

// deselect unwanted content

if ($textareause == 2) {
    $codeeditor = "";
}

include('article.php');

// output

echo '
<!-- Blank250 Starts Here -->';
include('view1.php');
echo '
<!-- Blank250 Ends Here -->';

if ($phpuse == 1) {

//delete temporary file

    unlink($tmp_file);
    $temp = "";
}
?>
