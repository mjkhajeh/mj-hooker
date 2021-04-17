<?php
namespace MJHooker\Backend;

class Page {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}

	public function menu() {
		add_menu_page(
			'Hooker', // $page_title:string
			'Hooker', // $menu_title:string
			'manage_options', // $capability:string
			'mjhooker', // $menu_slug:string
			array( $this, 'view' ), // $function:callable
			'dashicons-share-alt2', // $icon_url:string
			1 // $position:integer|null
		);
	}
}
Page::get_instance();