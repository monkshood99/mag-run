<?php

namespace ACA\Pods\Field\Pick;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;

class Country extends Field\Pick {

	public function get_options() {
		return parent::get_pick_field()->data_countries();
	}

	public function editing() {
		return new Editing\Pick( $this->column() );
	}

	public function filtering() {
		return new Filtering\Pick( $this->column() );
	}

	public function search() {
		return new Search\Pick( $this->column()->get_meta_key(), $this->column()->get_meta_type(), $this->get_options() );
	}

}