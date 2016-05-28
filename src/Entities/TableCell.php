<?php

namespace PHRE\Entities;

class TableCell extends HTMLElement {

	public static function create() {
		return new static('td');
	}

}
