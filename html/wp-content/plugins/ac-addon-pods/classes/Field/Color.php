<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Filtering;
use ACP\Search;
use ACP\Sorting;

class Color extends Field {

	public function get_value( $id ) {
		return ac_helper()->string->get_color_block( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return $this->get_single_raw_value( $id );
	}

	public function editing() {
		return new Editing\Color( $this->column() );
	}

	public function sorting() {
		return new Sorting\Model\Meta( $this->column() );
	}

	public function filtering() {
		return new Filtering\Model\Meta( $this->column() );
	}

	public function search() {
		return new Search\Comparison\Meta\Text( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

}