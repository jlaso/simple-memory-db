# simple-memory-db

A simple db in memory, that has to be populated from JSON data.

It lives entirely in memory, quick to access data and manipulate in order to process data at very high speed.

# Installation

You need only to require this package in your project ```composer require jlaso/simple-memory-db```

# Look at the Examples folders to see how to use it

## Example

You have a very simple example with two tables: customers and taxes, each customer has a tax_type associated.

In order to implement your data you have to create a class extending AbstractTable and declare the property $indexMap

by default ```id``` is automatically indexed. So, this field is mandatory in every table, must come in the json.

```
namespace JLaso\SimpleMemoryDb\Example;

use JLaso\SimpleMemoryDb\AbstractTable;

class CustomerTable extends AbstractTable
{
    protected $indexMap = [
        "tax_type",
    ];
}
```

## BigExample

You can just squeeze it in order to know how the limit of this database is in your system.

In order to populate the memory tables with real data, the json files can be generated parametrically:

```php BigExample/generate.php```

And to see the results

```php BigExample/demo.php```

### If you need to add some extra field or process the records somehow when they are loaded in memory you can implement the method ```processRecord``` in your table

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
    }
}
```

Although is not a real database you can insert new elements or remove the existent ones:

```insert($data)```

```remove($id)```

And, why not ... dump it into a json file again.



