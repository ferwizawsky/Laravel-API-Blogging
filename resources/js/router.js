import { createWebHistory, createRouter } from "vue-router";
// const Home = () => import("@/pages/Home.vue");

const routes = [
    {
        path: "/login",
        name: "Login",
        component: () => import("@/pages/login.vue"),
        meta: {
            public: true,
        },
    },
    {
        path: "/",
        name: "Point User",
        component: () => import("@/pages/home.vue"),
        meta: {
            public: false,
        },
    },
    {
        path: "/admin",
        // name: "Home",
        component: () => import("@/pages/admin/layout.vue"),
        redirect: "/admin/home",
        meta: {
            public: false,
        },
        children: [
            {
                path: "home",
                name: "Home",
                component: () => import("@/pages/admin/home.vue"),
            },
            {
                path: "post",
                name: "Post",
                component: () => import("@/pages/admin/post/index.vue"),
            },
            {
                path: "post-make",
                name: "Post Make",
                component: () => import("@/pages/admin/post/id.vue"),
            },
            {
                path: "post/:id",
                name: "Post Detail",
                component: () => import("@/pages/admin/post/id.vue"),
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = to.name + " | " + appName;
    const token = localStorage.getItem("token");
    //   const role = localStorage.getItem("role_id");

    //   if (to.name == "Login" && token) {
    //     if (role == "2") next("/dash");
    //     else next("/");
    //   }
    // if (to.name != "Login" && !token) next("/login");
    // const privateRoute = to.matched.some((record) => !record.meta.public);
    // const publicRoute = to.matched.some((record) => record.meta.public);
    // if (privateRoute && !token) next("/login");
    // if (to.name == "Login" && token) next("/");
    // else next();
    if (!to.meta?.public && !token) {
        next("/login");
    }

    next();
});

export default router;
