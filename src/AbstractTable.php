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
        foreach(json_decode($data, true) as $item){
            $this->data[$item['id']] = gzcompress(json_encode($item),9);
            // process indices
            foreach ($this->indexMap as $field) {
                $this->indices[$field][$item[$field]][] = $item['id'];
            }
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
            foreach($this->data as $index=>$item){
                $result[$index] = json_decode(gzuncompress($item), true);
            }
        }else {
            foreach ($this->indices[$field][$value] as $id) {
                $result[$id] = json_decode(gzuncompress($this->data[$id]), true);
            }
        }

        return $result;
    }
}
