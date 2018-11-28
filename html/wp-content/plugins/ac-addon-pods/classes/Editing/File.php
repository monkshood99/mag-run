<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class File extends Editing {

	public function get_edit_value( $id ) {
		return (array) $this->column->get_raw_value( $id );
	}

	public function get_view_settings() {
		$field = $this->column->get_field();

		$data = array(
			'type'         => 'media',
			'clear_button' => ( 0 == $field->get_option( 'required' ) ),
			'multiple'     => ( 'multi' === $field->get_option( 'file_format_type' ) ),
			'store_values' => true,
		);

		switch ( $field->get_option( 'file_type' ) ) {
			case 'images':
				$data['attachment']['library']['type'] = 'image';
				break;
			case 'video':
				$data['attachment']['library']['type'] = 'image';
				break;
			case 'audio':
				$data['attachment']['library']['type'] = 'audio';
				break;
		}

		return $data;
	}

	public function save( $id, $value ) {
		$data = false;
		foreach ( (array) $value as $attachment_id ) {
			$data[ $attachment_id ] = array(
				'id'    => $attachment_id,
				'title' => get_the_title( $attachment_id ),
			);
		}

		parent::save( $id, $data );
	}

}