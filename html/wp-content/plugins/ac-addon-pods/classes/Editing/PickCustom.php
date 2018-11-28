<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACA\Pods\Field\Pick\CustomSimple;

class PickCustom extends Editing {

	public function get_view_settings() {
		/** @var CustomSimple $field */
		$field = $this->column->get_field();

		return array(
			'type'         => 'select2_dropdown',
			'options'      => $field->get_options(),
			'clear_button' => ( $field->get_option( 'required' ) == 0 ),
			'multiple'     => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
		);
	}

}