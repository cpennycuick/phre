<?php

namespace PHRE\Entities;

abstract class Element implements Renderable {

	protected function __construct() {
	}

	abstract public function reset();

}
