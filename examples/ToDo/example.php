<?php

include __DIR__.'/../../vendor/autoload.php';

use \PHRE\Entities as PHREE;

$dataSource = new PHRE\DataSource\DataSource([
	['Action' => 'Buy Milk', 'Done' => true],
	['Action' => 'Pick up Mail', 'Done' => true],
	['Action' => 'Clean house', 'Done' => false],
	['Action' => 'Car Serviced', 'Done' => false],
	['Action' => 'Put more things on ToDo list', 'Done' => false],
]);

$doneFormatter = (new \PHRE\Formatter\Boolean())
	->setTrueFalseValues('Yes', 'No');

$headerStyle = (new PHRE\DataHolder\Style())
	->set('font-weight', 'bold')
	->set('border-bottom', '1px solid black');

$doneStyle = (new PHRE\DataHolder\Style())
	->set('text-align', 'center');

$report = (new PHRE\Report())
	->add(PHREE\Page::create()
		->add(PHREE\Tag::table()
			->add(PHREE\Group::create()
				->addHeader(PHREE\Tag::tr()
					->add(PHREE\Tag::td()
						->setStyle($headerStyle)
						->add(PHREE\Text::create('Action'))
					)
					->add(PHREE\Tag::td()
						->setStyle($headerStyle)
						->add(PHREE\Text::create('Done'))
					)
				)
				->addBody(PHREE\Tag::tr()
					->add(PHREE\Tag::td()
						->add(PHREE\Field::create('Action'))
					)
					->add(PHREE\Tag::td()
						->setStyle($doneStyle)
						->add(PHREE\Field::create('Done')
							->setFormatter($doneFormatter)
						)
					)
				)
			)
		)
	);

switch(@$_GET['mode']) {
	case 'pdf':
		header('Content-Type: application/pdf');
		echo $report->generatePDF($dataSource);
		break;
	default:
		echo $report->generateHTML($dataSource);
		break;
}
