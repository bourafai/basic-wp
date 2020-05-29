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
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
		add_action( 'wp_head', [ $this, 'render_title' ] );
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
	 * updates the pages title by setting the post_title in the global $post
	 */
	function render_title() {
		global $post;
		$title_alternatives = get_field( 'alternative_titles' );


		if ( ! empty( $title_alternatives ) ) {
			$printed_title = $this->get_printed_title( $title_alternatives, $post->post_title );

			$this->print_title( $printed_title );
			$post->post_title = esc_html( $printed_title );
		} else {
			$this->print_title( $post->post_title );
		}
	}

	/**
	 * verify the url parameters and the alternatives of the post title
	 * and decide which title to print
	 *
	 * @param $title_alternatives
	 * @param $post_title original post_title
	 *
	 * scenarios with the url "titre" param :
	 * titre = 1    => post_title
	 * titre = 0|>5 => random
	 * titre = null => random
	 * titre = 3 (if titre 3 is empty in db) => random
	 * titre = 2    => get acf field title_2
	 *
	 * @return string title to print
	 */
	private function get_printed_title( $title_alternatives, $post_title ) {
		$alternative_titles_values = array_values( $title_alternatives );
		if ( ! empty( $_GET['titre'] ) ) {
			if ( $_GET['titre'] == 1 ) {
				return $post_title;
			} else {
				$title_index = array_search( 'title_' . $_GET['titre'], array_keys( $title_alternatives ) );

				if ( $title_index === false || empty( array_values( $title_alternatives )[ $title_index ] ) ) {
					return $this->get_random_title( array_filter( $alternative_titles_values ), $post_title );
				} else {
					return $alternative_titles_values[ $title_index ];
				}
			}
		} else {
			return $this->get_random_title( array_filter( $alternative_titles_values ), $post_title );
		}
	}

	/**
	 * from a list of titles return a random one
	 *
	 * @param $alternative_titles all alternative titles
	 * @param string $post_title original post title
	 *
	 * @return string post title
	 */
	private function get_random_title( $alternative_titles, $post_title = '' ) {
		$titles = array_values( $alternative_titles );
		if ( ! empty( $post_title ) ) {
			$titles[] = $post_title;
		}

		return $titles[ rand( 0, sizeof( $titles ) - 1 ) ];
	}

	/**
	 * echo the html title tag with the wanted title
	 *
	 * @param $title to print
	 */
	private function print_title( $title ) {
		if ( did_action( 'wp_head' ) || doing_action( 'wp_head' ) ) {
			echo '<title>' . esc_html( $title ) . '</title>' . "\n";
		}
	}

}