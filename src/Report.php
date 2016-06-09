<?php

namespace PHRE;

use PHRE\DataSource\DataSource;
use PHRE\DataHolder\StyleSheet;

class Report {

	use \PHRE\Entities\Feature\SubElements;

	public static function create() {
		return new static();
	}

	public function generate(DataSource $data, StyleSheet $styleSheet = null) {
		$data->reset();
		$this->reset();

		$parts = ['<html>'];

		if ($styleSheet) {
			$parts[] = '<head><style>';
			$parts[] = (string) $styleSheet;
			$parts[] = '</style></head>';
		}

		$parts[] = '<body>';

		while ($data->current()->valid() and count($parts) < 1000) {
			$parts[] = $this->renderElements($data);

			$data->next();
		}

		$parts[] = '</body></html>';

		return implode("\n", $parts);
	}

	protected function reset() {
		$this->resetElements();
	}

}
