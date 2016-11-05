<?php

namespace JLaso\SimpleMemoryDb;

use JLaso\SimpleMemoryDb\Example\CustomerTable;

class Customer implements ToArrayInterface
{
    protected $id;
    protected $name;
    protected $taxType;

    /**
     * @param int $id
     * @param string $name
     * @param int $taxType
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

class ToArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $tmp = __DIR__.'/tmp.json';
        $customerTbl = new CustomerTable();

        $customer = new Customer(1, 'Test name', null);
        $customerTbl->insert($customer);
        $customerTbl->saveToJsonFile($tmp);

        $customerTbl = new CustomerTable($tmp);
        $customer = $customerTbl->find(1);
        $this->assertEquals($customer['name'], 'Test name');

        @unlink($tmp);
    }
}

