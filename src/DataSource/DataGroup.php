<?php

namespace PHRE\DataSource;

class DataGroup {

	const ACTION_SUM = 'Sum';
	const ACTION_COUNT = 'Count';

	private $data = null;
	private $dataRecord = null;

	private $active = true;
	private $values = [];

	public function __construct(DataSource $data) {
		$this->data = $data;
	}

	public function startCalc($field) {
		$this->values[$field] = [
			self::ACTION_COUNT => 0,
			self::ACTION_SUM => 0,
		];

		$this->processRecordField($field, $this->values[$field]);
	}

//	public function getSum($field) {
//		return $this->getValue($field, self::ACTION_SUM);
//	}
//
//	public function getCount($field) {
//		return $this->getValue($field, self::ACTION_COUNT);
//	}

	public function getValue($field, $action) {
		if (!array_key_exists($field, $this->values)) {
			return null;
		}

		return $this->values[$field][$action];
	}

	public function isActive() {
		return $this->active;
	}

	public function processRecord() {
		if ($this->dataRecord && ($this->dataRecord === $this->data->current())) {
			return;
		}

		$this->dataRecord = $this->data->current();

		foreach ($this->values as $field => &$values) {
			$this->processRecordField($field, $values);
		}
	}

	private function processRecordField($field, &$values) {
		$fieldValue = $this->dataRecord->get($field);

		if ($fieldValue !== null) {
			$values[self::ACTION_COUNT]++;
		}
		if (is_numeric($fieldValue)) {
			$values[self::ACTION_SUM] += $fieldValue;
		}
	}

	public function end() {
		$this->active = false;
	}

}
