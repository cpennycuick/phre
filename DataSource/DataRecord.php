<?php

namespace PHRE\DataSource;

class DataRecord {

	private $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function valid() {
		return ($this->data !== null);
	}

	public function get($value) {
		if ($value === null || !$this->valid()) {
			return null;
		}

		return (array_key_exists($value, $this->data)
			? $this->data[$value]
			: null
		);
	}

}
