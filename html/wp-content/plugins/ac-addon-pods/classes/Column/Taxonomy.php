<?php

namespace ACA\Pods\Column;

use ACA\Pods\Column;
use ACA\Pods\Filtering;
use ACA\Pods\Sorting;

class Taxonomy extends Column {

	protected function get_pod_name() {
		if ( ! method_exists( $this->list_screen, 'get_taxonomy' ) ) {
			return false;
		}

		return $this->list_screen->get_taxonomy();
	}

	/**
	 * Pods does not work with Tax meta, so search won't work with the default meta models
	 */
	public function search() {
		return false;
	}

	public function filtering() {
		return new Filtering\Disabled( $this );
	}

	public function sorting() {
		return new Sorting\Disabled( $this );
	}

}