<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Inertia\Inertia;

class CompanyController extends Controller
{
    protected string $modelName = Company::class;

    public function storeValidation(): ?FormRequest
    {
        return app(CompanyStoreRequest::class);
    }

    public function updateValidation(): ?FormRequest
    {
        return app(CompanyUpdateRequest::class);
    }

    public function index()
    {
        return [];
    }

    public function show(int $id)
    {
        $model = parent::show($id);

        return Inertia::render('CompanyPage', [
            'company' => $model
        ]);
    }

    public function store()
    {
        $model = parent::store();

        return Inertia::render('CompanyPage', [
            'companies' => Inertia::merge([$model]),
        ]);
    }

    public function update(int $id)
    {
        $model = parent::update($id);

        return Inertia::render('CompanyPage', [
            'company' => $model
        ]);
    }

    public function destroy(int $id)
    {
        parent::destroy($id);
        return Inertia::render('CompanyPage', []);
    }
}
