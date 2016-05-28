<?php

namespace PHRE\DataSource;

class DataSourceArray implements DataSource {

	/**
	 * @var \ArrayIterator
	 */
	private $iterator;
	private $current = null;
	private $peek = null;

	public function __construct(array $array) {
		$this->iterator = new \ArrayIterator($array);
		$this->reset();
	}

	public function reset() {
		$this->iterator->rewind();
	}

	public function hasNext() {
		return $this->peek()->valid();
	}

	public function next() {
		if (!$this->peek) {
			$this->current = null;
			$this->iterator->next();
		} else {
			$this->current = $this->peek;
		}

		if ($this->peek && $this->peek->valid()) {
			$this->peek = null;
		}
	}

	public function current() {
		if ($this->current) {
			return $this->current;
		}

		return ($this->current = new DataRecord($this->iterator->current()));
	}

	public function peek() {
		if ($this->peek) {
			return $this->peek;
		}

		$this->iterator->next();
		return ($this->peek = new DataRecord($this->iterator->current()));
	}

}
