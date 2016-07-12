<?php

namespace PHRE\Entities;

class Page extends Element
{

    use \PHRE\Entities\Feature\SubElements;

    public static function create()
    {
        return new static();
    }

    public function render(\PHRE\DataSource\DataSource $data)
    {
        return implode('', [
            '<div class="Page">',
            $this->renderElements($data),
            '</div>',
        ]);
    }

    public function reset()
    {
        $this->resetElements();
    }

}
