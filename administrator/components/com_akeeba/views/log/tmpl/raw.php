<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

defined('_JEXEC') or die();

JHtml::_('behavior.framework');
?>
<script language="javascript" type="text/javascript">
// Disable right-click
var isNS = (navigator.appName == "Netscape") ? 1 : 0;
if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
function mischandler(){
 return false;
}
function mousehandler(e){
	var myevent = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;
  if((eventbutton==2)||(eventbutton==3)) return false;
}
document.oncontextmenu = mischandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;

// Disable CTRL-C, CTRL-V
function onKeyDown() {
	return false;
}

document.onkeydown = onKeyDown;
</script>
<?php

// -- Get the log's file name
$tag = $this->tag;
$logName = AEUtilLogger::logName($tag);

// Load JFile class
JLoader::import('joomla.filesystem.file');

@ob_end_clean();

if(!JFile::exists($logName))
{
	// Oops! The log doesn't exist!
	echo '<p>'.JText::_('LOG_ERROR_LOGFILENOTEXISTS').'</p>';
	return;
}
else
{
	// Allright, let's load and render it
	$fp = fopen( $logName, "rt" );
	if ($fp === FALSE)
	{
		// Oops! The log isn't readable?!
		echo '<p>'.JText::_('LOG_ERROR_UNREADABLE').'</p>';
		return;
	}

	while( !feof($fp) )
	{
		$line = fgets( $fp );
		if(!$line) return;
		$exploded = explode( "|", $line, 3 );
		unset( $line );
		switch( trim($exploded[0]) )
		{
			case "ERROR":
				$fmtString = "<span style=\"color: red; font-weight: bold;\">[";
				break;
			case "WARNING":
				$fmtString = "<span style=\"color: #D8AD00; font-weight: bold;\">[";
				break;
			case "INFO":
				$fmtString = "<span style=\"color: black;\">[";
				break;
			case "DEBUG":
				$fmtString = "<span style=\"color: #666666; font-size: small;\">[";
				break;
			default:
				$fmtString = "<span style=\"font-size: small;\">[";
				break;
		}
		$fmtString .= $exploded[1] . "] " . htmlspecialchars($exploded[2]) . "</span><br/>\n";
		unset( $exploded );
		echo $fmtString;
		unset( $fmtString );
	}
}

@ob_start();