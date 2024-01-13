<template>
    <div
        class="max-w-lg mx-auto relative text-gray-600 min-h-screen bg-gray-100 pb-20 text-xs"
    >
        <nav
            class="bg-primary text-white h-[50px] shadow-lg flex justify-between items-center px-4"
        >
            <div class="font-semibold tracking-widest">Starpoint</div>
            <div></div>
        </nav>
        <div>
            <button
                class="w-[55px] h-[55px] flex items-center justify-center text-white shadow-xl bg-amber-400 fixed bottom-4 right-4 rounded-full"
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
                        d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15"
                    />
                </svg>
            </button>
        </div>
        <div class="px-4">
            <div class="pt-6 flex items-center justify-between">
                <div class="text-xl">
                    Hai, <br />
                    <span class="font-semibold">Ferry</span>
                </div>
                <div>
                    <div
                        class="tracking-[7px] mb-2 font-semibold text-primary text-center"
                    >
                        POIN
                    </div>
                    <div
                        class="w-[75px] h-[75px] flex items-center justify-center text-3xl font-semibold text-white rounded-full bg-primary"
                    >
                        50
                    </div>
                </div>
            </div>

            <div class="pt-10">
                <div
                    v-for="index in 10"
                    class="bg-white mb-4 p-2 rounded-full flex items-center justify-between"
                >
                    <div class="pl-4">
                        <div class="font-semibold">
                            Pelatihan Nasional Pembuatan Desain...
                        </div>
                        <div>
                            <span class="text-primary">Raden Mas Ferry</span>
                            <span> - </span>
                            <span class="text-gray-400">17 Agustus 2024</span>
                        </div>
                    </div>

                    <div
                        class="w-[55px] text-lg h-[55px] bg-primary flex items-center justify-center text-white font-semibold rounded-full"
                    >
                        +25
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
