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
    <nav class="flex justify-between mx-6 2xl:mx-24 my-4 md:my-8">
        <div class="w-full flex items-center justify-between md:justify-start">
            <h1 class="flex font-bold text-xl md:text-2xl lg:text-4xl">
                <span>Party</span>
                <span class="text-blue-700">dos</span>
            </h1>

            <div class="hidden md:flex pl-8 lg:pl-12 gap-6 text-xl items-center">
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
                @click="router.get(route('events.create'))"
                extra-classes="text-sm"
            />
            <BaseOutlineButton
                v-if="!$page.props.auth.user"
                :icon="UserIcon"
                @click="router.get(route('login'))"
                extra-classes="text-sm"
                label="Login"
            />
            <BaseOutlineButton
                v-else
                :icon="CalendarDaysIcon"
                @click="router.get(route('events.index'))"
                extra-classes="text-sm"
                label="Events"
            />
        </div>
    </nav>
</template>
