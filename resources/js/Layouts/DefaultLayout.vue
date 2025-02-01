<script setup>
import HeaderComponent from "./Partials/HeaderComponent.vue";
import Footer from "../Layouts/Partials/Footer.vue";
import ToastList from "@/Components/Layout/Toasts/ToastList.vue";

const menuItems = [
    {
        label: 'Home',
        url: '/',
        active: route().current() === 'home'
    },
    {
        label: 'Events',
        url: route('users-events.index'),
        active: route().current() === 'users-events.index'
    },
    {
        label: 'Features',
        url: route('features'),
        active: route().current() === 'features'
    },
    {
        label: 'Contact',
        url: route('contact'),
        active: route().current() === 'contact'
    }
]
</script>

<template>
    <div class="h-screen flex flex-col">
        <HeaderComponent :menu-items="menuItems" />
        <main class="flex-grow">
            <slot />
            <ToastList/>
        </main>
        <Footer />
    </div>
</template>
