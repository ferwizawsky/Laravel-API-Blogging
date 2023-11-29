<script setup>
import { useRouter } from "vue-router";
import { useAuth } from "@/stores/auth.js";
import { useNotif } from "@/stores/notif.js";
import { useMyFetch, jsonFormData } from "@/composables/fetch.js";
import { onMounted, ref } from "vue";

const isShow = ref(false);
const first = ref(false);
const auth = useAuth();
const router = useRouter();
const notif = useNotif();
const formPost = ref({
    username: "udeen_winter",
    password: "udeenwinter_48",
});
onMounted(() => {
    if (auth.token) {
        router.push("/");
    }
});
async function login() {
    if (notif.loading) return;
    notif.loading = true;
    try {
        const { data } = await useMyFetch(
            "POST",
            "/auth/login",
            jsonFormData(formPost.value)
        );

        localStorage.setItem("token", data.token);
        auth.token = data.token;
        router.push("/");
        // location.reload();
    } catch (error) {
        console.log(error);
        notif.make("Failed to Login Check your Username or Password", {
            type: "danger",
            delay: 4000,
        });
    } finally {
        notif.loading = false;
    }
}
</script>
<template>
    <section class="bg-gray-50">
        <div
            class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
        >
            <a
                href="#"
                class="flex items-center mb-6 text-2xl font-semibold text-gray-900"
            >
                <!-- <img
                    class="w-8 h-8 mr-2"
                    src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
                    alt="logo"
                />
                Flowbite -->
            </a>
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0"
            >
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl"
                    >
                        Sign in to your account
                    </h1>
                    <form
                        class="space-y-4 md:space-y-6"
                        @submit.prevent="login()"
                    >
                        <div>
                            <label
                                for="email"
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >Username</label
                            >
                            <input
                                autocomplete=""
                                type="username"
                                class="bg-gray-50 border focus:outline-none border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                                placeholder="username"
                                required=""
                                v-model="formPost.username"
                            />
                        </div>
                        <div class="relative">
                            <label
                                for="password"
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >Password</label
                            >
                            <input
                                v-model="formPost.password"
                                autocomplete=""
                                :type="!isShow ? 'password' : ''"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:outline-none focus:ring-primary focus:border-primary block w-full p-2.5"
                                placeholder="password"
                                required=""
                            />

                            <svg
                                @click="isShow = !isShow"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 absolute right-2 top-9 cursor-pointer"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                        </div>

                        <button
                            type="submit"
                            class="w-full text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        >
                            Sign in
                        </button>
                        <!-- <p
                            class="text-sm font-light text-gray-500 dark:text-gray-400"
                        >
                            Don’t have an account yet?
                            <a
                                href="#"
                                class="font-medium text-primary hover:underline dark:text-primary"
                                >Sign up</a
                            >
                        </p> -->
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
