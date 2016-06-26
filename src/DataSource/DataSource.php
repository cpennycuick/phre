<?php

namespace PHRE\DataSource;

use PHRE\DataSource\Iterator\PDOStatementIterator;

class DataSource {

	/**
	 * @var \Iterator
	 */
	private $iterator;
	private $current;
	private $peek;
	private $groups;
	private $currentGroup;

	public function __construct($data) {
		$this->iterator = $this->createIterator($data);
		$this->reset();
	}

	private function createIterator($data) {
		if (is_array($data)) {
			return new \ArrayIterator($data);
		} else if ($data instanceof \PDOStatement) {
			return new PDOStatementIterator($data);
		} else {
			throw new \Exception('Unsupported data format.');
		}
	}

	public function reset() {
		$this->iterator->rewind();
		$this->current = null;
		$this->peek = null;
		$this->groups = [];
		$this->currentGroup = null;
	}

	public function hasNext() {
		return $this->peek()->valid();
	}

	public function next() {
		$this->current = $this->peek();

		if ($this->current()->valid()) {
			$this->iterator->next();
			$this->processGroups();
		}

		$this->peek = null;
	}

	/**
	 * @return DataRecord
	 */
	public function current() {
		if (!$this->current) {
			$this->next();
		}

		return $this->current;
	}

	/**
	 * @return DataRecord
	 */
	public function peek() {
		if (!$this->peek) {
			$this->peek = $this->createNewDataRecord();
		}

		return $this->peek;
	}

	private function createNewDataRecord() {
		return new DataRecord($this->iterator->current());
	}

	/**
	 * @return DataGroup
	 */
	public function group() {
		if (!$this->currentGroup) {
			$this->current();
		}

		return $this->currentGroup;
	}

	public function startGroup($groupField) {
		$group = new DataGroup($this, $groupField);
		$this->groups[] = $group;
		$this->currentGroup = $group;

		$this->processGroups();

		return $group;
	}

	public function endGroup() {
		array_pop($this->groups);

		if ($this->groups) {
			$this->currentGroup = $this->groups[count($this->groups) - 1];
		} else {
			$this->currentGroup = null;
		}
	}

	private function processGroups() {
		foreach ($this->groups as $group) {
			$group->processRecord();
		}
	}

	public function isLastRecordInGroup() {
		if (!$this->hasNext()) {
			return true;
		}

		// Only check parent DataGroups; let the top most group handle its self.
		for ($i = 0; $i < (count($this->groups) - 1); $i++) {
			if ($this->groups[$i]->isLastRecordInGroup()) {
				return true;
			}
		}

		return false;
	}

}
