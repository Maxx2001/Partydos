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
</script>

<template>
    <DefaultLayout>
        <Hero />
        <UpcomingEvents :events="events" v-if="showUpcomingEvents"/>
        <FeatureList/>
        <AgendaTabs/>
    </DefaultLayout>
</template>
