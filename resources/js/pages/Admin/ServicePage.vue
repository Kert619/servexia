<template>
    <DataTable
        :columns="columns"
        :data="services.data"
        row-key="service_id"
        manual-pagination
        :row-count="services.total"
        :loading="loading"
        v-model:pagination="pagination"
        v-model:row-selection="selection"
        @update:pagination="onPaginationUpdate"
    >
        <template #top>
            <AppInput
                v-model="search"
                placeholder="Search..."
                :debounce="500"
                style="width: min(500px, 100%)"
            />
        </template>
    </DataTable>
</template>

<script setup lang="ts">
import { Service } from "@/types/service";
import DataTable from "@/components/ui/custom/Table/DataTable.vue";
import { createColumns } from "@/components/ui/custom/Table/create-columns";
import { h, ref, watch } from "vue";
import { Badge } from "@/components/ui/badge";
import DataTableDropdown from "@/components/ui/custom/Table/DataTableDropdown.vue";
import DropdownMenuItem from "@/components/ui/dropdown-menu/DropdownMenuItem.vue";
import { View } from "@lucide/vue";
import { PaginationState, RowSelectionState } from "@tanstack/vue-table";
import { Paginator } from "@/types/pagination";
import { router, usePage } from "@inertiajs/vue3";
import { sortableHeader } from "@/components/ui/custom/Table/sortable-header";
import AppInput from "@/components/ui/custom/AppInput.vue";
import AppCheckbox from "@/components/ui/custom/AppCheckbox.vue";

const props = defineProps<{
    services: Paginator<Service>;
    filters: { search: string };
}>();

const page = usePage();

const columnHelper = createColumns<Service>();

const columns = columnHelper.columns([
    columnHelper.display({
        id: "select",
        header: ({ table }) =>
            h(AppCheckbox, {
                modelValue:
                    table.getIsAllPageRowsSelected() ||
                    (table.getIsSomePageRowsSelected() && "indeterminate"),
                "onUpdate:modelValue": (value: boolean | "indeterminate") =>
                    table.toggleAllPageRowsSelected(!!value),
                ariaLabel: "Select all",
            }),
        cell: ({ row }) =>
            h(AppCheckbox, {
                modelValue: row.getIsSelected(),
                onClick: (e: MouseEvent) =>
                    row.getToggleSelectedHandler()({
                        shiftKey: e.shiftKey,
                        target: { checked: !row.getIsSelected() },
                    } as unknown as Event),
                ariaLabel: "Select row",
            }),
        enableSorting: false,
        enableHiding: false,
    }),
    columnHelper.accessor("service_id", {
        header: sortableHeader("#Id"),
        cell: ({ getValue }) => {
            const value = getValue();
            return h(Badge, {}, () => value);
        },
    }),
    columnHelper.accessor("place_id", {
        header: sortableHeader("Place Id"),
    }),
    columnHelper.accessor("service_name", {
        header: sortableHeader("Service Name"),
    }),
    columnHelper.accessor("latitude", {
        header: sortableHeader("Latitude"),
    }),
    columnHelper.accessor("longitude", {
        header: sortableHeader("Longitude"),
    }),
    columnHelper.accessor("rating", {
        header: sortableHeader("Rating"),
    }),
    columnHelper.accessor("reviews", {
        header: sortableHeader("Reviews"),
    }),
    columnHelper.accessor("unclaimed_listing", {
        header: sortableHeader("Unclaimed Listing"),
        cell: ({ getValue }) => {
            const value = !!getValue();

            return h(AppCheckbox, { disabled: true, modelValue: value });
        },
    }),
    columnHelper.accessor("address", {
        header: sortableHeader("Address"),
    }),
    columnHelper.display({
        id: "actions",
        enableHiding: false,
        cell: ({ row }) => {
            const service = row.original;
            return h(
                DataTableDropdown,
                {
                    onDelete: () => {},
                },
                {
                    extend: () => [
                        h(
                            DropdownMenuItem,
                            {
                                onClick: () => {},
                            },
                            () => [h(View), "View"],
                        ),
                    ],
                },
            );
        },
    }),
]);

const search = ref(props.filters.search);
const loading = ref(false);

const pagination = ref<PaginationState>({
    pageIndex: props.services.current_page - 1,
    pageSize: props.services.per_page,
});

const selection = ref<RowSelectionState>();

const onPaginationUpdate = (next: PaginationState) => {
    const searchQuery = search.value ? { search: search.value } : {};

    router.get(
        "/admin/services",
        { page: next.pageIndex + 1, per_page: next.pageSize, ...searchQuery },
        {
            preserveState: true,
            preserveScroll: true,
            only: ["services"],
            onStart: () => (loading.value = true),
            onFinish: () => (loading.value = false),
            onSuccess: () => {
                pagination.value = {
                    pageIndex: props.services.current_page - 1,
                    pageSize: props.services.per_page,
                };
            },
        },
    );
};

watch(search, () => {
    onPaginationUpdate({
        pageIndex: page.props.default_pagination_settings.default_page - 1,
        pageSize: props.services.per_page,
    });
});
</script>
