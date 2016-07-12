<?php

namespace PHRE\Entities\Tag;

use PHRE\Entities\Tag;

class td extends Tag
{

    public static function create()
    {
        return new static('td');
    }

}
