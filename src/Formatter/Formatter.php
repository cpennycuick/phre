<?php

namespace PHRE\Formatter;

use \PHRE\DataHolder\Style;
use \PHRE\DataSource\DataRecord;

class Formatter
{

    private $valueForEmptyValue = null;
    private $styleDefault = null;
    private $styleConditional = [];

    public function setValueForEmptyValue($valueForEmptyValue)
    {
        $this->valueForEmptyValue = $valueForEmptyValue;
        return $this;
    }

    /**
     * @param \PHRE\Formatter\Style $style
     * @param \Closure $conditionFn function(\PHRE\DataSource\DataRecord $record):Boolean
     * @return \PHRE\Formatter\Formatter
     */
    public function setStyle(Style $style, \Closure $conditionFn = null)
    {
        if ($conditionFn) {
            $this->styleConditional[] = [$style, $conditionFn];
        } else {
            $this->styleDefault = $style;
        }

        return $this;
    }

    public function getStyle(DataRecord $dataRecord)
    {
        $style = $this->styleDefault;

        foreach ($this->styleConditional as $condition) {
            list($conditionStyle, $conditionFn) = $condition;

            if (call_user_func($conditionFn, $dataRecord)) {
                $style = $conditionStyle;
                break;
            }
        }

        return $style;
    }

    public function formatValue($value)
    {
        if ($this->isValueEmpty($value) && $this->valueForEmptyValue !== null) {
            return $this->valueForEmptyValue;
        } else {
            return $value;
        }
    }

    private function isValueEmpty($value)
    {
        return ($value === null || $value === '');
    }

}
