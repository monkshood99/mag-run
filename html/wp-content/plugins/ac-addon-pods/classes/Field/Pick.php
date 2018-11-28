<?php

namespace ACA\Pods\Field;

use ACA\Pods\Field;
use ACP\Sorting;
use ACP\Export;
use PodsField_Pick;

class Pick extends Field {

	public function get_options() {
		return array();
	}

	protected function get_pick_field() {
		return new PodsField_Pick();
	}

	public function sorting() {
		return new Sorting\Model\Value( $this->column );
	}

	public function export() {
		return new Export\Model\StrippedValue( $this->column() );
	}

	protected function get_ids_from_array( $array, $id_name = 'ID' ) {
		$ids = array();

		if ( ! is_array( $array ) ) {
			return false;
		}

		if ( isset( $array[0] ) ) {
			$ids = wp_list_pluck( $array, $id_name );
		}

		if ( array_key_exists( $id_name, $array ) ) {
			$ids = array( $array[ $id_name ] );
		}

		return $ids;
	}

}