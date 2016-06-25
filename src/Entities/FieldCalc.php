<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;
use \PHRE\DataSource\DataGroup;

class FieldCalc extends Element {

	private $field;
	private $action;

	protected function __construct($field, $action) {
		parent::__construct();
		$this->field = $field;
		$this->action = $action;
	}

	public static function create($field, $action = DataGroup::ACTION_SUM) {
		return new static($field, $action);
	}

	public function getFieldName() {
		return $this->field;
	}

	public function render(DataSource $data) {
		if (!$data->group()) {
			return $data->current()->get($this->field);
		}

		return $data->group()->getValue($this->field, $this->action);
	}

	public function reset() {
	}

}
