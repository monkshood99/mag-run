<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class PickMedia extends Editing {

	public function get_view_settings() {
		$field = $this->column->get_field();

		$data = array(
			'type'         => 'media',
			'clear_button' => ( $field->get_option( 'required' ) == 0 ),
			'multiple'     => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
		);

		return $data;
	}

}