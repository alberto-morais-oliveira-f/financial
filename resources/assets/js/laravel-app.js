// import './import.plugins';
import './bootstrap';
import 'laravel-datatables-vite';

import $ from 'jquery';
window.$ = window.jQuery = $;

import './libs/select2.min.js'

import.meta.glob([
    '../images/**',
]);

if (document.querySelector('.select2')){
    $('.select2').select2();
}

import { createApp } from 'vue';
import Login from './vue/components/student/auth/Login.vue';

console.log('Laravel App JS carregado');

// Garantir que o DOM esteja carregado antes de montar o Vue
document.addEventListener('DOMContentLoaded', () => {
    const appElement = document.getElementById('app');

    if (appElement) {
        console.log('Elemento #app encontrado, montando Vue...');
        const app = createApp({});
        app.component('login-component', Login);
        app.mount('#app');
        console.log('Vue montado!');
    } else {
        console.log('Elemento #app NÃO encontrado.');
    }
});
