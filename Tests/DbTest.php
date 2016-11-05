<?php

namespace JLaso\SimpleMemoryDb;

use JLaso\SimpleMemoryDb\Example\CustomerTable;

class DbTest extends \PHPUnit_Framework_TestCase
{
    protected $folder = __DIR__.'/../src/Example/';

    public function testEmptyDB()
    {
        $customerTbl = new CustomerTable();

        $this->assertTrue($customerTbl->count() === 0);
    }

    public function testRecords()
    {
        $customerTbl = new CustomerTable($this->folder.'customers.json');

        $this->assertEquals($customerTbl->count(), 2);
        $customer = $customerTbl->find(1);
        $this->assertEquals($customer['name'], 'John Doe');
        $customer = $customerTbl->find(2);
        $this->assertEquals($customer['name'], 'Jane Doe');
        $customer = $customerTbl->find(3);
        $this->assertNull($customer);
    }

    public function testSave()
    {
        $tmp = __DIR__.'/tmp.json';
        $customerTbl = new CustomerTable();
        $customerTbl->saveToJsonFile($tmp);
        $this->assertEquals(file_get_contents($tmp), '[]');

        $customerTbl->insert(['id'=>1, 'name'=>'Test name', 'tax_type_id' => null]);
        $customerTbl->saveToJsonFile($tmp);

        $customerTbl = new CustomerTable($tmp);
        $customer = $customerTbl->find(1);
        $this->assertEquals($customer['name'], 'Test name');

        @unlink($tmp);
    }
}

