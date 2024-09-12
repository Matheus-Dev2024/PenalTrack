import './bootstrap';
import { createApp } from 'vue';
import Listagem from './components/Listagem.vue';
import Cadastro from './components/Cadastro.vue';
import router from './router';


import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
const vuetify = createVuetify({
    components,
    directives
})

const app = createApp();
app.component('Listagem', Listagem);
app.component('Cadastro', Cadastro);



app.use(router);
app.use(vuetify)
app.mount('#app');

