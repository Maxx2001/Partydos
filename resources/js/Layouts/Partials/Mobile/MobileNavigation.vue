<script setup>
import { CalendarDaysIcon, PlusCircleIcon, UserIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import {router, usePage} from "@inertiajs/vue3";
import BaseOutlineButton from "../../../Components/Base/BaseOutlineButton.vue";
import BaseButton from "../../../Components/Base/BaseButton.vue";
import MenuItem from "../../../Layouts/Partials/Mobile/MenuItem.vue";
import ProfilePicture from "@/Components/Profile/ProfilePicture.vue";

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

const user = usePage().props.auth.user;
</script>

<template>
    <div>
        <div
            class="fixed inset-0 bg-black transition-opacity duration-300 z-40"
            :class="props.isMobileMenuOpen ? 'opacity-50' : 'opacity-0 pointer-events-none'"
            @click="emits('closeMobileMenu')"
        ></div>

        <div
            class="fixed top-0 right-0 h-full w-11/12 max-w-full bg-gradient-to-br from-blue-300 to-purple-600 text-white shadow-lg p-6 transform transition-transform duration-300 z-50 flex flex-col"
            :class="props.isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'"
        >
            <div class="flex items-center justify-between text-white text-2xl font-bold border-b-2 pb-3">
                <div >Partydos</div>
                <button class="text-white flex items-center" @click="emits('closeMobileMenu')">
                    <XMarkIcon class="h-8 w-8" />
                </button>
            </div>

            <div class="flex flex-col space-y-2 mt-6 border-b-2 pb-3">
                <MenuItem
                    v-for="menuItem in menuItems"
                    :key="menuItem.label"
                    :url="menuItem.url"
                    :label="menuItem.label"
                    :active="menuItem.active"
                />
            </div>

            <div class="flex flex-col mt-4 pb-3" v-if="user">
                <div class="flex items-center pb-1">
                    <ProfilePicture
                        :image-url="user.profile_photo_url"
                        image-size="h-16 w-16"
                    />
                    <div class="flex flex-col text-2xl pl-4 font-bold">
                        <span>
                            {{ user.name }}
                        </span>
                    </div>
                </div>
                <div class="mt-2 w-full">
                    <button
                        class="block text-xl font-semibold transition duration-300 p-2 text-left w-full"
                        :class="route().current() === 'profile.edit' ? 'bg-white text-blue-600 rounded-md' : 'text-white'"
                        @click="router.get(route('profile.edit'))"
                    >
                        Profile
                    </button>
                    <button
                        class="block text-xl font-semibold transition duration-300 p-2 text-left w-full"
                    >
                        Settings (incoming page)
                    </button>
                    <button
                        class="block text-xl font-semibold transition duration-300 p-2 text-left w-full"
                        :class="active ? 'bg-white text-blue-600 rounded-md' : 'text-white'"
                        @click="logout"
                    >
                        Logout
                    </button>
                </div>

            </div>

            <div class="flex-grow"></div>

            <div class="mb-4 flex flex-col space-y-2">
                <BaseButton
                    :icon="PlusCircleIcon"
                    label="Create event"
                    class="w-full"
                    @click="router.get(route('guest-events.create'))"
                />
                <BaseOutlineButton
                    v-if="$page.props.auth && !$page.props.auth.user"
                    :icon="UserIcon"
                    @click="router.get(route('login'))"
                    label="Login"
                    class="w-full"
                />
            </div>
            <div class="border-t-2 pt-3">
                <p class="text-center text-sm text-gray-200">&copy; 2024 Partydos. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>
