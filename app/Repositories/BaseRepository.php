<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * Class for BaseRepository
 *
 * @package App\Repositories
 *
 */
class BaseRepository
{
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Fetch all records
    public function all()
    {
        return $this->model->all();
    }

    // Fetch a record by ID
    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByUuid($uuid)
    {
        $record = $this->model->where('uuid', $uuid)->first();
        if(!$record) {
            return false;
        }
        return $record;
    }

    // Create a new record
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Update a record by ID
    public function update($uuid, array $data)
    {
        $record = $this->findByUuid($uuid);
        if(!$record) {
            return false;
        }
        $record->update($data);
        return $record;
    }

    // Delete a record by ID
    public function delete($uuid)
    {
        $record = $this->findByUuid($uuid);
        if(!$record) {
            return false;
        }
        return $record->delete();
    }
}
