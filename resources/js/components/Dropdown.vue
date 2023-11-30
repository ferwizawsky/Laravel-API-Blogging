<template>
    <div>
        <Menu as="div" class="relative inline-block text-left">
            <div>
                <MenuButton class="inline-flex justify-center btn capitalize">
                    {{ prop.modelValue ? prop.modelValue : "Filter" }}
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
                    class="absolute left-0 mt-2 w-32 origin-top-left divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                    <div class="px-1 py-1">
                        <MenuItem
                            v-slot="{ active }"
                            v-for="index in prop.list"
                        >
                            <button
                                @click="$emit('update:modelValue', index)"
                                :class="[
                                    active
                                        ? 'bg-primary text-white'
                                        : 'text-gray-900',
                                    'group capitalize flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                            >
                                {{ index }}
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
const prop = defineProps(["list", "modelValue"]);
</script>
