<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Currency extends Editing {

	public function get_view_settings() {
		return array(
			'type'       => 'number',
			'range_step' => 'any',
		);
	}

}