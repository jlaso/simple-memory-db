<?php

namespace JLaso\SimpleMemoryDb\BigExample;

use JLaso\SimpleMemoryDb\AbstractTable;

class TaxTable extends AbstractTable
{
    protected $subtypes = [
        0 => 'mandatory',
        1 => 'optional',
        2 => 'regulated',
        3 => 'unknown',
    ];

    protected function processRecord(&$record)
    {
        $module = count($this->subtypes);
        $record['subtype'] = $this->subtypes[intval($record['value'] * 100) % $module];
    }
}