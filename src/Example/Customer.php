<?php

namespace JLaso\SimpleMemoryDb\Example;

use JLaso\SimpleMemoryDb\ToArrayInterface;

class Customer implements ToArrayInterface
{
    protected $id;
    protected $name;
    protected $taxType;

    /**
     * @param int    $id
     * @param string $name
     * @param int    $taxType
     */
    public function __construct($id, $name, $taxType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->taxType = $taxType;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax_type_id' => $this->taxType,
        ];
    }
}
