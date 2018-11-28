<?php

namespace ACP\Search\Controller;

use AC\ListScreen;
use AC\Request;
use ACP\Helper\Select\Response;
use ACP\Search;
use ACP\Search\Controller;
use ACP\Search\Searchable;

class Comparison extends Controller {

	/**
	 * @var ListScreen
	 */
	protected $list_screen;

	/**
	 * @param Request    $request
	 * @param ListScreen $list_screen
	 */
	public function __construct( Request $request, ListScreen $list_screen ) {
		parent::__construct( $request );

		$this->list_screen = $list_screen;
	}

	public function get_options_action() {
		$column = $this->list_screen->get_column_by_name(
			$this->request->get( 'column', FILTER_SANITIZE_STRING )
		);

		if ( ! $column instanceof Searchable ) {
			$this->json_response( false );
		}

		$comparison = $column->search();

		switch ( true ) {
			case $comparison instanceof Search\Comparison\RemoteValues :
				$options = $comparison->get_values();
				$has_more = false;

				break;
			case $comparison instanceof Search\Comparison\SearchableValues :
				$options = $comparison->get_values(
					$this->request->get( 'searchterm' ),
					$this->request->get( 'page', FILTER_SANITIZE_NUMBER_INT )
				);
				$has_more = ! $options->is_last_page();

				break;
			default :
				wp_die();
		}

		$response = new Response( $options, $has_more );

		$this->json_response( true, $response() );
	}

}