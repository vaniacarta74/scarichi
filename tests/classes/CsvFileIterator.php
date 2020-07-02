<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CsvFileIterator
 *
 * @author vania
 */
namespace vaniacarta74\scarichi\tests\classes;

class CsvFileIterator implements \Iterator
{
    protected $file;
    protected $key = 0;
    protected $current;
    protected $delimiter = ';';

    public function __construct($file)
    {
        $this->file = fopen($file, 'r');
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function rewind()
    {
        rewind($this->file);
        $this->current = fgetcsv($this->file, 0, $this->delimiter);
        $this->key = 0;
    }

    public function valid()
    {
        return !feof($this->file);
    }

    public function key()
    {
        return $this->key;
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        $this->current = fgetcsv($this->file, 0, $this->delimiter);
        $this->key++;
    }
}
