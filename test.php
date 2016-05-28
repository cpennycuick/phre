<?php

include 'Report.php';

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
		->add('.header', (new PHRE\DataHolder\Style())
			->set('font-weight', 'bold')
			->set('border-bottom', '1px solid black')
		)
		->add('.footer', (new PHRE\DataHolder\Style())
			->set('border-top', '1px solid black')
		);

	$report
		->add(PHREE\Table::create()
			->setGroupField('Group')
			->add(PHREE\TableHeader::create()
				->add(PHREE\TableCell::create()
					->setAttribute('class', 'header')
					->add(PHREE\Field::create('Group'))
				)
			)
			->add(PHREE\TableBody::create()
				->add(PHREE\TableCell::create()
					->add(PHREE\Field::create('Name'))
				)
			)
			->add(PHREE\TableFooter::create()
				->add(PHREE\TableCell::create()
					->setAttribute('class', 'footer')
					->add(PHREE\Text::create('Foot'))
				)
			)
		);

	echo $report->generate(new PHRE\DataSource\DataSourceArray($data), $styleSheet);
} catch (\Exception $e) {
	echo $e->getMessage()."\n".$e->getTraceAsString();
}
