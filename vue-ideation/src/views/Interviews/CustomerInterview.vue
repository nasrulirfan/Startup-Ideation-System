<template>
  <div class="customerContainer">
    <div
      class="customerCards"
      v-for="(item, index) in customerCards"
      :key="index"
    >
      <customer-card
        :passedID="item.cust_ID"
        :passedName="item.custname"
        :passedOcc="item.custocc"
        :passedEmail="item.custemail"
        :passedPhone="item.cust_phone_num"
        :passedScore="item.custscore"
        :passedImagePath="item.image_path"
        :passedLogs="item.logs"
      >
      </customer-card>
    </div>
    <add-customer-card @addCard="addCardInterview"></add-customer-card>
  </div>
</template>

<script>
import CustomerCard from "./CustomerCard.vue";
import AddCustomerCard from "./AddCustomerCard.vue";
import customerApi from "@/api/customerApi.js";
export default {
  data() {
    return {
      customerCards: [],
      interviewIndex : 0,
    };
  },
  components: {
    CustomerCard,
    AddCustomerCard,
  },
  methods: {
    async addCardInterview(customer_info) {
      let test = await customerApi.createCustomer(this.interviewIndex, customer_info);
      if (test.data.success){
        this.customerCards.push(customer_info);
      }
      else{
        throw new Error("Cannot create Customer")
      }
    },

    async getCustomerInterview() {
      let getCustomer = await customerApi.getAllCustomer(this.interviewIndex);
      for (let item in getCustomer.data) {
        this.customerCards.push(getCustomer.data[item]);
      }
    },
  },
  computed: {
    // ...mapGetters(["interviewIndex"]),
  },
  mounted() {
    this.interviewIndex = this.$route.params.id
    this.getCustomerInterview();
    this.$emit('finishData');

  },
  created() {},
};
</script>

<style scoped>
.customerContainer {
  display: flex;
  flex-wrap: wrap;
  gap: 150px;
}
</style>