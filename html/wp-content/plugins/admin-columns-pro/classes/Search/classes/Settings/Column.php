<?php

namespace ACP\Search\Settings;

use AC;
use AC\View;
use ACP;

class Column extends AC\Settings\Column {

	/**
	 * @return array
	 */
	protected function define_options() {
		return array(
			'search',
		);
	}

	/**
	 * @return string
	 */
	private function get_instructions() {
		ob_start();

		$id = "pointer-smart-filtering-" . $this->column->get_name();

		?>
		<a href="#" class="ac-pointer" rel="<?= $id; ?>" data-pos="right" data-width="370"><?php _e( 'What is smart filtering?', 'codepress-admin-columns' ); ?></a>
		<div id="<?= $id; ?>" style="display:none;">
			<h3><?php _e( 'Smart Filtering', 'codepress-admin-columns' ); ?></h3>
			<p><?php echo _x( 'Smart filtering allows you to segment your data by different criteria.', 'smart filtering help', 'codepress-admin-columns'); ?></p>
			<p><?php echo _x( 'Click on the <strong>Add Filter</strong> button and select a column and the criteria you want to filter on. You can add as many filters as you like. ', 'smart filtering help', 'codepress-admin-columns'); ?></p>
			<img src="<?php echo ACP()->get_url() . 'assets/'; ?>images/smart-filtering.png" alt="Smart filtering">
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$view = new View();
		$view->set( 'label', __( 'Smart Filtering', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Smart filtering is always enabled.', 'codepress-admin-columns' ) )
		     ->set( 'setting',
			     sprintf( '<em>%s</em>', __( 'Enabled.', 'codepress-admin-columns' ) ) . ' ' . $this->get_instructions()
		     );

		return $view;
	}

	/**
	 * @return bool True when search is selected
	 */
	public function is_active() {
		return apply_filters( 'acp/search/smart-filtering-active', true, $this );
	}

}