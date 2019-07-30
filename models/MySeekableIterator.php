<?php

class MySeekableIterator implements SeekableIterator
{
    private $position;

    public $content = [];

    public function seek($position)
    {
        if (!isset($this->content[$position])) {
            throw new OutOfBoundsException("invalid seek position ($position)");
        }

        $this->position = $position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->content[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->content[$this->position]);
    }
}