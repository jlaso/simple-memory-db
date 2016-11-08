<?php

namespace JLaso\SimpleMemoryDb;

use JLaso\SimpleMemoryDb\Example\CustomerTable;
use JLaso\SimpleMemoryDb\Example\Customer;
use JLaso\SimpleMemoryDb\Tests\AbstractTestCase;

class ToArrayTest extends AbstractTestCase
{
    public function testSave()
    {
        $customerTbl = new CustomerTable();

        $customer = new Customer(1, 'Test name', null);
        $customerTbl->insert($customer);
        $customerTbl->saveToJsonFile($this->tmpFile);

        $customerTbl = new CustomerTable($this->tmpFile);
        $customer = $customerTbl->find(1);
        $this->assertEquals($customer['name'], 'Test name');
    }
}
