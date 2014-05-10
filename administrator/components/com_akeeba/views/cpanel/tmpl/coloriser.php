<?php

class AkeebaChangelogColoriser
{
	public static function colorise($file, $onlyLast = false)
	{
		$ret = '';
		
		$lines = @file($file);
		if(empty($lines)) return $ret;
		
		array_shift($lines);
		
		foreach($lines as $line) {
			$line = trim($line);
			if(empty($line)) continue;
			$type = substr($line,0,1);
			switch($type) {
				case '=':
					continue;
					break;
					
				case '+':
					$ret .= "\t".'<li class="akeeba-changelog-added"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '-':
					$ret .= "\t".'<li class="akeeba-changelog-removed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '~':
					$ret .= "\t".'<li class="akeeba-changelog-changed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '!':
					$ret .= "\t".'<li class="akeeba-changelog-important"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '#':
					$ret .= "\t".'<li class="akeeba-changelog-fixed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				default:
					if(!empty($ret)) {
						$ret .= "</ul>";
						if($onlyLast) return $ret;
					}
					if(!$onlyLast) $ret .= "<h3 class=\"akeeba-changelog\">$line</h3>\n";
					$ret .= "<ul class=\"akeeba-changelog\">\n";
					break;
			}
		}
		
		return $ret;
	}
}