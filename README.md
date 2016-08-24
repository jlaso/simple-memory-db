# simple-memory-db

A simple db in memory, has to be populated from JSON data.

For now is read-only memory, quick to access data and manipulate in order to process data in a very high speed way.

# Installation

You need only to require this package in your project ```composer require jlaso/simple-memory-db```

# Look at the Example to see how use it

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

In order to populate the memory table with real data:



