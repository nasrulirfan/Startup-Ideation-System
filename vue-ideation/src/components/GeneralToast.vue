<template>
  <div>
    <div class="toast" :class="toastClassType">
      <div class="toast__icon">
        <svg
          version="1.1"
          class="toast__svg"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px"
          y="0px"
          viewBox="0 0 512 512"
          style="enable-background: new 0 0 512 512"
          xml:space="preserve"
        >
          <g>
            <g>
              <path
                d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0    c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7    C514.5,101.703,514.499,85.494,504.502,75.496z"
              ></path>
            </g>
          </g>
        </svg>
      </div>
        <div class="toast__content">
          <p class="toast__type">{{ toastType }}</p>
          <p class="toast__message">
            {{ toastMessage }}
          </p>
        </div>
      <div class="toast__close" @click="closeToast()">
        <svg
          version="1.1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 15.642 15.642"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          enable-background="new 0 0 15.642 15.642"
        >
          <path
            fill-rule="evenodd"
            d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"
          ></path>
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    toastType() {
      return this.$store.state.toastRepository.type;
    },
    toastMessage() {
      return this.$store.state.toastRepository.message;
    },
    toastClassType() {
      if (this.$store.state.toastRepository.type == "Error") {
        return "toast--error";
      } else if (this.$store.state.toastRepository.type == "Success") {
        return "toast--green";
      } else {
        return null;
      }
    },
  },
  methods: {
    closeToast() {
      this.$store.commit("closeToast");
    },
  },
};
</script>

<style lang="scss" scoped>
.toast__svg {
  fill: #fff;
}

.toast {
  position: fixed;
  margin-left: auto;
  margin-right: auto;
  left: 0;
  right: 0;
  z-index: 999999;
  text-align: left;
  padding: 21px 0;
  background-color: #fff;
  border-radius: 4px;
  max-width: 500px;
  box-shadow: 1px 7px 14px -5px rgba(0, 0, 0, 0.2);
}

.toast:before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}

.toast__icon {
  position: absolute;
  top: 50%;
  left: 22px;
  transform: translateY(-50%);
  width: 14px;
  height: 14px;
  padding: 7px;
  border-radius: 50%;
  display: inline-block;
}

.toast__type {
  color: #3e3e3e;
  font-size: 20px;
  font-weight: 700;
  margin-top: 0;
  margin-bottom: 8px;
}

.toast__message {
  font-size: 14px;
  margin-top: 0;
  margin-bottom: 0;
  color: #878787;
}

.toast__content {
  padding-left: 70px;
  padding-right: 60px;
}

.toast__close {
  position: absolute;
  right: 22px;
  top: 50%;
  width: 14px;
  cursor: pointer;
  height: 14px;
  fill: #878787;
  transform: translateY(-50%);
}

.toast--error .toast__icon {
  background-color: #dc3545;
}

.toast--error:before {
  background-color: #dc3545;
}

.toast--green .toast__icon {
  background-color: #198754;
}

.toast--green:before {
  background-color: #198754;
}

.toast-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.toast-leave-to {
  opacity: 0;
  transform: translateY(-60px);
}

.toast-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>