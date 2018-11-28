<?php

namespace ACA\Pods;

class FieldFactory extends FieldFactoryAbstract {

	/**
	 * @param Column $column
	 *
	 * @return Field
	 */
	public function create( Column $column ) {

		$class = $this->remove_factory_suffix( $this );
		$field_class = $class . '\\' . ucfirst( $column->get_field_type() );
		$factory = $field_class . self::SUFFIX;

		if ( class_exists( $factory ) ) {

			/* @var FieldFactory $instance */
			$instance = new $factory;

			return $instance->create( $column );
		}

		if ( class_exists( $field_class ) ) {
			return new $field_class( $column );
		}

		/* @var Field $class */
		return new $class( $column );
	}

}