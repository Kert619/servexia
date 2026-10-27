<?php

namespace App\Http\Controllers\Traits;

use App\Enums\DefaultPaginationSettings;
use App\Enums\SortDirection;
use Illuminate\Database\Eloquent\Builder;

trait HandlesDataTable
{
    /**
     * Apply page/per_page/sort/direction/search from the given values
     * and return a payload shaped for the generic DataTable component.
     */
    protected function paginateForTable(
        Builder $query,
        ?string $sort = null,
        ?SortDirection $direction = SortDirection::SORT_ASCENDING,
        ?string $search = null,
        ?int $perPage = null,
        ?int $page = null,
        array $sortable = [],
        array $searchable = []
    ): array {
        // sorting
        if ($sort && in_array($sort, $sortable, true)) {
            $query->orderBy($sort, $direction->value);
        }

        // simple global search across whitelisted columns
        if ($search && $searchable) {
            $query->where(function (Builder $q) use ($search, $searchable) {
                foreach ($searchable as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $paginator = $query->paginate(perPage: $perPage, page: $page);

        return $paginator->toArray();
    }

    protected function searchFilters(): array
    {
        return ['search' => request('search', '')];
    }
}
