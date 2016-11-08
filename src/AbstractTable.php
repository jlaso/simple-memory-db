<?php

namespace JLaso\SimpleMemoryDb;

abstract class AbstractTable implements RepositoryInterface
{
    protected $data = [];
    protected $indices = [];
    protected $indexMap = [];
    protected $notFoundThrowsException = true;

    /**
     * populate the table with the data provided, data can be an array, a json file name or a json string
     * in any case we can provide a key to insert only the data found in this key, useful when the json array
     * is an associative array starting with the key as the filename like this one
     * {
     *    "customers":[
     *       ...
     *    ]
     * }.
     *
     * @param string|mixed $data
     * @param null|string  $key
     */
    public function __construct($data = null, $key = null)
    {
        if (null === $data) {
            return;
        }
        if (is_array($data)) {
            $this->loadFromAssocArray($data, $key);
        }
        if (is_string($data)) {
            if ('.json' === substr($data, -5)) {
                $this->loadFromJsonFile($data, $key);
            } else {
                $this->loadFromJsonString($data, $key);
            }
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return isset($this->data[$id]) ? json_decode(gzuncompress($this->data[$id]), true) : null;
    }

    /**
     * @param string   $field
     * @param mixed    $value
     * @param callable $filterCallback
     *
     * @return array
     *
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function findAll($field = null, $value = null, callable $filterCallback = null)
    {
        $result = [];
        if (!$field && !$value) {
            foreach ($this->data as $index => $item) {
                $record = json_decode(gzuncompress($item), true);
                if (!$filterCallback || $filterCallback($record)) {
                    $result[$index] = $record;
                }
            }
        } else {
            if (!isset($this->indices[$field][$value]) || !is_array($this->indices[$field][$value])) {
                if ($this->notFoundThrowsException) {
                    throw new \Exception("Index {$field} doesn't have value {$value} on ".get_called_class().
                        sprintf(' possible values are [%s]', implode(',', array_keys($this->indices[$field]))));
                } else {
                    return null;
                }
            }
            foreach ($this->indices[$field][$value] as $id) {
                $record = json_decode(gzuncompress($this->data[$id]), true);
                if (!$filterCallback || $filterCallback($record)) {
                    $result[$id] = $record;
                }
            }
        }

        return $result;
    }

    /**
     * @param string $field
     * @param $value
     *
     * @return int
     */
    public function count($field = null, $value = null)
    {
        if (!$field && !$value) {
            return count($this->data);
        }

        return isset($this->indices[$field][$value]) ? count($this->indices[$field][$value]) : 0;
    }

    /**
     * @param mixed $data
     */
    public function insert($data)
    {
        if ($data instanceof ToArrayInterface) {
            $data = $data->toArray();
        }
        if (!$this->processRecord($data)) {
            return;
        }
        $this->data[$data['id']] = gzcompress(json_encode($data), 9);
        // process indices (insert the value into indices)
        foreach ($this->indexMap as $field) {
            $this->indices[$field][$data[$field]][] = $data['id'];
        }
    }

    /**
     * currently insert just overwrites the record by ID.
     *
     * @param $data
     */
    public function update($data)
    {
        return $this->insert($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function remove($id)
    {
        if (isset($this->data[$id])) {
            $item = json_decode(gzuncompress($this->data[$id]), true);
            // process indices (remove the value from indices)
            foreach ($this->indexMap as $field) {
                $index = array_search($id, $this->indices[$field][$item[$field]]);
                if (false !== $index) {
                    unset($this->indices[$field][$item[$field]][$index]);
                }
            }
            // remove data
            unset($this->data[$id]);

            return true;
        }

        return false;
    }

    /**
     * @param string      $data
     * @param null|string $key
     */
    private function loadFromJsonString($data, $key = null)
    {
        $data = $key ? (isset($data[$key]) ? $data[$key] : []) : $data;
        $this->loadFromAssocArray(json_decode($data, true));
    }

    /**
     * @param mixed       $data
     * @param null|string $key
     */
    private function loadFromAssocArray($data, $key = null)
    {
        $data = $key ? (isset($data[$key]) ? $data[$key] : []) : $data;
        foreach ($data as $item) {
            $this->insert($item);
        }
    }

    /**
     * @param string      $fileName
     * @param null|string $key
     */
    private function loadFromJsonFile($fileName, $key = null)
    {
        $this->loadFromJsonString(file_get_contents($fileName), $key);
    }

    /**
     * @param $fileName
     */
    public function saveToJsonFile($fileName)
    {
        file_put_contents($fileName, json_encode($this->findAll(), JSON_PRETTY_PRINT));
    }

    /**
     * @param mixed $record
     *
     * @return bool
     */
    protected function processRecord(&$record)
    {
        // just override in the real table if you need to do something
        // every time a record is processed when loaded in memory, look at the examples folders
        return (bool) $record;
    }
}
