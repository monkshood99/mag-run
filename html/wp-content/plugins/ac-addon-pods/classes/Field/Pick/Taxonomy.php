<?php

namespace ACA\Pods\Field\Pick;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;

class Taxonomy extends Field\Pick {

	public function get_raw_value( $id ) {
		return $this->get_ids_from_array( parent::get_raw_value( $id ), 'term_id' );
	}

	public function editing() {
		return new Editing\PickTaxonomy( $this->column() );
	}

	public function filtering() {
		return new Filtering\PickTaxonomy( $this->column() );
	}

	public function search() {
		return new Search\PickTaxonomy( $this->column()->get_meta_key(), $this->column()->get_meta_type(), $this->get_taxonomy() );
	}

	public function get_taxonomy() {
		return $this->get( 'pick_val' );
	}

}