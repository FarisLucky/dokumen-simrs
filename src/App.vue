<template>
  <vue-progress-bar></vue-progress-bar>
  <div>
    <div
      v-show="setting.layout === 'landing'"
      class="landing-bg h-100 bg-gradient-primary position-fixed w-100"
    ></div>
    <sidenav
      :custom_class="setting.mcolor"
      :class="[
        setting.isTransparent,
        setting.isRTL ? 'fixed-end' : 'fixed-start',
      ]"
      v-if="setting.showSidenav"
    />
    <main class="main-content position-relative h-100 border-radius-lg">
      <!-- nav -->
      <navbar
        :class="[navClasses]"
        :textWhite="
          setting.isAbsolute ? 'text-white opacity-8' : 'text-white'
        "
        :minNav="navbarMinimize"
        v-if="setting.showNavbar"
      />
      <div class="cover-container">
        <router-view v-slot="{ Component, route }">
          <transition
            name="fade-x"
            mode="out-in"
          >
            <component
              :is="Component"
              :key="route.fullPath"
            />
          </transition>
        </router-view>
      </div>
      <configurator
        :toggle="toggleConfigurator"
        :class="[
          setting.showConfig ? 'show' : '',
          setting.hideConfigButton ? 'd-none' : '',
        ]"
      />
    </main>
  </div>
</template>
<script>
import Sidenav from "./examples/Sidenav";
import Configurator from "@/examples/Configurator.vue";
import Navbar from "@/examples/Navbars/Navbar.vue";
import { mapActions, mapState } from "pinia";
import { useAppStore } from "./store/index_pinia";

export default {
  name: "App",
  components: {
    Sidenav,
    Configurator,
    Navbar,
  },
  data() {
    return {
      showModal: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, [
      "toggleSidebarColor",
      "toggleConfigurator",
      "navbarMinimize",
    ]),
    toggleSidebar() {
      this.toggleSidebarColor("bg-white");
      this.navbarMinimize();
    },
  },
  computed: {
    navClasses() {
      return {
        "position-sticky bg-white left-auto top-2 z-index-sticky":
          this.setting.isNavFixed && !this.setting.darkMode,
        "position-sticky bg-default left-auto top-2 z-index-sticky":
          this.setting.isNavFixed && this.setting.darkMode,
        "position-absolute px-4 mx-0 w-100 z-index-2": this.setting.isAbsolute,
        "px-0 mx-4": !this.setting.isAbsolute,
      };
    },
    ...mapState(useAppStore, ["setting"]),
  },
  // beforeMount() {
  //   this.setting.isTransparent = "bg-transparent";
  // },
  mounted() {
    //  [App.vue specific] When App.vue is finish loading finish the progress bar
    this.$Progress.finish();
    this.toggleSidebar();
    this.setting.isPinned = true;
  },
  created() {
    //  [App.vue specific] When App.vue is first loaded start the progress bar
    this.$Progress.start();
    //  hook the progress bar to start before we move router-view
    this.$router.beforeEach((to, from, next) => {
      //  does the page we want to go to have a meta.progress object
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress;
        // parse meta tags
        this.$Progress.parseMeta(meta);
      }
      //  start the progress bar
      this.$Progress.start();
      //  continue to next page
      next();
    });
    //  hook the progress bar to finish after we've finished moving router-view
    this.$router.afterEach(() => {
      //  finish the progress bar
      this.$Progress.finish();
    });
  },
};
</script>
<style>
.cover-container {
  height: 100%;
}
</style>
