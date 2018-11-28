<?php

namespace ACA\Pods\Field\Pick;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;

class NavMenu extends Field\Pick {

	public function get_raw_value( $id ) {
		return $this->get_ids_from_array( parent::get_raw_value( $id ), 'term_id' );
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

	public function get_options() {
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );

		if ( ! $menus || is_wp_error( $menus ) ) {
			return array();
		}

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

}