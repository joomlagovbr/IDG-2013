<?php
/**
 * Script used to build a sample site on Docker container.
 *
 * If configuration.php file is provide on start your container,
 * this script make a site with default Gov BR sample.
 *
 * @package     Joomla.Docker
 * @subpackage  PHP.Sample
 *
 * @copyright   Copyright (C) 2013 - 2021 JoomlaGovBR. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 * @since       1.0.0
 */

function usage($command)
{
	echo PHP_EOL;
	echo 'Usage: php ' . $command . ' /path/to/file.sql' . PHP_EOL;
	echo PHP_EOL;
}

function message($message)
{
	echo PHP_EOL;
	echo $message . PHP_EOL;
	echo PHP_EOL;
}

function replacePrefixTable($content, $prefixTable)
{
	$regex = "/#__/";
	return preg_replace($regex, $prefixTable, $content);
}

// From InstallationModelDatabase
function splitQueries($query)
{
	$buffer    = array();
	$queries   = array();
	$in_string = false;

	$query = trim($query);
	$query = preg_replace("/\n\#[^\n]*/", '', "\n" . $query);
	$query = preg_replace("/\n\--[^\n]*/", '', "\n" . $query);

	$funct = explode('CREATE OR REPLACE FUNCTION', $query);

	$query = $funct[0];

	for ($i = 0; $i < strlen($query) - 1; $i++)
	{
		if (!$in_string && $query[$i] === ';')
		{
			$queries[] = substr($query, 0, $i);
			$query     = substr($query, $i + 1);
			$i         = 0;
		}

		if ($in_string && $query[$i] == $in_string && $buffer[1] !== '\\')
		{
			$in_string = false;
		}
		elseif (!$in_string && ($query[$i] === '"' || $query[$i] === "'") && (!isset($buffer[0]) || $buffer[0] !== '\\'))
		{
			$in_string = $query[$i];
		}

		if (isset ($buffer[1]))
		{
			$buffer[0] = $buffer[1];
		}

		$buffer[1] = $query[$i];
	}

	if (!empty($query))
	{
		$queries[] = $query;
	}

	for ($f = 1, $fMax = count($funct); $f < $fMax; $f++)
	{
		$queries[] = 'CREATE OR REPLACE FUNCTION ' . $funct[$f];
	}

	return $queries;
}

$sampleSqlFile = $argv['1'];

if (!file_exists($sampleSqlFile) || empty($sampleSqlFile))
{
	usage($argv[0]);
	die();
}

if (strpos(getenv('JOOMLA_DB_HOST', true), ':') === false)
{
	$host = getenv('JOOMLA_DB_HOST', true);
	$port = 3306;
}
else
{
	list($host, $port) = explode(':', getenv('JOOMLA_DB_HOST', true), 2);
}

$database    = getenv('JOOMLA_DB_NAME', true);
$user        = getenv('JOOMLA_DB_USER', true);
$password    = getenv('JOOMLA_DB_PASSWORD', true);
$prefixTable = getenv('JOOMLA_DB_PREFIX', true);

$sampleContent = file_get_contents($sampleSqlFile);
$sampleContent = replacePrefixTable($sampleContent, $prefixTable);

$maxTries = 10;

do
{
	$mysql = new mysqli($host, $user, $password, '', $port);

	if ($mysql->connect_error)
	{
		message("MySQL Connection Error: ({$mysql->connect_errno}) {$mysql->connect_error}");

		--$maxTries;

		if ($maxTries <= 0)
		{
			exit(1);
		}

		sleep(3);
	}
}
while ($mysql->connect_error);

if (!$mysql->query('DROP DATABASE IF EXISTS `' . $mysql->real_escape_string($database) . '`'))
{
	message("MySQL 'DROP DATABASE' Error: " . $mysql->error);
	$mysql->close();
	exit(1);
}

if (!$mysql->query('CREATE DATABASE `' . $mysql->real_escape_string($database) . '`'))
{
	message("MySQL 'CREATE DATABASE' Error: " . $mysql->error);
	$mysql->close();
	exit(1);
}

if (!$mysql->query('USE `' . $mysql->real_escape_string($database) . '`'))
{
	message("MySQL 'USE' Error: " . $mysql->error);
	$mysql->close();
	exit(1);
}

$queries = splitQueries($sampleContent);
$error   = '';

foreach ($queries as $query)
{
	$result = $mysql->query($query);
	if (!$result)
	{
		$error .= $mysql->error . "\n";
	}
}

if ($error)
{
	message("MySQL 'POPULATE DATABASE' Error: " . $error);
} else
{
	message('Populate database sample complete.');
}

$mysql->close();
