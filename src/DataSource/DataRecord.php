<?php

namespace PHRE\DataSource;

class DataRecord {

	private $array;

	public function __construct($array) {
		$this->array = $array;
	}

	public function valid() {
		return ($this->array !== null);
	}

	public function get($value) {
		if ($value === null || !$this->valid()) {
			return null;
		}

		return (array_key_exists($value, $this->array)
			? $this->array[$value]
			: null
		);
	}

}
