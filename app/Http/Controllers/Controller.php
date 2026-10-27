<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationNotImplementedException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

abstract class Controller
{
    private Model $model;
    private string $primaryKey;

    protected string $modelName;
    protected array $indexColumns = ['*'];
    protected array $with = [];
    protected array $orderBy = [];
    protected string $optionColumn;

    abstract function storeValidation(): FormRequest|null;
    abstract function updateValidation(): FormRequest|null;

    public function __construct()
    {
        $this->model = new $this->modelName;
        $this->primaryKey = $this->model->getKeyName();
    }

    private function filter(Builder $query, array $filters)
    {
        foreach ($filters as $key => $value) {
            $query->where($key, "LIKE", "$value%");
        }
    }

    private function orderBy(Builder $query)
    {
        foreach ($this->orderBy as $orderBy) {
            $order = explode(' ', $orderBy);
            $column = $order[0];
            $direction = $order[1] ?? 'ASC';
            $query->orderBy($column, $direction);
        }
    }

    public function index()
    {
        $query = $this->model
            ->query()
            ->with($this->with)
            ->select($this->indexColumns);

        $this->filter($query, request()->all());

        $this->orderBy($query);

        return $query->get()->toArray();
    }

    public function show(int $id)
    {
        return $this->model
            ->query()
            ->with($this->with)
            ->select($this->indexColumns)
            ->findOrFail($id)
            ->toArray();
    }

    public function options()
    {
        $query = $this->model
            ->query()
            ->select(["$this->primaryKey as value", "$this->optionColumn as label"]);

        $this->orderBy($query);

        return $query->get()->toArray();
    }

    public function store()
    {
        $request = $this->storeValidation();
        if (!$request) {
            throw new ValidationNotImplementedException($this->modelName, 'Store');
        }

        $row = $this->model::query()->create($request->validated());

        return $row->load($this->with)->toArray();
    }

    public function update(int $id)
    {
        $request = $this->updateValidation();
        if (!$request) {
            throw new ValidationNotImplementedException($this->modelName, 'Update');
        }

        $row = $this->model::query()->findOrFail($id);
        $row->fill($request->validated())->save();

        return $row->load($this->with)->toArray();
    }

    public function destroy(int $id)
    {
        $this->model::destroy($id);
        return response()->noContent();
    }
}
