<?php

namespace ACA\Pods\Filtering;

use ACA\Pods\Filtering;

class PickPosts extends Filtering {

	public function get_filtering_data() {
		return array(
			'options'      => acp_filtering()->helper()->get_post_titles( $this->get_meta_values() ),
			'empty_option' => true,
		);
	}

}