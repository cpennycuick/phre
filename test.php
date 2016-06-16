<?php

//define('_MPDF_PATH','vendor/mpdf/mpdf/');
include 'vendor/autoload.php';

$data = [
	['ID' => 1, 'Name' => 'Frank', 'Group' => 'Funny', 'Value' => 2.5],
	['ID' => 2, 'Name' => 'Chris', 'Group' => 'Family', 'Value' => 4.8],
	['ID' => 4, 'Name' => 'Nicki', 'Group' => 'Family', 'Value' => 5.1],
	['ID' => 3, 'Name' => 'John', 'Group' => 'Random', 'Value' => 7.6],
	['ID' => 5, 'Name' => 'Sam', 'Group' => 'Random', 'Value' => 1.6],
	['ID' => 6, 'Name' => 'Jerry', 'Group' => 'Random', 'Value' => 1.0],
	['ID' => 7, 'Name' => 'Homer', 'Group' => 'Other', 'Value' => 9.2],
];

use \PHRE\Entities as PHREE;

try {
	$report = new PHRE\Report();

	$styleSheet = (new PHRE\DataHolder\StyleSheet())
		->add('tr.header td', (new PHRE\DataHolder\Style())
			->set('font-weight', 'bold')
			->set('border-bottom', '1px solid black')
		)
		->add('tr.footer td', (new PHRE\DataHolder\Style())
			->set('border-top', '1px solid black')
		);

	$report
		->addStyleSheet($styleSheet)
		->add(PHREE\Page::create()
			->add(PHREE\Table::create()
				->setGroupField('Group')
				->add(PHREE\TableHeader::create()
					->setAttribute('class', 'header')
					->add(PHREE\Field::create('Group'))
					->add(PHREE\Text::create('Value'))
					->add(PHREE\Text::create('Balanace'))
				)
				->add(PHREE\TableBody::create()
					->add(PHREE\Field::create('Name'))
					->add(PHREE\Field::create('Value'))
					->add(PHREE\FieldCalc::create('Value'))
				)
				->add(PHREE\TableFooter::create()
					->setAttribute('class', 'footer')
					->add(PHREE\Text::create('Foot'))
					->add(PHREE\Text::create(''))
					->add(PHREE\FieldCalc::create('Value'))
				)
			)
		);

	echo $report->generateHTML(new PHRE\DataSource\DataSourceArray($data));
//	echo $report->generatePDF(new PHRE\DataSource\DataSourceArray($data));

} catch (\Exception $e) {
	echo $e->getMessage()."\n".$e->getTraceAsString();
}
