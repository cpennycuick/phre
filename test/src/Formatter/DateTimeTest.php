<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\DateTime;

class DateTimeTest extends TestCase {

    public function testFormatDefaultDateTime() {
        $this->assertFormatValue('2020-12-31 23:59:59', '2020/12/31T23:59:59');
    }

    /**
     * @dataProvider dataProviderCurrencyCode
     */
    public function testFormatCurrency($expectedValue, $value, $srcFormat, $destFormat) {
        $this->formatter->setFromFormat($srcFormat);
        $this->formatter->setFormat($destFormat);
        $this->assertFormatValue($expectedValue, $value);
    }

    public function dataProviderCurrencyCode() {
        // expectedValue, value, srcFormat, destFormat
        return [
            ['31-12-2020', '31/12/2020', 'd/m/Y', 'd-m-Y'],
            ['20201231', '31/12/2020', 'd/m/Y', 'Ymd'],
            ['December 31st, 2020', '1609372800', 'U', 'F jS, Y'],
            ['1609372800', 'December 31st, 2020', 'F jS, Y', 'U'],
            ['00:02:00', '120', 'U', 'H:i:s'],
        ];
    }

    /* --- */

    /**
     * @var DateTime
     */
    private $formatter;

    protected function setUp() {
        parent::setUp();

        $this->formatter = new DateTime();
    }

    private function assertFormatValue($expected, $value) {
        $this->assertEquals($expected, $this->formatter->formatValue($value));
    }

}
