<script setup>
import { ref, onMounted } from "vue";
import { useNotif } from "@/stores/notif.js";
import { useMyFetch, jsonFormData } from "@/composables/fetch.js";
import { dateFormatter } from "@/composables/timeFormatter.js";
import { useRoute, useRouter } from "vue-router";

const item = ref({
    title: "",
    content: "",
});
const notif = useNotif();
const route = useRoute();
const router = useRouter();
async function getPost() {
    if (notif.loading) return;
    notif.loading = true;
    item.value = {};
    try {
        const { data } = await useMyFetch("GET", `/event/${route.params.id}`);
        item.value = { ...data.data };
        // location.reload();
    } catch (error) {
    } finally {
        notif.loading = false;
    }
}

async function store() {
    if (notif.loading) return;
    notif.loading = true;
    let url = "/event";
    let fm = jsonFormData(item.value);
    if (route.params.id) {
        url = `/event/${route.params.id}`;
    }
    try {
        const { data } = await useMyFetch("POST", url, fm);
        notif.make("Succed Update Post!");
        if (route.params.id) {
            router.go(-1);
        } else {
            router.push(`/admin/event`);
        }
        // console.log(data);
        // location.reload();
    } catch (error) {
    } finally {
        notif.loading = false;
    }
}

onMounted(() => {
    if (route.params.id) {
        getPost();
    }
});
</script>
<template>
    <div class="">
        <div class="max-w-xl mx-auto pt-10">
            <div>
                <button
                    @click="$router.go(-1)"
                    class="flex items-center text-gray-400"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 mr-2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 19.5L8.25 12l7.5-7.5"
                        />
                    </svg>
                    Back
                </button>
            </div>
            <div
                class="bg-white rounded-xl shadow-xl border border-gray-100 p-4 mt-6"
            >
                <div class="text-center">Event</div>
                <div
                    v-if="item?.created_at"
                    class="flex justify-between text-xs items-end"
                >
                    <div class="text-gray-400">
                        Created At {{ dateFormatter(item?.created_at) }}
                    </div>
                    <div>
                        <div class="text-gray-400">Creator</div>
                        <div class="flex items-center">
                            <span class="mr-2 font-semibold">{{
                                item?.user?.name
                            }}</span>
                            <div
                                class="w-6 h-6 bg-amber-400 flex items-center justify-center rounded-full text-white"
                            >
                                {{ item.user?.name.substring(0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="font-semibold mt-6 text-lg">
                    <input
                        placeholder="Title..."
                        type="text"
                        v-model="item.title"
                        class="inputan"
                        required
                        :disabled="!$route.query.type ? true : false"
                    />
                </div>
                <div class="text-sm text-gray-400">
                    <div class="pt-4">
                        <span>Description</span>
                        <textarea
                            required
                            placeholder="Content..."
                            v-model="item.description"
                            class="inputan min-h-[250px]"
                            :disabled="!$route.query.type ? true : false"
                        ></textarea>
                    </div>
                    <div class="pt-5">
                        <span>Slot</span>
                        <input
                            type="number"
                            v-model="item.slot"
                            class="inputan"
                            required
                            :disabled="!$route.query.type ? true : false"
                        />
                    </div>
                    <div class="pt-5">
                        <span>Location</span>
                        <input
                            type="text"
                            v-model="item.location"
                            class="inputan"
                            required
                            :disabled="!$route.query.type ? true : false"
                        />
                    </div>
                    <div class="pt-5">
                        <span>Time</span>
                        <input
                            type="datetime-local"
                            v-model="item.time"
                            class="inputan"
                            required
                            :disabled="!$route.query.type ? true : false"
                        />
                    </div>
                </div>
            </div>

            <div class="pt-4 text-center" v-if="$route.query.type">
                <button @click="store()" class="btn">Save</button>
            </div>
        </div>
    </div>
</template>
