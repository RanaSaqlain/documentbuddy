import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, ref } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PdfViewer from './Components/PdfViewer.vue';
import Loader from './Components/Loader.vue';
import { appName } from '@/constants.js';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const isLoading = ref(false);

        const app = createApp({
            render: () => h(App, props)
        });

        app.use(plugin)
            .use(ZiggyVue)
            .component('PdfViewer', PdfViewer)
            .component('Loader', Loader)
            .component('AppLoader', {
                setup() {
                    return () => isLoading.value ? h(Loader) : null;
                }
            })
            .mount(el);

        app.mixin({
            created() {
                this.$inertia.on('start', () => {
                    isLoading.value = true;
                });
                this.$inertia.on('finish', () => {
                    isLoading.value = false;
                });
            }
        });

        return app;
    },
    progress: {
        color: '#0f0101',
    },
});
