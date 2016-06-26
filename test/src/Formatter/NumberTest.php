<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\Number;

class NumberTest extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testFormatNumber($value, $expectedValue, $decimals, $decimalSeperator, $thousandsSeperator, $rounding) {
		$this->formatter->setFormatCustom($decimals, $decimalSeperator, $thousandsSeperator, $rounding);
		$this->assertFormatValue($expectedValue, $value);
	}

	public function dataProvider() {
		// value, expectedValue, decimals, decimalSeperator, thousandsSeperator, rounding
		return [
			['14', '14', 0, '', '', true],
			['62.78', '63', 0, '', '', true],
			['57', '57', 2, '.', '', true],
			['7.6929', '7,6', 1, ',', '', false],
			['12345678.6543', '12,345,678.654300', 6, '.', ',', true],
			['123456789.1', '123 456 789,1', 1, ',', ' ', true],
			['76.87', '769', 1, '', '', true],
			[null, '0', 0, '', '', true],
		];
	}

	/* --- */

	/**
	 * @var Number
	 */
	private $formatter;

	protected function setUp() {
		parent::setUp();

		$this->formatter = new Number();
	}

	private function assertFormatValue($expected, $value) {
		$this->assertEquals($expected, $this->formatter->formatValue($value));
	}

}
