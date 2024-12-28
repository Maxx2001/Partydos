<script setup lang="ts">
import { CalendarDaysIcon, PlusCircleIcon, UserIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import { router } from "@inertiajs/vue3";
import BaseOutlineButton from "../../../Components/Base/BaseOutlineButton.vue";
import BaseButton from "../../../Components/Base/BaseButton.vue";
import MenuItem from "../../../Layouts/Partials/Mobile/MenuItem.vue";

const props = defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        default: false
    },
    menuItems: {
        type: Array,
        required: true
    }
});

const emits = defineEmits(["closeMobileMenu"]);

const logout = () => router.post(route('logout'));
</script>

<template>
    <div>
        <!-- Overlay -->
        <div
            class="fixed inset-0 bg-black transition-opacity duration-300 z-40"
            :class="props.isMobileMenuOpen ? 'opacity-50' : 'opacity-0 pointer-events-none'"
            @click="emits('closeMobileMenu')"
        ></div>

        <div
            class="fixed top-0 right-0 h-full w-11/12 max-w-full bg-gradient-to-br from-blue-300 to-purple-600 text-white shadow-lg p-8 transform transition-transform duration-300 z-50 flex flex-col"
            :class="props.isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
        >
            <div class="flex items-center justify-between text-white text-2xl font-bold border-b-2 pb-3">
                <div >Partydos</div>
                <button class="text-white flex items-center" @click="emits('closeMobileMenu')">
                    <XMarkIcon class="h-8 w-8" />
                </button>
            </div>

            <div class="flex flex-col space-y-3 mt-6">
                <MenuItem
                    v-for="menuItem in menuItems"
                    :key="menuItem.label"
                    :url="menuItem.url"
                    :label="menuItem.label"
                    :active="menuItem.active"
                />
            </div>

            <div class="mt-6 flex flex-col space-y-4">
                <BaseButton
                    :icon="PlusCircleIcon"
                    label="Create event"
                    class="w-full"
                    @click="router.get(route('guest-events.create'))"
                />
                <BaseOutlineButton
                    v-if="page && !$page.props.auth.user"
                    :icon="UserIcon"
                    @click="router.get(route('login'))"
                    label="Login"
                    class="w-full"
                />
                <BaseOutlineButton
                    v-else
                    :icon="CalendarDaysIcon"
                    @click="router.get(route('users-events.index'))"
                    label="Events"
                    class="w-full"
                />
            </div>

            <div class="flex-grow"></div>
           <button
               v-if="page && $page.props.auth.user"
               @click="logout"
               class="mb-1.5"
           >
               Logout
           </button>
            <div class="border-t border-blue-300 pt-6">
                <p class="text-center text-sm text-gray-200">&copy; 2024 Partydos. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>
