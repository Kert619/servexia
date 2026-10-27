<template>
    <div class="border rounded-md p-3">
        <slot name="top" :table="table"></slot>
        <Table>
            <TableHeader>
                <TableRow
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                >
                    <TableHead
                        v-for="header in headerGroup.headers"
                        :key="header.id"
                    >
                        <FlexRender
                            v-if="!header.isPlaceholder"
                            :header="header"
                        />
                    </TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <template v-if="table.getRowModel().rows?.length">
                    <TableRow
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        :data-state="row.getIsSelected() && 'selected'"
                    >
                        <TableCell
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                        >
                            <FlexRender :cell="cell" />
                        </TableCell>
                    </TableRow>
                </template>

                <template v-else>
                    <TableRow>
                        <TableCell
                            :colspan="columns.length"
                            class="h-24 text-center"
                        >
                            No results.
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>

        <div class="flex justify-between mt-3 overflow-auto">
            <div class="flex-1">
                <AppQuickSelect
                    label="Items Per Page"
                    :model-value="pagination.pageSize"
                    :options="itemsPerPage"
                    :loading="loading"
                    @update:model-value="handlePageSizeChange"
                />
            </div>

            <div class="flex-1">
                <Pagination
                    v-slot="{ page }"
                    :page="pagination.pageIndex + 1"
                    :items-per-page="pagination.pageSize"
                    :total="rowCount ?? data.length"
                    :disabled="loading"
                    @update:page="handlePageChange"
                    class="justify-end"
                >
                    <PaginationContent v-slot="{ items }">
                        <PaginationFirst />
                        <PaginationPrevious />
                        <template v-if="paginationMode === 'numbered'">
                            <template
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                >
                                    {{ item.value }}
                                </PaginationItem>
                            </template>
                            <PaginationEllipsis />
                        </template>
                        <PaginationNext />
                        <PaginationLast />
                    </PaginationContent>
                </Pagination>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts" generic="TData extends RowData">
import type {
    ColumnDef,
    ColumnFiltersState,
    PaginationState,
    RowData,
    RowSelectionState,
} from "@tanstack/vue-table";
import { FlexRender, useTable } from "@tanstack/vue-table";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
    PaginationFirst,
    PaginationLast,
} from "@/components/ui/pagination";
import {
    features,
    type DataTableFeatures,
} from "@/components/ui/custom/Table/features";
import AppQuickSelect from "@/components/ui/custom/AppQuickSelect.vue";
import { Option } from "@/types/option";

const props = withDefaults(
    defineProps<{
        columns: ColumnDef<DataTableFeatures, TData>[];
        data: TData[];
        rowKey: keyof TData;
        loading?: boolean;
        manualPagination?: boolean;
        manualFiltering?: boolean;
        rowCount?: number;
        paginationMode?: "simple" | "numbered";
    }>(),
    {
        paginationMode: "simple",
    },
);

const pagination = defineModel<PaginationState>("pagination", {
    required: true,
});

const columnFilters = defineModel<ColumnFiltersState>("columnFilters", {
    default: () => [],
});

const rowSelection = defineModel<RowSelectionState>("rowSelection", {
    default: (): RowSelectionState => ({}),
});

const table = useTable({
    features,

    get data() {
        return props.data;
    },

    get columns() {
        return props.columns;
    },

    getRowId: (row: TData) => String(row[props.rowKey]),

    manualPagination: props.manualPagination,
    manualFiltering: props.manualFiltering,
    rowCount: props.rowCount,

    state: {
        get pagination() {
            return pagination.value;
        },

        get columnFilters() {
            return columnFilters.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
    },

    onPaginationChange: (updater) => {
        pagination.value =
            typeof updater === "function" ? updater(pagination.value) : updater;
    },

    onColumnFiltersChange: (updater) => {
        columnFilters.value =
            typeof updater === "function"
                ? updater(columnFilters.value)
                : updater;
    },

    onRowSelectionChange: (updater) => {
        rowSelection.value =
            typeof updater === "function"
                ? updater(rowSelection.value)
                : updater;
    },
});

const itemsPerPage: Option<number>[] = [
    {
        value: 10,
        label: "10",
    },
    {
        value: 20,
        label: "20",
    },
    {
        value: 30,
        label: "30",
    },
    {
        value: 40,
        label: "40",
    },
    {
        value: 50,
        label: "50",
    },
];

// const handlePageChange = (newPage: number) => {
//     const newPageIndex = newPage - 1;

//     if (newPageIndex === pagination.value.pageIndex) return;

//     if (!props.manualPagination) {
//         // client-side: setPageIndex triggers onPaginationChange, which
//         // already emits — do NOT also emit manually here
//         table.setPageIndex(newPageIndex);
//     } else {
//         // server-side: no client row model to drive
//         pagination.value = {
//             pageIndex: newPageIndex,
//             pageSize: pagination.value.pageSize,
//         };
//     }
// };

// const handlePageSizeChange = (value: number | null | undefined) => {
//     if (!value) return;

//     if (!props.manualPagination) {
//         table.setPagination({ pageIndex: 0, pageSize: value });
//     } else {
//         // server-side: no client row model to drive
//         pagination.value = { pageIndex: 0, pageSize: value };
//     }
// };

const handlePageChange = (newPage: number) => {
    const newPageIndex = newPage - 1;

    if (newPageIndex === pagination.value.pageIndex) {
        return;
    }

    pagination.value = {
        pageIndex: newPageIndex,
        pageSize: pagination.value.pageSize,
    };
};

const handlePageSizeChange = (value: number | null | undefined) => {
    if (!value || value === pagination.value.pageSize) {
        return;
    }

    pagination.value = {
        pageIndex: 0,
        pageSize: value,
    };
};
</script>
