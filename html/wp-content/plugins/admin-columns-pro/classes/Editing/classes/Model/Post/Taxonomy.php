<?php

namespace ACP\Editing\Model\Post;

use ACP\Editing\Model;
use ACP\Editing\PaginatedOptions;
use ACP\Editing\Settings;
use ACP\Helper\Select;

class Taxonomy extends Model
	implements PaginatedOptions {

	/**
	 * @param int $id
	 *
	 * @return array
	 */
	public function get_edit_value( $id ) {
		$values = array();

		$terms = get_the_terms( $id, $this->column->get_taxonomy() );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$values[ $term->term_id ] = htmlspecialchars_decode( $term->name );
			}
		}

		return $values;
	}

	private function get_taxonomy_object() {
		return get_taxonomy( $this->column->get_taxonomy() );
	}

	public function get_view_settings() {
		$taxonomy = $this->get_taxonomy_object();

		if ( ! $taxonomy ) {
			return false;
		}

		$data = array(
			'type'          => 'select2_dropdown',
			'multiple'      => true,
			'ajax_populate' => true,
		);

		if ( 'on' === $this->column->get_option( 'enable_term_creation' ) ) {
			$data = array(
				'type'    => 'select2_tags',
				'options' => $this->get_term_options(),
			);
		}

		if ( 'post_format' == $taxonomy->name ) {
			$data = array(
				'type'     => 'select2_dropdown',
				'multiple' => false,
			);
		}

		return $data;
	}

	public function get_paginated_options( $search, $page, $id = null ) {
		$entities = new Select\Entities\Taxonomy( array(
			'search'   => $search,
			'page'     => $page,
			'taxonomy' => $this->column->get_taxonomy(),
		) );

		return new Select\Options\Paginated(
			$entities,
			new Select\Formatter\TermName( $entities )
		);

	}

	/**
	 * @return array
	 */
	protected function get_term_options() {
		$entities = new Select\Entities\Taxonomy( array(
			'number'   => 200,
			'taxonomy' => $this->column->get_taxonomy(),
		) );

		$results = new Select\Options\Paginated(
			$entities,
			new Select\Formatter\TermName( $entities )
		);

		$options = array();

		foreach ( $results as $result ) {
			$options[ $result->get_value() ] = $result->get_label();
		}

		return $options;
	}

	/**
	 * @param int       $id
	 * @param array|int $value
	 *
	 * @return array
	 */
	public function save( $id, $value ) {
		return $this->set_terms( $id, $value, $this->column->get_taxonomy() );
	}

	/**
	 * Register editing settings
	 */
	public function register_settings() {
		$this->column->add_setting( new Settings\Taxonomy( $this->column ) );
	}

	/**
	 * @param $post     \WP_Post|int
	 * @param $term_ids int[]|int Term ID's
	 * @param $taxonomy string Taxonomy name
	 *
	 * @return array
	 */
	protected function set_terms( $post, $term_ids, $taxonomy ) {
		$post = get_post( $post );

		if ( ! $post || ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		// Filter list of terms
		if ( empty( $term_ids ) ) {
			$term_ids = array();
		}

		$term_ids = array_unique( (array) $term_ids );

		// maybe create terms?
		$created_term_ids = array();

		foreach ( (array) $term_ids as $index => $term_id ) {
			if ( is_numeric( $term_id ) ) {
				continue;
			}

			$term = get_term_by( 'name', $term_id, $taxonomy );

			if ( $term ) {
				$term_ids[ $index ] = $term->term_id;
			} else {
				$created_term = wp_insert_term( $term_id, $taxonomy );
				$created_term_ids[] = $created_term['term_id'];
			}
		}

		// merge
		$term_ids = array_merge( $created_term_ids, $term_ids );

		//to make sure the terms IDs is integers:
		$term_ids = array_map( 'intval', (array) $term_ids );
		$term_ids = array_unique( $term_ids );

		if ( $taxonomy == 'category' && is_object_in_taxonomy( $post->post_type, 'category' ) ) {
			wp_set_post_categories( $post->ID, $term_ids );
		} else if ( $taxonomy == 'post_tag' && is_object_in_taxonomy( $post->post_type, 'post_tag' ) ) {
			wp_set_post_tags( $post->ID, $term_ids );
		} else {
			wp_set_object_terms( $post->ID, $term_ids, $taxonomy );
		}

		wp_update_post( array( 'ID' => $post->ID ) );

		return $term_ids;
	}

}