<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Textarea extends Editing {

	public function get_view_settings() {
		$data = parent::get_view_settings();
		$data['type'] = 'textarea';

		return $data;
	}

}