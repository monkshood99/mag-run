<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;
use ACP\Editing\PaginatedOptions;
use ACP\Helper\Select;

class PickPosts extends Editing
	implements PaginatedOptions {

	public function get_edit_value( $id ) {
		return $this->get_titles( parent::get_edit_value( $id ) );
	}

	public function get_view_settings() {
		$field = $this->column->get_field();

		$settings = array(
			'type'            => 'select2_dropdown',
			'formatted_value' => 'post',
			'ajax_populate'   => true,
			'clear_button'    => ( $field->get_option( 'required' ) == 0 ),
			'multiple'        => ( 'multi' == $field->get_option( 'pick_format_type' ) ),
		);

		return $settings;
	}

	/**
	 * @param \WP_Post[] | int[] $post_ids
	 *
	 * @return array
	 */
	protected function get_titles( $post_ids ) {
		$titles = array();

		foreach ( (array) $post_ids as $k => $post_id ) {
			$title = ac_helper()->post->get_raw_post_title( $post_id );

			if ( $title ) {
				$titles[ $post_id ] = $title;
			}
		}

		return $titles;
	}

	/**
	 * @param string $search
	 * @param int    $page
	 * @param null   $id
	 *
	 * @return Select\Options\Paginated
	 */
	public function get_paginated_options( $s, $paged, $id = null ) {
		$field = $this->column->get_field();

		$args = array(
			's'         => $s,
			'paged'     => $paged,
			'post_type' => $field->get( 'pick_val' ),
		);

		if ( $field->get_option( 'pick_post_status' ) ) {
			$args['post_status'] = $field->get_option( 'pick_post_status' );
		}

		$entities = new Select\Entities\Post( $args );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\PostType(
				new Select\Formatter\PostTitle( $entities )
			)
		);
	}

}