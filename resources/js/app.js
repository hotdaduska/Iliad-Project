import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'


const vuetify = createVuetify({
    components,
    directives,
        theme: {
            defaultTheme: 'light',
            themes: {
            light: {
                primary: '#1976D2',
                secondary: '#424242',
                accent: '#82B1FF',
                error: '#FF5252',
                info: '#2196F3',
                success: '#4CAF50',
                warning: '#FFC107',
            },
            },
        },
    });

const app = createApp(App);
app.use(router);
app.use(vuetify); // Use Vuetify
app.component('VueDatePicker', VueDatePicker);
app.mount('#app');