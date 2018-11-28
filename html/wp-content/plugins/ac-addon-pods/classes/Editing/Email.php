<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Email extends Editing {

	public function get_view_settings() {
		return array(
			'type' => 'email',
		);
	}

}