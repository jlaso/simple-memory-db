<?php

namespace JLaso\SimpleMemoryDb;

abstract class AbstractTable implements RepositoryInterface
{
    protected $data = [];
    protected $indices = [];
    protected $indexMap = [];

    /**
     * Instance constructor.
     */
    public function __construct($data)
    {
        foreach (json_decode($data, true) as $item) {
            $this->insert($item);
        };
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
     * @param string $field
     * @param $value
     *
     * @return array
     */
    public function findAll($field = null, $value = null)
    {
        $result = [];
        if (!$field && !$value) {
            foreach ($this->data as $index => $item) {
                $result[$index] = json_decode(gzuncompress($item), true);
            }
        } else {
            foreach ($this->indices[$field][$value] as $id) {
                $result[$id] = json_decode(gzuncompress($this->data[$id]), true);
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
        $this->processRecord($data);
        $this->data[$data['id']] = gzcompress(json_encode($data), 9);
        // process indices
        foreach ($this->indexMap as $field) {
            $this->indices[$field][$data[$field]][] = $data['id'];
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function remove($id)
    {
        if(isset($this->data[$id])){
            $item = json_decode(gzuncompress($this->data[$id]), true);
            // process indices
            foreach ($this->indexMap as $field) {
                $index = array_search($this->indices[$field][$item[$field]], $id);
                if(false !== $index){
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
     * @param mixed $record
     */
    protected function processRecord(&$record)
    {
    }
}
