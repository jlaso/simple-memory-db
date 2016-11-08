<?php

namespace JLaso\SimpleMemoryDb\Example;

use JLaso\SimpleMemoryDb\AbstractTable;

class CustomerTable extends AbstractTable
{
    protected $indexMap = [
        'tax_type_id',
    ];
}
