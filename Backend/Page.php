<?php
namespace MJHooker\Backend;

class Page {
	private $active_tab = '';
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

	private function tab_content_creator( $args ) {
		?>
		<div class="tab-content" id="mjhooker_tab_<?php echo $args['tab'] ?>"<?php echo $this->active_tab != $args['tab'] ? ' style="display:none"' : '' ?>>
			<h2><?php echo $args['title'] ?></h2>
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
						foreach( $args['data'] as $data ) {
							?>
							<tr data-dir="<?php echo $data['dir'] ?>" data-type="<?php echo $args['data_type'] ?>">
								<td>
									<a href="#" class="mjhooker_dl"><?php echo $data['name'] ?></a>
								</td>
								<td><?php echo $data['version'] ?></td>
								<td><?php echo $data['author_name'] ?></td>
								<td>
									<?php if( file_exists( "{$args['base_dir']}/{$data['dir']}.zip" ) ) { ?>
										<a href="#" class="mjhooker_del"><i class="dashicons dashicons-trash"></i></a>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	public function view() {
		$plugins	= get_plugins();
		$themes		= wp_get_themes();

		$tabs = array();
		if( !empty( $plugins ) ) {
			$tabs['plugins'] = __( "Plugins", 'mjhooker' );
		}
		if( !empty( $themes ) ) {
			$tabs['themes'] = __( "Themes", 'mjhooker' );
		}

		if( !empty( $tabs ) ) {
			$this->active_tab = array_keys( $tabs )[0]; // Select first tab
			if( !empty( $_GET['tab'] ) ) {
				$this->active_tab = sanitize_text_field( $_GET['tab'] );
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
						<a href="#" class="nav-tab mjhooker_tab<?php echo $this->active_tab == $tab ? ' nav-tab-active' : '' ?>" data-tab="<?php echo $tab ?>"><?php echo $title ?></a>
						<?php
					}
				}
				?>
			</nav>
			<?php
			if( !empty( $plugins ) ) {
				$plugins_data = array();
				foreach( $plugins as $path => $plugin ) {
					$plugin = get_plugin_data( WP_PLUGIN_DIR . "/{$path}" );
					$plugins_data[] = array(
						'dir'			=> dirname( $path ),
						'name'			=> $plugin['Name'],
						'version'		=> $plugin['Version'],
						'author_name'	=> $plugin['AuthorName'],
					);
				}
				$this->tab_content_creator( array(
					'tab'		=> 'plugins',
					'title'		=> __( 'Plugins', 'mjhooker' ),
					'data_type'	=> 'plugins',
					'base_dir'	=> WP_PLUGIN_DIR,
					'data'		=> $plugins_data
				) );
			}

			if( !empty( $themes ) ) {
				$themes_data = array();
				foreach( $themes as $theme ) {
					$themes_data[] = array(
						'dir'			=> basename( $theme->get_stylesheet_directory() ),
						'name'			=> __( $theme->get( 'Name' ), $theme->get( 'TextDomain' ) ),
						'version'		=> __( $theme->get( 'Version' ), $theme->get( 'TextDomain' ) ),
						'author_name'	=> __( $theme->get( 'Author' ), $theme->get( 'TextDomain' ) ),
					);
				}
				$this->tab_content_creator( array(
					'tab'		=> 'themes',
					'title'		=> __( 'Themes', 'mjhooker' ),
					'data_type'	=> 'themes',
					'base_dir'	=> WP_CONTENT_DIR . "/themes",
					'data'		=> $themes_data
				) );
			}
			?>
		</div>
		<?php
	}
}
Page::get_instance();