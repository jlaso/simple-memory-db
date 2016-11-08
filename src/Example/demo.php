<?php

namespace JLaso\SimpleMemoryDb\Example;

require_once __DIR__.'/../../vendor/autoload.php';

use JLaso\SimpleMemoryDb\RepositoryInterface;

/** @var RepositoryInterface[] $databases */
$databases = [];

$databases['customers'] = new CustomerTable(__DIR__.'/customers.json');
$databases['taxes'] = new TaxTable(__DIR__.'/taxes.json');

foreach ($databases['customers']->findAll() as $customer) {
    $tax = $databases['taxes']->find($customer['tax_type_id']);

    printf(
        "Customer (%d) %s applies %s [%f]\r\n",
        $customer['id'],
        $customer['name'],
        $tax['name'],
        $tax['percent']
    );
}

printf("There are %d customers\r\n", $databases['customers']->count());
