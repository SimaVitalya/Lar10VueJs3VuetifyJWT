import { createApp } from 'vue';
import App from '@/App.vue';
import Router from '@/router/router.js';
import Store from '@/store/store.js';
import vuetify from "./vuetify";
// createApp(App).use(Router).use(vuetify)use(PrimeVue).use(Store).mount('#app'); ну или так написать

const app = createApp(App);

app.use(Router);
app.use(Store);
app.use(vuetify);

app.mount('#app');
