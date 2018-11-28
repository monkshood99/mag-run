<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Datetime extends Editing {

	public function get_edit_value( $id ) {
		$value = $this->column->get_raw_value( $id );

		if ( ! $value ) {
			return false;
		}

		if ( '0000-00-00 00:00:00' === $value ) {
			return false;
		}

		return $value;
	}

	public function get_view_settings() {
		$field = $this->column->get_field();
		$data = array(
			'type' => 'date_time',
		);

		if ( $field->get_option( 'datetime_allow_empty' ) ) {
			$data['clear_button'] = true;
		}

		return $data;
	}

}