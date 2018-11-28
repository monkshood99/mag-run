<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Date extends Editing {

	public function get_edit_value( $id ) {
		$value = $this->column->get_raw_value( $id );

		if ( ! $value ) {
			return false;
		}

		if ( '0000-00-00' === $value ) {
			return false;
		}

		// TODO: does not work when only year as format is set in the pod

		return date( 'Ymd', strtotime( $value ) );
	}

	public function get_view_settings() {
		return array(
			'type' => 'date',
		);
	}

}