<template>
    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="row">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-5 mb-3">
                        <div class="card-body bg-white rounded">
                            <form @submit.prevent="handleLogin">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h2 class="text-center">Acessar ao Sistema</h2>
                                        <p class="text-center">Digite seu e-mail e senha para fazer login</p>
                                    </div>

                                    <div class="col-md-12" v-if="errorMessage">
                                        <div class="alert alert-danger">{{ errorMessage }}</div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" v-model="form.email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">Senha</label>
                                            <input type="password" v-model="form.password" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-6 offset-3">
                                        <div class="mb-4">
                                            <button type="submit" class="btn btn-primary w-100" style="font-size: 20px" :disabled="loading">
                                                <span v-if="loading">Carregando...</span>
                                                <span v-else>Entrar</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="">
                                            <div class="seperator">
                                                <hr>
                                                <div class="seperator-text"><span>Ou continue com</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-12 m-auto">
                                        <div class="mb-4">
                                            <button type="button" class="btn btn-social-login w-100">
                                                <img :src="googleIcon" alt="" class="img-fluid">
                                                <span class="btn-text-inner" style="font-size: 20px">Google</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

// Usando o alias configurado no vite.config.js
import googleIcon from '@images/google-gmail.svg';

export default {
    name: 'StudentLogin',
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            loading: false,
            errorMessage: '',
            googleIcon: googleIcon
        };
    },
    mounted() {
        console.log('Componente Login montado!');
    },
    methods: {
        async handleLogin() {
            this.loading = true;
            this.errorMessage = '';

            try {
                // Usando a rota web /login para garantir sessão + token
                const response = await axios.post('/login', this.form);

                const token = response.data.token;
                const user = response.data.user;
                const redirectUrl = response.data.redirect_url;

                // Armazenar o token
                localStorage.setItem('auth_token', token);

                // Redirecionar
                window.location.href = redirectUrl || '/student/dashboard';

            } catch (error) {
                if (error.response && error.response.data && error.response.data.message) {
                    this.errorMessage = error.response.data.message;
                } else {
                    this.errorMessage = 'Ocorreu um erro ao tentar fazer login.';
                }
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
</style>
