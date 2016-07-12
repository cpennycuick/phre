<?php

namespace PHRE\DataSource\Iterator;

class PDOStatementIterator implements \Iterator
{

    /**
     * @var \PDOStatement
     */
    private $statment;
    private $record;
    private $valid;
    private $key;

    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
        $this->rewind();

        // Iterate to first record
        $this->next();
    }

    public function current()
    {
        return $this->record;
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        if ($this->key === null) {
            $this->valid = true;
            $this->key = 0;
        } elseif ($this->valid) {
            $this->key++;
        } else {
            return; // Don't try the next value if last one is invalid
        }

        $this->record = $this->statment->fetch(
            \PDO::FETCH_ASSOC, \PDO::FETCH_ORI_ABS, $this->key
        );

        if ($this->record === false) {
            $this->valid = false;
            $this->record = null;
        }
    }

    public function rewind()
    {
        $this->record = null;
        $this->valid = false;
        $this->key = null;
    }

    public function valid()
    {
        return $this->valid;
    }

}
