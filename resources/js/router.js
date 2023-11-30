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
        name: "Landing",
        component: () => import("@/pages/Home.vue"),
        meta: {
            public: false,
        },
    },
    {
        path: "/admin",
        // name: "Home",
        component: () => import("@/pages/admin/Layout.vue"),
        redirect: "/admin/event",
        meta: {
            public: false,
        },
        children: [
            {
                path: "home",
                name: "Home",
                component: () => import("@/pages/admin/Home.vue"),
            },
            {
                path: "event",
                name: "Event",
                component: () => import("@/pages/admin/event/Index.vue"),
            },
            {
                path: "event-make",
                name: "Event Make",
                component: () => import("@/pages/admin/event/Id.vue"),
            },
            {
                path: "event/:id",
                name: "Event Detail",
                component: () => import("@/pages/admin/event/Id.vue"),
            },
            {
                path: "post",
                name: "Post",
                component: () => import("@/pages/admin/post/Index.vue"),
            },
            {
                path: "post-make",
                name: "Post Make",
                component: () => import("@/pages/admin/post/Id.vue"),
            },
            {
                path: "post/:id",
                name: "Post Detail",
                component: () => import("@/pages/admin/post/Id.vue"),
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
