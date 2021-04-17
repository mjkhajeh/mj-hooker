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
			'Hooker',				// $page_title:string
			'Hooker',				// $menu_title:string
			'manage_options',		// $capability:string
			'mjhooker',				// $menu_slug:string
			array( $this, 'view' ),	// $function:callable
			'dashicons-share-alt2',	// $icon_url:string
			1						// $position:integer|null
		);
	}

	public function view() {
		$plugins = get_plugins();
		$themes = wp_get_themes();
		?>
		<div class="wrap">
			<h1>Hooker</h1>
			<p class="description">Just click on plugin or theme what you want to download the zip file</p>

			<?php if( !empty( $plugins ) ) { ?>
				<h2>Plugins</h2>
				<div class="mjhooker_table_container">
					<div class="mjhooker_overlay"></div>
					<table class="mjhooker_table" id="mjhooker_plugins">
						<thead>
							<tr>
								<th>Title</th>
								<th>Version</th>
								<th>Author</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach( $plugins as $path => $plugin ) {
								$path = dirname( $path );
								?>
								<tr data-dir="<?php echo $path ?>" data-type="plugin">
									<td>
										<a href="#" class="mjhooker_dl"><?php echo $plugin['Name'] ?></a>
									</td>
									<td><?php echo $plugin['Version'] ?></td>
									<td><?php echo $plugin['AuthorName'] ?></td>
									<td>
										<?php if( file_exists( WP_PLUGIN_DIR . "/{$path}.zip" ) ) { ?>
											<a href="#" class="mjhooker_del"><i class="dashicons dashicons-trash"></i></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>

			<?php if( !empty( $themes ) ) { ?>
				<h2>Themes</h2>
				<div class="mjhooker_table_container">
					<div class="mjhooker_overlay"></div>
					<table class="mjhooker_table" id="mjhooker_themes">
						<thead>
							<tr>
								<th>Title</th>
								<th>Version</th>
								<th>Author</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach( $themes as $theme ) {
								$path = basename( $theme->get_stylesheet_directory() );
								?>
								<tr data-dir="<?php echo $path ?>" data-type="theme">
									<td>
										<a href="#" class="mjhooker_dl"><?php echo $theme->get( 'Name' ) ?></a>
									</td>
									<td><?php echo $theme->get( 'Version' ) ?></td>
									<td><?php echo $theme->get( 'Author' ) ?></td>
									<td>
										<?php if( file_exists( WP_CONTENT_DIR . "/themes/{$path}.zip" ) ) { ?>
											<a href="#" class="mjhooker_del"><i class="dashicons dashicons-trash"></i></a>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
Page::get_instance();