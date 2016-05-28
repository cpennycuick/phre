<?php

namespace PHRE;

include 'DataSource/DataRecord.php';
include 'DataSource/DataSource.php';
include 'DataSource/DataSourceArray.php';

include 'DataHolder/StyleSheet.php';
include 'DataHolder/Attributes.php';
include 'DataHolder/SpecialAttribute.php';
include 'DataHolder/AttributeList.php';
include 'DataHolder/Style.php';

include 'Entities/Feature/SubElements.php';

include 'Entities/Renderable.php';
include 'Entities/Element.php';
include 'Entities/Text.php';
include 'Entities/Field.php';
include 'Entities/HTMLElement.php';
include 'Entities/Span.php';
include 'Entities/Table.php';
include 'Entities/TableRow.php';
include 'Entities/TableHeader.php';
include 'Entities/TableBody.php';
include 'Entities/TableFooter.php';
include 'Entities/TableCell.php';

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

		while ($data->current()->valid()) {
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
