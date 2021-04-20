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
			__( 'Hooker', 'mjhooker' ),	// $page_title:string
			__( 'Hooker', 'mjhooker' ),	// $menu_title:string
			'manage_options',			// $capability:string
			'mjhooker',					// $menu_slug:string
			array( $this, 'view' ),		// $function:callable
			'dashicons-share-alt2',		// $icon_url:string
			1							// $position:integer|null
		);
	}

	public function view() {
		$plugins = get_plugins();
		$themes = wp_get_themes();

		$tabs = array();
		if( !empty( $plugins ) ) {
			$tabs['plugins'] = __( "Plugins", 'mjhooker' );
		}
		if( !empty( $themes ) ) {
			$tabs['themes'] = __( "Themes", 'mjhooker' );
		}
		if( !empty( $tabs ) ) {
			$active_tab = array_keys( $tabs )[0]; // Select first tab
			if( !empty( $_GET['tab'] ) ) {
				$active_tab = sanitize_text_field( $_GET['tab'] );
			}
		}
		?>
		<div class="wrap">
			<h1><?php _e( "Hooker", 'mjhooker' ) ?></h1>
			<p class="description"><?php _e( "Just click on plugin or theme what you want to download the zip file", 'mjhooker' ) ?></p>
			<nav class="nav-tab-wrapper">
				<?php
				if( !empty( $tabs ) ) {
					foreach( $tabs as $tab => $title ) {
						?>
						<a href="#" class="nav-tab mjhooker_tab<?php echo $active_tab == $tab ? ' nav-tab-active' : '' ?>" data-tab="<?php echo $tab ?>"><?php echo $title ?></a>
						<?php
					}
				}
				?>
			</nav>
			<?php if( !empty( $plugins ) ) { ?>
				<div class="tab-content" id="mjhooker_tab_plugins"<?php echo $active_tab != 'plugins' ? ' style="display:none"' : '' ?>>
					<h2><?php _e( "Plugins", 'mjhooker' ) ?></h2>
					<div class="mjhooker_table_container">
						<div class="mjhooker_overlay"></div>
						<table class="mjhooker_table" id="mjhooker_plugins">
							<thead>
								<tr>
									<th><?php _e( "Title", 'mjhooker' ) ?></th>
									<th><?php _e( "Version", 'mjhooker' ) ?></th>
									<th><?php _e( "Author", 'mjhooker' ) ?></th>
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
				</div>
			<?php } ?>

			<?php if( !empty( $themes ) ) { ?>
				<div class="tab-content" id="mjhooker_tab_themes"<?php echo $active_tab != 'themes' ? ' style="display:none"' : '' ?>>
					<h2><?php _e( "Themes", 'mjhooker' ) ?></h2>
					<div class="mjhooker_table_container">
						<div class="mjhooker_overlay"></div>
						<table class="mjhooker_table" id="mjhooker_themes">
							<thead>
								<tr>
									<th><?php _e( "Title", 'mjhooker' ) ?></th>
									<th><?php _e( "Version", 'mjhooker' ) ?></th>
									<th><?php _e( "Author", 'mjhooker' ) ?></th>
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
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
Page::get_instance();