import "../css/app.css";
import { createSSRApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import Layout from "./layout/AdminLayout.vue";

createInertiaApp({
    title: (title) => (title ? `${title} - Servexia` : "Servexia"),
    resolve: async (name) => {
        const pages = import.meta.glob("./pages/**/*.vue");
        const page = await pages[`./pages/${name}.vue`]();
        page.default.layout =
            page.default.layout === undefined ? Layout : page.default.layout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createSSRApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
