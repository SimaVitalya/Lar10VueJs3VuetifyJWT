import { createApp } from 'vue';
import App from '@/App.vue';
import Router from '@/router/router.js';
import Store from '@/store/store.js';
import vuetify from "./vuetify";
createApp(App).use(Router).use(vuetify).use(Store).mount('#app');
