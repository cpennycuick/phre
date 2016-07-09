<?php

include __DIR__.'/../vendor/autoload.php';

use phpDocumentor\Reflection\DocBlock;

$factory  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();

$rootPath = __DIR__.'/../src/';

$dirIterator = new RecursiveDirectoryIterator($rootPath);
$iterator = new RecursiveIteratorIterator(
	$dirIterator,
	RecursiveIteratorIterator::SELF_FIRST
);

header('Content-Type: text/plain');

$content = [];

foreach ($iterator as $file) {
	/* @var $file SplFileInfo */
	if (!$file->isFile() ||  ($file->getExtension() !== 'php')) {
		continue;
	}

	$className = getClassNameFromFilePath($rootPath, (string) $file);
	$class = new ReflectionClass($className);

	$classContent = [
		'ClassName' => $className,
		'Description' => null,
	];

	if ($class->getDocComment()) {
		$classContent['Description'] = formatDocumentation($factory->create($class->getDocComment()));
	}

	$classMethods = [];
	foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
		/* @var $method ReflectionMethod */

		if ($method->getDeclaringClass()->getName() !== $class->getName()) {
			continue;
		}

		if ($method->getDocComment()) {
			$methodContent = formatDocumentation($factory->create($method->getDocComment()));
		} else {
			$methodContent = '';
		}

		$classMethods[$method->getName()] = $methodContent;
	}

	$classContent['Methods'] = $classMethods;

	$content[] = $classContent;
}

file_put_contents('documentation.js', 'renderDocumentation('.json_encode($content, JSON_PRETTY_PRINT).')');

return;

function getClassNameFromFilePath($rootPath, $fileName) {
	$rootPath = str_replace('\\', '/', $rootPath);
	$fileName = str_replace('\\', '/', $fileName);
	$fileName = str_replace('.php', '', $fileName);

	$className = str_replace($rootPath, '', $fileName);
	$className = str_replace('/', '\\', 'PHRE/'.$className);

	return $className;
}

function formatDocumentation(DocBlock $docBlock) {
	$parts = [
		'<b>'.$docBlock->getSummary().'</b><br/>',
		'<p>'.(trim($docBlock->getDescription()) ?: '<i>Missing</i>').'</p>',
	];

	foreach ($docBlock->getTags() as $tag) {
		$parts[] = formatTag($tag);
	}

	return implode('', $parts);
}

function formatTag($tag) {
	switch ($tag->getName()) {
		case 'example':
			$name = 'Example';
			$content = "<code>{$tag->getDescription()}</code>";
			break;
		default:
			$name = $tag->getName();
			$content = "<b>{$tag->getDescription()}</b>";
	}

	return "<div><b>$name</b><br/>$content</div>";
}
