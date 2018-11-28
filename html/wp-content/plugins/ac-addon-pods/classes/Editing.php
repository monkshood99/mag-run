<?php

namespace ACA\Pods;

use ACP;

/**
 * @property Column $column
 */
class Editing extends ACP\Editing\Model {

	public function __construct( Column $column ) {
		parent::__construct( $column );
	}

	public function get_view_settings() {
		$data = array(
			'type' => 'text',
		);

		return $data;
	}

	public function save( $id, $value ) {
		$field = $this->column->get_field();

		$pod = pods( $field->get( 'pod' ), $id, true );
		$pod->save( array( $field->get( 'name' ) => $value ) );

		return true;
	}

}