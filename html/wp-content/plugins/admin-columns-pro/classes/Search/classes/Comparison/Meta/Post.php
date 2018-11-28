<?php

namespace ACP\Search\Comparison\Meta;

use ACP\Helper\Select;
use ACP\Search\Comparison;
use ACP\Search\Comparison\Meta;
use ACP\Search\Operators;
use ACP\Search\Value;

class Post extends Meta
	implements Comparison\SearchableValues {

	public function __construct( $meta_key, $meta_type ) {
		$operators = new Operators( array(
			Operators::EQ,
			Operators::IS_EMPTY,
			Operators::NOT_IS_EMPTY,
		) );

		parent::__construct( $operators, $meta_key, $meta_type, Value::INT );
	}

	public function get_values( $s, $paged ) {
		$entities = new Select\Entities\Post( array(
			's'     => $s,
			'paged' => $paged,
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\PostType(
				new Select\Formatter\PostTitle( $entities )
			)
		);
	}

}