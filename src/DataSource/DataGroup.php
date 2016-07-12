<?php

namespace PHRE\DataSource;

class DataGroup
{

    const ACTION_SUM = 'Sum';
    const ACTION_COUNT = 'Count';

    private $data;
    private $groupField;
    private $dataRecord = null;
    private $values = [];

    public function __construct(DataSource $data, $groupField)
    {
        $this->data = $data;
        $this->groupField = $groupField;
    }

    public function startCalc($field)
    {
        $this->values[$field] = [
            self::ACTION_COUNT => 0,
            self::ACTION_SUM => 0,
        ];

        echo "Start calc for $field (" . spl_object_hash($this) . ")\n";
        $this->processRecordField($field, $this->values[$field]);
    }

    public function resetValues()
    {
        foreach ($this->values as &$values) {
            $values[self::ACTION_COUNT] = 0;
            $values[self::ACTION_SUM] = 0;
        }
    }

    public function getValue($field, $action)
    {
        if (!array_key_exists($field, $this->values)) {
            echo "No value for $field; " . implode(',', $this->values) . " (" . spl_object_hash($this) . ")\n";
            return null;
        }

        echo "Group get $action from $field\n";
        return $this->values[$field][$action];
    }

    public function isActive()
    {
        return $this->active;
    }

    public function processRecord()
    {
        if ($this->dataRecord && ($this->dataRecord === $this->data->current())) {
            return;
        }

        $this->dataRecord = $this->data->current();

        foreach ($this->values as $field => &$values) {
            $this->processRecordField($field, $values);
        }
    }

    private function processRecordField($field, &$values)
    {
        $fieldValue = $this->dataRecord->get($field);

        if ($fieldValue !== null) {
            $values[self::ACTION_COUNT] ++;
        }
        if (is_numeric($fieldValue)) {
            $values[self::ACTION_SUM] += $fieldValue;
        }
    }

    public function isLastRecordInGroup()
    {
        return $this->isLastRecord($this->groupField);
    }

    private function isLastRecord($field)
    {
        if (!$field) {
            return false;
        }

        $next = $this->data->peek();

        return ($this->data->current()->get($field) !== $next->get($field));
    }

}
