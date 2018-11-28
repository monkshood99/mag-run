<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class TrueFalse extends Editing {

	public function get_view_settings() {
		return array(
			'type'    => 'togglable',
			'options' => array( '0', '1' ),
		);
	}

}