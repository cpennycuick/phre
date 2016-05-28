<?php

namespace PHRE\DataHolder;

class StyleSheet {

	private $elements = [];

	public function add($path, Style $style) {
		$this->elements[$path] = $style;
		return $this;
	}

	public function __toString() {
		$parts = [];

		foreach ($this->elements as $path => $style) {
			$parts[] = "{$path} { {$style} }";
		}

		return implode("\n", $parts);
	}

}
