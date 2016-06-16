<?php

namespace PHRE\Entities;

class Page extends HTMLElement {

	public static function create() {
		return new static('page');
	}

}
