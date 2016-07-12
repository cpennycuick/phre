<?php

namespace PHRE\DataHolder;

interface SpecialAttribute
{

    public function fromText($text);

    public function hasElements();

    public function reset();
}
