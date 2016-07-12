<?php

namespace PHRE\Entities;

use \PHRE\DataSource\DataSource;
use \PHRE\DataSource\DataGroup;

class FieldCalc extends Field
{

    private $action;

    protected function __construct($field, $action)
    {
        parent::__construct($field);
        $this->action = $action;
    }

    public static function create($field, $action = DataGroup::ACTION_SUM)
    {
        return new static($field, $action);
    }

    public function getFieldName()
    {
        return $this->field;
    }

    private function getValue(DataSource $data)
    {
        if ($data->group()) {
            return $data->group()->getValue($this->field, $this->action);
        } else {
            return $data->current()->get($this->field);
        }
    }

}
