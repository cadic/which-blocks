<?php
/**
 * Tools page
 *
 * @package Which_Blocks
 */

namespace WhichBlocks;

/**
 * Tools page class
 */
class Tools_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
	}

	/**
	 * Add page to the Tools menu
	 *
	 * @return void
	 */
	public function add_page() {
		add_management_page(
			__( 'Which Blocks', 'which-blocks' ),
			__( 'Which Blocks', 'which-blocks' ),
			'edit_posts',
			'which-blocks',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page
	 *
	 * @return void
	 */
	public function render_page() {
		$list_table = new Blocks_List_Table( array( 'screen' => 'which-blocks' ) );
		$list_table->prepare_items();

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Which Blocks', 'which-blocks' ); ?></h1>
			<?php $list_table->views(); ?>
			<form method="post" id="bulk-action-form">

			<input type="hidden" name="plugin_status" value="<?php echo esc_attr( $status ); ?>" />
			<input type="hidden" name="paged" value="<?php echo esc_attr( $page ); ?>" />

			<?php $list_table->display(); ?>

			</form>
		</div>
		<?php
	}
}
