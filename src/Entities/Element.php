<?php

namespace PHRE\Entities;

use PHRE\DataSource\DataSource;

abstract class Element
{

    protected function __construct()
    {

    }

    abstract public function render(DataSource $data);

    abstract public function reset();
}
