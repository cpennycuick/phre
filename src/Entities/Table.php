<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;

class Table extends HTMLElement {

	private $headerRows = [];
	protected $bodyRows = [];
	private $footerRows = [];
	private $groupField = null;

	private $stateCurrentGroupValue = null;

	public static function create() {
		return new static('table');
	}

	public function setGroupField($field) {
		$this->groupField = $field;
		return $this;
	}

	public function add(Element $row) {
		if ($row instanceof TableHeader) {
			$this->headerRows[] = $row;
		} elseif ($row instanceof TableBody) {
			$this->bodyRows[] = $row;
		} elseif ($row instanceof TableFooter) {
			$this->footerRows[] = $row;
		} else {
			throw new \Exception('Table only accpets TableRow Elements.');
		}

		return $this;
	}

	protected function renderElements(DataSource $data) {

		if ($this->groupField) {
			$record = $data->current();
			$this->stateCurrentGroupValue = $record->get($this->groupField);
		}

		// foreach calc fields
		$dataGroup = $data->startGroup();
		$dataGroup->startCalc('Value');

		$bodyParts = [];
		while ($data->current()->valid()) {
			foreach ($this->bodyRows as $row) {
				$bodyParts[] = $row->render($data);
			}

			if ($this->checkNextRecordInCurrentGroup($data)) {
				$data->next();
			} else {
				break;
			}
		}

		$parts = [];
		foreach ($this->headerRows as $row) {
			$parts[] = $row->render($data);
		}

		$parts = array_merge($parts, $bodyParts);

		foreach ($this->footerRows as $row) {
			$parts[] = $row->render($data);
		}

		$dataGroup->end();

		return implode("\n", $parts);
	}

	private function checkNextRecordInCurrentGroup(DataSource $data) {
		if (!$data->hasNext()) {
			return false;
		}

		if (!$this->groupField) {
			return true;
		}

		$next = $data->peek();

		return ($this->stateCurrentGroupValue === $next->get($this->groupField));
	}

	public function reset() {
		$this->stateCurrentGroupValue = null;
	}

}
