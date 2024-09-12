// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Listagem from './components/Listagem.vue';
import Cadastro from './components/Cadastro.vue';

const routes = [

    {
        path: '/',
        name: 'Cadastro',
        component: Cadastro
    },
    {
        path: '/Listagem',
        name: 'Listagem',
        component: Listagem
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
