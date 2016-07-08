<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;

class Field extends Element {

	use \PHRE\Entities\Feature\Formatting;

	protected $field;

	protected function __construct($field) {
		parent::__construct();
		$this->field = $field;
	}

	public static function create($field) {
		return new static($field);
	}

	public function render(DataSource $data) {
		return $this->formatValue(
			$this->sanitiseValue(
				$this->getValue($data)
			)
		);
	}

	private function getValue(DataSource $data) {
		if (is_callable($this->field)) {
			return call_user_func($this->field, $data->current());
		} else {
			return $data->current()->get($this->field);
		}
	}

	private function sanitiseValue($value) {
		if (is_string($value)) {
			return htmlentities($value);
		}

		return $value;
	}

	public function reset() {
	}

}
