<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Page;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected string $modelName = Category::class;

    public function storeValidation(): ?FormRequest
    {
        throw new \Exception('Not implemented');
    }

    public function updateValidation(): ?FormRequest
    {
        throw new \Exception('Not implemented');
    }

    public function index()
    {
        $categories = parent::index();

        return Inertia::render(Page::AdminCategories->value, [
            'categories' => $categories
        ]);
    }
}
