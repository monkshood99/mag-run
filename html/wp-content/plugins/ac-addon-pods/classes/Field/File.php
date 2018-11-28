<?php

namespace ACA\Pods\Field;

use AC;
use AC\Collection;
use ACA\Pods\Editing;
use ACA\Pods\Export;
use ACA\Pods\Field;
use ACP\Search;

class File extends Field {

	public function get_value( $id ) {
		return $this->column->get_formatted_value( new Collection( $this->get_raw_value( $id ) ) );
	}

	public function get_raw_value( $id ) {
		return (array) $this->get_db_value( $id );
	}

	public function get_separator() {
		return ' ';
	}

	public function editing() {
		return new Editing\File( $this->column() );
	}

	public function export() {
		return new Export\File( $this->column() );
	}

	public function search() {
		return new Search\Comparison\Meta\Media( $this->column->get_meta_key(), $this->column->get_meta_type() );
	}

	public function get_dependent_settings() {
		$settings = array();

		switch ( $this->get_option( 'file_type' ) ) {
			case 'images' :
			case 'any' :
				$settings[] = new AC\Settings\Column\Image( $this->column() );

				break;
		}

		return $settings;
	}

}