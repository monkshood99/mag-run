<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Editing\PaginatedOptions;
use ACP\Helper\Select;

class PickTaxonomy extends Editing implements PaginatedOptions {

	public function get_edit_value( $id ) {
		$field = $this->get_field();

		if ( ! $field ) {
			return false;
		}

		$term_ids = $this->column->get_raw_value( $id );

		if ( ! $term_ids ) {
			return false;
		}

		$values = array();
		foreach ( (array) $term_ids as $term_id ) {
			$term = get_term_by( 'id', $term_id, $field->get_taxonomy() );

			if ( $term ) {
				$values[ $term->term_id ] = htmlspecialchars_decode( $term->name );
			}
		}

		return $values;
	}

	/**
	 * @return array|bool
	 */
	public function get_view_settings() {
		$field = $this->get_field();

		if ( ! $field ) {
			return false;
		}

		$settings = array(
			'type'          => 'select2_dropdown',
			'ajax_populate' => true,
			'clear_button'  => ( 0 == $field->get_option( 'required' ) ),
			'multiple'      => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
		);

		return $settings;
	}

	public function get_paginated_options( $search, $page, $id = null ) {
		$entities = new Select\Entities\Taxonomy( array(
			'search'   => $search,
			'page'     => $page,
			'taxonomy' => $this->get_field()->get_taxonomy(),
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\Taxonomy(
				new Select\Formatter\TermName( $entities )
			)
		);
	}

	/**
	 * @return Field\Pick\Taxonomy|false
	 */
	private function get_field() {
		$field = $this->column->get_field();

		if ( ! $field instanceof Field\Pick\Taxonomy ) {
			return false;
		}

		return $field;
	}

}