<?php

namespace ACA\Pods;

use AC;
use ACP;

abstract class Column extends AC\Column\Meta
	implements ACP\Editing\Editable, ACP\Sorting\Sortable, ACP\Filtering\Filterable, ACP\Export\Exportable, ACP\Search\Searchable {

	/**
	 * @var array Pod settings
	 */
	private $pod;

	/**
	 * @return string
	 */
	abstract protected function get_pod_name();

	public function __construct() {
		$this->set_type( 'column-pods' );
		$this->set_label( __( 'Pods', 'codepress-admin-columns' ) );
		$this->set_group( 'pods' );
	}

	public function get_meta_key() {
		return $this->get_setting( 'pods_field' )->get_value();
	}

	public function get_value( $id ) {
		$value = $this->get_field()->get_value( $id );

		if ( $value instanceof AC\Collection ) {
			$value = $value->filter()->implode( $this->get_separator() );
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return $this->get_field()->get_raw_value( $id );
	}

	public function get_separator() {
		$seprator = $this->get_field()->get_separator();

		if ( ! is_null( $seprator ) ) {
			return $seprator;
		}

		return parent::get_separator();
	}

	public function editing() {
		return $this->get_field()->editing();
	}

	public function sorting() {
		return $this->get_field()->sorting();
	}

	public function filtering() {
		return $this->get_field()->filtering();
	}

	public function export() {
		return $this->get_field()->export();
	}

	public function search() {
		return $this->get_field()->search();
	}

	protected function register_settings() {
		$this->add_setting( new Setting\Field( $this ) );
	}

	/**
	 * Current Pod field settings
	 * @return false|array Field settings
	 */
	public function get_pod_field() {
		$fields = $this->get_pod_fields();

		return isset( $fields[ $this->get_meta_key() ] ) ? $fields[ $this->get_meta_key() ] : false;
	}

	/**
	 * @param string $property
	 *
	 * @return mixed|false
	 */
	public function get_pod_field_option( $property ) {
		$field = $this->get_pod_field();

		return isset( $field[ $property ] ) ? $field[ $property ] : false;
	}

	/**
	 * @return string|false
	 */
	public function get_field_type() {
		return $this->get_pod_field_option( 'type' );
	}

	private function set_pod() {
		add_filter( 'pods_error_exception', '__return_true', 12 ); // otherwise pods_error() will throw an exit
		$pod = pods_api()->load_pod( array( 'name' => $this->get_pod_name() ) );

		remove_filter( 'pods_error_exception', '__return_true', 12 );

		$this->pod = isset( $pod['id'] ) ? $pod : false;
	}

	/**
	 * @return array
	 */
	public function get_pod() {
		if ( null === $this->pod ) {
			$this->set_pod();
		}

		return $this->pod;
	}

	/**
	 * @return false|Field
	 */
	public function get_field() {
		$field_object = new FieldFactory();

		return $field_object->create( $this );
	}

	/**
	 * @return false|array Field settings
	 */
	public function get_pod_fields() {
		$pod = $this->get_pod();

		return isset( $pod['fields'] ) ? $pod['fields'] : false;
	}

}