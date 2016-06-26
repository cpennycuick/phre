<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\ArrayList;

class ArrayListTest extends TestCase {

	public function testFormatArray() {
		$this->formatter->setFormatSeparators('; ', ' = ');
		$this->assertFormatValue('One = 1; Two = 2', [
			'One' => '1',
			'Two' => '2',
		]);
	}

	public function testFormatArrayFromJSON() {
		$this->formatter->setIsJSON(true);
		$this->formatter->setFormatSeparators('; ', ' = ');
		$this->assertFormatValue('One = 1; Two = 2', '{"One":"1","Two":"2"}');
	}

	/* --- */

	/**
	 * @var Number
	 */
	private $formatter;

	protected function setUp() {
		parent::setUp();

		$this->formatter = new ArrayList();
	}

	private function assertFormatValue($expected, $value) {
		$this->assertEquals($expected, $this->formatter->formatValue($value));
	}

}
