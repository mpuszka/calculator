<?php
/**
 * Plugin Name: Calculator
 * Plugin URI: #
 * Description: Simple calculator for converting amounts
 * Version: 1.0
 * Author: Marcin Puszka
 * Author URI: http://www.moskitocode.pl
 */

if (!function_exists('add_action')) 
{
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define('CHECK_VERSION', '1.0');
define('CHECK__MINIMUM_WP_VERSION', '4.0');
define('CHECK__PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once(CHECK__PLUGIN_DIR . 'vendor/autoload.php');

require_once(ABSPATH . "wp-includes/pluggable.php");
require_once(CHECK__PLUGIN_DIR . 'class/bootstrap.php');

if (file_exists(CHECK__PLUGIN_DIR . 'assets/style.css'))
{
	wp_enqueue_style('calculatorPlugin', plugin_dir_url(__FILE__) . 'assets/style.css', false,'1.0','all');
}
