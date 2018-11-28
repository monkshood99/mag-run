<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Search;
use ACA\Pods\Setting;
use ACP\Sorting;

class Datetime extends Field {

	public function get_value( $id ) {
		if ( ! parent::get_value( $id ) ) {
			return $this->column()->get_empty_char();
		}

		return $this->column->get_formatted_value( $this->get_raw_value( $id ) );
	}

	public function sorting() {
		return new Sorting\Model\Meta( $this->column() );
	}

	public function editing() {
		return new Editing\Datetime( $this->column() );
	}

	public function search() {
		return new Search\DateTime( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

	public function get_dependent_settings() {
		return array(
			new Setting\Date( $this->column() ),
		);
	}

}