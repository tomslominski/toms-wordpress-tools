<?php
/**
 * Tom's WordPress Tools
 *
 * @package TomsWordPressTools
 * @author Tom Slominski <tom@slomin.ski>
 * @license GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Tom's WordPress Tools
 * Plugin URI: https://github.com/tomslominski/toms-wordpress-tools
 * Description: A bunch of WordPress helpers for my hosted WordPress sites.
 * Version: 0.1.5
 * Requires at least: 5.2
 * Requires PHP: 7.3
 * Author: Tom Slominski
 * Author URI: https://slomin.ski/
 * License: GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

use HaydenPierce\ClassFinder\ClassFinder;

// Include autoloader for development, this isn't used in production
$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
	require_once($autoloader);
}

do_action( 'TomsWordPressTools/before_init' );

try {
	$classes = ClassFinder::getClassesInNamespace('TomsWordPressTools\Modules', ClassFinder::RECURSIVE_MODE);

	foreach ($classes as $class) {
		new $class;
	}
} catch (Exception $e) {
	error_log($e->getMessage());
}

do_action( 'TomsWordPressTools/after_init' );
