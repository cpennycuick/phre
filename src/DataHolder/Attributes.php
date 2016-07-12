<?php

namespace PHRE\DataHolder;

class Attributes
{

    const ATTR_STYLE = 'style';
    const ATTR_CLASS = 'class';

    private $attributes = [];
    private $specialAttributes = [];

    public function __construct()
    {
        $this->specialAttributes[self::ATTR_STYLE] = new Style();
        $this->specialAttributes[self::ATTR_CLASS] = new AttributeList();
    }

    public function style()
    {
        return $this->specialAttributes[self::ATTR_STYLE];
    }

    public function classes()
    {
        return $this->specialAttributes[self::ATTR_CLASS];
    }

    public function hasAttributes()
    {
        foreach ($this->specialAttributes as $attribute) {
            if ($attribute->hasElements()) {
                return true;
            }
        }

        return (count($this->attributes) > 0);
    }

    public function set($name, $value = null)
    {
        if (array_key_exists(strtolower($name), $this->specialAttributes)) {
            $this->specialAttributes[strtolower($name)]->fromText($value);
        } else {
            $this->attributes[$name] = $value;
        }

        return $this;
    }

    public function remove($name)
    {
        if (array_key_exists(strtolower($name), $this->specialAttributes)) {
            $this->specialAttributes[strtolower($name)]->reset();
        } else {
            unset($this->attributes[$name]);
        }
    }

    public function getAsText()
    {
        return (string) $this;
    }

    public function __toString()
    {
        if (!$this->hasAttributes()) {
            return '';
        }

        $parts = [];

        foreach ($this->specialAttributes as $name => $value) {
            if ($value->hasElements()) {
                $value = addcslashes((string) $value, '"');
                $parts[] = "{$name}=\"{$value}\"";
            }
        }

        foreach ($this->attributes as $name => $value) {
            if (!empty($value)) {
                // Prevent bad values from breaking our quoting
                $value = addcslashes($value, '"');
                $parts[] = "{$name}=\"{$value}\"";
            } else {
                $parts[] = $name;
            }
        }

        return implode(' ', $parts);
    }

    public function reset()
    {
        $this->attributes = null;
        $this->style->reset();
    }

}
