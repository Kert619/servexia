<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DefaultPaginationSettings;
use App\Enums\Page;
use App\Enums\SortDirection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HandlesDataTable;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Inertia\Inertia;

class ServiceController extends Controller
{
    use HandlesDataTable;

    protected string $modelName = Service::class;

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
        return Inertia::render(Page::AdminServices->value, [
            'services' => $this->paginateForTable(
                query: Service::query(),
                sort: 'service_id',
                direction: SortDirection::SORT_DESCENDING,
                search: request('search'),
                perPage: request('per_page', DefaultPaginationSettings::DEFAULT_PER_PAGE->value),
                page: request('page', DefaultPaginationSettings::DEFAULT_PAGE->value),
                searchable: ['service_name'],
                sortable: ['service_id'],
            ),
            'filters' => $this->searchFilters(),
        ]);
    }
}
