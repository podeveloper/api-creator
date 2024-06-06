<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\ExceptionTrait;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    use ExceptionTrait;

    private $model = User::class;

    public function getAll()
    {
        try {
            return $this->model::paginate();
        } catch (\Exception $e) {
            $this->throwFetchModelsException($e);
        }
    }

    public function findById($id)
    {
        try {
            return $this->model::findOrFail($id);
        } catch (\Exception $e) {
            $this->throwModelNotFoundException($id);
        }
    }

    public function create(array $data)
    {
        try {
            return $this->model::create($data);
        } catch (\Exception $e) {
            $this->throwCreateModelException($e);
        }
    }

    public function update(array $data, $id)
    {
        $model = $this->findById($id);

        try {
            return DB::transaction(function () use ($data, $model) {
                $model->update($data);
                return $model;
            });
        } catch (\Exception $e) {
            $this->throwUpdateModelException($id, $e);
        }
    }

    public function delete($id)
    {
        $model = $this->findById($id);

        try {
            return DB::transaction(function () use ($model) {
                $model->delete();
                return true;
            });
        } catch (\Exception $e) {
            $this->throwDeleteModelException($id, $e);
        }
    }
}
