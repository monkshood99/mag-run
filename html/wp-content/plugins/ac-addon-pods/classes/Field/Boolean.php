<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACP\Search;
use ACP\Sorting;

class Boolean extends Field {

	public function get_value( $id ) {
		$value = $this->get_single_raw_value( $id );

		return ac_helper()->icon->yes_or_no( '1' == $value );
	}

	public function editing() {
		return new Editing\TrueFalse( $this->column() );
	}

	public function sorting() {
		return new Sorting\Model\Meta( $this->column() );
	}

	public function filtering() {
		return new Filtering\TrueFalse( $this->column() );
	}

	public function search() {
		return new Search\Comparison\Meta\Checkmark( $this->column()->get_meta_key(), $this->column()->get_meta_type() );
	}

}