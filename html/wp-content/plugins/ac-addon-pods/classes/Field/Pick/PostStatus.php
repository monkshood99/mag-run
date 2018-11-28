<?php

namespace ACA\Pods\Field\Pick;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;
use PodsField_Pick;

class PostStatus extends Field\Pick {

	public function editing() {
		return new Editing\Pick( $this->column() );
	}

	public function filtering() {
		return new Filtering\Pick( $this->column() );
	}

	public function search() {
		return new Search\Pick( $this->column()->get_meta_key(), $this->column()->get_meta_type(), $this->get_options() );
	}

	public function get_options() {
		if ( ! class_exists( 'PodsField_Pick', true ) ) {
			return array();
		}

		$pod = new PodsField_Pick();

		return $pod->data_post_stati();
	}

}