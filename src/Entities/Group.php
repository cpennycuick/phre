<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;
use \PHRE\DataSource\DataGroup;

class Group extends Element {

	private $bodyElements = [];
	private $headerElements = [];
	private $footerElements = [];

	private $groupField = null;
	private $dataSourceCreate = null;
	private $dataFieldCalcCache = null;

	public static function create() {
		return new static();
	}

	public function setGroupField($field) {
		$this->groupField = $field;
		return $this;
	}

	public function setDataSource($dataSourceCreate) {
		$this->dataSourceCreate = $dataSourceCreate;
		return $this;
	}

	public function addBody(Element $element) {
		$this->bodyElements[] = $element;
		return $this;
	}

	public function addHeader(Element $row) {
		$this->headerElements[] = $row;
		return $this;
	}

	public function addFooter(Element $row) {
		$this->footerElements[] = $row;
		return $this;
	}

	public function render(DataSource $data) {
		if (is_callable($this->dataSourceCreate)) {
			$data = call_user_func($this->dataSourceCreate, $data->current());
			if ($data) {
				assert($data instanceof DataSource);
			} else {
				return null;
			}
		}

		$dataGroup = $data->startGroup($this->groupField);

		$this->startDataGroupCalc($dataGroup);

		$parts = [];

		$body = [];
		while ($data->current()->valid()) {
			foreach ($this->bodyElements as $row) {
				$body[] = $row->render($data);
			}

			$endOfPartition = $dataGroup->isLastRecordInGroup();
			$endOfGroup = $data->isLastRecordInGroup();

			if ($endOfPartition || $endOfGroup) {
				$parts = array_merge(
					$parts,
					$this->renderHeaders($data),
					$body,
					$this->renderFooters($data)
				);

				$dataGroup->resetValues();
				$body = [];
			}

			if ($endOfGroup) {
				break;
			}

			$data->next();
		}

		$data->endGroup();

		return implode("\n", $parts);
	}

	private function startDataGroupCalc(DataGroup $dataGroup) {
		if ($this->dataFieldCalcCache === null) {
			echo "# {$this->groupField}\n";

			echo "- Body\n";
			$fieldCalcBody = $this->scanElementsForFieldCalc($this->bodyElements);
			echo "- Header\n";
			$fieldCalcHeader = $this->scanElementsForFieldCalc($this->headerElements);
			echo "- Footer\n";
			$fieldCalcFooter = $this->scanElementsForFieldCalc($this->footerElements);

			$this->dataFieldCalcCache = array_unique(array_merge(
				$fieldCalcBody,
				$fieldCalcHeader,
				$fieldCalcFooter
			));

			echo 'Group calc: '.implode(';', $this->dataFieldCalcCache)."\n";
		}

		foreach ($this->dataFieldCalcCache as $field) {
			$dataGroup->startCalc($field);
		}
	}

	private function scanElementsForFieldCalc($elements) {
		$fieldNames = [];
		foreach ($elements as $element) {
			echo get_class($element).($element instanceof Tag ? ':'.$element->getTag() : '')."\n";

			if ($element instanceof Group) {
				continue;
			} elseif ($element instanceof FieldCalc) {
				$fieldNames[] = $element->getFieldName();
			} elseif (method_exists($element, 'getElements')) {
				$subFieldNames = $this->scanElementsForFieldCalc($element->getElements());
				$fieldNames = array_merge($fieldNames, $subFieldNames);
			}
		}

		return $fieldNames;
	}

	private function renderHeaders($data) {
		$headers = [];
		foreach ($this->headerElements as $row) {
			$headers[] = $row->render($data);
		}

		return $headers;
	}

	private function renderFooters($data) {
		$footers = [];
		foreach ($this->footerElements as $row) {
			$footers[] = $row->render($data);
		}

		return $footers;
	}

	public function reset() {
		$elements = array_merge(
			$this->headerElements,
			$this->bodyElements,
			$this->footerElements
		);

		foreach ($elements as $element) {
			$element->reset();
		}
	}

}
