<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

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
    public function all($perPage = 10)
    {
        return $this->model->paginate($perPage);
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

    // Search or filter records
    public function search($query)
    {
        return $this->model::search($query)->get();
    }

    /**
     * Retrieve records based on specified conditions and relations.
     *
     * @param array $conditions An associative array of conditions for filtering.
     *
     * @param array $relations  An array of relationships to eager load.
     * @param bool $singleResult If true, returns only the first matching record.
     * @param bool $count If true, returns the count of records that match the conditions.
     *
     * @return mixed The result of the query, either a collection of records, a single record, or a count.
     */
    public function findWithConditions(array $conditions = [], array $relations = [], bool $singleResult = false, bool $count = false)
    {
        $query = $this->model->newQuery();
        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $query = $query->where($column, $value[0], $value[1]);
            } else {
                $query = $query->where($column, $value);
            }
        }
        if (!empty($relations) && !$count) {
            $query = $query->with($relations);
        }
        if ($count) {
            return $query->count();
        }
        return $singleResult ? $query->first() : $query->get();
    }

    public function exportAll($exportObj, $fileName){
        return Excel::download($exportObj, $fileName);
    }

    public function importData($importObj, $fileName){
        Excel::import($importObj, $fileName);
        return true;
    }

}
