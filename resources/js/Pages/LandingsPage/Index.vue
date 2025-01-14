<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Hero from "@/Pages/LandingsPage/Partials/Hero.vue";
import 'swiper/swiper-bundle.css';
import FeatureList from "@/Pages/LandingsPage/Partials/FeatureList.vue";
import AgendaTabs from "@/Pages/LandingsPage/Partials/AgendaTabs.vue";
import {useTitle} from "@/Composables/useTitle.js";
import UpcomingEvents from "@/Pages/LandingsPage/Partials/UpcomingEvents.vue";

useTitle('Partydos | Home');

defineProps({
    showUpcomingEvents: {
        type: Boolean,
        required: false,
        default: true
    },
    events: {
        type: Array,
        required: false
    }
});

import AOS from "aos";
import "aos/dist/aos.css";

// Initialize AOS with settings
AOS.init({
    duration: 1000, // Default duration for all animations
    once: true,     // Animations should happen only once when scrolled into view
    offset: 40,    // Offset from the original trigger point
});
</script>

<template>
    <DefaultLayout>
        <Hero />
        <UpcomingEvents :events="events" v-if="showUpcomingEvents"/>
        <FeatureList/>
        <AgendaTabs/>
    </DefaultLayout>
</template>
