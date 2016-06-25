<?php

namespace PHRE\Entities\Tag;

use PHRE\Entities\Element;
use PHRE\Entities\Tag;

class tr extends Tag {

	public static function create() {
		return new static('tr');
	}

	public function add(Element $element) {
		if ($element instanceof td) {
			parent::add($element);
		} else {
			$cell = self::td();
			$cell->add($element);
			parent::add($cell);
		}

		return $this;
	}

}
