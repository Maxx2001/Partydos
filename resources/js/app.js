import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp, router, usePage} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import toastsStore from "@/Stores/ToastMessages.ts";
import {createPinia} from 'pinia'; // Import Pinia

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

if (import.meta.env.PROD) {
    (function(c, l, a, r, i, t, y) {
        c[a] = c[a] || function() { (c[a].q = c[a].q || []).push(arguments) };
        t = l.createElement(r); t.async = 1; t.src = "https://www.clarity.ms/tag/" + i;
        y = l.getElementsByTagName(r)[0]; y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "qa5ud3327i");
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});

        const pinia = createPinia(); // Create a Pinia instance

        return app
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

const page = usePage();
router.on('finish', (event) => {
    if (page.props.message) {
        toastsStore().open(page.props.message.content, page.props.message.type);
    }
})
