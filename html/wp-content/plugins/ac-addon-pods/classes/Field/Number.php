<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACP\Search;
use ACP\Sorting;

class Number extends Field {

	public function editing() {
		return new Editing\Number( $this->column() );
	}

	public function filtering() {
		return new Filtering\Number( $this->column() );
	}

	public function sorting() {
		$model = new Sorting\Model\Meta( $this->column() );

		return $model->set_data_type( 'numeric' );
	}

	public function search() {
		return new Search\Comparison\Meta\Numeric( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

}