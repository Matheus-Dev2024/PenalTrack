@extends('master')
@section('content')

    <div ref="divRef" class="container mt-4 container-md-4">
        <div class="card shadow-sm" style="width: 70%; max-width: 1100px; margin: 0 auto; text-align: center;">
            <div class="card-header d-flex justify-content-between">
                <div class="mt-1">
                    <h3>LISTAGEM</h3>
                </div>
                <span class="mt-2">
                    <form @submit.prevent="pesquisar">
                         <v-btn type="button" href="{{route('cadastro')}}" elevation="5" color="success" class="mr-1">
                            Cadastrar
                         </v-btn>
                        <v-dialog max-width="500" v-model="isDialogOpen">
                           <template v-slot:activator="{ props }">
                                <v-btn v-bind="props" color="primary" variant="flat">Pesquisar</v-btn>
                            </template>
                            <v-card>
                                <v-card-text>
                                    <div>
                                        <label for="nome" class="form-label">Nome</label>
                                        <input type="text" v-model="pesquisas.nome" id="nome" class="form-control" placeholder="Nome da conta">
                                    </div>
                                    <div class="mt-2">
                                        <label for="data_inicio" class="form-label">Data De Cadastro</label>
                                        <input type="date" v-model="pesquisas.dataCadastro" id="data_inicio" class="form-control">
                                    </div>
                                    <div class="mt-3">
                                        <v-btn color="success" @click="pesquisar">Pesquisar</v-btn>
                                        <v-btn color="warning" class="ms-1" @click="limpar">Limpar</v-btn>
                                    </div>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn color="red" @click="isDialogOpen = false">Fechar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </form>
                </span>
            </div>
            <table class="table table-hover border table-bordered mb-n1">
                <thead>
                <tr style="text-align: center" class="table-dark">
                    <th scope="col">ID</th>
                    <th scope="col">NOME</th>
                    <th scope="col">DATA</th>
                    <th scope="col">AÇÕES</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-center"  v-for="P in presos" :key="P.id">
                    <td>@{{ P.id }}</td>
                    <td>@{{ P.nome }}</td>
                    <td>@{{ P.data_cadastro }}</td>
                    <td>
                        <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen>
                            <template v-slot:activator="{ props: activatorProps }">
                                <v-btn v-bind="activatorProps" color="primary" size="small">FOTOS</v-btn>
                            </template>
                            <v-card>
                                <v-toolbar>
                                    <v-toolbar-title>FOTOS</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-toolbar-items>
                                        <v-btn @click="dialog = false">Sair</v-btn>
                                    </v-toolbar-items>
                                </v-toolbar>
                            </v-card>
                        </v-dialog>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@3.7.1/dist/vuetify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
        const { createApp, reactive, ref } = Vue;
        const { createVuetify } = Vuetify;
        const app = createApp({
            setup() {
                const dialog = ref(false);
                const isDialogOpen = ref(false);
                const presos = ref([]);
                const pesquisas = reactive({
                    nome: '',
                    dataCadastro: ''
                });

                const pesquisar = async () => {
                    let query = `?nome=${pesquisas.nome}&dataCadastro=${pesquisas.dataCadastro}`;
                    await fetchPresos(where);
                };

                const limpar = async () => {
                    pesquisas.nome = '';
                    pesquisas.dataCadastro = '';
                    await fetchPresos();
                };

                function fetchPresos (query = '') {
                    axios.get('http://localhost:8004/pesquisar/presos' + query)
                        .then(response => {
                            presos.value = response.data
                        })
                }
                function getlistar () {
                    axios.get('http://localhost:8004/listar/presos')
                        .then(response => {
                            presos.value = response.data
                        })
                }
                return {
                    dialog,
                    presos,
                    pesquisas,
                    pesquisar,
                    limpar,
                    fetchPresos,
                    isDialogOpen,
                    getlistar
                };
            },
            mounted() {
                this.getlistar();
            }
        });

        app.use(createVuetify());
        app.mount('#app');

</script>
<@endsection
