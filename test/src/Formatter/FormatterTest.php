<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\Formatter;
use PHRE\DataSource\DataRecord;
use PHRE\DataHolder\Style;

class FormatterTest extends TestCase {

    /**
     * @dataProvider dataProvider
     */
    public function testFormatValueUnchanged($value, $expectedEmptyValue) {
        $this->assertFormatValue($value, $value);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFormatValueEmpty($value, $expectedEmptyValue) {
        $this->formatter->setValueForEmptyValue('empty');
        $this->assertFormatValue($expectedEmptyValue, $value);
    }

    public function testSetStyleDefault() {
        $defaultStyle = new Style();

        $this->formatter->setStyle($defaultStyle);
        $formatterStyle = $this->formatter->getStyle($this->createDataRecord());

        $this->assertEquals($defaultStyle, $formatterStyle);
    }

    public function testSetStyleWhenPositiveConditional() {
        $positiveStyle = new Style();

        $this->formatter->setStyle(
            $positiveStyle,
            function (DataRecord $dataRecord) {
                return ($dataRecord->get('value') >= 0);
            }
        );

        $formatterStyle = $this->formatter->getStyle($this->createDataRecord(10));

        $this->assertEquals($positiveStyle, $formatterStyle);
    }

    public function testSetStyleNoneWhenPositiveConditional() {
        $this->formatter->setStyle(
            new Style(),
            function (DataRecord $dataRecord) {
                return ($dataRecord->get('Value') >= 0);
            }
        );

        $formatterStyle = $this->formatter->getStyle($this->createDataRecord(-10));

        $this->assertNull($formatterStyle);
    }

    public function testSetStyleDefaultWhenPositiveConditionalForNegative() {
        $defaultStyle = new Style();
        $positiveStyle = new Style();

        $this->formatter->setStyle($defaultStyle);
        $this->formatter->setStyle(
            $positiveStyle,
            function (DataRecord $dataRecord) {
                return ($dataRecord->get('Value') >= 0);
            }
        );

        $formatterStyle = $this->formatter->getStyle($this->createDataRecord(10));

        $this->assertEquals($defaultStyle, $formatterStyle);
    }

    public function testSetStyleConditionalOrder() {
        $firstStyle = new Style();
        $secondStyle = new Style();

        $this->formatter->setStyle(
            $firstStyle,
            function (DataRecord $dataRecord) {
                return ($dataRecord->get('Value') >= 20);
            }
        );
        $this->formatter->setStyle(
            $secondStyle,
            function (DataRecord $dataRecord) {
                return ($dataRecord->get('Value') >= 10);
            }
        );

        $formatterStyle = $this->formatter->getStyle($this->createDataRecord(50));

        $this->assertEquals($firstStyle, $formatterStyle);
    }

    public function dataProvider() {
        // value, expectedEmptyValue
        return [
            [14, 14],
            [62.78, 62.78],
            [-5, -5],
            ['57', '57'],
            ['test', 'test'],
            ['', 'empty'],
            [true, true],
            [false, false],
            [null, 'empty'],
        ];
    }

    /* --- */

    /**
     * @var Formatter
     */
    private $formatter;

    protected function setUp() {
        parent::setUp();

        $this->formatter = new Formatter();
    }

    private function assertFormatValue($expected, $value) {
        $this->assertEquals($expected, $this->formatter->formatValue($value));
    }

    /**
     * @return DataRecord
     */
    private function createDataRecord($value = null) {
        return new DataRecord(['Value' => $value]);
    }

}
