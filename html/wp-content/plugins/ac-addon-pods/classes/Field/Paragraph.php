<?php

namespace ACA\Pods\Field;

use AC\Settings;
use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Search;
use ACP\Sorting;

class Paragraph extends Field {

	public function get_value( $id ) {
		return $this->column->get_formatted_value( parent::get_value( $id ) );
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

	public function get_dependent_settings() {
		return array(
			new Settings\Column\WordLimit( $this->column() ),
		);
	}

}