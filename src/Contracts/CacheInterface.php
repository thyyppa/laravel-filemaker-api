<?php

namespace Hyyppa\Filemaker\Contracts;

use Hyyppa\Filemaker\Record\Record;

interface CacheInterface
{
    /**
     * @param string|Record $record
     *
     * @return Record|null
     */
    public function add($record): ?Record;

    /**
     * @param Record $record
     */
    public function update(Record $record): void;

    /**
     * @param Record $record
     */
    public function remove(Record $record): void;

    /**
     * @param string|FilemakerModel $model
     * @param string                $id
     *
     * @return Record|null
     */
    public function get($model, $id): ?Record;

    /**
     * @param $table
     * @param $id
     *
     * @return bool
     */
    public function has($table, $id): bool;
}
