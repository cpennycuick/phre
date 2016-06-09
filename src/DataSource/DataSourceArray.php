<?php

namespace PHRE\DataSource;

class DataSourceArray implements DataSource {

	/**
	 * @var \ArrayIterator
	 */
	private $iterator;
	private $current;
	private $peek;
	private $groups;
	private $currentGroup;

	public function __construct(array $array) {
		$this->iterator = new \ArrayIterator($array);
		$this->reset();
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
	 * @return DataGroup
	 */
	public function group() {
		if (!$this->currentGroup) {
			$this->current();
		}

		return $this->currentGroup;
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

	public function startGroup() {
		$group = new DataGroup($this);
		$this->groups[] = $group;

		$this->processGroups();

		return $group;
	}

	private function processGroups() {
		$groups = [];
		foreach ($this->groups as $group) {
			if (!$group->isActive()) {
				break; // Sub-groups must end anyway if parent group ends
			}

			$group->processRecord();
			$groups[] = $group;
			$this->currentGroup = $group;
		}

		$this->groups = $groups;
	}

}
