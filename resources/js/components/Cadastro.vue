<template>
    <v-app style="background: #eaeaea">
        <v-container class="mt-5">
            <v-card style="width: 100%; max-width: 600px; margin: 0 auto;"
                    :elevation="15" class="shadow-lg p-3 mb-3 rounded-3 border custom-card shadow">
                <v-container>
                    <v-sheet class="d-flex">
                        <v-card-title>
                            <v-sheet class="ma-2 pa-2 me-auto text-center mt-n3">
                                <h1>PENALTRACK</h1>
                            </v-sheet>
                        </v-card-title>
                    </v-sheet>
                    <v-form ref="formRef" @submit.prevent="getRegistrar">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Nome"
                                    v-model="form.nome"
                                    :rules="[rules.required]"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-file-input
                                    append-icon=""
                                    prepend-icon=""
                                    append-inner-icon="mdi-camera-burst"
                                    label="Upload"
                                    v-model="form.file"
                                    show-size></v-file-input>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" class="d-flex justify-center mt-n6">
                                <v-sheet class="d-flex mb-6">
                                    <v-sheet class="ma-2 pa-2 me-auto">
                                        <v-btn
                                            color="#00C853"
                                            class="btn mb-2 mt-2"
                                            @click="getRegistrar">
                                            Registrar
                                        </v-btn>
                                        <v-btn
                                            color="#212121"
                                            class="btn btn-danger ma-2"
                                            :to="{ name: 'Listagem' }">
                                            LISTAR
                                        </v-btn>
                                    </v-sheet>
                                </v-sheet>
                            </v-col>
                        </v-row>
                    </v-form>
                    <v-dialog
                        v-model="successDialog"
                        persistent
                        hide-overlay
                        transition="dialog-bottom-transition">
                        <v-row align="center" justify="center" dense>
                            <v-col cols="12" md="6">
                                <v-card
                                    class="mx-auto mt-3"
                                    subtitle=""
                                    title="Cadastrado com sucesso">
                                    <template v-slot:prepend>
                                        <v-icon color="primary" icon="mdi-account"></v-icon>
                                    </template>
                                    <template v-slot:append>
                                        <v-icon color="success" icon="mdi-check"></v-icon>
                                    </template>
                                    <div class="d-flex flex-row-reverse mb-3 mr-3">
                                        <v-btn
                                            color="#DD2C00"
                                            @click="successDialog = false">
                                            Fechar
                                        </v-btn>
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-dialog>
                </v-container>
            </v-card>
        </v-container>
    </v-app>
</template>

<script>
import { ref, reactive } from 'vue';
import axios from 'axios';

export default {
    name: 'Cadastro',
    setup() {
        const formRef = ref(null);
        const form = reactive({
            nome: '',
            file: null,
        });

        const rules = reactive({
            required: value => !!value || 'Campo obrigatório.',
        });

        const validForm = ref(false);
        const successDialog = ref(false);

        const getRegistrar = async () => {
            if (!form.nome) {
                console.warn('Form is invalid.');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('nome', form.nome);
                if (form.file) {
                    formData.append('file', form.file);
                }

                const response = await axios.post('http://localhost:8004/cadastrar/preso', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                console.log('Sucesso:', response.data);
                successDialog.value = true;
                resetForm();

                setTimeout(() => {
                    successDialog.value = false;
                }, 3000);

            } catch (error) {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao registrar.');
            }
        };

        const resetForm = () => {
            form.nome = '';
            form.file = null;
        };

        return {
            formRef,
            form,
            rules,
            validForm,
            getRegistrar,
            successDialog,
        };
    },
};
</script>

<style scoped>
.custom-card {
    border: 1px solid #ddd;
}
</style>
