<?php
/**
 * Plugin Name: Random Banner
 * Plugin URI: https://buffercode.com/plugin/random-banner-pro
 * Description: Random Banner WordPress plugin provides users with high level of flexibility to show image banner and script ads randomly on Widgets
 * Version: 4.1.11
 * Author: vinoth06
 * Author URI: https://buffercode.com/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: random-banner
 * Domain Path: /languages/
 *
 * @package random-banner
 */

/**
 * Version Number
 */
define( 'BC_RB_PLUGIN_VERSION', '4.1.11' );
/**
 * Random Banner DB Name
 */
define( 'BC_RB_RANDOM_BANNER_DB', 'bc_random_banner' );
/**
 * Random Banner Option DB Name
 */
define( 'BC_RB_RANDOM_BANNER_OPTION_DB', 'bc_random_banner_options' );
/**
 * Random Banner Category Name
 */
define( 'BC_RB_RANDOM_BANNER_CATEGORY', 'bc_random_banner_category' );
/**
 * Root Path
 */
define( 'BC_RB_PLUGIN', __FILE__ );
/**
 * Plugin Base Name
 */
define( 'BC_RB_PLUGIN_BASENAME', plugin_basename( BC_RB_PLUGIN ) );
/**
 * Plugin Name
 */
define( 'BC_RB_PLUGIN_NAME', trim( dirname( BC_RB_PLUGIN_BASENAME ), '/' ) );
/**
 * Plugin Directory
 */
define( 'BC_RB_PLUGIN_DIR', untrailingslashit( dirname( BC_RB_PLUGIN ) ) );

/**
 * Include Necessary Files
 */
require_once BC_RB_PLUGIN_DIR . '/bc-rb-load.php';

/**
 * Clear Cache
 */

bc_clear_all_cache();


