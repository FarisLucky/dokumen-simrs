import { defineStore } from "pinia";

export const useAppStore = defineStore('app', {
    state: () => ({
        setting: {
            hideConfigButton: false,
            isPinned: true,
            showConfig: false,
            sidebarType: "bg-white",
            isRTL: false,
            mcolor: "",
            darkMode: false,
            isNavFixed: false,
            isAbsolute: false,
            showNavs: true,
            showSidenav: true,
            showNavbar: true,
            showFooter: true,
            showMain: true,
            layout: "default",
            isTransparent: "bg-transparent"
        }
    }),
    actions: {

        toggleSidebarColor(payload) {
            this.setting.sidebarType = payload;
        },
        toggleConfigurator(state) {
            state.showConfig = !state.showConfig;
        },
        navbarMinimize() {
            const sidenav_show = document.querySelector(".g-sidenav-show");

            if (sidenav_show.classList.contains("g-sidenav-hidden")) {
                sidenav_show.classList.remove("g-sidenav-hidden");
                sidenav_show.classList.add("g-sidenav-pinned");
                this.setting.isPinned = true;
            } else {
                sidenav_show.classList.add("g-sidenav-hidden");
                sidenav_show.classList.remove("g-sidenav-pinned");
                this.setting.isPinned = false;
            }
        },
        sidebarType(state, payload) {
            state.sidebarType = payload;
        },
        navbarFixed(state) {
            if (state.isNavFixed === false) {
                state.isNavFixed = true;
            } else {
                state.isNavFixed = false;
            }
        }
    },
})