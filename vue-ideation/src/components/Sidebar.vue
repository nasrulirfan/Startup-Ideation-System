<template>
  <div>
    <!----===== Boxicons CSS ===== -->
    <link
      href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css"
      rel="stylesheet"
    />
    <nav class="sidebar" :class="toggleClose">
      <header>
        <div class="image-text">
          <span class="image">
            <img src="@/assets/logo.png" alt="" />
          </span>

          <div class="text logo-text">
            <span class="name">Startup</span>
            <span class="profession">Ideation System</span>
          </div>
        </div>

        <i class="bx bx-chevron-right toggle" @click="toggleSidebar"></i>
      </header>

      <div class="menu-bar">
        <div class="menu">
          <!-- <li class="search-box">
                    <i class='bx bx-search icon'></i> 
                    <input type="text" placeholder="Search...">
                </li> -->

          <ul class="menu-links">
            <li class="nav-link">
              <a @click="routeSidebar('Project')">
                <i class="bx bx-home-alt icon"></i>
                <span class="text nav-text">Dashboard</span>
              </a>
            </li>

            <li class="nav-link">
              <a @click="routeSidebar('TodoPage')">
                <i class="bx bx-list-ul icon"></i>
                <span class="text nav-text">To Do List</span>
              </a>
            </li>

            <li class="nav-link">
              <a @click="routeSidebar('ChatPage')">
                <i class="bx bx-message-dots icon"></i>
                <span class="text nav-text">Project Chat</span>
              </a>
            </li>

            <li class="nav-link">
              <a @click="routeSidebar('LeanCanvas')">
                <i class="bx bx-brain icon"></i>
                <span class="text nav-text">Lean Canvas</span>
              </a>
            </li>

            <li class="nav-link">
              <a @click="routeSidebar('FreeCanvas')">
                <i class="bx bx-chalkboard icon"></i>
                <span class="text nav-text">Free Canvas</span>
              </a>
            </li>

            <li class="nav-link">
              <a @click="routeSidebar('ProjectsList')">
                <i class="bx bx-wallet icon"></i>
                <span class="text nav-text">Project List</span>
              </a>
            </li>
            <li class="nav-link" v-if="this.$route.name == 'Interview'">
              <a @click="routeSidebar('Hypothesis')">
                <i class="bx bx-abacus icon"></i>
                <span class="text nav-text">Hypothesis</span>
              </a>
            </li>
            <li
              class="nav-link"
              v-if="
                this.$route.name == 'SurveySummary' ||
                this.$route.name == 'Survey'
              "
            >
              <a @click="routeSidebar('SurveyDashboard')">
                <i class="bx bx-line-chart icon"></i>
                <span class="text nav-text">Back to Dashboard</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="bottom-content">
          <li class="">
            <a @click="logout">
              <i class="bx bx-log-out icon"></i>
              <span class="text nav-text">Logout</span>
            </a>
          </li>
        </div>
      </div>
    </nav>

    <section class="home">
      <slot></slot>
      <!-- <div class="text">Dashboard Sidebar</div> -->
    </section>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      togglesidebar: false,
      // projectId: this.$route.params.id
    };
  },
  computed: {
    ...mapGetters(["project", "currentProjectID"]),

    toggleClose() {
      return {
        close: this.togglesidebar === false,
      };
    },

    ...mapGetters(["project"]),
  },

  methods: {
    routeSidebar(routename) {
      console.log(this.currentProjectID);
      if (routename === "SurveyDashboard") {
        this.$router.push({
          name: routename,
          params: { projectID: this.currentProjectID },
        });
      } else {
        this.$router.push({
          name: routename,
          params: { id: this.currentProjectID },
        });
      }
    },

    handleResume() {
      // this.$router.push({})
    },
    toggleSidebar() {
      this.togglesidebar = !this.togglesidebar;
    },
    async logout() {
      try {
        await this.$store.dispatch("logout");
        await this.$router.push({ name: "HomeGuest" });
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  background-color: #e4e9f7;
  transition: all 0.3s ease;
}

::selection {
  background: linear-gradient(180deg, #8743ff 0%, #4136f1 100%);
  color: #fff;
}

.color {
  color: black;
}

.fonticon {
  ::v-deep svg {
    height: 20px;
    width: 20px;
  }
}

/* ===== Sidebar ===== */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 250px;
  padding: 10px 14px;
  background: #fff;
  transition: all 0.3s ease;
  z-index: 100;
}
.sidebar.close {
  width: 88px;
}

/* ===== Reusable code - Here ===== */
.sidebar li {
  height: 50px;
  list-style: none;
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.sidebar header .image,
.sidebar .icon {
  min-width: 60px;
  border-radius: 6px;
}

.sidebar .icon {
  min-width: 60px;
  border-radius: 6px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}

.sidebar .text,
.sidebar .icon {
  color: #000000;
  transition: all 0.2s ease;
}

.sidebar .text {
  font-size: 17px;
  font-weight: 500;
  white-space: nowrap;
  opacity: 1;
}
.sidebar.close .text {
  opacity: 0;
}
/* =========================== */

.sidebar header {
  position: relative;
}

.sidebar header .image-text {
  display: flex;
  align-items: center;
}
.sidebar header .logo-text {
  display: flex;
  flex-direction: column;
}
header .image-text .name {
  margin-top: 2px;
  font-size: 18px;
  font-weight: 600;
}

header .image-text .profession {
  font-size: 16px;
  margin-top: -2px;
  display: block;
}

.sidebar header .image {
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar header .image img {
  width: 40px;
  border-radius: 6px;
}

.sidebar header .toggle {
  position: absolute;
  top: 50%;
  right: -25px;
  transform: translateY(-50%) rotate(180deg);
  height: 25px;
  width: 25px;
  background: linear-gradient(180deg, #8743ff 0%, #4136f1 100%);
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  cursor: pointer;
  transition: all 0.3s ease;
}

body.dark .sidebar header .toggle {
  color: #000000;
}

.sidebar.close .toggle {
  transform: translateY(-50%) rotate(0deg);
}

.sidebar .menu {
  margin-top: 40px;
}

.sidebar li.search-box {
  border-radius: 6px;
  background-color: #f6f5ff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.sidebar li.search-box input {
  height: 100%;
  width: 100%;
  outline: none;
  border: none;
  background-color: #f6f5ff;
  color: #000000;
  border-radius: 6px;
  font-size: 17px;
  font-weight: 500;
  transition: all 0.3s ease;
}
.sidebar li a {
  list-style: none;
  height: 100%;
  background-color: transparent;
  display: flex;
  align-items: center;
  height: 100%;
  width: 100%;
  border-radius: 6px;
  text-decoration: none;
  transition: all 0.2s ease;
}

.sidebar li a:hover {
  background: linear-gradient(180deg, #8743ff 0%, #4136f1 100%);
}
.sidebar li a:hover .icon,
.sidebar li a:hover .text {
  color: #fff;
}
body.dark .sidebar li a:hover .icon,
body.dark .sidebar li a:hover .text {
  color: #000000;
}

.sidebar .menu-bar {
  height: calc(100% - 55px);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  overflow-y: scroll;
  -ms-overflow-style: none; /* IE 11 */
  scrollbar-width: none; /* Firefox 64 */
}
.menu-bar::-webkit-scrollbar {
  display: none;
}
.sidebar .menu-bar .mode {
  border-radius: 6px;
  background-color: #f6f5ff;
  position: relative;
  transition: all 0.3s ease;
}

.menu-bar .mode .sun-moon {
  height: 50px;
  width: 60px;
}

.mode .sun-moon i {
  position: absolute;
}
.mode .sun-moon i.sun {
  opacity: 0;
}
body.dark .mode .sun-moon i.sun {
  opacity: 1;
}
body.dark .mode .sun-moon i.moon {
  opacity: 0;
}

.menu-bar .bottom-content .toggle-switch {
  position: absolute;
  right: 0;
  height: 100%;
  min-width: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  cursor: pointer;
}
.toggle-switch .switch {
  position: relative;
  height: 22px;
  width: 40px;
  border-radius: 25px;
  background-color: #ddd;
  transition: all 0.3s ease;
}

.switch::before {
  content: "";
  position: absolute;
  height: 15px;
  width: 15px;
  border-radius: 50%;
  top: 50%;
  left: 5px;
  transform: translateY(-50%);
  background-color: #fff;
  transition: all 0.3s ease;
}

body.dark .switch::before {
  left: 20px;
}

.home {
  position: absolute;
  top: 0;
  top: 0;
  left: 250px;
  height: 100vh;
  width: calc(100% - 250px);
  background-color: #f3f1f5;
  transition: all 0.3s ease;
}
.home .text {
  font-size: 30px;
  font-weight: 500;
  color: #000000;
  padding: 12px 60px;
}

.sidebar.close ~ .home {
  left: 78px;
  height: 100vh;
  width: calc(100% - 78px);
}
body.dark .home .text {
  color: #000000;
}
</style>