<?php

namespace AB\Posts_Render;
require_once AB_POSTS_RENDER_DIR . 'classes/Singleton.php';

/**
 * The purpose of the main class is to init all the plugin base code like :
 *  - Taxonomies
 *  - Post types
 *  - Shortcodes
 *  - Posts to posts relations etc.
 *  - Loading the text domain
 *
 * Class Main
 * @package AB\Posts_Render
 */
class Main {
	/**
	 * Use the trait
	 */
	use Singleton;

	protected function init() {
		add_action( 'init', [ $this, 'init_translations' ] );
		add_action( 'the_post', [ $this, 'the_post' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	public function enqueue_script() {
		//load scripts
		wp_enqueue_script( 'sticky-sidebar', AB_POSTS_RENDER_URL . '/assets/js/jquery.sticky-sidebar.min.js', [ 'jquery' ] );
		wp_enqueue_script( 'jquery-appear', AB_POSTS_RENDER_URL . '/assets/js/jquery-appear.js', [ 'jquery' ] );
		wp_enqueue_script( 'ab-scripts', AB_POSTS_RENDER_URL . '/assets/js/scripts.js', [
			'jquery',
			'sticky-sidebar',
			'jquery-appear'
		], AB_POSTS_RENDER_VERSION );

		//load custom css
		wp_enqueue_style( 'ab-styles', AB_POSTS_RENDER_URL . '/assets/css/styles.css', [], AB_POSTS_RENDER_VERSION );
	}


	/**
	 * Load the plugin translation
	 */
	public function init_translations() {
		// Load translations
		load_plugin_textdomain( 'ab-posts-render', false, AB_POSTS_RENDER_PLUGIN_DIRNAME . '/languages' );
	}

	/**
	 * make the post loads 5 random linked posts
	 *
	 * @param $post_object
	 */
	public function the_post( $post_object ) {

		return $post_object;
	}

	private function render_pagination() {
		return '';
	}


}