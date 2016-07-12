<?php

namespace PHRE\DataHolder;

class Style implements SpecialAttribute
{

    private $elements = [];

    public function hasElements()
    {
        return (count($this->elements) > 0);
    }

    public function set($name, $value = null)
    {
        if ($name instanceof Style) {
            $name->mergeInto($this);
        } elseif (strlen(trim($value)) === 0) {
            unset($this->elements[$name]);
        } else {
            $this->elements[$name] = $value;
        }

        return $this;
    }

    public function fromText($text)
    {
        foreach ($this->parseStyleText($text) as $name => $value) {
            $this->setStyle($name, $value);
        }
    }

    private function parseStyleText($styleText)
    {
        $elements = [];

        if (strlen(trim($styleText)) > 0) {
            foreach (explode(';', $styleText) as $stylePart) {
                list($name, $value) = explode(':', (array) $stylePart, 2);
                $elements[$name] = $value;
            }
        }

        return $elements;
    }

    public function mergeInto($style)
    {
        foreach ($this->elements as $name => $value) {
            $style->set($name, $value);
        }
    }

    public function getAsText()
    {
        return (string) $this;
    }

    public function __toString()
    {
        $parts = [];

        foreach ($this->elements as $name => $value) {
            $parts[] = "{$name}:{$value};";
        }

        return implode('', $parts);
    }

    public function reset()
    {
        $this->elements = [];
    }

}
