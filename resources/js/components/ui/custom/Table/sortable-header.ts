import { h } from "vue";
import type { Column, RowData } from "@tanstack/vue-table";
import { Button } from "@/components/ui/button";
import { ArrowUpDown } from "@lucide/vue";
import type { DataTableFeatures } from "@/components/ui/custom/Table/features";

export function sortableHeader<TData extends RowData, TValue>(label: string) {
    return ({ column }: { column: Column<DataTableFeatures, TData, TValue> }) =>
        h(
            Button,
            {
                variant: "ghost",
                onClick: () =>
                    column.toggleSorting(column.getIsSorted() === "asc"),
            },
            () => [label, h(ArrowUpDown, { class: "ml-2 h-4 w-4" })],
        );
}
