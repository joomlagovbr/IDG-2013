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



echo '
<div id="blank'.$modno_bm.'" >
    <div id="holder' . $modno_bm . '">
        <div id="inner' . $modno_bm . '">';

// contentselection/order

$codeeditor='
            <div>
                '.$codeeditor.'
            </div>';
$article='
            <div>
                '.$article.'
            </div>';

if ($content1==1){echo $codeeditor;}
if ($content1==2){echo '
            <div>
            ';
if(file_exists($temp)){include($temp);}else{$temp="";}
echo '
            </div>';}
if ($content1==3){echo $article;}
if ($content2==1){echo $codeeditor;}
if ($content2==2){echo  '
            <div>
            ';
if(file_exists($temp)){include($temp);}else{$temp="";}
echo '
            </div>';}
if ($content2==3){echo $article;}
if ($content3==1){echo $codeeditor;}
if ($content3==2){echo  '
            <div>
            ';
if(file_exists($temp)){include($temp);}else{$temp="";}
echo '
            </div>';}
if ($content3==3){echo $article;}
echo '
        </div>
    </div>
</div>';
