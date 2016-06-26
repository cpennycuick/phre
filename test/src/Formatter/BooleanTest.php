<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\Boolean;

class BooleanTest extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testFormatBoolean($value, $expectedValue, $true, $false) {
		$this->formatter->setTrueFalseValues($true, $false);
		$this->assertFormatValue($expectedValue, $value);
	}

	public function dataProvider() {
		// value, expectedValue, true, false
		return [
			[true, '', null, null],
			[false, '', null, null],
			[true, 'True', 'True', null],
			[false, 'False', null, 'False'],
		];
	}

	/* --- */

	/**
	 * @var Boolean
	 */
	private $formatter;

	protected function setUp() {
		parent::setUp();

		$this->formatter = new Boolean();
	}

	private function assertFormatValue($expected, $value) {
		$this->assertEquals($expected, $this->formatter->formatValue($value));
	}

}
