<script setup>
import { useMyFetch, jsonFormData } from "@/composables/fetch.js";
import { dateFormatter } from "@/composables/timeFormatter.js";
import { ref, onMounted } from "vue";
import { useNotif } from "@/stores/notif.js";
import Paginate from "@/components/Paginate.vue";
import { useRouter, useRoute } from "vue-router";

const notif = useNotif();
const search = ref("");
const route = useRoute();
const router = useRouter();
const input_search = ref();
const limit = 10;
const page = ref(1);
const list = ref([]);
const meta = ref({
    links: [],
});

onMounted(() => {
    if (route.query.page) page.value = route.query.page;
    getData();
});

async function getData() {
    meta.value = { links: [] };
    list.value = [];
    notif.loading = true;
    try {
        const { data } = await useMyFetch(
            "GET",
            `/event?page=${page.value}&search=${search.value}&limit=${limit}`
        );
        list.value = [...data.data];
        meta.value = data.meta;
    } catch (error) {
    } finally {
        notif.loading = false;
    }
}

async function deleteData(e) {
    let text = `Delete Data ${e.title} ?`;
    if (confirm(text) == true) {
        try {
            const { data } = await useMyFetch("delete", `/post/${e.id}/delete`);
            getData();
            notif.make("Succed Delete Data");
        } catch (e) {
            // console.log(e);
            notif.make("Failed Delete Data", { type: "danger" });
        }
    }
}

function setPage(index) {
    page.value = index.url.split("=")[1];
    router.push(`/admin/home?page=${page.value}`);
    getData();
}
</script>
<template>
    <div class="grid grid-cols-1 lg:grid-cols-3">
        <div></div>
        <div></div>
        <div class="pb-4 flex justify-between relative text-xs lg:text-sm">
            <form
                @submit.prevent="setPage({ url: '?=1' })"
                class="relative grow mr-4"
            >
                <input
                    type="text"
                    placeholder="Search.... "
                    v-model="search"
                    ref="input_search"
                    class="w-full bg-white border border-gray-200 py-4 px-6 pr-24 rounded-xl focus:outline-none"
                />
                <div class="absolute right-2 top-2 flex items-center">
                    <span
                        v-if="search"
                        class="mr-2 cursor-pointer"
                        @click="
                            () => {
                                search = '';
                                input_search.focus();
                            }
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </span>
                    <button class="btn-square">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                            />
                        </svg>
                    </button>
                </div>
            </form>
            <div class="pt-2">
                <button
                    @click="$router.push(`/admin/post-make?type=add`)"
                    class="btn-square"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 6v12m6-6H6"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-xl p-4 lg:px-8 pb-8"
    >
        <div class="overflow-auto text-sm">
            <div class="mt-4">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="table-head">
                            <th>No.</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Slot</th>
                            <th>Time</th>
                            <!-- <th>Created At</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <tr v-for="index in list" :key="index">
                            <td>
                                {{
                                    list.indexOf(index) +
                                    1 +
                                    (page - 1) * limit
                                }}.
                            </td>
                            <td>{{ index.title }}</td>
                            <td>
                                {{ index.description.substring(0, 15) + "..." }}
                            </td>
                            <td>
                                {{ index.location.substring(0, 15) + "..." }}
                            </td>
                            <td>
                                <span
                                    :class="
                                        index.booking == index.slot
                                            ? 'font-bold text-rose-600'
                                            : 'text-lime-600'
                                    "
                                    class="text-xs"
                                >
                                    {{ index.booking }}/{{ index.slot }}
                                </span>
                            </td>
                            <td>
                                {{ dateFormatter(index.time).split(",")[0] }}
                                {{ dateFormatter(index.time).split(",")[1] }}
                            </td>
                            <!-- <td>
                                {{
                                    dateFormatter(index.created_at).split(
                                        ","
                                    )[0]
                                }}
                                {{
                                    dateFormatter(index.created_at).split(
                                        ","
                                    )[1]
                                }}
                            </td> -->
                            <td>
                                <div class="flex items-center">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        @click="
                                            $router.push(
                                                `/admin/post/${index.id}`
                                            )
                                        "
                                        class="w-5 mr-2 text-primary cursor-pointer hover:text-primary/50 ease-in-out duration-200"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                        />
                                    </svg>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        @click="
                                            $router.push(
                                                `/admin/post/${index.id}?type=edit`
                                            )
                                        "
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 mr-2 text-primary cursor-pointer hover:text-primary/50 ease-in-out duration-200"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                        />
                                    </svg>

                                    <svg
                                        @click="deleteData(index)"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 text-rose-500 cursor-pointer hover:text-primary/50 ease-in-out duration-200"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                        />
                                    </svg>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Paginate @move="setPage($event)" :page="page" :list="meta?.links" />
    </div>
</template>
<style>
.table-head {
    @apply text-left border-b;
}
.table-head th {
    @apply pb-2;
}
.table-body tr td {
    @apply pt-3 px-2;
}
</style>
