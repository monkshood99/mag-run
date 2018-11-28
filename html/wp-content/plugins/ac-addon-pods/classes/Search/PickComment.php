<?php

namespace ACA\Pods\Search;

use ACP\Helper\Select;
use ACP\Helper\Select\Formatter;
use ACP\Search\Comparison\Meta;
use ACP\Search\Comparison\SearchableValues;
use ACP\Search\Operators;

class PickComment extends Meta
	implements SearchableValues {

	public function __construct( $meta_key, $type ) {
		$operators = new Operators( array(
			Operators::EQ,
			Operators::IS_EMPTY,
			Operators::NOT_IS_EMPTY,
		) );

		parent::__construct( $operators, $meta_key, $type );
	}

	public function get_values( $search, $paged ) {
		$entities = new Select\Entities\Comment( compact( 'search', 'paged' ) );

		return new Select\Options\Paginated(
			$entities,
			new Formatter\CommentSummary( $entities )
		);
	}

}