<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TELA DE EDIÇÃO</title>
    <link href="https://cdn.jsdelivr.net/npm/vuetify@3.7.1/dist/vuetify.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .image-container {
            position: relative;
            width: 700px;
            height: 165px;
            margin: 5px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4a5568;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .select-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }
        .image-container.selected .select-overlay {
            opacity: 1;
        }
    </style>
</head>
<body>
<div id="app">
    <v-container>
        <v-card style="background-color: #f3f3f3; max-width: 1000px; margin: auto;" elevation="10" class="mt-16">
            <v-form>
                <div class="container mt-5 ml-5">
                    <v-row>
                        <v-col cols="6">
                            <v-text-field class="shadow-lg" variant="solo-inverted" label="Nome" v-model="person.nome"></v-text-field>
                        </v-col>
                    </v-row>
                </div>

                <div class=" mb-5 ">
                    <v-container>
                        <v-row>
                            <v-col v-for="(image, index) in images" :key="index" class="d-flex child-flex" cols="4" style="padding: 0;">
                                <div
                                    :class="['image-container', { selected: image.selected }]"
                                    @click="toggleImageSelection(index)">
                                    <v-img
                                        :src="image.src"
                                        :lazy-src="image.lazySrc"
                                        class="bg-grey-lighten-2"
                                        cover>
                                        <template v-slot:placeholder>
                                            <v-row align="center" class="fill-height ma-0" justify="center">
                                                <v-progress-circular color="grey-lighten-5" indeterminate></v-progress-circular>
                                            </v-row>
                                        </template>
                                    </v-img>
                                    <div class="select-overlay">✓</div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>



                    <v-btn @click="updatePerson" class="ml-2" color="success">Salvar</v-btn>
                    <v-btn @click="irParaUrlExterna" color="warning" class="ml-2">Voltar</v-btn>
                    <v-btn @click="deleteSelectedImages" color="red" class="ml-2">Excluir Selecionadas</v-btn>
                </div>
            </v-form>
        </v-card>
    </v-container>

    <v-dialog v-model="successDialog" max-width="290">
        <v-card>
            <v-card-title class="headline">Sucesso</v-card-title>
            <v-card-text>
                Os dados foram alterados com sucesso!
            </v-card-text>
            <v-card-actions>
                <v-btn color="green darken-1" @click="successDialog = false">Fechar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@3.7.1/dist/vuetify.min.js"></script>
<script>
    const { createApp } = Vue;
    const { createVuetify } = Vuetify;

    const app = createApp({
        data() {
            return {
                tab: null,
                person: {
                    nome: '',
                    id: null
                },
                successDialog: false,
                images: [
                    { src: 'https://picsum.photos/500/300?image=10', lazySrc: 'https://picsum.photos/10/6?image=10', selected: false },
                    { src: 'https://picsum.photos/500/300?image=15', lazySrc: 'https://picsum.photos/10/6?image=15', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },
                    { src: 'https://picsum.photos/500/300?image=20', lazySrc: 'https://picsum.photos/10/6?image=20', selected: false },


                ]
            };
        },
        mounted() {

            const data = @json($preso);
            if (data) {
                this.person = data;
            }
        },
        methods: {
            async updatePerson() {
                if (!this.person || !this.person.id) {
                    console.error('Dados do usuário não estão corretamente definidos.');
                    return;
                }
                const dados = {
                    nome: this.person.nome,
                };
                try {
                    const response = await axios.put(`http://localhost:8004/presos/${this.person.id}`, dados, {
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    console.log('Dados atualizados com sucesso', response.data);
                    this.successDialog = true;
                } catch (error) {
                    console.error('Erro ao atualizar dados', error);
                }
            },

            irParaUrlExterna() {
                window.location.href = 'http://localhost:8004/Listagem';
            },

            toggleImageSelection(index) {
                this.images[index].selected = !this.images[index].selected;
            },

            deleteSelectedImages() {
                this.images = this.images.filter(image => !image.selected);
            }
        }
    });

    app.use(createVuetify());
    app.mount('#app');
</script>
</body>
</html>
