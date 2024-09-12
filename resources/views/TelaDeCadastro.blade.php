@extends('layouts.home')
@section('title', 'Cadastro de Preso')
@section('content')
    <div id="app">
        <v-app style="background: #eaeaea">
            <v-container class="mt-5">
                <v-card style=" width: 100%;max-width: 600px;margin: 0 auto;"
                        :elevation="15" class="shadow-lg p-3 mb-3 rounded-3 border custom-card shadow">
                    <v-container>
                        <v-sheet class="d-flex">
                            <v-card-title>
                                <v-sheet class="ma-2 pa-2 me-auto text-center mt-n3">
                                    <h1>PENALTRACK</h1>
                                </v-sheet>
                            </v-card-title>
                        </v-sheet>
                        <v-form ref="formRef">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field label="Nome" v-model="form.nome" :rules="[tela.rules.required]"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12">
                                    <v-file-input append-icon="" prepend-icon="" label="Upload" show-size ></v-file-input>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" class="d-flex justify-center mt-n6">
                                    <v-sheet class="d-flex mb-6">
                                        <v-sheet class="ma-2 pa-2 me-auto">
                                            <v-btn color="success" class="btn mb-2 mt-2" @click="getRegistrar">
                                                Registrar
                                            </v-btn>
                                            <a href="{{route('listagem')}}" class="btn btn-danger ma-2" >
                                                LISTAR
                                            </a>
                                        </v-sheet>
                                    </v-sheet>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-container>
                </v-card>
            </v-container>
        </v-app>
    </div>
@endsection
@section('head')
    <script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@3.0.0/dist/vuetify.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const { createApp, reactive, ref } = Vue;
            const { createVuetify } = Vuetify;

            const app = createApp({
                data() {
                    return {
                        formRef: ref(null),
                        form: reactive({
                            nome: '',
                            file: '',
                        }),
                        tela: reactive({
                            rules: {
                                required: value => !!value || 'Campo obrigatório.',
                            },
                        }),
                    };
                },
                methods: {
                    valid() {
                        return this.formRef.validate();
                    },
                    async getRegistrar() {
                        if (!this.valid()) {
                            return;
                        }

                        try {
                            const formData = new FormData();
                            formData.append('nome', this.form.nome);
                            if (this.form.file) {
                                formData.append('file', this.form.file);
                            }

                            const response = await axios.post('http://localhost:8004/cadastrar/preso', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            });

                            console.log('Sucesso:', response.data);
                            alert('Registro feito com sucesso!');
                            this.form.nome = '';
                            this.form.file = null;
                            this.formRef.reset();
                        } catch (error) {
                            console.error('Erro:', error);
                            alert('Ocorreu um erro ao registrar.');
                        }
                    },
                },
                mounted() {
                    this.formRef = this.$refs.formRef;
                },
            });

            app.use(createVuetify());
            app.mount('#app');
        });
    </script>
@endsection


