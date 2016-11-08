<?php

namespace JLaso\SimpleMemoryDb\BigExample;

require_once __DIR__.'/../../vendor/autoload.php';

use JLaso\SimpleMemoryDb\RepositoryInterface;

/** @var RepositoryInterface[] $databases */
$databases = [];

$databases['customers'] = new CustomerTable(__DIR__.'/customers.json');
$databases['taxes'] = new TaxTable(__DIR__.'/taxes.json');
$databases['countries'] = new CountryTable(__DIR__.'/countries.json');

$countryId = 1 + rand(0, count($databases['countries']->findAll()));
$country = $databases['countries']->find($countryId);

printf("Showing results for country %s ...\r\n", $country['name']);
foreach ($databases['customers']->findAll('country_id', $countryId) as $customer) {
    $tax = $databases['taxes']->find($customer['tax_type_id']);

    printf(
        "Customer (%d) %s applies %s '%s' [%.2f%%]\r\n",
        $customer['id'],
        $customer['name'],
        $tax['name'],
        $tax['subtype'],
        $tax['value']
    );
}

printf("There are %d customers\r\n", $databases['customers']->count());
