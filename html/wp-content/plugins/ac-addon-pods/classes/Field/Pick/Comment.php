<?php

namespace ACA\Pods\Field\Pick;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Search;

class Comment extends Field\Pick {

	public function get_raw_value( $id ) {
		return $this->get_ids_from_array( parent::get_raw_value( $id ), 'comment_ID' );
	}

	public function editing() {
		return new Editing\PickComments( $this->column() );
	}

	public function search() {
		return new Search\PickComment( $this->column()->get_meta_key(), $this->column()->get_meta_type() );
	}

}