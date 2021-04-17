<?php
namespace MJHooker;

class AJAX {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		add_action( 'wp_ajax_mjhooker_dl', array( $this, 'dl' ) );
		add_action( 'wp_ajax_mjhooker_del', array( $this, 'del' ) );
	}

	public function dl() {
		if( !empty( $_REQUEST['dir'] ) && !empty( $_REQUEST['type'] ) ) {
			$dir = sanitize_text_field( $_REQUEST['dir'] );
			$type = sanitize_text_field( $_REQUEST['type'] );
			if( $type == 'plugin' || $type = 'theme' ) {
				if( $type == 'plugin' ) {
					$base_dir = WP_PLUGIN_DIR . "/";
					$base_uri = WP_PLUGIN_URL . "/";
				} else {
					$base_dir = WP_CONTENT_DIR . "/themes/";
					$base_uri = WP_CONTENT_URL . "/themes/";
				}
				if( !file_exists( "{$base_dir}{$dir}.zip" ) ) {
					include_once( MJHOOKER_DIR . "Libraries/HZip.php" );
					HZip::zipDir( "{$base_dir}{$dir}", "{$base_dir}{$dir}.zip" );
				}
				wp_send_json_success( "{$base_uri}{$dir}.zip" );
			}
		}
		die;
	}

	public function del() {
		if( !empty( $_REQUEST['dir'] ) && !empty( $_REQUEST['type'] ) ) {
			$dir = sanitize_text_field( $_REQUEST['dir'] );
			$type = sanitize_text_field( $_REQUEST['type'] );
			if( $type == 'plugin' || $type = 'theme' ) {
				if( $type == 'plugin' ) {
					$base_dir = WP_PLUGIN_DIR . "/";
					$base_uri = WP_PLUGIN_URL . "/";
				} else {
					$base_dir = WP_CONTENT_DIR . "/themes/";
					$base_uri = WP_CONTENT_URL . "/themes/";
				}
				if( file_exists( "{$base_dir}{$dir}.zip" ) ) {
					unlink( "{$base_dir}{$dir}.zip" );
					wp_send_json_success();
				}
			}
		}
		die;
	}
}
AJAX::get_instance();