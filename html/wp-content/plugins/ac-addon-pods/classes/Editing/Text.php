<?php

namespace ACA\Pods\Editing;

use ACA\Pods\Editing;

class Text extends Editing {

	public function get_view_settings() {
		$data = parent::get_view_settings();
		$data['type'] = 'text';

		return $data;
	}

}