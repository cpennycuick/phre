<?php

namespace PHRE\DataHolder;

class AttributeList implements SpecialAttribute {

	private $separator;
	private $elements = [];

	public function __construct($separator = ' ') {
		$this->separator = $separator;
	}

	public function hasElements() {
		return (count($this->elements) > 0);
	}

	public function add($value) {
		if (!$this->has($value)) {
			$this->elements[trim($value)] = true;
		}

		return $this;
	}

	public function has($value) {
		$value = trim($value);

		if (strlen($value) === 0) {
			return false;
		}

		return array_key_exists($value, $this->elements);
	}

	public function remove($value) {
		unset($this->elements[trim($value)]);
		return $this;
	}

	public function fromText($text) {
		foreach (explode($this->separator, $text) as $value) {
			$this->add($value);
		}
	}

	public function __toString() {
		return implode($this->separator, array_keys($this->elements));
	}

	public function reset() {
		$this->elements = [];
	}

}
