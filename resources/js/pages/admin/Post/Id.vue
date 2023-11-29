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
        const { data } = await useMyFetch("GET", `/post/${route.params.id}`);
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
    let url = "/post";
    if (route.params.id) {
        url = `/post/${route.params.id}`;
    }
    try {
        const { data } = await useMyFetch("POST", url, {
            title: item.value.title,
            content: item.value.content,
        });
        notif.make("Succed Update Post!");
        if (route.params.id) {
            router.go(-1);
        } else {
            router.push(`/admin/post`);
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
                <div class="text-center">Post</div>
                <div class="flex justify-between text-xs items-center">
                    <div v-if="item?.created_at" class="text-gray-400">
                        {{ dateFormatter(item?.created_at) }}
                    </div>
                    <div>
                        <span
                            v-if="item.tag"
                            class="bg-primary px-4 py-1 rounded-lg text-white capitalize"
                            >{{ item.tag }}</span
                        >
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
                <div class="text-sm text-gray-400 font-thin pt-4">
                    <textarea
                        required
                        placeholder="Content..."
                        v-model="item.content"
                        class="inputan min-h-[250px]"
                        :disabled="!$route.query.type ? true : false"
                    ></textarea>
                </div>
                <div
                    v-if="item?.publisher"
                    class="flex justify-between text-xs items-center mt-5"
                >
                    <div class="text-gray-400"></div>
                    <div class="flex items-center">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4 text-gray-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z"
                            />
                        </svg>
                        <div class="text-xs ml-1 mr-4">
                            {{ item?.reactions?.length }}
                        </div>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4 text-gray-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"
                            />
                        </svg>
                        <div class="text-xs ml-1 mr-4">
                            {{ item?.comments?.length }}
                        </div>

                        <div
                            class="w-6 h-6 bg-amber-400 flex items-center justify-center rounded-full text-white"
                        >
                            {{ item.publisher?.name.substring(0, 1) }}
                        </div>
                        <span class="ml-2 font-semibold">{{
                            item?.publisher?.name
                        }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 text-center">
                <button @click="store()" class="btn">Simpan</button>
            </div>
        </div>
    </div>
</template>
