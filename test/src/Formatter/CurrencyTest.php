<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\Currency;

class CurrencyTest extends TestCase {

    /**
     * @dataProvider dataProviderCurrencyCode
     */
    public function testFormatCurrency($value, $expectedValue, $currencySymbol, $currencyCode) {
        $this->formatter->setCurrency($currencySymbol, $currencyCode);
        $this->assertFormatValue($expectedValue, $value);
    }

    public function dataProviderCurrencyCode() {
        // value, expectedValue, currencySymbol, currentyCode
        return [
            ['2514.5', '2,514.50', null, null],
            ['2', '$2.00', '$', null],
            ['100.13', '100.13 AUD', null, 'AUD'],
            ['45.7', '$45.70 AUD', '$', 'AUD'],
            ['-45.7', '-45.70', null, null],
            ['-69.7', '-$69.70', '$', null],
        ];
    }

    /**
     * @dataProvider dataProviderNegative
     */
    public function testFormatNegativeCurrency($value, $expectedValue, $preCurrencySymbol, $preNumber, $postNumber, $postCurrencyCode) {
        $this->formatter->setCurrency('$', 'D');
        $this->formatter->setNegativeFormat($preCurrencySymbol, $preNumber, $postNumber, $postCurrencyCode);
        $this->assertFormatValue($expectedValue, $value);
    }

    public function dataProviderNegative() {
        // value, expectedValue, preCurrencySymbol, preNumber, postNumber, postCurrencyCode
        return [
            ['-24.7', '$24.70 D', '', '', '', ''],
            ['-7.12', '-$7.12 D', '-', '', '', ''],
            ['-985', '$-985.00 D', '', '-', '', ''],
            ['-91.00', '$(91.00) D', '', '(', ')', ''],
            ['-8096', '-$8,096.00 D-', '-', '', '', '-'],
            ['-1.9', '!$@1.90# D$', '!', '@', '#', '$'],
        ];
    }

    /* --- */

    /**
     * @var Currency
     */
    private $formatter;

    protected function setUp() {
        parent::setUp();

        $this->formatter = new Currency();
    }

    private function assertFormatValue($expected, $value) {
        $this->assertEquals($expected, $this->formatter->formatValue($value));
    }

}
