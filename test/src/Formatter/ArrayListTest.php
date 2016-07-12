<?php

use PHPUnit\Framework\TestCase;

use PHRE\Formatter\ArrayList;

class ArrayListTest extends TestCase {

    private $testArray = [
        'One' => '1',
        'Two' => '2',
    ];

    public function testFormatArray() {
        $this->assertFormatValue('One = 1; Two = 2');
    }

    public function testFormatArrayValuesOnly() {
        $this->formatter->setFormatSeparators('; ', null);
        $this->assertFormatValue('1; 2');
    }

    public function testFormatArrayFromJSON() {
        $this->formatter->setIsJSON(true);
        $this->assertFormatValue('One = 1; Two = 2', json_encode($this->testArray));
    }

    public function testFormatArrayFromText() {
        $this->formatter->setValueSeparators(';', ':');
        $this->assertFormatValue('One = 1; Two = 2', 'One:1;Two:2');
    }

    public function testFormatArrayValuesFromText() {
        $this->formatter->setFormatSeparators(', ', null);
        $this->formatter->setValueSeparators(';', ':');
        $this->assertFormatValue('1, 2', 'One:1;Two:2');
    }

    /* --- */

    /**
     * @var Number
     */
    private $formatter;

    protected function setUp() {
        parent::setUp();

        $this->formatter = new ArrayList();
        $this->formatter->setFormatSeparators('; ', ' = ');
    }

    private function assertFormatValue($expected, $value = null) {
        $this->assertEquals($expected, $this->formatter->formatValue($value ?: $this->testArray));
    }

}
