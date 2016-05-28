<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;

class Text extends Element {

	private $text;

	protected function __construct($text) {
		parent::__construct();
		$this->text = $text;
	}

	public static function create(... $text) {
		return new static(implode('', $text));
	}

	public function render(DataSource $data) {
		return $this->text;
	}

	public function reset() {
	}

}
