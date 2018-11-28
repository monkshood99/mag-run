<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACP\Editing\PaginatedOptions;
use ACP\Helper\Select;

class PickUsers extends Editing implements PaginatedOptions {

	public function get_edit_value( $id ) {
		$field = $this->column->get_field();

		if ( ! $field instanceof Field\Pick\User ) {
			return null;
		}

		return $field->get_users( parent::get_edit_value( $id ) );
	}

	/**
	 * @return array
	 */
	public function get_view_settings() {
		return array(
			'type'            => 'select2_dropdown',
			'formatted_value' => 'user',
			'ajax_populate'   => true,
			'clear_button'    => ( $this->column->get_field()->get_option( 'required' ) == 0 ),
			'multiple'        => ( 'multi' == $this->column->get_field()->get_option( 'pick_format_type' ) ),
		);
	}

	public function get_paginated_options( $search, $paged, $id = null ) {
		$entities = new Select\Entities\User( array(
			'paged'    => $paged,
			'search'   => $search,
			'role__in' => $this->column->get_field()->get_option( 'pick_user_role' ),
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\UserRole(
				new Select\Formatter\UserName( $entities )
			)
		);
	}

}