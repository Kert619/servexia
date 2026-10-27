// global.d.ts
import { DefaultPaginationSettings } from "@/types/pagination";
import "@inertiajs/core";

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            default_pagination_settings: DefaultPaginationSettings;
        };
    }
}
