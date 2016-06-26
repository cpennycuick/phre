<?php

namespace PHRE\Entities\Feature;

use PHRE\Formatter\Formatter;

trait Formatting {

	/**
	 * @var Formatter
	 */
	private $formatter = null;

	public function setFormatter(Formatter $formatter) {
		$this->formatter = $formatter;
	}

	protected function formatValue($value) {
		if ($this->formatter) {
			return $this->formatter->formatValue($value);
		} else {
			return $value;
		}
	}

}
