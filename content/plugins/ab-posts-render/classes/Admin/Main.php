<?php

namespace AB\Posts_Render\Admin;

use AB\Posts_Render\Singleton;

/**
 * Basic class for Admin
 *
 * Class Main
 * @package AB\Posts_Render\Admin
 */
class Main {
	/**
	 * Use the trait
	 */
	use Singleton;

	public function __construct() {
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	public function admin_init() {

		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'advanced-custom-fields/acf.php' ) && ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {

			add_action( 'admin_notices', [ $this, 'child_plugin_notice' ] );

			//optional : deactivate plugin
//			deactivate_plugins( AB_POSTS_RENDER_VIEWS_FOLDER_NAME.'/'.AB_POSTS_RENDER_VIEWS_FOLDER_NAME.'.php' );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}

	public function child_plugin_notice() {
		?>
        <div class="error"><p>Sorry, but The AB Posts render Plugin requires the ACF plugin to be installed and
                active.</p></div>
		<?php
	}
}


