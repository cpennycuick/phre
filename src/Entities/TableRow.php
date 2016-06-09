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
			$cell = TableCell::create();
			$cell->add($element);
			parent::add($cell);
		}

		return $this;
	}

}
