<style>
@import url("https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600&family=Inter:wght@100;200;300;400;500;600;700;800&display=swap");

.title {
    font-family: "Bricolage Grotesque", sans-serif !important;
}
</style>
<template>
    <div>
        <vue-progress-bar></vue-progress-bar>
        <div class="container top-0 position-sticky z-index-sticky">
            <div class="row">
                <div class="col-12">
                    <navbar
                        isBlur="blur  border-radius-lg my-3 py-2 start-0 end-0 mx-4 shadow"
                        v-bind:darkMode="true"
                        isBtn="bg-gradient-success"
                    />
                </div>
            </div>
        </div>
        <main class="mt-0 main-content">
            <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                        <div class="row">
                            <div
                                class="mx-auto col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0"
                            >
                                <div class="card card-plain">
                                    <div class="pb-0 card-header text-start">
                                        <h4 class="font-weight-bolder">
                                            Masuk
                                        </h4>
                                        <p class="mb-0">
                                            Lengkapi Form dibawah
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <form
                                            role="form"
                                            @submit.prevent="onSubmit"
                                            autocomplete="false"
                                        >
                                            <div class="mb-3">
                                                <input
                                                    type="text"
                                                    placeholder="Username"
                                                    name="username"
                                                    class="form-control form-control-lg"
                                                    v-model="form.username"
                                                    autofocus
                                                />
                                            </div>
                                            <div class="mb-3">
                                                <input
                                                    type="password"
                                                    placeholder="Password"
                                                    name="password"
                                                    class="form-control form-control-lg"
                                                    v-model="form.password"
                                                />
                                            </div>

                                            <div class="text-center">
                                                <argon-button
                                                    type="submit"
                                                    class="mt-4"
                                                    variant="gradient"
                                                    color="success"
                                                    fullWidth
                                                    size="lg"
                                                    >Masuk</argon-button
                                                >
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="top-0 my-auto text-center col-6 d-lg-flex d-none h-100 pe-0 position-absolute end-0 justify-content-center flex-column"
                            >
                                <div
                                    class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                    style="
                                        background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg');
                                        background-size: cover;
                                    "
                                >
                                    <span
                                        class="mask bg-gradient-success opacity-6"
                                    ></span>
                                    <h4
                                        class="mt-5 text-white font-weight-bolder position-relative"
                                    >
                                        "Attention is the new currency"
                                    </h4>
                                    <p class="text-white position-relative">
                                        Silahkan upload file penunjang SIMRS.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import { useAuthStore } from "@/store/auth";
import { mapActions, mapState } from "pinia";
import Cookies from "js-cookie";
import Navbar from "@/examples/PageLayout/Navbar.vue";
import ArgonButton from "@/components/ArgonButton.vue";
import { useAppStore } from "@/store/index_pinia";
const body = document.getElementsByTagName("body")[0];

export default {
    components: { Navbar, ArgonButton },
    name: "Login",
    computed: {
        ...mapState(useAuthStore, ["user", "form", "token", "validate"]),
        ...mapState(useAppStore, ["setting"]),
    },
    created() {
        this.$Progress.start();
        //  hook the progress bar to start before we move router-view
        this.$router.beforeEach((to, from, next) => {
            //  does the page we want to go to have a meta.progress object
            console.log(from);
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

        // Menu
        this.setting.hideConfigButton = true;
        this.setting.showNavbar = false;
        this.setting.showSidenav = false;
        this.setting.showFooter = false;
        body.classList.remove("bg-gray-100");
    },
    methods: {
        ...mapActions(useAuthStore, [
            "login",
            "logout",
            "getLoggedIn",
            "getToken",
            "getUser",
            "setValidation",
            "resetValidation",
        ]),
        showNavbar() {
            this.setting.hideConfigButton = true;
            this.setting.showNavbar = true;
            this.setting.showSidenav = true;
            this.setting.showFooter = true;
            body.classList.add("bg-gray-100");
        },
        onSubmit() {
            this.resetValidation();
            console.log(this.form);
            this.login(this.form)
                .then((response) => {
                    // update pinia state
                    Cookies.set(
                        "user",
                        JSON.stringify({
                            loggedIn: true,
                            data: response.data.user,
                            token: response.data.token,
                        }),
                        {
                            expires: 30,
                        }
                    );

                    this.getLoggedIn();
                    this.getToken();
                    this.getUser();
                    // this.showNavbar();
                    // redirect to previous url or default to home page
                    // window.location.href = Cookies.get("redirectPath") ?? "/";
                    if (Cookies.get("from") === "ViewByReg") {
                        Cookies.set("fromLogin", true);
                        this.$router.push({
                            path: Cookies.get("redirectPath") ?? "/",
                        });
                        Cookies.remove("redirectPath");
                        Cookies.remove("from");
                    } else {
                        this.$router.go("/");
                    }
                })
                .catch((errors) => {
                    if (errors?.response?.status == 422) {
                        this.setValidation(errors.response.data.errors);
                    } else {
                        alert(errors?.response?.data);
                    }
                });
        },
        beforeUnmount() {
            this.setting.hideConfigButton = false;
            this.setting.showNavbar = true;
            this.setting.showSidenav = true;
            this.setting.showFooter = true;
            body.classList.add("bg-gray-100");
        },
    },
};
</script>
