<?php

namespace PHRE\Entities;

class TableRow extends HTMLElement {

	public static function create() {
		return new static('tr');
	}

	public function add(Element $element) {
		if ($element instanceof TableCell) {
			parent::add($element);
		} else {
			throw new \Exception('TableRow only accepts TableCell.');
		}

		return $this;
	}

}
