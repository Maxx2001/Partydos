<script setup lang="ts">
import HeaderComponent from "./Partials/HeaderComponent.vue";
import Footer from "../Layouts/Partials/Footer.vue";

const menuItems = [
    {
        label: 'Home',
        url: '/',
        active: true
    },
    {
        label: 'Features',
        url: '#',
        active: false
    },
    {
        label: 'Roadmap',
        url: '#',
        active: false
    },
    {
        label: 'Contact',
        url: '#',
        active: false
    },
]
</script>

<template>
    <div>
        <HeaderComponent :menu-items="menuItems"/>
        <main>
            <slot />
        </main>
        <Footer/>
    </div>
</template>
