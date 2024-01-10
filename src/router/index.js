import { createRouter, createWebHashHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import { useAuthStore } from "@/store/auth";
import Cookies from "js-cookie";

const routes = [
  {
    path: "/",
    name: "/",
    redirect: "/dashboard-default",
  },
  {
    path: "/pasien",
    name: "Pasien",
    component: () => import("@/views/pasien/Index.vue"),
    meta: {
      auth: false,
    },
    children: [
      {
        path: "",
        name: "ListPasien",
        component: () => import("@/views/pasien/List.vue"),
        meta: {
          auth: true,
        },
      },
      {
        path: "detail",
        name: "DetailPasien",
        component: () => import("@/views/pasien/Edit.vue"),
        meta: {
          auth: false,
        },
        children: [
          {
            path: "by-mr/:mr",
            name: "ViewByMr",
            component: () => import("@/views/pasien/ViewByMr.vue"),
            meta: {
              auth: false,
            },
          },
          {
            path: "by-reg/:register",
            name: "ViewByReg",
            component: () => import("@/views/pasien/ViewByReg.vue"),
            meta: {
              reload: false,
              auth: false,
            },
          },
        ]
      },
      {
        path: "/pasien/dokumen/:id_file",
        component: () => import("@/views/pasien/ViewPdf.vue"),
        name: "PasienDokumen",
        meta: {
          auth: false,
        },
      },
    ],
  },
  {
    path: "/history",
    name: "History",
    component: () => import("@/views/history/Index.vue"),
    meta: {
      auth: true,
    },
    children: [
      {
        path: "",
        name: "ListHistory",
        component: () => import("@/views/history/List.vue"),
        meta: {
          auth: true,
        },
      },
      {
        path: "by-mr/:mr?/view",
        name: "HistoryViewByMr",
        component: () => import("@/views/history/ViewByMr.vue"),
        meta: {
          auth: true,
        },
      },
      {
        path: "by-reg/:reg?/view",
        name: "HistoryViewByReg",
        component: () => import("@/views/history/ViewByReg.vue"),
        meta: {
          auth: true,
        },
      },
    ],
  },
  {
    path: "/dashboard-default",
    name: "Dashboard",
    component: Dashboard,
    meta: {
      auth: true,
    },
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("@/views/auth/Login"),
    beforeEnter: (to, from) => {
      // reject the navigation

      Cookies.set('redirectPath', from.fullPath);

      const auth = useAuthStore();

      if (auth.getLoggedIn()) {
        return Cookies.get('redirectPath') ?? "/";
      }
    },
  },
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
  linkActiveClass: "active",
});

router.beforeEach(async (to, from) => {
  // redirect to login page if not logged in and trying to access a restricted page
  // const publicPages = ['/login']
  const authRequired = to.meta.auth;
  // const authRequired = !publicPages.includes(to.path)
  const auth = useAuthStore();
  if (from.name === 'ViewByReg') {
    Cookies.set("from", from.name);
  } else {
    if (Cookies.get('from')) {
      Cookies.remove('from')
    }
  }

  if (authRequired && !auth.getLoggedIn()) {
    auth.returnUrl = to.fullPath;

    return "/login";
  }
});

export default router;
