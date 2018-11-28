<?php

namespace ACP\ThirdParty\RelatedPosts;

use ACP\Editing\Model;
use ACP\Editing\PaginatedOptions;
use WP_Error;
use ACP\Helper\Select;

class Editing extends Model
	implements PaginatedOptions {

	public function get_paginated_options( $s, $paged, $id = null ) {
		$pt_manager = new \RP4WP_Post_Type_Manager();
		$post_types = $pt_manager->get_installed_post_type( $this->column->get_post_type() );

		$entities = new Select\Entities\Post( array(
			's'         => $s,
			'paged'     => $paged,
			'post_type' => $post_types,
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Group\PostType(
				new Select\Formatter\PostTitle( $entities )
			)
		);
	}

	public function get_view_settings() {
		$settings = array(
			'type'            => 'select2_dropdown',
			'ajax_populate'   => true,
			'multiple'        => true,
			'formatted_value' => 'post',
		);

		return $settings;
	}

	public function save( $id, $values ) {
		if ( ! class_exists( 'RP4WP_Post_Link_Manager' ) ) {
			return new WP_Error( 'related-posts-error', 'Class RP4WP_Post_Link_Manager not found.' );
		}

		// remove any false booleans
		$values = array_filter( array_map( 'intval', (array) $values ) );

		$post_link_manager = new \RP4WP_Post_Link_Manager();
		$current_related_ids = (array) $this->column->get_raw_value( $id );

		if ( $removed_ids = array_diff( $current_related_ids, $values ) ) {
			foreach ( $removed_ids as $removed_id ) {
				$post_link_manager->delete( $removed_id );
			}
		}
		if ( $added_ids = array_diff( $values, $current_related_ids ) ) {
			foreach ( $added_ids as $added_id ) {
				$post_link_manager->add( $id, $added_id, get_post_type( $id ), false, true );
			}
		}

		return true;
	}

}