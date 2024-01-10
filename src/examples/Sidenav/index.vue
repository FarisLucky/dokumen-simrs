<style scoped>
.name-login {
  padding: 0.5rem 1rem;
  position: relative;
  font-size: 13px;
  font-weight: 700;
  border-radius: 4px;
  background: rgb(85, 213, 193);
  color: rgb(214, 107, 107);
}
</style>
<template>
  <div
    v-show="setting.layout === 'default'"
    class="min-height-300 position-absolute w-100"
    :class="`${setting.darkMode ? 'bg-transparent' : 'bg-success-cs'}`"
  />
  <aside
    v-if="loggedIn"
    class="my-3 overflow-auto border-0 sidenav navbar navbar-vertical navbar-expand-xs border-radius-xl"
    :class="`${
      setting.isRTL
        ? 'me-3 rotate-caret fixed-end'
        : 'fixed-start ms-3'
    } 
    ${
      setting.layout === 'landing'
        ? 'bg-transparent shadow-none'
        : ' '
    } ${setting.sidebarType}`"
    id="sidenav-main"
    data-color="danger"
  >
    <div class="sidenav-header">
      <i
        class="top-0 p-3 cursor-pointer fas fa-times text-secondary opacity-5 position-absolute end-0 d-none d-xl-none"
        aria-hidden="true"
        id="iconSidenav"
      ></i>
      <router-link
        class="m-0 navbar-brand"
        to=""
      >
        <img
          :src="
            setting.darkMode ||
            setting.sidebarType === 'bg-default'
              ? logoWhite
              : logo
          "
          class="navbar-brand-img h-100"
          alt="main_logo"
        />
        <span class="ms-2 font-weight-bold me-2">Dokumen SIMRS</span>
      </router-link>
    </div>
    <hr class="mt-0 horizontal dark" />

    <transition
      name="fade-x"
      mode="out-in"
    >
      <div
        class="my-3 text-center"
        v-if="setting.isPinned"
      >
        <span class="name-login">User: {{ user.name }}</span>
      </div>
    </transition>
    <sidenav-list :cardBg="custom_class" />
  </aside>
</template>
<script>
import SidenavList from "./SidenavList.vue";
import logo from "@/assets/img/logo.jpg";
import logoWhite from "@/assets/img/logo.jpg";
import { useAuthStore } from "../../store/auth";
import { mapState } from "pinia";
import { useAppStore } from "../../store/index_pinia";

export default {
  name: "index",
  components: {
    SidenavList,
  },
  data() {
    return {
      loggedIn: false,
      logo,
      logoWhite,
      name: null,
    };
  },
  computed: {
    ...mapState(useAppStore, ["setting"]),
    ...mapState(useAuthStore, ["user"]),
  },
  created() {
    this.loggedIn = useAuthStore().getLoggedIn();
    this.name = useAuthStore().getUser().name;
  },
  props: ["custom_class", "layout"],
};
</script>
