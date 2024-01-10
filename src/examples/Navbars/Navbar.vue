<template>
  <div>

    <nav
      class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
      :class="
      setting.isRTL ? 'top-0 position-sticky z-index-sticky' : ''
    "
      v-bind="$attrs"
      id="navbarBlur"
      data-scroll="true"
    >
      <div class="px-3 py-1 container-fluid">
        <breadcrumbs
          v-if="loggedIn"
          :currentPage="currentRouteName"
          textWhite="text-white"
        />

        <div
          class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4"
          :class="setting.isRTL ? 'px-0' : 'me-sm-4'"
          id="navbar"
        >
          <div
            class="pe-md-3 d-flex align-items-center"
            :class="setting.isRTL ? 'me-md-auto' : 'ms-md-auto'"
          >
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item ps-3 d-flex align-items-center pe-3">
              <a
                v-if="loggedIn"
                href="#"
                data-bs-toggle="modal"
                data-bs-target="#passwordModal"
                class="py-2 px-3 nav-link text-white bg-warning rounded"
                id="iconNavbarSidenav"
              >
                <i
                  class="fa fa-key me-1"
                  aria-hidden="true"
                ></i>
              </a>
            </li>
            <li class="nav-item ps-3 d-flex align-items-center pe-3">
              <a
                href="#"
                @click.prevent="toggleSidebar"
                class="p-0 nav-link text-white"
                id="iconNavbarSidenav"
              >
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li
              v-if="loggedIn"
              class="nav-item d-flex align-items-center"
            >
              <a
                href="javascript(0)"
                @click.prevent="onLogout"
                class="nav-link font-weight-bold logout"
                target="_blank"
              >
                <i
                  class="fa fa-sign-out me-2"
                  aria-hidden="true"
                ></i>
                <span class="d-sm-inline d-none">Logout</span>
              </a>
            </li>
            <li
              v-else
              class="nav-item d-flex align-items-center"
            >
              <router-link
                class="nav-link font-weight-bold logout"
                :to="{name: 'Login'}"
              >
                <i
                  class="fa fa-sign-in me-2"
                  aria-hidden="true"
                ></i>
                <span class="d-sm-inline d-none">Login</span>
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <GantiPassword
      v-if="loggedIn"
      ref="passwordComponent"
    />
  </div>
</template>
<script>
import { useAuthStore } from "@/store/auth";
import Breadcrumbs from "../Breadcrumbs.vue";
import { mapState, mapActions } from "pinia";
import { useAppStore } from "@/store/index_pinia";
import GantiPassword from "@/views/auth/GantiPassword.vue";

export default {
  name: "navbar",
  components: {
    GantiPassword,
    Breadcrumbs,
  },
  data() {
    return {
      showMenu: false,
      loggedIn: false,
    };
  },
  props: ["minNav", "textWhite"],
  created() {
    this.minNav;
    this.loggedIn = useAuthStore().getLoggedIn();
  },
  computed: {
    ...mapState(useAppStore, ["setting"]),
    currentRouteName() {
      return this.$route.name;
    },
  },
  methods: {
    ...mapActions(useAppStore, [
      "toggleSidebarColor",
      "navbarMinimize",
      "toggleConfigurator",
    ]),

    toggleSidebar() {
      this.toggleSidebarColor("bg-white");
      this.navbarMinimize();
    },
    onLogout() {
      useAuthStore()
        .logout()
        .then(() => {
          useAuthStore().removeCookies();
          this.$router.go("/");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    showModalPassword() {
      this.$refs.passwordComponent.showModal();
    },
  },
};
</script>
<style>
.logout {
  background: rgba(255, 255, 255, 0.82);
  border-radius: 0.2rem;
}
</style>