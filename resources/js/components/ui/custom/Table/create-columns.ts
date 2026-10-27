// components/ui/custom/Table/createColumns.ts

import { createColumnHelper, type RowData } from "@tanstack/vue-table";

import type { DataTableFeatures } from "@/components/ui/custom/Table/features";

export function createColumns<TData extends RowData>() {
    return createColumnHelper<DataTableFeatures, TData>();
}
