<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Search;
use ACP\Sorting;

class Code extends Field {

	public function get_value( $id ) {
		return ac_helper()->html->codearea( parent::get_value( $id ) );
	}

	public function editing() {
		return new Editing\Textarea( $this->column() );
	}

	public function sorting() {
		return new Sorting\Model\Meta( $this->column() );
	}

	public function search() {
		return new Search\Comparison\Meta\Text( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

}