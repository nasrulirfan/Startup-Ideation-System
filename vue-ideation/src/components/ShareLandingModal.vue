<template>
  <div>
    <general-button @click.native="showModal = true">
      Share Landing Page
    </general-button>

    <div id="modal-comp">
      <transition name="fade" appear>
        <div
          class="modal-overlay"
          v-if="showModal"
          @click="showModal = false"
        ></div>
      </transition>
      <transition name="slide" appear>
        <div class="modal" v-if="showModal">
          <transition name="fade" appear>
            <div class="preInterview">
              <h2>Your Landing Page Link!</h2>
              <span class="inlineLink">
              <font-awesome-icon icon="fa-solid fa-link" />
              <textarea
                id="sharebox"
                rows="4"
                cols="50"
                v-model="shareableLink"
                readonly
              ></textarea>
              </span>

            </div>
          </transition>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import GeneralButton from "./GeneralButton.vue";
export default {
  props: {
    shareableLink: {
      type: String,
    },
  },
  components: { GeneralButton },
  data() {
    return {
      showModal: false,
    };
  },
  methods: {},
  mounted() {},
  computed: {},
};
</script>

<style lang = 'scss' scoped>
.fa-link{
  color:#8743FF;
}

.inlineLink{
  display: flex;
  align-items: center;
  gap: 10px;
}

textarea {
  width: 100%;
  height: 35px;
  border-radius: 4px;
  background-color: #f8f8f8;
  box-sizing: border-box;
  border: 2px solid #ccc;
  font-size: 16px;
    font-family: 'Poppins';
}

.inputField {
  border-radius: 5px;
  padding: 12px 20px;
  height: 30px;
  width: 100%;
  border: 1px solid #ccc;
  transition: all 0.3s ease 0s;

  &:focus {
    border: 1px solid rgb(133, 133, 133);
  }
}

.danger {
  border: 1px solid #ff3156;
}

.goalsCurrency {
  display: flex;
  .currency {
    display: flex;
    gap: 5px;
    height: 30px;
    justify-content: center;
    align-items: center;
    border-radius: 5px;
    padding: 5px;
    text-align: center;
    color: white;
    background: linear-gradient(180deg, #8743ff 0%, #4136f1 100%);
  }
}
.modal {
  display: grid;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 99;

  width: 100%;
  max-width: 800px;
  max-height: 750px;
  background-color: #fff;
  border-radius: 16px;
  overflow: scroll;
  padding: 25px;

  p {
    padding-top: 2px;
    padding-bottom: 3px;
  }

  .footerButton {
    margin-top: 25px;
    display: flex;
    gap: 20px;
  }
}

.modal-overlay {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  overflow: auto;
  z-index: 99;
  /* min-height: 100%; */
  background-color: rgba(0, 0, 0, 0.3);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.5s;
}

.slide-enter,
.slide-leave-to {
  transform: translateY(-50%) translateX(100vw);
}
</style>