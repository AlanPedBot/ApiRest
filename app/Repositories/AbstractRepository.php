<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function selectAttribute($attribute)
    {
        $this->model =  $this->model->with($attribute);
    }
    public function filter($filter)
    {
        $filter = explode(';', $filter);
        foreach ($filter as $key => $condition) {

            $conditions = explode(':', $condition);
            $this->model = $this->model->where($conditions[0], $conditions[1], $conditions[2]);
        }
    }
    public function selectAttributeQuery($attribute)
    {
        $this->model = $this->model->selectRaw($attribute);
    }
    public function getResult()
    {
        return $this->model->get();
    }
}
