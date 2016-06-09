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

	public function render(DataSource $data) {
//		if (!$data->group()) {
//			return null;
//		}

		return $data->group()->getValue($this->field, $this->action);
	}

	public function reset() {
	}

}
