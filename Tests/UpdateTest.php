<?php

namespace JLaso\SimpleMemoryDb;

use JLaso\SimpleMemoryDb\Example\CustomerTable;
use JLaso\SimpleMemoryDb\Example\Customer;
use JLaso\SimpleMemoryDb\Tests\AbstractTestCase;

class UpdateTest extends AbstractTestCase
{
    public function testUpdate()
    {
        // prepare some records to write in the json file
        $customerTbl = new CustomerTable();

        $customer = new Customer(1, 'Customer 1', 1);
        $customerTbl->insert($customer);
        $customer = new Customer(2, 'Customer 2', 2);
        $customerTbl->insert($customer);

        $customerTbl->saveToJsonFile($this->tmpFile);

        // create the DB from the json file and start testing
        $customerTbl = new CustomerTable($this->tmpFile);

        $customers = $customerTbl->findAll();
        $this->assertEquals(2, count($customers));
        $this->assertArrayHasKey(1, $customers);
        $this->assertArrayHasKey(2, $customers);
        $this->assertEquals($customers[1]['name'], 'Customer 1');

        // demostration of update
        $customer = new Customer(1, 'No customer 1 anymore', 1);
        $customerTbl->update($customer);

        $customers = $customerTbl->findAll();
        $this->assertEquals(2, count($customers));
        $this->assertArrayHasKey(1, $customers);
        $this->assertArrayHasKey(2, $customers);
        $this->assertNotEquals($customers[1]['name'], 'Customer 1');
        $this->assertEquals($customers[1]['name'], 'No customer 1 anymore');
    }
}

