`<template lang="">
    <div>
        <section class="c-container">
            <div class="page-title">
                <span>Free Canvas</span>
                <button class="general-button-colored" @click="openAddModal" >New</button>
            </div>

            <div class="page-body ">
                <div class="grid grid-cols-3">

                    <div v-for="(canvas, index) in freeCanvas" :key="index" class="" >
                        <!-- <p class="content-card" @click="goContent(canvas.id)" >{{ canvas.name }}</p> -->

                        <!-- <div class="notifications__item" >
                            <div class="notifications__item__content" @click="goContent(canvas.id)">
                                <span class="notifications__item__title">
                                    {{ canvas.name }}
                                </span>
                            </div>
                            <div >
                                <div class="notifications__item__option edit" @click="openUpdateModal(canvas)"  >
                                    <font-awesome-icon icon="fa-edit" size="xs"></font-awesome-icon>
                                </div>
                                <div class="notifications__item__option delete" @click="openDeleteModal(canvas)" >
                                    <font-awesome-icon icon="trash-alt" size="xs"></font-awesome-icon>
                                </div>
                            </div>
                        </div>  -->

                        <div class="book-wrapper">
                            <div class="book-notebook">
                                <div class="book-cover" :class="coverColor(canvas.name)" @click="goContent(canvas.id)">
                                    <div class="book-skin">
                                        <span> {{ canvas.name }} </span>
                                    </div>    
                                </div>
                                <div class="book-page ruled">
                                    <div class="book-btn-container">
                                        <div class="btn-update"  @click="openUpdateModal(canvas)">
                                            <font-awesome-icon icon="fa-edit" size="lg"></font-awesome-icon>
                                        </div>
                                        <div class="btn-delete" @click="openDeleteModal(canvas)">
                                            <font-awesome-icon icon="trash-alt" size="lg"></font-awesome-icon>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
        <!-- create canvas modal  -->
        <modal
         :showModal="showAddModal"
         @close="closeAddModal">
            <h2>Add Free Canvas</h2>
            <form @submit.prevent="addCanvas">
                <div class="input-container">
                    <input type="text" id="name" class="material-input" v-model="freeCanvasForm.name" required>
                    <label for="name" class="material-label">Canvas Name</label>
                </div>
                <div class="text-right">
                    <button id="submit-button-modal" class=" general-button">Submit</button>
                </div>
            </form>
        </modal>

        <!-- update canvas modal -->
        <modal 
         :showModal="showUpdateModal"
         @close="closeUpdateModal">
            <h2>Update Free Canvas</h2>
            <form @submit.prevent="updateCanvas">
                <div class="input-container">
                    <input type="text" class="material-input" id="name" v-model="updatedForm.name" required>
                    <label for="name" class="material-label">Canvas Name</label>
                </div>
                <div class="text-right">
                    <button id="submit-button-modal" class="general-button">Update</button>
                </div>
            </form>
        </modal>
        <!-- delete modal -->
        <modal 
         :showModal="showDeleteModal"
         @close="closeDeleteModal">
            <h2>Delete Canvas</h2>
            <div class="grid grid-cols-2 gap-2">
                <button class="general-button full-width" @click="closeDeleteModal">cancel</button>
                <button class="general-button-danger full-width" @click="deleteCanvas">Delete</button>
            </div>
        </modal>

    </div>
</template>
<script>
import ProjectModalVue from '../../components/ProjectModal.vue'
import api from '../../api/freeCanvasApi'
import { mapGetters } from 'vuex'

export default {
    name: 'FreeCanvas',
    components: {
        'modal': ProjectModalVue,
    },
    computed: {
        ...mapGetters([
            'freeCanvas'
        ])
    },

    async created() {
        this.initialize()
        this.connect()
    },

    beforeDestroy() {
        this.disconnect()
    },

    data() {
        return {
            showAddModal: false,
            freeCanvasForm: {
                name: ''
            },
            showUpdateModal: false,
            showDeleteModal: false,
            
            updatedForm: {
                name: ''
            },

            tempId: null,
            processing: false,
        }
    },

    methods: {

        async initialize() {
            try {
                await this.$store.dispatch('getCanvas', this.$route.params.id)
            } catch (error) {
                console.log(error)
            }
        },
        connect() {
            window.Echo.private(`Project.${this.$route.params.id}`)
                        .listen('FreeCanvasUpdated', async () => {
                            await this.$store.dispatch('getCanvas', this.$route.params.id)
                        })
        }, 

        disconnect() {
            window.Echo.leaveChannel(`Project.${this.$route.params.id}`)
        },

        openAddModal() {
            this.showAddModal = true
        },

        closeAddModal() {
            this.showAddModal = false 
            this.freeCanvasForm.name = ''
        }, 

        async addCanvas() {
            try {
                if(!this.processing && this.showAddModal) {
                    this.processing = true                    
                    let res = await api.addFreeCanvas(this.$route.params.id, this.freeCanvasForm)
                    if(res.data.success) {
                        this.$store.dispatch('getCanvas', this.$route.params.id)
                        this.closeAddModal()
                        this.processing = false
                    } else {
                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Attempt unsuccessful')
                        this.$store.commit('showTimeoutToast')
                        this.closeAddModal()
                        this.processing = false
                        
                    }
                }
                
            } catch (error) {
                console.log(error)
            }
        },

        goContent(canvasId) {
            this.$router.push({ name: 'EditorPage', params: {id: this.$route.params.id, canvasId: canvasId} })
        },

        async updateCanvas() {
            try {
                if(!this.processing && this.showUpdateModal) {
                    this.processing = true
                    let {data} = await api.updateFreeCanvas(this.$route.params.id, this.tempId, this.updatedForm)
                    if(data.success) {
                        await this.$store.dispatch('getCanvas', this.$route.params.id)
                        this.closeUpdateModal()
                        this.processing = false
                    } else {
                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Attempt unsuccessful')
                        this.$store.commit('showTimeoutToast')
                        this.closeUpdateModal()
                        this.processing = false
                        
                    }
                }
            } catch (error) {
                console.log(error)
            }
        }, 

        async deleteCanvas() {
            try {
                if(!this.processing && this.showDeleteModal) {
                    this.processing = true
                    let {data} = await api.deleteFreeCanvas(this.$route.params.id, this.tempId)
                    if(data.success) {
                        await this.$store.dispatch('getCanvas', this.$route.params.id) 
                        this.closeDeleteModal()
                        this.processing = false
                    } else {
                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Attempt unsuccessful')
                        this.$store.commit('showTimeoutToast')
                        this.processing = false
                    }
                }
            } catch (error) {
                console.log(error)
            }
        },

        openUpdateModal(canvas) {
            this.updatedForm.name = canvas.name
            this.tempId = canvas.id
            this.showUpdateModal = true
        },

        closeUpdateModal() {
            this.showUpdateModal = false
            this.updatedForm.name = ''
            this.tempId = null
        },

        openDeleteModal(canvas) {
            this.tempId = canvas.id
            this.showDeleteModal = true
        },

        closeDeleteModal() {
            this.tempId = null
            this.showDeleteModal = false
        },

        coverColor(name) {
            let code = name.charCodeAt(0)
            code = String(code).charAt(0)
            if(code >= 0 && code < 3) return ''
            else if(code >= 3 && code < 5) return 'blue'
            else if(code >= 5 && code < 8) return 'green'
            else return 'yellow'
        }
    }
    
}
</script>
<style lang="scss" scoped>

    .book-wrapper {
        text-align: center;
    }

    .page-title {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 0 40px rgb(0 0 0 / 5%);
        margin-bottom: 2rem;
        min-height: 2rem;
        padding: 1rem 1rem;

        display: flex;
        align-items: center;
        justify-content: space-between;

        span {
            letter-spacing: 0.2rem;
            font-weight: bold;
            font-size: 2rem;
        }

        .general-button-colored, #submit-button-modal {
            width: 8rem;
        }
    }
    #submit-button-modal {
        width: 8rem;
    }

    .page-body {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 0 40px rgb(0 0 0 / 5%);
        height: 45rem;
        max-height: 45rem;
        padding: 2rem 1rem;
        overflow: scroll;        
    }

    .content-card {
        background: #e0fbfc;
        box-shadow: 0 0 40px rgb(0 0 0 / 5%);

    }

    // !notification part 
    // .notifications__item {
    //     display: flex;
    //     align-items: center;
    //     justify-content: space-between;
    //     width: 95%;
    //     margin-bottom: 1rem;
    //     padding: 0 20px;
    //     margin-left:auto;
    //     margin-right: auto;

    //     background-color: white;
    //     border-radius: 5px;
    //     transition: all .3s ease-in;
    //     border: 3px solid black;

    //     .notifications__item__content {
    //         cursor: pointer;
    //     }
    // }

    // .notifications__item__title,
    // .notifications__item__message { display: block; }

    // .notifications__item__title {
    //     letter-spacing: 2px;
    //     font-family: 'atvice', sans-serif;
    //     font-size: 17px;
    // }

    // .notifications__item__message {
    //     font-family: Roboto, sans-serif;
    //     font-size: 14px;
    //     color: #929292;
    // }

    // .notifications__item__option {
    //     width: 20px;
    //     height: 20px;
    //     margin: 8px 0;

    //     border-radius: 50%;
    //     color: white;
    //     opacity: 0;

    //     font-size: 1rem;
    //     text-align: center;
    //     line-height: 20px;

    //     cursor: pointer;
    //     transition: all .2s;
    // }

    // .notifications__item__option.edit { background-color: #3dc98c; }

    // .notifications__item__option.delete { background-color: #c93d4d; }


    // /*
    // * Animation part
    // */
    // .notifications__item:hover {
    // background-color: #f7f7f7;
    // transform: scale( 0.95 );
    // box-shadow: 0px 5px 10px 0px rgb( 0, 0, 0, .2 );
    // }

    // .notifications__item:hover .notifications__item__option { opacity: 1; }


    /* select starting stylings ------------------------------*/
    .select {
        position: relative;
    }

    .select-text {
        position: relative;
        font-family: inherit;
        background-color: transparent;
        width: stretch;
        padding: 10px 10px 10px 0;
        font-size: 18px;
        border-radius: 0;
        border: none;
        border-bottom: 1px solid rgba(0,0,0, 0.12);
    }

    /* Remove focus */
    .select-text:focus {
        outline: none;
        border-bottom: 1px solid rgba(0,0,0, 0);
    }

        /* Use custom arrow */
    .select .select-text {
        appearance: none;
        -webkit-appearance:none
    }

    .select:after {
        position: absolute;
        top: 18px;
        right: 10px;
        /* Styling the down arrow */
        width: 0;
        height: 0;
        padding: 0;
        content: '';
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid rgba(0, 0, 0, 0.12);
        pointer-events: none;
    }


    /* LABEL ======================================= */
    .select-label {
        color: rgba(0,0,0, 0.5);
        font-size: 18px;
        font-weight: normal;
        position: absolute;
        pointer-events: none;
        left: 0;
        top: 10px;
        transition: 0.2s ease all;
    }

    /* active state */
    .select-text:focus ~ .select-label, .select-text:valid ~ .select-label {
        color: #2F80ED;
        top: -20px;
        transition: 0.2s ease all;
        font-size: 14px;
    }

    /* BOTTOM BARS ================================= */
    .select-bar {
        position: relative;
        display: block;
        width: stretch;
    }

    .select-bar:before, .select-bar:after {
        content: '';
        height: 2px;
        width: 0;
        bottom: 1px;
        position: absolute;
        background: #2F80ED;
        transition: 0.2s ease all;
    }

    .select-bar:before {
        left: 50%;
    }

    .select-bar:after {
        right: 50%;
    }

    /* active state */
    .select-text:focus ~ .select-bar:before, .select-text:focus ~ .select-bar:after {
        width: 50%;
    }

    /* HIGHLIGHTER ================================== */
    .select-highlight {
        position: absolute;
        height: 60%;
        width: stretch;
        top: 25%;
        left: 0;
        pointer-events: none;
        opacity: 0.5;

    }
    .btn-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
</style>`