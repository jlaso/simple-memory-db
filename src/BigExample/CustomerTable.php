<?php

namespace JLaso\SimpleMemoryDb\BigExample;

use JLaso\SimpleMemoryDb\AbstractTable;

class CustomerTable extends AbstractTable
{
    protected $indexMap = [
        'tax_type_id',
        'country_id',
    ];
}