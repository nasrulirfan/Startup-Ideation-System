<template lang="">
    <div>
        <div class="c-body-card">
            <div class="c-body-title " >
                Solution
            </div>
            <div class="c-body-content" :style="guide ? {height: '70vh' } : {height: '30rem'}" >
                <div class="general-button" @click="openAddModal">
                    NEW
                </div>

                <!--!! list of Problem  -->
                <div class="content-container" >
                    <div v-if="leanSolution === []">
                        <h2>No Solution yet</h2>
                    </div>
                    <div v-for="(seg, index) in leanSolution" :key="index" class="lean-content-card" v-else>
                        <div class="lean-divider">
                            <div class="lean-topic">
                                <span>{{ seg.topic }}</span>
                                <span class="cs-segment">
                                    For Customer Segment: {{ segmentName(seg.customer_segment_id) }}
                                </span>
                                <span class="created-by">
                                    Created by: {{ getPublisherName(seg.publisher_id) }}
                                </span>
                            </div>
                            <div class="lean-btn-container grid grid-cols-2 gap-4">
                                <button class="general-button-success lean-update" @click="openUpdateModal(seg)">
                                    <font-awesome-icon icon="fa-pen-to-square" v-if="!guide"></font-awesome-icon>
                                    <span v-else>Update</span>
                                </button>
                                <button class="general-button-danger lean-delete" @click="openDeleteModal(seg.id)">
                                    <font-awesome-icon icon="fa-trash-alt"  v-if="!guide"  ></font-awesome-icon>
                                    <span v-else>Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- add new modal   -->
        <modal
            :showModal="showAddModal"
            @close="closeAddModal" >

            <h2 class="modal-title">Add Solution</h2>
            <form @submit.prevent="addContent">
                <div class="input-container">
                    <input type="text" class="material-input" id="topic" v-model="contentForm.topic" >
                    <label for="topic" class="material-label">Solution</label>
                </div>

                <div class="select input-container" >
                    <select id="" class="select-text" v-model="contentForm.customer_segment_id" required>
                        <option value="" disabled selected></option>
                        <option v-for="(cust, index) in customerSegment" :key="index" :value="cust.id">{{ cust.topic }}</option>
                    </select>

                    <span class="select-highlight"></span>
                    <span class="select-bar"></span>
                    <label for="" class="select-label">For Customer Segment</label>
                </div>

                <div class="text-right">
                    <button class="general-button">Submit</button>
                </div>
            </form>

        </modal>
    <!-- delete confirm modal -->
        <modal
            :showModal="showDeleteModal"
            @close="closeDeleteModal" >

            <h2 class="modal-title">Confirm Delete?</h2>
            <div class="grid grid-cols-2 gap-2">
                <button class="general-button full-width" @click="closeDeleteModal">Cancel</button>
                <button class="general-button-danger full-width" @click="deleteContent">Delete</button>
            </div>                            

        </modal>
    <!-- udpate modal -->
        <modal
            :showModal="showUpdateModal"
            @close="closeUpdateModal" >

            <h2 class="modal-title">Update </h2>
            <form @submit.prevent="addContent">
                <div class="input-container">
                    <input type="text" class="material-input" id="topic" v-model="tempContent.topic" >
                    <label for="topic" class="material-label">Solution</label>
                </div>

                <div class="select input-container" >
                    <select id="" class="select-text" v-model="tempContent.customer_segment_id" required>
                        <option value="" disabled selected></option>
                        <option v-for="(cust, index) in customerSegment" :key="index" :value="cust.id">{{ cust.topic }}</option>
                    </select>

                    <span class="select-highlight"></span>
                    <span class="select-bar"></span>
                    <label for="" class="select-label">For Customer Segment</label>
                </div>

                <div class="text-right">
                    <button class="general-button" @click="updateContent">Update</button>
                </div>
            </form>

        </modal>


    </div>
</template>
<script>
import ProjectModalVue from '../ProjectModal.vue'
import api from '../../api/leanCanvasApi'
import { mapGetters } from 'vuex'

export default {
    name: "ProblemLeanCanvas",
    components: {
        'modal': ProjectModalVue
    },
    props: ['user', 'guide'],
    computed: {
        ...mapGetters([
            'leanSolution',
            'collaborator',
            'customerSegment'
        ])
    },

    data() {
        return {
            type: 4,
            projectId: this.$route.params.id,
            showAddModal: false,
            contentForm: {
                topic: null,
                customer_segment_id: null,
                publisher_id: this.user.id,
                contentType: 4,
            },
            tempId: null,

            showDeleteModal: false,

            showUpdateModal: false, 
            tempContent: {
                topic: null
            },

            processing: false,
        }
    },

    destroyed() {
        this.$store.commit('closeToast')
    },

    methods: {

        async setSegment() {
            try {
                let {data} = await api.getSegment(this.projectId, this.type)
                if(data.success) {
                    this.$store.commit('SET_LEAN_SOLUTION', data.content)
                }

            } catch (error) {
                console.log(error)
            }
        },

        closeAddModal() {
            this.showAddModal = false
            this.contentForm.topic = null
            this.contentForm.customer_segment_id = null

        },

        openAddModal() {
            this.showAddModal = true
        },

        async addContent() {
            try {
                if(!this.processing && this.showAddModal) {
                    this.processing = true
                    if(this.contentForm.customer_segment_id == -1) this.contentForm.customer_segment_id = null

                    let {data} = await api.addContent(this.projectId, this.contentForm)
                    if(data.success) {
                        this.setSegment()

                        this.showAddModal = false
                        this.processing = false

                        this.$store.commit('setTypeToast', 'Success')
                        this.$store.commit('setMessage', 'Successfully Added')
                        this.$store.commit('showTimeoutToast')
                    } else {
                        this.showAddModal = false
                        this.processing = false

                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Attempt unsuccessful')
                        this.$store.commit('showTimeoutToast')
                    }
                }
            } catch (error) {
                console.log(error)
            }
        },

        getPublisherName(id) {
            let user = this.collaborator.find( (user) => user.id === id )
            return user != null ? user.username : 'removed collaborator' 
        },

        openDeleteModal(id) {
            this.tempId = id
            this.showDeleteModal = true
        }, 

        closeDeleteModal() {
            this.tempId = null
            this.showDeleteModal = false
        },

        async deleteContent() {
            try {
                if(!this.processing && this.showDeleteModal) {
                    this.processing = true
                    let res = await api.deleteContent(this.tempId, this.type)
                    if(res.data.success) {
                        this.setSegment()

                        this.closeDeleteModal()
                        this.processing = false
                    } else {

                        this.closeDeleteModal()
                        this.processing = false

                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Delete is unsuccessful')
                        this.$store.commit('showToast')
                    }
                }
            } catch (error) {
                console.log(error)
            }
        },

        openUpdateModal(segment) {
            this.tempContent.topic = segment.topic
            this.tempId = segment.id  
            this.tempContent.customer_segment_id = segment.customer_segment_id
            this.showUpdateModal = true
        }, 

        closeUpdateModal() {
            this.tempContent.topic = null
            this.tempId = null
            this.tempContent.customer_segment_id = null
            this.showUpdateModal = false
        },

        async updateContent() {
            try {
                if(!this.processing && this.showUpdateModal) {
                    this.processing = true
                    if(this.tempContent.customer_segment_id === -1) this.tempContent.customer_segment_id = null

                    let {data} = await api.updateContent(this.tempId, this.type, this.tempContent)
                    if(data.success) {
                        this.setSegment()
                        
                        this.closeUpdateModal()
                        this.processing = false
                    } else {
                        this.closeUpdateModal() 
                        this.processing = false

                        this.$store.commit('setTypeToast', 'Error')
                        this.$store.commit('setMessage', 'Update is unsuccessful')
                        this.$store.commit('showToast')
                    }
                }
            } catch (error) {
                console.log(error)
            }
        },

        segmentName(id) {                
            let cust = this.customerSegment.find( (cs) => cs.id === id )
            if(cust == null) return 'None'
            return cust.topic 
        }

        

        
    }


    
}
</script>
<style lang="scss" scoped>
    .text-right {
        text-align: right;
        .general-button {
            width: 8rem;
        }
    }

    .content-container {
        padding: 0 2rem;
    }



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

    
</style>