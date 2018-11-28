<?php

namespace ACA\Pods;

abstract class FieldFactoryAbstract {

	/**
	 * Factory classes should have this as suffix
	 */
	const SUFFIX = 'Factory';

	/**
	 * @param Column $column
	 *
	 * @return false|Field
	 */
	abstract public function create( Column $column );

	/**
	 * @param object $object
	 *
	 * @return string
	 */
	protected function remove_factory_suffix( $object ) {
		return substr( get_class( $object ), 0, -strlen( self::SUFFIX ) );
	}

}
