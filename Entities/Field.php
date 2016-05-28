<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;

class Field extends Element {

	private $field;

	protected function __construct($field) {
		parent::__construct();
		$this->field = $field;
	}

	public static function create($field) {
		return new static($field);
	}

	public function render(DataSource $data) {
		return $data->current()->get($this->field);
	}

	public function reset() {
	}

}
