<?php

namespace PHRE\Formatter;

class ArrayList extends Formatter
{

    private $isJSON = false;
    private $valueSeperatorValue = null;
    private $valueSeperatorKeyValue = null;
    private $formatSeperatorValue = null;
    private $formatSeperatorKeyValue = null;

    public function setIsJSON($isJSON)
    {
        $this->isJSON = $isJSON;
        return $this;
    }

    /**
     * Seperators used to break the string value in to an array.
     * @param type $seperatorValue
     * @param type $seperatorKeyValue
     * @return \PHRE\Formatter\ArrayList
     */
    public function setValueSeparators($seperatorValue, $seperatorKeyValue)
    {
        $this->valueSeperatorValue = $seperatorValue;
        $this->valueSeperatorKeyValue = $seperatorKeyValue;

        return $this;
    }

    /**
     * Seperators used to glue the array back together formatted;
     * @param type $seperatorValue
     * @param type $seperatorKeyValue
     * @return \PHRE\Formatter\ArrayList
     */
    public function setFormatSeparators($seperatorValue, $seperatorKeyValue)
    {
        $this->formatSeperatorValue = $seperatorValue;
        $this->formatSeperatorKeyValue = $seperatorKeyValue;

        return $this;
    }

    public function formatValue($value)
    {
        $value = parent::formatValue($value);

        if (is_array($value)) {
            return $this->formatArray($value);
        } elseif ($this->isJSON) {
            $array = json_decode($value, true);
            assert($array !== false);

            return $this->formatArray($array);
        } elseif ($value) {
            return $this->formatArray(
                    $this->explodeValue($value)
            );
        } else {
            return '';
        }
    }

    private function explodeValue($value)
    {
        if (!$this->hasValue($this->valueSeperatorValue)) {
            $baseArray = [$value];
        } else {
            $baseArray = explode($this->valueSeperatorValue, $value);
        }

        $hasSeperatorKeyValue = $this->hasValue($this->valueSeperatorKeyValue);

        $array = [];
        foreach ($baseArray as $entry) {
            if (!$hasSeperatorKeyValue) {
                $array[] = $entry;
                continue;
            }

            list($key, $entryVaue) = explode($this->valueSeperatorKeyValue, $entry, 2);

            if ($entryVaue === null) {
                $array[] = $key;
            } else {
                $array[$key] = $entryVaue;
            }
        }

        return $array;
    }

    private function formatArray($array)
    {
        if ($this->hasValue($this->formatSeperatorKeyValue)) {
            $return = [];
            foreach ($array as $key => $value) {
                $return[] = "{$key}{$this->formatSeperatorKeyValue}{$value}";
            }
        } else {
            $return = array_values($array);
        }

        return implode($this->formatSeperatorValue ? : '', $return);
    }

    private function hasValue($value)
    {
        return ($value !== null && $value !== '');
    }

}
