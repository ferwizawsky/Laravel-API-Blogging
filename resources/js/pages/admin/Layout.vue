<script setup>
import { useMyFetch, jsonFormData } from "@/composables/fetch.js";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { ref, onMounted } from "vue";
import { useAuth } from "@/stores/auth.js";
import { useRouter } from "vue-router";

const item = ref();
const auth = useAuth();
const router = useRouter();
async function getUser() {
    item.value = {};
    try {
        const { data } = await useMyFetch("GET", `/auth/profile`);
        item.value = { ...data.data };
        auth.user = item.value;
        // location.reload();
    } catch (error) {
        logout();
    } finally {
    }
}

function logout() {
    auth.token = "";
    localStorage.removeItem("token");
    router.push("/login");
}
onMounted(() => {
    if (!item.value) {
        getUser();
    }
});
</script>
<template>
    <div>
        <div
            class="w-full bg-white/20 backdrop-blur-sm fixed top-0 z-10 left-0 min-h-[65px] flex items-center justify-between px-4 lg:px-8"
        >
            <div></div>
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="w-8 h-8 cursor-pointer bg-secondary flex items-center justify-center rounded-full text-white"
                    >
                        {{ item?.name?.substring(0, 1) }}
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute border border-gray-200 right-0 mt-2 w-32 origin-top-right rounded-md bg-white shadow-lg focus:outline-none"
                    >
                        <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                                <button
                                    @click="logout()"
                                    :class="[
                                        active
                                            ? 'bg-primary text-white'
                                            : 'text-gray-900',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                    ]"
                                >
                                    Logout
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
        <div class="pt-[85px] px-4 lg:px-8 pb-20">
            <RouterView />
        </div>
    </div>
</template>
