<?php

namespace ACA\Pods\Search;

use ACP\Helper\Select;
use ACP\Helper\Select\Formatter;
use ACP\Search\Comparison\Meta;
use ACP\Search\Comparison\SearchableValues;
use ACP\Search\Operators;

class PickPost extends Meta
	implements SearchableValues {

	/** @var array */
	private $post_type;

	public function __construct( $meta_key, $type, $post_type ) {
		$this->post_type = $post_type;

		$operators = new Operators( array(
			Operators::EQ,
			Operators::NEQ,
			Operators::IS_EMPTY,
			Operators::NOT_IS_EMPTY,
		) );

		parent::__construct( $operators, $meta_key, $type );
	}

	public function get_values( $search, $page ) {
		$entities = new Select\Entities\Post( array(
			'paged'     => $page,
			'post_type' => $this->post_type,
			's'         => $search,
		) );

		return new Select\Options\Paginated(
			$entities,
			new Formatter\PostTitle( $entities )
		);
	}

}