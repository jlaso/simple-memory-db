# jlaso/simple-memory-db

[![Build Status](https://travis-ci.org/jlaso/simple-memory-db.svg?branch=master)](https://travis-ci.org/jlaso/simple-memory-db)

### A simple db in memory, that has to be populated from JSON data.

It lives entirely in memory, quick to access data and manipulate in order to process data at very high speed.

# Installation

You need only to require this package in your project 

```composer require jlaso/simple-memory-db```

### Look at the Examples folders to see how to use it

#### Example

You have a very simple example with two tables: customers and taxes, each customer has a tax_type associated.

In order to implement your data you have to create a class extending AbstractTable and declare the property $indexMap

by default ```id``` is automatically indexed. So, this field is mandatory in every table, must come in the json.

```
namespace JLaso\SimpleMemoryDb\Example;

use JLaso\SimpleMemoryDb\AbstractTable;

class CustomerTable extends AbstractTable
{
    protected $indexMap = [
        "tax_type_id",
    ];
}
```

#### BigExample

You can just squeeze it in order to know how big is the limit of this database is in your system.

In order to populate the memory tables with real data, the json files can be generated parametrically:

```php BigExample/generate.php```

And to see the results

```php BigExample/demo.php```

### ProcessRecord

If you need to add some extra fields or process the records somehow when they are loaded in memory you can implement the method ```processRecord``` in your table

```
class TaxTable extends AbstractTable
{
    protected $subtypes = [
        0 => 'mandatory',
        1 => 'optional',
        2 => 'regulated',
        3 => 'unknown',
    ];

    protected function processRecord(&$record)
    {
        $module = count($this->subtypes);
        $record['subtype'] = $this->subtypes[intval($record['value'] * 100) % $module];
        
        return true;  // or return false if you want to filter this record
    }
}
```

# Modifier methods

Although is not a real database you can insert new elements or remove the existent ones:

## Insert

- ```insert($data)```

This insert the new record in memory and updates 
the indices to make it accessible.
`$data` can be an array or an object that implements the `JLaso\SimpleMemoryDb\ToArrayInterface` 

## Update

This is an alias for insert, currently insert just replaces the existent copy of the record inserted if any.

## Remove

- ```remove($id)```

This removes the record pointed by ```$id``` and 
updates the indices to make it not accessible.


# Storing method

And, why not ... dump it into a json file again.

- ```saveToJsonFile($fileName)```

# Find methods

## find($id)

Just fetches the current record pointed by `$id` or null if does not exist.

## findAll($field, $value, $filterCallback)

if `$field` and `$value` are null returns all the records.

Always passes the record to `$filterCallback` being to added in order to know if 
passes the filter.  Obviously if `$filterCallback` is null no filter applied.
 
You can check the examples on the Tests folder.
 
## Exceptions
 
Since version 1.5 added the property `$notFoundThrowsException` in order to throw or not 
an Excepcion in the case the record is not found.


