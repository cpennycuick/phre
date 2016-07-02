<?php

//define('_MPDF_PATH','vendor/mpdf/mpdf/');
include 'vendor/autoload.php';

$data = [
	['ID' => 1, 'Name' => 'Frank', 'Group' => 'Funny', 'SubGroup' => null, 'Value' => 2.5],
	['ID' => 2, 'Name' => 'Chris', 'Group' => 'Family', 'SubGroup' => 'Father', 'Value' => 4.8],
	['ID' => 4, 'Name' => 'Nicki', 'Group' => 'Family', 'SubGroup' => 'Mother', 'Value' => 5.1],
	['ID' => 3, 'Name' => 'John', 'Group' => 'Family', 'SubGroup' => 'Sibling', 'Value' => 7.6],
	['ID' => 5, 'Name' => 'Sam', 'Group' => 'Family', 'SubGroup' => 'Sibling', 'Value' => 1.6],
	['ID' => 6, 'Name' => 'Jerry', 'Group' => 'Random', 'SubGroup' => null, 'Value' => 1.0],
	['ID' => 7, 'Name' => 'Homer', 'Group' => 'Randome', 'SubGroup' => null, 'Value' => 9.2],
	['ID' => 8, 'Name' => 'Homer', 'Group' => 'Other', 'SubGroup' => null, 'Value' => 9.2],
];

$altData = [
	4 => [
		['One' => 1, 'Two' => 2],
		['One' => 4, 'Two' => 3],
	],
];

use \PHRE\Entities as PHREE;

//try {
	$report = new PHRE\Report();

	$styleSheet = (new PHRE\DataHolder\StyleSheet())
		->add('table', (new PHRE\DataHolder\Style())
			->set('border-collapse', 'collapse')
		)
		->add('tr.header td', (new PHRE\DataHolder\Style())
			->set('font-weight', 'bold')
			->set('border-bottom', '1px solid black')
		)
		->add('tr.footer td', (new PHRE\DataHolder\Style())
			->set('border-top', '1px solid black')
		);

	$currencyAU = (new PHRE\Formatter\Currency())
		->setCurrency('$')
		->setFormatCustom(2, '.', ',');

	$report
		->addStyleSheet($styleSheet)
		->add(PHREE\Group::create()
			->setGroupField('Group')
			->addBody(PHREE\Tag::div()
				->setAttribute('class', 'page')
				->add(PHREE\Tag::table()
					->add(PHREE\Group::create()
//						->setGroupField('Group')
						->addHeader(PHREE\Tag::tr()
							->setAttribute('class', 'header')
							->add(PHREE\Field::create('Group'))
							->add(PHREE\Text::create('Name'))
							->add(PHREE\Text::create('Balanace'))
						)
						->addBody(PHREE\Group::create()
							->setGroupField('SubGroup')
							->addHeader(PHREE\Tag::tr()
								->setVisible(function (PHRE\DataSource\DataRecord $record) {
									return !empty($record->get('SubGroup'));
								})
								->add(PHREE\Field::create('SubGroup'))
								->add(PHREE\Text::create(''))
								->add(PHREE\Text::create(''))
							)
							->addBody(PHREE\Tag::tr()
								->add(PHREE\Field::create('ID'))
								->add(PHREE\Field::create('Name'))
								->add(PHREE\Field::create('Value')
									->setFormatter($currencyAU)
								)
							)
							->addBody(PHREE\Group::create()
								->setDataSource(function (PHRE\DataSource\DataRecord $record) use ($altData) {
									$id = $record->get('ID');

									if (!isset($altData[$id])) {
										return null;
									}

									return new \PHRE\DataSource\DataSource($altData[$id]);
								})
								->addBody(PHREE\Tag::tr()
									->add(PHREE\Text::create('AltGroup'))
									->add(PHREE\Field::create('One'))
									->add(PHREE\Field::create('Two'))
								)
							)
						)
						->addFooter(PHREE\Tag::tr()
							->setAttribute('class', 'footer')
							->add(PHREE\Text::create('Foot'))
							->add(PHREE\Text::create(''))
							->add(PHREE\FieldCalc::create('Value')
								->setFormatter($currencyAU)
							)
						)
					)
				)
			)
		);

	if (defined('HTML')) {
		echo $report->generateHTML(new PHRE\DataSource\DataSource($data));
	} elseif (defined('PDF')) {
		header('Content-Type: application/pdf');
		echo $report->generatePDF(new PHRE\DataSource\DataSource($data));
	} else {
		echo 'Unknown';
	}

//} catch (\Exception $e) {
//	echo $e->getMessage()."\n".$e->getTraceAsString();
//}
