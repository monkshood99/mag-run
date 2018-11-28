<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Search;
use ACA\Pods\Setting;
use ACP\Filtering;
use ACP\Sorting;

class Date extends Field {

	public function get_value( $id ) {
		if ( ! parent::get_value( $id ) ) {
			return $this->column()->get_empty_char();
		}

		return $this->column->get_formatted_value( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return $this->get_single_raw_value( $id );
	}

	public function editing() {
		return new Editing\Date( $this->column );
	}

	public function sorting() {
		$model = new Sorting\Model\Meta( $this->column );
		$model->set_data_type( 'date' );

		return $model;
	}

	public function filtering() {
		return new Filtering\Model\MetaDate( $this->column );
	}

	public function search() {
		return new Search\Date( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

	public function get_dependent_settings() {
		return array(
			new Setting\Date( $this->column ),
		);
	}

}