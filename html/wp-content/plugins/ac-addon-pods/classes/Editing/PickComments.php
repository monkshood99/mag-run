<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACP\Editing\PaginatedOptions;
use ACP\Helper\Select;
use ACP\Helper\Select\Entities;

class PickComments extends Editing implements PaginatedOptions {

	public function get_view_settings() {
		$field = $this->column->get_field();

		$settings = array(
			'type'            => 'select2_dropdown',
			'formatted_value' => 'comment',
			'ajax_populate'   => true,
			'clear_button'    => ( 0 == $field->get_option( 'required' ) ),
			'multiple'        => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
		);

		return $settings;
	}

	public function get_paginated_options( $search, $paged, $id = null ) {
		$entities = new Entities\Comment( compact( 'search', 'paged' ) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Formatter\CommentSummary( $entities )
		);
	}

	public function get_edit_value( $id ) {
		return $this->get_titles( $this->column->get_field()->get_raw_value( $id ) );
	}

	/**
	 * @param int[] $comment_ids
	 *
	 * @return array
	 */
	protected function get_titles( $comment_ids ) {
		$titles = array();
		foreach ( (array) $comment_ids as $k => $comment_id ) {
			$comment = get_comment( $comment_id );

			if ( $comment ) {
				$titles[] = $comment->comment_date;
			}
		}

		return $titles;
	}

}