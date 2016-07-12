<?php

namespace PHRE\Formatter;

class DateTime extends Formatter
{

    private $srcFormat;
    private $destFormat = 'Y-m-d H:i:s';

    public function setFormat($format)
    {
        $this->destFormat = $format;
    }

    public function setFromFormat($format)
    {
        $this->srcFormat = $format;
    }

    public function formatValue($value)
    {
        $value = parent::formatValue($value);

        if ($this->srcFormat) {
            $dateTime = \DateTime::createFromFormat('!' . $this->srcFormat, $value);
        } else {
            $dateTime = new \DateTime($value);
        }

        return $dateTime->format($this->destFormat);
    }

}
