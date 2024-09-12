<template>
    <v-app>
        <v-container class="mt-4" max-width="1100px">
            <v-card elevation="7" class="mx-auto" max-width="100%">
                <v-card-title>
                    <v-sheet class="d-flex align-center">
                        <v-sheet class="ma-2 pa-2 me-auto ml-3">
                            <h2>LISTAGEM</h2>
                        </v-sheet>
                        <v-sheet class="ma-2 pa-2">
                            <v-btn size="small" @click="isDialogOpen = true" elevation="5" color="#212121">
                                <v-icon size="x-large" icon="mdi-account-search"></v-icon>
                                Pesquisar
                            </v-btn>
                        </v-sheet>
                        <v-sheet class="ml-n3 ma-2 pa-2">
                            <v-btn size="small" :to="{ name: 'Cadastro' }" elevation="5" color="#00E676">
                                <v-icon size="x-large" icon="mdi-account-multiple-plus"></v-icon>
                                Cadastrar
                            </v-btn>
                        </v-sheet>
                        <v-btn size="small" color="#455A64" @click="limpar">
                            <v-icon size="x-large">mdi-account-convert</v-icon>
                            Limpar
                        </v-btn>
                    </v-sheet>
                </v-card-title>

                <v-dialog v-model="isDialogOpen" max-width="500">
                    <v-card>
                        <v-card-title>Pesquisar</v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        label="Nome"
                                        v-model="pesquisas.nome"
                                        placeholder="Nome da conta"
                                    ></v-text-field>
                                </v-col>
                            </v-row>


                            <v-row>
                                <v-col cols="6">
                                    <v-text-field
                                        label="Data Início"
                                        v-model="pesquisas.data_inicio"
                                        type="date"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="6">
                                    <v-text-field
                                        label="Data Fim"
                                        v-model="pesquisas.data_fim"
                                        type="date"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn  color="success" @click="pesquisar">Pesquisar</v-btn>
                            <v-btn  color="warning" @click="limpar">Limpar</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="red" @click="isDialogOpen = false">Fechar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-data-table
                    :headers="headers"
                    :items="presos"
                    item-key="id"
                    class="elevation-1"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>Tabela de Presos</v-toolbar-title>
                        </v-toolbar>
                    </template>

                    <template v-slot:item="{ item }">
                        <tr>
<!--                            <td class="text-left">{{ item.id }}</td>-->
                            <td class="text-left">{{ item.nome }}</td>
                            <td class="text-left">{{ item.data_cadastro }}</td>
                            <td class="text-center">
                                <v-btn size="small" class="mr-3" elevation="4" @click="dialog = true" color="#3D5AFE">
                                    <v-icon size="x-large">mdi-folder-image</v-icon>
                                    Fotos
                                </v-btn>

                                <v-btn size="small" elevation="4" color="#F57C00">
                                    <v-icon @click="editPreso(item.id)" size="x-large">mdi-account-edit</v-icon>
                                    Editar
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
            </v-card>
        </v-container>
    </v-app>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>FOTOS</v-toolbar-title>
                <v-spacer>

                </v-spacer>
                <v-btn @click="dialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-container>
                <v-sheet class="d-flex align-content-space-between flex-wrap text-center " min-height="200">
                    <v-sheet v-for="n in 20" :key="n" class="ma-2 pa-2">
                        <v-img
                            :width="210"
                            aspect-ratio="4/3"
                            cover
                            src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                        </v-img>
                    </v-sheet>
                </v-sheet>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const dialog = ref(false);
const isDialogOpen = ref(false);
const presos = ref([]);
const pesquisas = reactive({
    nome: '',
    data_inicio: '',
    data_fim: ''
});

const editPreso = (id) => {
    window.location.href = `/presos/${id}/edicao`;
};

const headers = [
    // { text: 'ID', value: 'id' },
    { text: 'NOME', value: 'nome' },
    { text: 'DATA', value: 'data_cadastro' },
    { text: 'AÇÕES', value: 'actions', sortable: false }
];

const pesquisar = async () => {
    let query = `?nome=${pesquisas.nome}`;
    if (pesquisas.data_inicio) {
        query += `&data_inicio=${pesquisas.data_inicio}`;
    }
    if (pesquisas.data_fim) {
        query += `&data_fim=${pesquisas.data_fim}`;
    }
    await fetchPresos(query);
};

const limpar = async () => {
    pesquisas.nome = '';
    pesquisas.data_inicio = '';
    pesquisas.data_fim = '';
    await fetchPresos();
};

const fetchPresos = (query = '') => {
    axios.get('http://localhost:8004/pesquisar/presos' + query)
        .then(response => {
            presos.value = response.data;
        });
};

const getlistar = () => {
    axios.get('http://localhost:8004/listar/presos')
        .then(response => {
            presos.value = response.data;
        });
};

onMounted(() => {
    getlistar();
});
</script>
