<?php

namespace JLaso\SimpleMemoryDb;

interface RepositoryInterface
{
    public function find($id);

    public function findAll($field = null, $value = null);
}