<?php

namespace ACP\Search;

use AC;
use AC\ListScreenFactory;
use ACP\Search\Middleware;

final class Addon extends AC\Addon
	implements AC\Registrable {

	/**
	 * @var AC\Request
	 */
	private $request;

	public function __construct() {
		AC\Autoloader::instance()->register_prefix( __NAMESPACE__, $this->get_dir() . 'classes' );

		$request = new AC\Request();
		$request->add_middleware( new Middleware\Request() );
		$this->request = $request;

		$table_options = new TableScreenOptions( $this );
		$table_options->register();
	}

	public function register() {
		$settings = new Settings( $this );
		$settings->register();

		add_action( 'ac/screen', array( $this, 'table_screen_request' ), 5 );
		add_action( 'wp_ajax_acp_search_segment_request', array( $this, 'segment_request' ) );
		add_action( 'wp_ajax_acp_search_comparison_request', array( $this, 'comparison_request' ) );
	}

	/**
	 * @return AC\ListScreen
	 */
	private function get_list_screen() {
		check_ajax_referer( 'ac-ajax' );

		$list_screen = ListScreenFactory::create(
			$this->request->get( 'list_screen', FILTER_SANITIZE_STRING ),
			$this->request->get( 'layout', FILTER_SANITIZE_STRING )
		);

		if ( ! $list_screen ) {
			wp_die();
		}

		return $list_screen;
	}

	/**
	 * @param Controller $controller
	 */
	private function dispatch_request( Controller $controller ) {
		$method = $this->request->get( 'method' ) . '_action';

		if ( ! method_exists( $controller, $method ) ) {
			wp_die();
		}

		call_user_func( array( $controller, $method ) );
	}

	public function segment_request() {
		$this->dispatch_request(
			new Controller\Segment(
				$this->request,
				$this->get_list_screen(),
				new Middleware\Rules()
			)
		);
	}

	public function comparison_request() {
		$this->dispatch_request(
			new Controller\Comparison(
				$this->request,
				$this->get_list_screen()
			)
		);
	}

	/**
	 * @param AC\Screen $screen
	 */
	public function table_screen_request( AC\Screen $screen ) {
		$list_screen = $screen->get_list_screen();
		$table_options = new TableScreenOptions( $this );

		if ( ! $list_screen ) {
			return;
		}

		if( ! $table_options->is_active( $list_screen ) ){
			return;
		}

		$table_screen = TableScreenFactory::create( $this, $list_screen, $this->request );

		if ( ! $table_screen ) {
			return;
		}

		$table_screen->register();
	}

	/**
	 * @return string
	 */
	protected function get_file() {
		return __FILE__;
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return ACP()->get_version();
	}

}