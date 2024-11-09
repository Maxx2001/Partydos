<script setup>
import { ref } from "vue";
import MobileNavigation from "@/Layouts/Partials/Mobile/MobileNavigation.vue";
import DesktopNavigation from "@/Layouts/Partials/Desktop/DesktopNavigation.vue";  // Import ref to manage state

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

defineProps({
    menuItems: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <header>
        <DesktopNavigation
            :is-mobile-menu-open="isMobileMenuOpen"
            @toggle-mobile-menu="toggleMobileMenu"
            :menu-items="menuItems"
        />
        <MobileNavigation
            :is-mobile-menu-open="isMobileMenuOpen"
            @close-mobile-menu="closeMobileMenu"
            :menu-items="menuItems"
        />
    </header>
</template>
