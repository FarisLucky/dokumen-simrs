<template>
  <div class="fixed-plugin">
    <a
      class="px-3 py-2 fixed-plugin-button text-dark position-fixed"
      @click="toggle"
    >
      <i class="py-2 fa fa-cog"></i>
    </a>
    <div class="shadow-lg card">
      <div class="pt-3 pb-0 bg-transparent card-header">
        <div
          class=""
          :class="setting.isRTL ? 'float-end' : 'float-start'"
        >
          <h5 class="mt-3 mb-0">Argon Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div
          class="mt-4"
          @click="toggle"
          :class="setting.isRTL ? 'float-start' : 'float-end'"
        >
          <button class="p-0 btn btn-link text-dark fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="my-1 horizontal dark" />
      <div class="pt-0 card-body pt-sm-3">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a
          href="#"
          class="switch-trigger background-color"
        >
          <div
            class="my-2 badge-colors"
            :class="setting.isRTL ? 'text-end' : ' text-start'"
          >
            <span
              class="badge filter bg-gradient-primary active"
              data-color="primary"
              @click="sidebarColor('primary')"
            ></span>
            <span
              class="badge filter bg-gradient-dark"
              data-color="dark"
              @click="sidebarColor('dark')"
            ></span>
            <span
              class="badge filter bg-gradient-info"
              data-color="info"
              @click="sidebarColor('info')"
            ></span>
            <span
              class="badge filter bg-gradient-success"
              data-color="success"
              @click="sidebarColor('success')"
            ></span>
            <span
              class="badge filter bg-gradient-warning"
              data-color="warning"
              @click="sidebarColor('warning')"
            ></span>
            <span
              class="badge filter bg-gradient-danger"
              data-color="danger"
              @click="sidebarColor('danger')"
            ></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex gap-2">
          <button
            id="btn-white"
            class="btn w-100 px-3 mb-2"
            :class="
              setting.sidebarType === 'bg-white'
                ? 'bg-gradient-success'
                : 'btn-outline-success'
            "
            @click="sidebarType('bg-white')"
          >
            White
          </button>
          <button
            id="btn-dark"
            class="btn w-100 px-3 mb-2"
            :class="
              setting.sidebarType === 'bg-default'
                ? 'bg-gradient-success'
                : 'btn-outline-success'
            "
            @click="sidebarType('bg-default')"
          >
            Dark
          </button>
        </div>
        <p class="mt-2 text-sm d-xl-none d-block">
          You can change the sidenav type just on desktop view.
        </p>
        <!-- Navbar Fixed -->
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input
              class="mt-1 form-check-input"
              :class="
                setting.isRTL ? 'float-end  me-auto' : ' ms-auto'
              "
              type="checkbox"
              id="navbarFixed"
              :checked="setting.isNavFixed"
              @click="setNavbarFixed"
            />
          </div>
        </div>

        <hr class="horizontal dark my-4" />
        <div class="mt-2 mb-5 d-flex">
          <h6
            class="mb-0"
            :class="setting.isRTL ? 'ms-2' : ''"
          >
            Light / Dark
          </h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input
              class="form-check-input mt-1 ms-auto"
              type="checkbox"
              :checked="setting.darkMode"
              @click="setDarkMode"
            />
          </div>
        </div>
        <div class="text-center w-100">
          <h6 class="mt-3">RS Graha Sehat Kraksaan</h6>
          <a
            href="https://grahasehat.com"
            class="mb-0 btn btn-dark me-2"
            target="_blank"
          >
            <i
              class="fab fa-internet-explorer me-1"
              aria-hidden="true"
            ></i> Website
          </a>
          <a
            href="#"
            class="mb-0 btn btn-dark me-2"
            target="_blank"
          >
            <i
              class="fab fa-facebook-square me-1"
              aria-hidden="true"
            ></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { activateDarkMode, deactivateDarkMode } from "@/assets/js/dark-mode";
import { mapActions, mapState } from "pinia";
import { useAppStore } from "../store/index_pinia";
export default {
  name: "configurator",
  props: ["toggle"],
  methods: {
    ...mapActions(useAppStore, [
      "navbarMinimize",
      "sidebarType",
      "navbarFixed",
    ]),
    sidebarColor(color = "success") {
      document.querySelector("#sidenav-main").setAttribute("data-color", color);
      this.setting.mcolor = `card-background-mask-${color}`;
    },
    sidebarType(type) {
      this.setting.sidebarType = type;
    },
    setNavbarFixed() {
      if (
        this.$route.name !== "Profile" ||
        this.$route.name !== "All Projects"
      ) {
        this.setting.isNavFixed = !this.setting.isNavFixed;
      }
    },
    setDarkMode() {
      if (this.setting.darkMode) {
        this.setting.darkMode = false;
        this.setting.sidebarType = "bg-white";
        deactivateDarkMode();
        return;
      } else {
        this.setting.darkMode = true;
        this.setting.sidebarType = "bg-default";
        activateDarkMode();
      }
    },
    sidenavTypeOnResize() {
      let white = document.querySelector("#btn-white");
      if (window.innerWidth < 1200) {
        white.classList.add("disabled");
      } else {
        white.classList.remove("disabled");
      }
    },
  },
  computed: {
    ...mapState(useAppStore, ["setting"]),

    sidenavResponsive() {
      return this.sidenavTypeOnResize;
    },
  },
  beforeMount() {
    this.setting.isTransparent = "bg-transparent";
    window.addEventListener("resize", this.sidenavTypeOnResize);
    window.addEventListener("load", this.sidenavTypeOnResize);
  },
};
</script>
