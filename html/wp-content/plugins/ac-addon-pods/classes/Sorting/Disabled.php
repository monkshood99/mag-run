<?php

namespace ACA\Pods\Sorting;

use ACP;

class Disabled extends ACP\Sorting\Model\Meta {

	public function is_active() {
		return false;
	}

	public function register_settings() {
	}

}