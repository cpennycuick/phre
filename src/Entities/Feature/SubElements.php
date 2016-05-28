<?php

namespace PHRE\Entities\Feature;

use PHRE\DataSource\DataSource;
use PHRE\Entities\Element;

trait SubElements {

	private $elements = [];

	public function add(Element $element) {
		$this->elements[] = $element;
		return $this;
	}

	protected function renderElements(DataSource $data) {
		$parts = [];

		foreach ($this->elements as $element) {
			$parts[] = $element->render($data);
		}

		return implode("\n", $parts);
	}

	protected function resetElements() {
		foreach ($this->elements as $element) {
			$element->reset();
		}
	}

}
