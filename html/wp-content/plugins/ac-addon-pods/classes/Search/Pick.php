<?php

namespace ACA\Pods\Search;

use ACP\Helper\Select\Options;
use ACP\Search\Comparison\Meta;
use ACP\Search\Comparison\Values;
use ACP\Search\Operators;

class Pick extends Meta
	implements Values {

	/** @var array */
	private $options;

	public function __construct( $meta_key, $type, $options ) {
		$this->options = $options;

		$operators = new Operators( array(
			Operators::EQ,
			Operators::NEQ,
			Operators::IS_EMPTY,
			Operators::NOT_IS_EMPTY,
		) );

		parent::__construct( $operators, $meta_key, $type );
	}

	public function get_values() {
		return Options::create_from_array( $this->options );
	}

}