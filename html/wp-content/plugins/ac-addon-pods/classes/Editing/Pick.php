<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACA\Pods\Field;

class Pick extends Editing {

	public function get_view_settings() {
		$field = $this->column->get_field();

		if ( ! $field instanceof Field\Pick ) {
			return array();
		}

		$settings = array(
			'type'         => 'select2_dropdown',
			'options'      => $field->get_options(),
			'clear_button' => ( 0 == $field->get_option( 'required' ) ),
			'multiple'     => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
			'store_values' => true,
		);

		return $settings;
	}

	public function get_edit_value( $id ) {
		return (array) parent::get_edit_value( $id );
	}

}