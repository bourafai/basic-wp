<?php

namespace AB\Posts_Render;
require_once AB_POSTS_RENDER_DIR . 'classes/Singleton.php';
require_once AB_POSTS_RENDER_DIR . 'classes/Helpers.php';


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
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );

		add_action( 'init', [ $this, 'ab_init' ] );
		add_action( 'wp_logout', [ $this, 'wp_logout' ] );

		add_action( 'wp_head', [ $this, 'render_title' ] );
		add_action( 'get_footer', [ $this, 'get_footer' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );

		add_filter( 'next_post_link', [ $this, 'adjacent_post_link' ], 10, 5 );
		add_filter( 'previous_post_link', [ $this, 'adjacent_post_link' ], 10, 5 );
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

	public function wp_logout() {
		if ( isset( $_COOKIE[ session_name() ] ) ) {
			session_unset();   // détruit les variables de session
			session_destroy();
		}
	}

	/**
	 * Load the plugin translation
	 */
	public function ab_init() {
		//include ACF file
		require_once AB_POSTS_RENDER_DIR . 'assets/acf/title-alternatives-fields.php';

		// Load translations
		load_plugin_textdomain( 'ab-posts-render', false, AB_POSTS_RENDER_PLUGIN_DIRNAME . '/languages' );

		if ( ! session_id() ) {
			@session_start();
		}
	}


	/**
	 * updates the pages title by setting the post_title in the global $post
	 */
	function render_title() {
		global $post;
		$title_alternatives = get_field( 'alternative_titles' );
		if ( ! $post ) {
			return;
		}
		//set a default 'v' to the post
		$_SESSION['navigation'][ $post->ID ]['v'] = empty( $_GET['v'] ) ? rand( 1, 3 ) : intval( $_GET['v'] );

		if ( ! empty( $title_alternatives ) ) {
			$printed_title = $this->get_printed_title( $title_alternatives, $post );
			$this->print_title( $printed_title );
			$post->post_title = esc_html( $printed_title );
		} else {
			$_GET['titre']                                = 1;
			$_SESSION['navigation'][ $post->ID ]['titre'] = 1;

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
	private function get_printed_title( $title_alternatives, $post ) {
		$post_title                = $post->post_title;
		$alternative_titles_values = array_values( $title_alternatives );
		if ( isset( $_GET['titre'] ) ) {
			if ( $_GET['titre'] == 1 ) {
				$_GET['titre']                                = 1;
				$_SESSION['navigation'][ $post->ID ]['title'] = 1;

				return $post_title;
			} else {
				$title_index = array_search( 'title_' . $_GET['titre'], array_keys( $title_alternatives ) );

				if ( $title_index === false || empty( array_values( $title_alternatives )[ $title_index ] ) ) {
					return $this->get_random_title( $title_alternatives, $post );
				} else {
					$_SESSION['navigation'][ $post->ID ]['titre'] = $_GET['titre'];

					return $alternative_titles_values[ $title_index ];

				}
			}
		} else {
			if ( ! empty( $_GET['nav'] ) && ! empty( $_SESSION['navigation'][ $post->ID ]['titre'] ) ) {
				$titre       = $_SESSION['navigation'][ $post->ID ]['titre'];
				$title_index = array_search( 'title_' . $titre, array_keys( $title_alternatives ) );
				if ( $title_index === false ) {
					return $this->get_random_title( $title_alternatives, $post );
				} else {
					return $alternative_titles_values[ $title_index ];
				}
			} else {
				if ( empty( $_GET['nav'] ) ) {
					$_SESSION['navigation'] = null;
				}

				return $this->get_random_title( $title_alternatives, $post );
			}
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
	private function get_random_title( $alternative_titles, $post ) {
		$post_title = $post->post_title;
		$titles     = array_values( array_filter( $alternative_titles ) );
		if ( ! empty( $post_title ) ) {
			$titles[] = $post_title;
		}
		$title_index = rand( 0, sizeof( $titles ) - 1 );
		$title_key   = array_search( $titles[ $title_index ], $alternative_titles, true );
		$title_key   = (int) filter_var( $title_key, FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $title_key ) ) {
			$_GET['titre']                                = $title_key;
			$_SESSION['navigation'][ $post->ID ]['titre'] = $title_key;
		} else {
			$_GET['titre']                                = 1;
			$_SESSION['navigation'][ $post->ID ]['titre'] = 1;

		}

		return $titles[ $title_index ];
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

	/**
	 * Filters the adjacent post link.
	 *
	 * The dynamic portion of the hook name, `$adjacent`, refers to the type
	 * of adjacency, 'next' or 'previous'.
	 *
	 * @since 2.6.0
	 * @since 4.2.0 Added the `$adjacent` parameter.
	 *
	 * @param string $output The adjacent post link.
	 * @param string $format Link anchor format.
	 * @param string $link Link permalink format.
	 * @param WP_Post $post The adjacent post.
	 * @param string $adjacent Whether the post is previous or next.
	 */
	public function adjacent_post_link( $output, $format, $link, $adjacent_post, $adjacent ) {

		global $post;
		//save current post markup_version
		$_SESSION['navigation'][ $post->ID ]['v'] = ! empty( $_GET['v'] ) ? $_GET['v'] : rand( 1, 3 );


		if ( $adjacent_post ) {

			$query_args['nav'] = true; // activate saving
			//if adjacent post doesn't have a 'v' set => use random
			$query_args['v'] = empty( $_SESSION['navigation'][ $adjacent_post->ID ]['v'] ) ? rand( 1, 3 ) : $_SESSION['navigation'][ $adjacent_post->ID ]['v'];

			$adjacent_post_link = get_permalink( $adjacent_post );
			$adjacent_post_link = add_query_arg( $query_args, $adjacent_post_link );

			$link_class = $adjacent === 'next' ? 'btn__primary' : 'btn__secondary';
			$rel        = $adjacent === 'previous' ? 'prev' : 'next';
			$string     = '<a class="btn btn-rounded ' . $link_class . '" href="' . $adjacent_post_link . '" rel="' . $rel . '">';

			$inlink = $string . $adjacent . '</a>';

			$output = str_replace( '%link', $inlink, $format );

			return preg_replace( '/href=".*?"/', 'href="' . esc_url( $adjacent_post_link ) . '"', $output );
		}
	}

	/**
	 * add related posts section to all single templates
	 */
	public function get_footer() {
		if ( is_single() ) {
			return Helpers::render( 'related-posts' );
		}
	}

}