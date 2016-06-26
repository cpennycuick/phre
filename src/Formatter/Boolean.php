<?php

namespace PHRE\Formatter;

class Boolean extends Formatter {

	private $valueTrue = 'True';
	private $valueFalse = 'False';

	public function setTrueFalseValues($valueTrue, $valueFalse) {
		$this->valueTrue = $valueTrue;
		$this->valueFalse = $valueFalse;

		return $this;
	}

	public function formatValue($value) {
		$value = parent::formatValue($value);

		if ($value === true) {
			return $this->valueTrue;
		} else if ($value === false) {
			return $this->valueFalse;
		} else {
			return '';
		}
	}

}
