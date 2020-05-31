<?php
/*
Plugin Name: AB Posts Render
Version: 1.0.0
Version Boilerplate: 3.0.0
Description: A plugin for Job interview
Author: Aymene Bourafai
Author URI: https://aymenebourafai.com
Domain Path: languages
Text Domain: ab-posts-render

----

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'AB_POSTS_RENDER_VERSION', '1.0.0' );
define( 'AB_POSTS_RENDER_MIN_PHP_VERSION', '5.4' );
define( 'AB_POSTS_RENDER_VIEWS_FOLDER_NAME', 'ab-posts-render' );
define( 'AB_POSTS_RENDER_CPT_NAME', 'custom_post_type' );
define( 'AB_POSTS_RENDER_TAXO_NAME', 'custom_taxonomy' );

// Plugin URL and PATH
define( 'AB_POSTS_RENDER_URL', plugin_dir_url( __FILE__ ) );
define( 'AB_POSTS_RENDER_DIR', plugin_dir_path( __FILE__ ) );
define( 'AB_POSTS_RENDER_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );

// Check PHP min version
if ( version_compare( PHP_VERSION, AB_POSTS_RENDER_MIN_PHP_VERSION, '<' ) ) {
	require_once AB_POSTS_RENDER_DIR . 'classes/Compatibility.php';

	// Possibly display a notice, trigger error
	add_action( 'admin_init', array( 'AB\Posts_Render\Compatibility', 'admin_init' ) );

	// Stop execution of this file
	return;
}

require_once AB_POSTS_RENDER_DIR . 'classes/Main.php';
require_once AB_POSTS_RENDER_DIR . 'classes/Admin/Main.php';


add_action( 'plugins_loaded', 'init_ab_posts_render_plugin' );
/**
 * Init the plugin
 */
function init_ab_posts_render_plugin() {
	// Client
	\AB\Posts_Render\Main::get_instance();

	// Admin
	if ( is_admin() ) {
		\AB\Posts_Render\Admin\Main::get_instance();
	}
}
