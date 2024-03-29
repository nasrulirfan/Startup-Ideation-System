<template>
  <div>
    <div v-if="!loading">
      <div class="headerDashboard">
        <p class="surveyName">{{ surveyName }} Dashboard</p>
        <general-button @click.native="showSurvey"
          >Preview Survey</general-button
        >
        <share-survey-modal
          :shareableLink="encodeShareLink"
        ></share-survey-modal>
        <general-button @click.native="showSummary"> Summary</general-button>
        <disabled-button
          class="updatetrashButton"
          @click.native="handleDelete()"
          ><font-awesome-icon icon="fa-solid fa-trash-can"
        /></disabled-button>
        <survey-update-modal class="updatetrashButton"></survey-update-modal>
      </div>
      <div class="summary">
        <h2>{{ surveyName }} Statistic</h2>
        <div class="cardFlexContainer">
          <dashboard-card>
            <template #logo>
              <sign-up :gradientBoolean="true"></sign-up
            ></template>
            <template #title> <h4>Responses</h4></template>
            <template #content>
              <h1>{{ totalResponse }}</h1>
            </template>
          </dashboard-card>
          <dashboard-card>
            <template #logo> <eyes :gradientBoolean="true"></eyes></template>
            <template #title> <h4>Total Views</h4></template>
            <template #content>
              <h1>{{ totalView }}</h1>
            </template>
          </dashboard-card>
          <dashboard-card>
            <template #logo>
              <revenue-target :gradientBoolean="true"></revenue-target>
            </template>
            <template #title> <h4>Responses Goal</h4></template>
            <template #content>
              <h1>{{ goalResponse }}</h1>
            </template>
          </dashboard-card>
        </div>
      </div>
    </div>
    <div v-else><loading-screen></loading-screen></div>
    <div class="chart" v-if="!loading">
      <h2>Unique Views Charts</h2>
      <chart-card>
        <apexchart
          type="area"
          height="350"
          :options="chartOptions"
          :series="series"
        ></apexchart>
      </chart-card>
    </div>
  </div>
</template>

<script>
import DashboardCard from "@/components/DashboardCard.vue";
import SignUp from "../../components/icons/sign-up.vue";
import Eyes from "../../components/icons/eyes.vue";
import RevenueTarget from "../../components/icons/revenueTarget.vue";
import ChartCard from "@/components/ChartCard.vue";
import GeneralButton from "@/components/GeneralButton.vue";
import surveyApi from "@/api/surveyApi.js";
// import { mapGetters } from "vuex";
import ShareSurveyModal from "../../components/ShareSurveyModal.vue";
import DisabledButton from "@/components/DisabledButton.vue";
import SurveyUpdateModal from "../../components/SurveyUpdateModal.vue";
import LoadingScreenVue from "@/components/general/LoadingScreen.vue";
export default {
  components: {
    DashboardCard,
    SignUp,
    Eyes,
    RevenueTarget,
    ChartCard,
    GeneralButton,
    ShareSurveyModal,
    DisabledButton,
    SurveyUpdateModal,
    "loading-screen": LoadingScreenVue,
  },
  data() {
    return {
      //Response Survey data
      loading: true,
      surveyName: "",
      totalResponse: 0,
      totalView: 0,
      goalResponse: 0,
      currentDate: undefined,
      todayPageView: 0,
      remainderPageView: 0,
      currentProjectID: undefined,

      series: [
        {
          name: "Views",
          data: [],
        },
      ],
      chartOptions: {
        chart: {
          height: 350,
          type: "area",
          fontFamily: "Poppins",
          zoom: {
            enabled: false,
          },
        },
        colors: ["#8743FF", "#4136F1"],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
          width: 4,
        },
        title: {
          text: "Unique Views",
          align: "left",
        },
        markers: {
          colors: ["#8743FF", "#4136F1"],
          size: 3,
        },
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.75,
            opacityTo: 0.75,
            stops: [20, 100, 100, 100],
          },
        },
        dropShadow: {
          enabled: true,
          top: 0,
          left: 0,
          blur: 3,
          opacity: 0.5,
        },
        grid: {
          show: false,
        },
        xaxis: {
          type: "datetime",
        },
      },
    };
  },
  methods: {
    showSummary() {
      this.$router.push({
        name: "SurveySummary",
        params: { projectID: this.currentProjectID },
      });
    },
    showSurvey() {
      this.$router.push({ name: "Survey" });
    },
    async handleDelete() {
      let confirmDelete = confirm(
        "Are you sure you want to delete this Survey?"
      );
      if (confirmDelete) {
        await surveyApi.deleteSurvey(this.currentProjectID);
        this.$router.push({
          name: "Project",
          params: { id: this.currentProjectID },
        });
      }
    },
  },

  computed: {
    encodeShareLink() {
      let encodeProjectID = btoa(this.currentProjectID);
      return window.location.origin + "/survey/share/" + encodeProjectID;
    },
    // ...mapGetters(["currentProjectID"]),
  },

  async created() {
    this.currentProjectID = this.$route.params.projectID;
    //Get Survey Data and Bind
    let surveyData = await surveyApi.getSurveyData(this.currentProjectID);
    this.surveyName = surveyData.data.surveyData[0].survey_name;
    this.totalResponse = surveyData.data.surveyData[0].responses;
    this.totalView = surveyData.data.surveyData[0].total_view;
    this.goalResponse = surveyData.data.surveyData[0].responses_goal;
    //If achieved goal
    //Get Dissapointed data
    let userAnswer = await surveyApi.getUserAnswer(this.currentProjectID);
    let dissapointedData = userAnswer.data.userAnswer.map(function (el) {
      return el.dissapointed;
    });
    //Calculate percentage of how many dissapointed
    let dissapointCount = 0;
    for (let i = 0; i < dissapointedData.length; i++) {
      if (
        dissapointedData[i] == "Very Disappointed" ||
        dissapointedData[i] == "Somewhat Disappointed"
      ) {
        dissapointCount++;
      }
    }
    let dissapointPercentage =
      (dissapointCount / dissapointedData.length) * 100;
    //If dissapointed > 40% (means product is a must have)
    if (
      this.totalResponse >= this.goalResponse &&
      surveyData.data.surveyData[0].validated === false
    ) {
      if (dissapointPercentage >= 40) {
        await surveyApi.setValidated(this.currentProjectID);
        this.$store.commit("setTypeToast", "Success");
        this.$store.commit(
          "setMessage",
          "Congrats you have achieved the goal! This product has a Product/Market Fit Potential!"
        );
        this.$store.commit("showToast");
      } else {
        this.$store.commit("setTypeToast", "Error");
        this.$store.commit(
          "setMessage",
          "Congrats you have achieved the goal response! However, This product does not have a Product/Market Fit Potential due to low 'Dissapointed' Rates! Re-iterate your survey to find more customer"
        );
        this.$store.commit("showToast");
      }
    }
    this.currentDate = surveyData.data.surveyData[0].current_date;
    this.todayPageView = surveyData.data.surveyData[0].today_view;
    this.remainderPageView = surveyData.data.surveyData[0].remainder_view;
    this.series = [
      {
        name: "Views",
        data: JSON.parse(surveyData.data.surveyData[0].series),
      },
    ];

    let today = new Date().toLocaleDateString();
    let projectdate = new Date(this.currentDate).toLocaleDateString();
    //If projectdate is today
    if (projectdate == today) {
      //Keep replacing with latest today data
      let newData = this.series[0].data;
      let newTodayPageView = { x: this.currentDate, y: this.todayPageView };
      // newData[newData.length] = newTodayPageView;
      newData.splice(newData.length - 1, 1, newTodayPageView);
      this.series = [
        {
          name: "Views",
          data: newData,
        },
      ];
      //Update Database
      let jsonSeries = JSON.stringify(this.series[0].data);
      let payloadUpdate = {
        updateSeries: jsonSeries,
      };
      await surveyApi.updateSeries(this.currentProjectID, payloadUpdate);
    }

    //If projectdate is not today
    else if (projectdate != today) {
      //Reset today page view and add remainder
      let pageviewResetRemainder = 0 + this.remainderPageView;
      let payload = {
        newTodayPageView: pageviewResetRemainder,
      };
      await surveyApi.resetUpdateTodayPageView(this.currentProjectID, payload);

      //Update with new Series of data
      let newData = this.series[0].data;
      newData.push({ x: today, y: pageviewResetRemainder });
      this.series = [
        {
          name: "Views",
          data: newData,
        },
      ];
      //Update Series Database
      let jsonSeries = JSON.stringify(this.series[0].data);
      let payloadUpdate = {
        updateSeries: jsonSeries,
      };
      await surveyApi.updateSeries(this.currentProjectID, payloadUpdate);

      //Update Project date
      let payload2 = {
        newCurrentDate: today,
      };
      this.currentDate = today;
      await surveyApi.updateCurrentDate(this.currentProjectID, payload2);
    }
    this.loading = false;
  },
};
</script>

<style lang="scss" scoped>
.updatetrashButton {
  ::v-deep button {
    width: 40px;
    height: 40px;
    cursor: pointer;
  }
  .fa-trash-can {
    margin: -1px;
  }
  .fa-pen-to-square {
    margin: -1px;
  }
}

* {
  margin: 10px;
}

.headerDashboard {
  display: flex;
  .surveyName {
    font-size: 30px;
    font-weight: bold;
  }
}
.cardFlexContainer {
  display: flex;
  gap: 30px;

  ::v-deep svg {
    height: 40px;
    width: 40px;
  }
}
</style>