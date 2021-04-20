<?php
/*
Plugin Name: Hooker
Plugin URI: https://wordpress.org/plugins/hooker
Description: Download plugins/themes from wordpress
Version: 1.0.0.0
Author: MohammadJafar Khajeh
Author URI: http://mjkhajeh.com
Text Domain: mjhooker
Domain Path: /languages
*/
/**
 * TO DO:
 * 		Support mu-plugins
 * 		Translate Hooker
 * 		Translate plugin/theme info
 */
namespace MJHooker;

if( !defined( 'ABSPATH' ) ) exit;

class Init {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 5 );
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
	}

	public function constants() {
		if( ! defined( 'MJHOOKER_DIR' ) )
			define( 'MJHOOKER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		if( ! defined( 'MJHOOKER_URI' ) )
			define( 'MJHOOKER_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	public function includes() {
		if( is_admin() ) {
			include_once( MJHOOKER_DIR . 'AJAX.php' );
			include_once( MJHOOKER_DIR . 'Backend/Page.php' );
		}
	}

	public function i18n() {
		// Load languages
		load_plugin_textdomain( 'mjhooker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function admin_enqueue() {
		wp_enqueue_style( 'mjhooker-css', MJHOOKER_URI . "assets/css/mjhooker.backend.css" );
		wp_enqueue_script( 'mjhooker-js', MJHOOKER_URI . "assets/js/mjhooker.backend.js", array( 'jquery' ), false, true );
		wp_localize_script( 'mjhooker-js', 'mjhooker', array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
		) );
	}
}
Init::get_instance();