<?php

namespace ACA\Pods\Field\Pick;

use AC\Collection;
use AC\Settings;
use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;

class PostType extends Field\Pick {

	public function get_value( $id ) {
		return $this->column->get_formatted_value( new Collection( $this->get_raw_value( $id ) ) );
	}

	public function get_raw_value( $id ) {
		return $this->get_db_value( $id );
	}

	public function editing() {
		return new Editing\PickPosts( $this->column );
	}

	public function filtering() {
		return new Filtering\PickPosts( $this->column );
	}

	public function search() {
		return new Search\PickPost( $this->column()->get_meta_key(), $this->column()->get_meta_type(), $this->get_option( 'pick_val' ) );
	}

	public function get_dependent_settings() {
		return array(
			new Settings\Column\Post( $this->column ),
		);
	}

}
