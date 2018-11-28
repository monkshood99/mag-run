<?php

namespace ACA\Pods;

use AC;
use ACP;

class Field
	implements ACP\Search\Searchable {

	/**
	 * @var Column
	 */
	protected $column;

	public function __construct( Column $column ) {
		$this->column = $column;
	}

	public function filtering() {
		return new Filtering\Disabled( $this->column() );
	}

	public function editing() {
		return new Editing\Disabled( $this->column() );
	}

	public function sorting() {
		return new Sorting\Disabled( $this->column() );
	}

	public function export() {
		return new ACP\Export\Model\RawValue( $this->column() );
	}

	public function search() {
		return false;
	}

	/**
	 * @return AC\Settings\Column[]
	 */
	public function get_dependent_settings() {
		return array();
	}

	/**
	 * @return Column
	 */
	protected function column() {
		return $this->column;
	}

	public function get_value( $id ) {
		return pods_field_display( $this->get( 'pod' ), $id, $this->get( 'name' ) );
	}

	public function get_raw_value( $id ) {
		return pods_field_raw( $this->get( 'pod' ), $id, $this->get( 'name' ), true );
	}

	protected function get_single_raw_value( $id ) {
		$raw_value = self::get_raw_value( $id );

		if ( is_array( $raw_value ) && isset( $raw_value[0] ) ) {
			$raw_value = $raw_value[0];
		}

		return $raw_value;
	}

	/**
	 * Get the raw DB value
	 *
	 * @param int $id
	 *
	 * @return array|false
	 */
	protected function get_db_value( $id ) {
		global $wpdb;

		switch ( $this->column->get_meta_type() ) {
			case AC\MetaType::POST:
				$sql = $wpdb->prepare(
					"
					SELECT {$wpdb->postmeta}.meta_value 
					FROM {$wpdb->postmeta} 
					WHERE meta_key = %s 
					AND post_id = %d
				", $this->column->get_meta_key(), $id );

				break;
			case AC\MetaType::USER:
				$sql = $wpdb->prepare(
					"
					SELECT {$wpdb->usermeta}.meta_value 
					FROM {$wpdb->usermeta} 
					WHERE meta_key = %s 
					AND user_id = %d
				", $this->column->get_meta_key(), $id );

				break;
			case AC\MetaType::COMMENT:
				$sql = $wpdb->prepare(
					"
					SELECT {$wpdb->commentmeta}.meta_value 
					FROM {$wpdb->commentmeta} 
					WHERE meta_key = %s 
					AND comment_id = %d
				", $this->column->get_meta_key(), $id );

				break;
			case AC\MetaType::TERM:
				$sql = $wpdb->prepare(
					"
					SELECT {$wpdb->termmeta}.meta_value 
					FROM {$wpdb->termmeta} 
					WHERE meta_key = %s 
					AND term_id = %d
				", $this->column->get_meta_key(), $id );

				break;
			default :
				$sql = false;
		}

		if ( ! $sql ) {
			return false;
		}

		return $wpdb->get_col( $sql );
	}

	public function get_separator() {
		return null;
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|false
	 */
	public function get( $key ) {
		return $this->column()->get_pod_field_option( $key );
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|false
	 */
	public function get_option( $key ) {
		$options = $this->get( 'options' );

		return isset( $options[ $key ] ) ? $options[ $key ] : false;
	}

}