<?php

namespace ACP\Search\Controller;

use AC\ListScreen;
use AC\Preferences;
use AC\Request;
use ACP\Search\Controller;
use ACP\Search\Middleware;
use ACP\Search\Segments;

class Segment extends Controller {

	/**
	 * @var ListScreen
	 */
	protected $list_screen;

	/**
	 * @var Middleware\Rules
	 */
	protected $rules;

	/**
	 * @var Segments
	 */
	protected $segments;

	/**
	 * @param Request          $request
	 * @param ListScreen       $list_screen
	 * @param Middleware\Rules $rules
	 */
	public function __construct( Request $request, ListScreen $list_screen, Middleware\Rules $rules ) {
		parent::__construct( $request );

		$this->list_screen = $list_screen;
		$this->rules = $rules;
		$this->segments = new Segments(
			new Preferences\User( 'search_segments_' . $this->list_screen->get_layout_id() )
		);
	}

	/**
	 * @param array $data
	 */
	protected function handle_segments_response( $data = array() ) {
		$errors = array(
			Segments::ERROR_DUPLICATE_NAME => __( 'A segment with this name already exists.', 'codepress-admin-columns' ),
			Segments::ERROR_NAME_NOT_FOUND => __( 'Could not find current segment.', 'codepress-admin-columns' ),
			Segments::ERROR_SAVING         => __( 'Could save the segment.', 'codepress-admin-columns' ),
		);

		if ( $this->segments->has_errors() ) {
			$this->json_response( false, $errors[ $this->segments->get_first_error() ] );
		}

		$this->json_response( true, $data );
	}

	/**
	 * @param Segments\Segment $segment
	 *
	 * @return array
	 */
	protected function get_segment_response( Segments\Segment $segment ) {
		$rules = $this->rules;
		$url = add_query_arg(
			array(
				'ac-rules'   => urlencode( json_encode( $rules( $segment->get_value( 'rules' ) ) ) ),
				'order'      => $segment->get_value( 'order' ),
				'orderby'    => $segment->get_value( 'orderby' ),
				'ac-segment' => urlencode( $segment->get_name() ),
			),
			$this->list_screen->get_screen_link()
		);

		return array(
			'name' => $segment->get_name(),
			'url'  => $url,
		);
	}

	public function read_action() {
		$response = array();

		foreach ( $this->segments->get_segments() as $segment ) {
			$response[] = $this->get_segment_response( $segment );
		}

		$this->json_response( true, $response );
	}

	public function create_action() {
		$name = $this->request->get( 'name', FILTER_SANITIZE_STRING );

		$data = array(
			'rules'   => $this->request->get( 'rules', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ),
			'order'   => $this->request->get( 'order', FILTER_SANITIZE_STRING ),
			'orderby' => $this->request->get( 'orderby', FILTER_SANITIZE_STRING ),
		);

		$segment = new Segments\Segment( $name, $data );
		$this->segments
			->add_segment( $segment )
			->save();

		$this->handle_segments_response( array(
			'segment' => $this->get_segment_response( $segment ),
		) );
	}

	public function delete_action() {
		$name = $this->request->get( 'name', FILTER_SANITIZE_STRING );

		$this->segments
			->remove_segment( $name )
			->save();

		$this->handle_segments_response();
	}

}