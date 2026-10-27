import "../css/app.css";
import { createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { createSSRApp, h } from "vue";
import { renderToString } from "vue/server-renderer";
import Layout from "./layout/AdminLayout.vue";

createServer((page) =>
    createInertiaApp({
        title: (title) => (title ? `${title} - Servexia` : "Servexia"),
        page,
        render: renderToString,
        resolve: async (name) => {
            const pages = import.meta.glob("./pages/**/*.vue");
            const page = await pages[`./pages/${name}.vue`]();
            page.default.layout =
                page.default.layout === undefined
                    ? Layout
                    : page.default.layout;
            return page;
        },
        setup({ App, props, plugin }) {
            return createSSRApp({
                render: () => h(App, props),
            }).use(plugin);
        },
    }),
);
