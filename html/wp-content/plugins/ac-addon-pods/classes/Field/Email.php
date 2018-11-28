<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Filtering;
use ACP\Search;
use ACP\Sorting;

class Email extends Field {

	public function editing() {
		return new Editing\Email( $this->column() );
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