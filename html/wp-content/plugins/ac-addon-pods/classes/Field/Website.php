<?php

namespace ACA\Pods\Field;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Filtering;
use ACP\Search;
use ACP\Sorting;

class Website extends Field {

	public function get_value( $id ) {
		$field = $this->column()->get_pod_field();
		$target = $field['options']['website_new_window'] ? '_blank' : '_self';
		$url = parent::get_raw_value( $id );

		return ac_helper()->html->link( $url, str_replace( array( 'http://', 'https://' ), '', $url ), array( 'target' => $target ) );
	}

	public function editing() {
		return new Editing( $this->column() );
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