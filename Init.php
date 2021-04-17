<?php
/*
Plugin Name: Hooker
Plugin URI: http://mjkhajeh.com
Description: Download plugins/themes from wordpress
Version: 1.0.0.0
Author: Mohammad Jafar Khajeh
*/
namespace MJHooker;

if (!defined('ABSPATH')) exit;

class Init {
	public static function get_instance() {
		static $instance = null;
		if($instance === null){
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 5 );
	}

	public function constants() {
		if( ! defined( 'MJHOOKER_DIR' ) )
			define( 'MJHOOKER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		if( ! defined( 'MJHOOKER_URI' ) )
			define( 'MJHOOKER_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	public function includes() {
		
	}
}
Init::get_instance();