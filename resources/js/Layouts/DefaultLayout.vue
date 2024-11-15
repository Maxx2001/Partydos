<script setup lang="ts">
import HeaderComponent from "./Partials/HeaderComponent.vue";
import Footer from "../Layouts/Partials/Footer.vue";

const menuItems = [
    {
        label: 'Home',
        url: '/',
        active: route().current() === 'home'
    },
    {
        label: 'Events',
        url: route('events.index'),
        active: route().current() === 'events.index'
    },
    {
        label: 'Features',
        url: route('features'),
        active: route().current() === 'features'
    },
    {
        label: 'Roadmap',
        url: route('roadmap'),
        active: route().current() === 'roadmap'
    },
    {
        label: 'Contact',
        url: route('contact'),
        active: route().current() === 'contact'
    }
]
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <HeaderComponent :menu-items="menuItems" />
        <main class="flex-grow">
            <slot />
        </main>
        <Footer />
    </div>
</template>
