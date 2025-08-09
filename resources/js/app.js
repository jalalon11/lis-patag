import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import LoadingSpinner from './Components/LoadingSpinner.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Patag Elementary School LIS';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('LoadingSpinner', LoadingSpinner)
            .mount(el);
    },
    progress: {
        color: '#2c5aa0',
        showSpinner: true,
    },
});
