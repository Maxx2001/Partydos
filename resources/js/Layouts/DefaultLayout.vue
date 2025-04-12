<script setup>
import HeaderComponent from "./Partials/HeaderComponent.vue";
import Footer from "../Layouts/Partials/Footer.vue";
import ToastList from "@/Components/Layout/Toasts/ToastList.vue";
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const userIsLoggedIn = usePage().props.auth.user;

const menuItems = [
    {
        label: 'Home',
        url: '/',
        active: route().current() === 'home',
        show: true,
    },
    {
        label: 'Events',
        url: route('users-events.index'),
        active: route().current() === 'users-events.index',
        show: true,
    },
    {
        label: 'Features',
        url: route('features'),
        active: route().current() === 'features',
        show: true,
    },
    {
        label: 'Contact',
        url: route('contact'),
        active: route().current() === 'contact',
        show: true,
    },
]
</script>

<template>
    + <div class="min-h-screen flex flex-col">
        <HeaderComponent :menu-items="menuItems" />
        <main class="flex-grow">
            <slot />
            <ToastList/>
        </main>
        <Footer />
    </div>
</template>
