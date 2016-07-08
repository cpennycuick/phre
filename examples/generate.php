<?php

$output = [];

foreach (glob(__DIR__.'/*/') as $path) {

	$xml = simplexml_load_file("{$path}/example.xml");

	if (!$xml) {
		continue;
	}

	$output[] = [
		'Path' => (string) $xml->Path,
		'Name' => (string) $xml->Name,
		'Description' => (string) $xml->Description,
	];

	foreach (['html', 'pdf'] as $mode) {
		$_GET['mode'] = $mode;

		ob_start();
		include "{$path}example.php";
		$content = ob_get_contents();
		ob_end_clean();

		file_put_contents("{$path}example.{$mode}", $content);
	}
}

file_put_contents(__DIR__.'/examples.js', 'renderExamples('.json_encode($output).');');

header('Content-Type: text/plain');
print_r($output);
