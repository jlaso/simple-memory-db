<?php

namespace JLaso\SimpleMemoryDb;

use JLaso\SimpleMemoryDb\Example\CustomerTable;
use JLaso\SimpleMemoryDb\Example\Customer;
use JLaso\SimpleMemoryDb\Tests\AbstractTestCase;

class FilterTest extends AbstractTestCase
{
    public function testFilter()
    {
        $customerTbl = new CustomerTable();

        $customer = new Customer(1, 'Customer 1', 1);
        $customerTbl->insert($customer);
        $customer = new Customer(2, 'Customer 2', 2);
        $customerTbl->insert($customer);

        $customerTbl->saveToJsonFile($this->tmpFile);

        $customerTbl = new CustomerTable($this->tmpFile);

        $customers = $customerTbl->findAll();
        $this->assertEquals(2, count($customers));

        $customers = $customerTbl->findAll(null, null, function ($r) {
            return $r['tax_type_id'] == 2;
        });
        $this->assertEquals(1, count($customers));

        $this->assertArrayHasKey(2, $customers);
        $this->assertEquals($customers[2]['name'], 'Customer 2');
        $this->assertArrayNotHasKey(1, $customers);
    }
}
