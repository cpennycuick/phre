<?php

namespace PHRE\Formatter;

class Number extends Formatter
{

    private $decimals = 0;
    private $decimalPoint = '';
    private $thousandsSeperator = '';
    private $enableRounding = true;

    public function setFormatCustom($decimals, $decimalPoint, $thousandsSeperator, $enableRounding = true)
    {
        $this->decimals = $decimals;
        $this->decimalPoint = $decimalPoint;
        $this->thousandsSeperator = $thousandsSeperator;
        $this->enableRounding = $enableRounding;

        return $this;
    }

    public function formatValue($value)
    {
        $number = parent::formatValue($value);

        if (!$this->enableRounding) {
            $exponent = pow(10, $this->decimals);
            $number = floor($number * $exponent) / $exponent;
        }

        return number_format(
            $number, $this->decimals, $this->decimalPoint, $this->thousandsSeperator
        );
    }

}
