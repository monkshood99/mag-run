<?php

namespace ACP\Search;

use AC\Request;

abstract class Controller {

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Send JSON as response
	 *
	 * @param bool  $result
	 * @param mixed $data
	 */
	protected function json_response( $result, $data = null ) {
		$result
			? wp_send_json_success( $data )
			: wp_send_json_error( $data );
	}

}