<?php

namespace ACA\Pods\Field;

use ACA\Pods\FieldFactoryAbstract;
use ACA\Pods\Column;

class PickFactory extends FieldFactoryAbstract {

	public function create( Column $column ) {
		$class = $this->remove_factory_suffix( $this ) . '\\' . $this->get_class( $column->get_pod_field_option( 'pick_object' ) );

		if ( ! class_exists( $class, true ) ) {
			$class = $this->remove_factory_suffix( $this );
		}

		return new $class( $column );
	}

	protected function get_class( $field ) {
		$class = '';

		foreach ( preg_split( '/_|-/', $field ) as $part ) {
			$class .= ucfirst( $part );
		}

		return $class;
	}

}