<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import ProfilePicture from "@/Components/Profile/ProfilePicture.vue";
import {router} from "@inertiajs/vue3";
import {PencilSquareIcon} from "@heroicons/vue/20/solid/index.js";
import ArrowDownIcon from "@/Components/Icons/ArrowDownIcon.vue";

const showMenu = ref(false);
const menuRef = ref(null);

const handleClickOutside = (event) => {
    if (menuRef.value && !menuRef.value.contains(event.target)) {
        showMenu.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
const logout = () => router.post(route('logout'));
</script>

<template>
    <div class="relative" ref="menuRef">
        <div class="inline-flex items-center overflow-hidden">
            <button class="text-gray-600" @click.stop="showMenu = !showMenu">
                <span class="sr-only">Menu</span>
                <ProfilePicture
                    :image-url="$page.props.auth.user.profile_photo_url"
                    image-size="h-12 w-12"
                />
                <ArrowDownIcon
                    class="h-4 w-4 absolute bottom-1 right-1 bg-white p-1 rounded-full shadow cursor-pointer"
                />

<!--                <PencilSquareIcon-->
<!--                    class="h-8 w-8 absolute bottom-1 right-1 bg-white p-1 rounded-full shadow cursor-pointer"-->
<!--                />-->
            </button>
        </div>

        <div
            class="absolute end-0 z-10 mt-2 w-52 divide-y divide-gray-100 rounded-md border border-gray-100 bg-white shadow-lg"
            role="menu"
            v-if="showMenu"
        >
            <div class="p-2 font-bold">
                <strong class="block p-2 text-xs font-medium uppercase text-gray-400"> General </strong>

                <button
                    @click="router.get(route('profile.edit'))"
                    class="block rounded-lg px-4 py-2 text-base text-gray-500 hover:bg-gray-50 hover:text-gray-700 w-full text-left"
                    role="menuitem"
                >
                    Profile
                </button>
                <hr class="my-2">
                <button
                    @click="logout"
                    class="block rounded-lg px-4 py-2 text-base text-red-500 hover:bg-red-50 hover:text-red-700 w-full text-left"
                >
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>
