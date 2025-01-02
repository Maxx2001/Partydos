<script setup>
import {Bars3Icon, CalendarDaysIcon, PlusCircleIcon, UserIcon, XMarkIcon} from "@heroicons/vue/20/solid";
import {router} from "@inertiajs/vue3";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import MenuItem from "../Desktop/MenuItem.vue";


defineProps({
    isMobileMenuOpen: {
        type: Boolean,
        default: false
    },
    menuItems: {
        type: Array,
        required: true
    }
});

const emits = defineEmits(['toggleMobileMenu']);

</script>

<template>
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-start h-full">
        <nav class="flex my-4 md:my-8 w-full px-8 xl:px-0">
            <div class="flex items-center justify-between md:justify-start w-full">
                <h1 class="flex font-bold text-2xl lg:text-4xl ">
                    <a :href="route('home')">
                        <span>Party</span>
                        <span class="text-blue-700">dos</span>
                    </a>
                </h1>

                <div class="hidden md:flex pl-8 lg:pl-12 gap-6 items-center">
                    <MenuItem
                        v-for="menuItem in menuItems"
                        :url="menuItem.url"
                        :label="menuItem.label"
                        :active="menuItem.active"
                    />
                </div>

                <button class="md:hidden" @click="emits('toggleMobileMenu')">
                    <Bars3Icon v-if="!isMobileMenuOpen" class="h-8 w-8 text-gray-700" />
                    <XMarkIcon v-else class="h-8 w-8 text-gray-700" />
                </button>
            </div>

            <div class="hidden md:flex items-center w-full justify-end">
                <BaseButton
                    :icon="PlusCircleIcon"
                    label="Create event"
                    class="mr-6"
                    @click="router.get(route('guest-events.create'))"
                    extra-classes="text-sm"
                />
                <BaseOutlineButton
                    v-if="$page.props.auth && !$page.props.auth.user"
                    :icon="UserIcon"
                    @click="router.get(route('login'))"
                    extra-classes="text-sm"
                    label="Login"
                />
                <BaseOutlineButton
                    v-else
                    :icon="CalendarDaysIcon"
                    @click="router.get(route('users-events.index'))"
                    extra-classes="text-sm"
                    label="Events"
                />
            </div>
        </nav>
    </div>
</template>
