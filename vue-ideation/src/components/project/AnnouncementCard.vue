<template lang="">
    <div class="announcement-card">
        <div class="announcement-title">
            <h2>Announcement</h2>
            <div v-if="project.creator_id === user.id">
                <button id="hover-white" @click="showAnnounModal = true" class="general-button">Create Announcement</button>
            </div>
        </div>  
        <div class="announcement-body">
            <div v-for="(ann, index) in announcement" :key="index">
                <shrink-card>
                    <div class="notifications__item">
                        <div class="notifications__item__content">
                            <span class="notifications__item__title">{{ ann.title }}</span>
                            <span class=" notifications__item__message">{{ ann.description }}</span>
                        </div>
                        <div v-if="project.creator_id === user.id">
                            <div class="notifications__item__option edit"  @click="openEditModal(ann.id, ann.title, ann.description)">
                                <font-awesome-icon icon="fa-edit" size="xs"></font-awesome-icon>
                            </div>
                            <div class="notifications__item__option delete" @click="openDeleteModal(ann.id)" >
                                <font-awesome-icon icon="trash-alt" size="xs"></font-awesome-icon>
                            </div>
                        </div>
                        <div v-else>
                            <div class="notifications__item__option " style="cursor: auto;" >
                            </div>
                            <div class="notifications__item__option " style="cursor: auto;" >
            
                            </div>
                        </div>
                    </div>
                </shrink-card>
            </div>
        </div>

        <modal
        :showModal="showEditModal"
        @close="closeEditModal">
            <h2 class="modal-title">Update Modal</h2>
            <form @submit.prevent="updateAnnouncement">
                <div class="input-container">
                    <input type="text" id="title" class="material-input" v-model="editForm.title" required>
                    <label for="title" class="material-label">Title</label>
                </div>
                <div class="input-container">
                    <input type="text" id="description" class="material-input" v-model="editForm.description" required>
                    <label for="description" class="material-label">Description</label>
                </div>
                <div class="text-right"> 
                    <button class="general-button">Update</button>
                </div>
            </form>
        </modal>
        <!-- delete modal -->
        <modal 
         :showModal="showDeleteModal"
         @close="closeDeleteModal">
            <h2 class="modal-title">Confirm Delete</h2>
            <div class="grid grid-cols-2 gap-2">
                <button class="general-button full-width" @click="closeDeleteModal">Cancel</button>
                <button class="general-button-danger full-width" @click="deleteAnnouncement">Confirm</button>
            </div>
        </modal>


        
        <!-- add announcement modal -->
        <modal 
        :showModal="showAnnounModal"
        @close="closeAnnounModal">
            <h2 class="modal-title" >Create Announcement</h2>
            <form @submit.prevent="addAnnouncement">
                <div class="input-container">
                    <input type="text" id="title" class="material-input" v-model="announForm.title" required>
                    <label for="title" class="material-label">Title</label>
                </div>
                <div class="input-container">
                    <input type="text" id="description" class="material-input" v-model="announForm.description" required>
                    <label for="description" class="material-label" >Description</label>
                </div>
                <div class="text-right">
                    <button class="general-button">Submit</button>
                </div>
            </form>
        </modal>

    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import ProjectModalVue from '../ProjectModal.vue'
import ShrinkCardVue from '../ShrinkCard.vue'
import api from '@/api/communicationApi'

export default {
    name: 'AnnouncementCard',
    components:{
        'shrink-card': ShrinkCardVue,
        'modal': ProjectModalVue
    },

    computed: {
        ...mapGetters([
            'announcement',
            'project',
            'user'
        ]),
    },  

    data() {
        return{
            projectId: this.$route.params.id,
            showEditModal: false,

            showDeleteModal: false,

            tempId: '',
            editForm: {
                title: '',
                description: ''
            },

            showAnnounModal: false,
            announForm: {
            title: "",
            description: "",
            },

            processing: false,
        }
    },

    methods: {
        openEditModal(id, title, description) {
            this.tempId = id
            this.editForm.title = title
            this.editForm.description = description
            this.showEditModal = true
        },

        closeEditModal() {
            this.showEditModal = false
            this.editForm.title = ''
            this.editForm.description = ''
            this.tempId = ''
        },

        async updateAnnouncement() {
            try {
                let { data } = await api.updateAnnouncement(this.projectId, this.tempId, this.editForm)
                if(data.success) {
                    this.showEditModal = false
                    await this.$store.dispatch('getAnnouncement', this.projectId)
                }
            } catch (error) {
                console.log(error)
            }
        },

        openDeleteModal(annId) {
            this.tempId = annId
            this.showDeleteModal = true
        },

        closeDeleteModal() {
            this.tempId = '',
            this.showDeleteModal = false
        },

        async deleteAnnouncement() {
            try {
                if(!this.processing && this.showDeleteModal) {
                    this.processing = true
                    let { data } = await api.deleteAnnouncement(this.projectId, this.tempId)
                    if(data.success) {
                        await this.$store.dispatch('getAnnouncement', this.projectId)
                        this.showDeleteModal = false    
                        this.processing = false 
                    } else {
                        this.processing = true
                    }
                }
                } catch (error) {
                    console.log(error)
                }
        },

        closeAnnounModal() {
            this.showAnnounModal = false;
            this.announForm.title = "";
            this.announForm.description = "";
        },

        async addAnnouncement() {
            try {
                if(!this.processing && this.showAnnounModal) {
                    this.processing = true
                    let { data } = await api.postAnnouncement(this.projectId, this.announForm);
                    if (data.success) {
                        await this.$store.dispatch("getAnnouncement", this.projectId);
                        this.closeAnnounModal();
                        this.processing = false
                    } else {
                        this.closeAnnounModal()
                        alert('error')
                        this.processing = false
                    }
                } 
            } catch (error) {
                console.log(error);
            }
        },

        
    },
}
</script>
<style lang="scss" scoped>
    #hover-white:hover {
        background: #fff;
        color: black;
        box-shadow: 0px 15px 20px rgb( 0, 0, 0, .2 );
        
    }
    .notifications__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 95%;
        margin-bottom: 1rem;
        padding: 0 20px;
        margin-left:auto;
        margin-right: auto;

        background-color: white;
        border-radius: 5px;
        transition: all .3s ease-in;
        border: 2px solid black;
    }

    .notifications__item__title,
    .notifications__item__message { display: block; }

    .notifications__item__title {
        letter-spacing: 2px;
        font-family: 'atvice', sans-serif;
        font-size: 17px;
    }

    .notifications__item__message {
        font-family: Roboto, sans-serif;
        font-size: 14px;
        color: #929292;
    }

    .notifications__item__option {
        width: 20px;
        height: 20px;
        margin: 8px 0;

        border-radius: 50%;
        color: white;
        opacity: 0;

        font-size: 1rem;
        text-align: center;
        line-height: 20px;

        cursor: pointer;
        transition: all .2s;
    }

    .notifications__item__option.edit { background-color: #3dc98c; }

    .notifications__item__option.delete { background-color: #c93d4d; }


    /*
    * Animation part
    */
    .notifications__item:hover {
        background-color: #f7f7f7;
        transform: scale( 1.05 );

        box-shadow: 0px 5px 10px 0px rgb( 0, 0, 0, .2 );
    }

    .notifications__item:hover .notifications__item__option { opacity: 1; }


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

    .announcement-card {
        box-shadow: 0 0 2rem -1rem rgba(0, 0, 0, 0.05);
        border-radius: 1rem;
        margin-bottom: 2rem;

        .announcement-title {
            background: linear-gradient(180deg, #8743FF 0%, #4136F1 100%);

            color: white;
            padding: 0.2rem 0.5rem;
            letter-spacing: 0.1rem;
            font-size: .9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .announcement-body {
            max-height: 25rem;
            padding-top: 2rem;
            min-height: 20rem;
            overflow: scroll;
            background-color: #fff;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }
        }
</style>