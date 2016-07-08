<?php

include __DIR__.'/../../vendor/autoload.php';

use \PHRE\Entities as PHREE;

$report = new PHRE\Report();

$report
	->add(PHREE\Page::create()
		->add(PHREE\Tag::h1()
			->add(PHREE\Text::create('Hello World!'))
		)
	);

$dataSource = new PHRE\DataSource\DataSource([[]]);

switch(@$_GET['mode']) {
	case 'pdf':
		header('Content-Type: application/pdf');
		echo $report->generatePDF($dataSource);
		break;
	default:
		echo $report->generateHTML($dataSource);
		break;
}
