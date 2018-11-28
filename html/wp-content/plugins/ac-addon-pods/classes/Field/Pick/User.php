<?php

namespace ACA\Pods\Field\Pick;

use AC\Collection;
use AC\Settings;
use ACA\Pods\Editing;
use ACA\Pods\Field;
use ACA\Pods\Filtering;
use ACA\Pods\Search;

class User extends Field\Pick {

	public function get_value( $id ) {
		return $this->column->get_formatted_value( new Collection( $this->get_raw_value( $id ) ) );
	}

	public function get_raw_value( $id ) {
		return (array) $this->get_ids_from_array( parent::get_raw_value( $id ) );
	}

	public function editing() {
		return new Editing\PickUsers( $this->column() );
	}

	public function filtering() {
		return new Filtering\PickUsers( $this->column() );
	}

	public function search() {
		return new Search\PickUser( $this->column()->get_meta_key(), $this->column()->get_meta_type(), $this->get_option( 'pick_user_role' ) );
	}

	public function get_users( $user_ids ) {
		$names = array();

		if ( empty( $user_ids ) ) {
			return false;
		}

		foreach ( (array) $user_ids as $k => $user_id ) {
			if ( ! $user_id ) {
				continue;
			}
			$names[ $user_id ] = ac_helper()->user->get_display_name( $user_id );
		}

		return $names;
	}

	public function get_dependent_settings() {
		return array(
			new Settings\Column\User( $this->column() ),
		);
	}

}