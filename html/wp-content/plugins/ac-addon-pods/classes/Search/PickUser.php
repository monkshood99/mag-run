<?php

namespace ACA\Pods\Search;

use ACP\Helper\Select;
use ACP\Search\Comparison\Meta;
use ACP\Search\Comparison\SearchableValues;
use ACP\Search\Operators;

class PickUser extends Meta
	implements SearchableValues {

	/** @var array */
	private $role;

	public function __construct( $meta_key, $type, $role ) {
		$this->role = $role;

		$operators = new Operators( array(
			Operators::EQ,
			Operators::NEQ,
			Operators::IS_EMPTY,
			Operators::NOT_IS_EMPTY,
		) );

		parent::__construct( $operators, $meta_key, $type );
	}

	public function get_values( $search, $page ) {
		$entities = new Select\Entities\User( array(
			'paged'    => $page,
			'search'   => $search,
			'role__in' => $this->role,
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\UserRole(
				new Select\Formatter\UserName( $entities )
			)
		);
	}

}