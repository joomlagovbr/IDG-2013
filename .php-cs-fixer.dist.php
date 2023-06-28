<?php

/**
 * @package    Joomla.Site
 *
 * @copyright  (C) 2021-2023 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * This is the configuration file for php-cs-fixer
 *
 * @see https://github.com/FriendsOfPHP/PHP-CS-Fixer
 * @see https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0
 *
 *
 * If you would like to run the automated clean up, then open a command line and type one of the commands below
 *
 * To run a quick dry run to see the files that would be modified:
 *
 *        ./tools/vendor/bin/php-cs-fixer fix --dry-run
 *
 * To run a full check, with automated fixing of each problem :
 *
 *        ./tools/vendor/bin/php-cs-fixer fix
 *
 * You can run the clean up on a single file if you need to, this is faster
 *
 *        ./tools/vendor/bin/php-cs-fixer fix --dry-run path/to/file.php
 *        ./tools/vendor/bin/php-cs-fixer fix path/to/file.php
 */

// Only index the files in /templates/padraogoverno01, to prevent the core Joomla folder being indexed
$finder = PhpCsFixer\Finder::create()
    ->in(
        [
            __DIR__ . '/templates/padraogoverno01',
        ]
    );

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules(
        [
            // Basic ruleset is PSR 12
            '@PSR12' => true,
            // Short array syntax
            'array_syntax' => ['syntax' => 'short'],
            // Lists should not have a trailing comma like list($foo, $bar,) = ...
            'no_trailing_comma_in_list_call' => true,
            // Arrays on multiline should have a trailing comma
            'trailing_comma_in_multiline' => ['elements' => ['arrays']],
            // Align elements in multiline array and variable declarations on new lines below each other
            'binary_operator_spaces' => ['operators' => ['=>' => 'align_single_space_minimal', '=' => 'align']],
            // The "No break" comment in switch statements
            'no_break_comment' => ['comment_text' => 'No break'],
        ]
    )
    ->setFinder($finder);

return $config;
