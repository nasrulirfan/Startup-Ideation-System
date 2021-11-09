<template>
  <div>
    <div class="card" @click="showModalCustomer = true">
      <div class="card_image">
        <img
          src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
        />
      </div>
      <div class="customer_data">
        <p><strong>Customer Name</strong></p>
        <p class="occupation">Occupation</p>
        <p class="rating">3<font-awesome-icon icon="fa-solid fa-star" /></p>
      </div>
    </div>

    <transition name="fade" appear>
      <div
        class="modal-overlay"
        v-if="showModalCustomer"
        @click="
          showModalCustomer = false;
          showModalConclude = false;
        "
      ></div>
    </transition>
    <transition name="slide" appear>
      <div class="customerInfoModal" v-if="showModalCustomer">
        <div
          class="customerContent"
          v-if="showModalCustomer && showModalConclude == false"
        >
          <div class="customerInfo">
            <img
              src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
            />
            <p class="custName"><strong>Elizabeth Tan</strong></p>
            <p class="custRating">
              <strong>Current Rating</strong>
              <span class="rating">
                3<font-awesome-icon icon="fa-solid fa-star"
              /></span>
            </p>
            <p class="custOcc">
              <strong>Occupation</strong> <span>Teacher</span>
            </p>
            <p class="custPhone">
              <strong>Phone Number</strong> <span>+6019-5153880</span>
            </p>
            <p class="custEmail">
              <strong>Email Address</strong> <span>elitan@gmail.com</span>
            </p>
            <button class="button">Edit Profile</button>
          </div>
          <div class="interviewdata">
            <div class="interviewdata_header">
              <span class="interviewlogs_title">
                <strong>Interview Logs</strong></span
              >
              <button class="button" @click="showModalConclude = true">
                Conclude Interview
              </button>
              <button class="button">Open Interview Logs</button>
            </div>
            <div class="interviewdata_content">
              <p>
                1. Thanks for taking my call, I’m doing some research on [main
                activity related to problem]. Before we start, can you tell me a
                bit about yourself? - Focus on learning about WHO your customer
                is before moving onto the problem... 2. When was the last time
                you [main activity related to problem]?
              </p>
            </div>
          </div>
        </div>
        <div
          class="concludeContent"
          v-if="showModalCustomer && showModalConclude == true"
        >
          <div class="conclude Header">
            circleProgress & current score detected
            <circular-progress></circular-progress>
          </div>
          <div class="concludeQuestion">Questions</div>
          <div class="concludeAnswer">conclude Answer</div>
        </div>
        <button
          class="button"
          style="margin-top: 10px"
          @click="
            showModalCustomer = false;
            showModalConclude = false;
          "
        >
          Close
        </button>
        <button
          v-if="showModalConclude"
          class="button"
          style="margin-top: 10px"
          @click="
            showModalCustomer = true;
            showModalConclude = false;
          "
        >
          Back
        </button>
      </div>
    </transition>
  </div>
</template>

<script>
import circularProgress from "./CircularProgress.vue";

export default {
  components: {
      circularProgress
  },
  data() {
    return {
      showModalCustomer: false,
      showModalConclude: false,
    };
  },
  mounted() {
    console.log(this.showModalCustomer);
  },
};
</script>

<style lang='scss' scoped >
.fa-star {
  color: #e67100;
  font-size: 20px;
}
.card {
  width: 220px;
  height: 220px;
  border-radius: 40px;
  box-shadow: 5px 5px 30px 7px rgba(0, 0, 0, 0.25),
    -5px -5px 30px 7px rgba(0, 0, 0, 0.22);
  cursor: pointer;
  transition: 0.3s ease-out;
  .card_image {
    width: inherit;
    height: inherit;
    border-radius: 40px;
    img {
      width: inherit;
      height: inherit;
      border-radius: 40px;
      object-fit: cover;
    }
  }
  .customer_data {
    text-align: center;
    font-family: "Poppins";
    font-size: 20px;
    margin-top: 28px;
    .fa-star {
      color: #e67100;
      font-size: 20px;
    }
    p {
      margin-top: -20px;
    }
    .occupation {
      margin-top: -24px;
      font-size: 18px;
      margin-left: 30px;
    }
    .rating {
      font-size: 39px;
      font-weight: bold;
    }
  }
}

.card:hover {
  transform: scale(0.9, 0.9);
  box-shadow: 5px 5px 30px 15px rgba(0, 0, 0, 0.25),
    -5px -5px 30px 15px rgba(0, 0, 0, 0.22);
}

.button {
  appearance: none;
  outline: none;
  border: none;
  background: none;
  cursor: pointer;
  padding: 10px 10px;
  background: linear-gradient(180deg, #8743ff 0%, #4136f1 100%);
  border-radius: 8px;

  color: #fff;
  font-size: 15px;
  font-weight: 600;

  box-shadow: 3px 3px rgba(0, 0, 0, 0.4);
  transition: 0.4s ease-out;
  &:hover {
    box-shadow: 6px 6px rgba(0, 0, 0, 0.6);
  }
}
.customerInfoModal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 99;

  width: 100%;
  max-width: 1000px;
  background-color: #fff;
  border-radius: 16px;
  overflow: scroll;
  padding: 25px;
  p {
    padding-top: 2px;
    padding-bottom: 3px;
  }
  .button {
    margin-right: 20px;
  }
  .customerContent {
    display: flex;
    gap: 50px;

    .customerInfo {
      width: 300px;
      height: 550px;
      border-radius: 40px;
      box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.25),
        -2px -2px 2px 2px rgba(0, 0, 0, 0.22);
      img {
        width: 300px;
        height: 300px;
        border-radius: 40px;
        object-fit: cover;
      }
      p {
        margin: 5px;
        padding: 0px;
        .rating {
          font-size: 30px;
          font-weight: bold;
        }
      }
      .custName {
        margin-top: 5px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
      }
      button {
        margin-left: 90px;
        margin-top: 10px;
        padding: 8px 10px;
        //   margin: auto;
      }
    }

    .interviewdata {
      width: 600px;
      height: 550px;
      border-radius: 40px;
      box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.25),
        -2px -2px 2px 2px rgba(0, 0, 0, 0.22);
      .interviewdata_header {
        display: flex;
        margin-top: 10px;
      }
      .interviewlogs_title {
        font-size: 20px;
        margin-left: 18px;
        margin-right: 50px;
      }
      button {
        padding: 8px 8px;
      }
      .interviewdata_content {
        margin-top: 15px;
        margin-left: 20px;
        width: 530px;
        height: 470px;
        border-radius: 20px;
        background: #f0f0f0;

        p {
          padding-left: 10px;
          margin: auto;
        }
      }
    }
  }
}

.modal-overlay {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  z-index: 98;
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